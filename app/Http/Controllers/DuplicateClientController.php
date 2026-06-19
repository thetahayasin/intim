<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Billing;
use App\Models\Receipt;
use App\Models\Sale;
use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DuplicateClientController extends Controller
{
    public function index()
    {
        return view('admin.duplicates');
    }

    public function scan()
    {
        $clients = Client::withCount(['billings', 'receipts'])->orderBy('name')->get();

        $clientList = $clients->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'email' => $c->email,
            'representative' => $c->client_representative,
            'billings_count' => $c->billings_count,
            'receipts_count' => $c->receipts_count,
        ])->toArray();

        $prompt = "You are an expert data deduplication assistant. I will give you a JSON array of clients.
Identify groups of clients that are likely duplicates. Be extremely aggressive and inclusive in identifying potential duplicates. It is much better to return a potential duplicate group for user review (even if you are not 100% certain) than to miss one.
Look for all possible patterns, including:
- Phonetic similarities or spelling variations (e.g., \"G&Y\" vs \"GNY\", \"H.A.M.D\" vs \"HAMD\")
- Typos, missing ending characters, or truncations (e.g., \"Muhammad Taha Yasee\" vs \"Muhammad Taha Yaseen\")
- Word order variation or transpositions (e.g., \"Taha Yaseen Muhammad\" vs \"Muhammad Taha Yaseen\")
- Abbreviations, acronyms, or initials vs full names (e.g., \"Asif Associates\" vs \"AA\", \"M. Asif\" vs \"Muhammad Asif\")
- Honorifics or business entity suffixes (e.g., \"Asif Associates Pvt Ltd\" vs \"Asif Associates\", \"Mr. Taha\" vs \"Taha\")
- Typos, missing spaces, punctuation differences, or case differences
- Matching or highly similar email addresses, client representatives, or representative contact numbers (even if client names differ slightly)
- Any other partial match or similarity indicating it might be the same entity or person.

Return ONLY a valid JSON array of arrays of client IDs representing the duplicate groups (e.g., [[1, 5], [3, 7, 12]]). Do not include any code fences, markdown, markdown blocks, or text explanation. Return [] if no duplicates are identified.";

        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return response()->json(['error' => 'GEMINI_API_KEY not set in .env'], 500);
        }

        try {
            $response = Http::withoutVerifying()->timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt . "\n\nClients:\n" . json_encode($clientList)]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'responseMimeType' => 'application/json',
                    ]
                ]
            );

            if ($response->failed()) {
                if ($response->status() === 429) {
                    return response()->json(['error' => 'Gemini API Quota Exceeded (429). Please check your billing or rate limits.'], 429);
                }
                return response()->json(['error' => 'Gemini API returned status ' . $response->status() . ': ' . $response->body()], 500);
            }

            $body = $response->json();
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
            $groups = json_decode($text, true);

            if (!is_array($groups)) {
                return response()->json(['error' => 'Gemini returned invalid JSON: ' . $text], 500);
            }

            // Build response with full client data
            $result = [];
            foreach ($groups as $group) {
                $groupClients = Client::with([
                    'billings' => fn($q) => $q->with('items')->latest(),
                    'receipts' => fn($q) => $q->latest(),
                ])->whereIn('id', $group)->get();

                $result[] = $groupClients->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'name' => $c->name,
                        'email' => $c->email,
                        'representative' => $c->client_representative,
                        'rep_contact' => $c->representative_contact,
                        'created_at' => $c->created_at?->format('d M Y'),
                        'billings' => $c->billings->map(fn($b) => [
                            'id' => $b->id,
                            'description' => $b->description,
                            'remarks' => $b->remarks,
                            'amount' => number_format($b->computed_amount),
                            'tax' => number_format($b->computed_tax),
                            'total' => number_format($b->grand_total),
                            'firm' => $b->firm,
                            'date' => $b->created_at?->format('d M Y'),
                        ]),
                        'receipts' => $c->receipts->map(fn($r) => [
                            'id' => $r->id,
                            'amount' => number_format($r->amount),
                            'date' => $r->date?->format('d M Y'),
                        ]),
                    ];
                });
            }

            return response()->json(['groups' => $result]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gemini API error: ' . $e->getMessage()], 500);
        }
    }

    public function merge(Request $request)
    {
        $request->validate([
            'keep_id' => 'required|exists:clients,id',
            'remove_id' => 'required|exists:clients,id|different:keep_id',
        ]);

        $keepId = $request->keep_id;
        $removeId = $request->remove_id;

        DB::transaction(function () use ($keepId, $removeId) {
            // Move all billings
            Billing::where('client_id', $removeId)->update(['client_id' => $keepId]);
            // Move all receipts
            Receipt::where('client_id', $removeId)->update(['client_id' => $keepId]);
            // Move all sales
            Sale::where('client_id', $removeId)->update(['client_id' => $keepId]);
            // Move all documents
            Document::where('client_id', $removeId)->update(['client_id' => $keepId]);

            // Delete the duplicate client
            Client::destroy($removeId);
        });

        $kept = Client::find($keepId);

        return response()->json([
            'success' => true,
            'message' => "Merged into \"{$kept->name}\" (#{$keepId}). Client #{$removeId} deleted.",
        ]);
    }
}

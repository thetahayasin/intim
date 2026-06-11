<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\Client;

class AdminDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('client')->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('firm')) {
            $query->where('firm', $request->firm);
        }
        if ($request->filled('client')) {
            $query->where('client_name', 'like', '%' . $request->client . '%');
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $documents = $query->paginate(20)->appends($request->all());

        // Sync: clear signed_pdf if file no longer exists on disk
        foreach ($documents as $doc) {
            if ($doc->signed_pdf && !Storage::exists($doc->signed_pdf)) {
                $doc->update(['signed_pdf' => null]);
            }
        }

        $stats = [
            'total'      => Document::count(),
            'proposals'  => Document::where('type', 'proposal')->count(),
            'agreements' => Document::where('type', 'agreement')->count(),
            'aa'         => Document::where('firm', 0)->count(),
            'hamd'       => Document::where('firm', 1)->count(),
        ];

        return view('admin.documents.index', compact('documents', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('admin.documents.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name'     => 'required|string|max:255',
            'firm'            => 'required|in:0,1',
            'services'        => 'required|array|min:1',
            'services.*.name' => 'required|string|max:255',
            'services.*.fee'  => 'nullable|string|max:255',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'notes'           => 'nullable|string|max:2000',
        ]);

        $duplicate = Document::where('type', 'agreement')
            ->where('firm', $request->firm)
            ->where('client_name', $request->client_name)
            ->exists();

        if ($duplicate) {
            $firmLabel = $request->firm == 1 ? 'H.A.M.D & Co' : 'Asif Associates';
            return back()
                ->withErrors(['firm' => "An agreement for \"{$request->client_name}\" with {$firmLabel} already exists."])
                ->withInput();
        }

        $client = Client::where('name', $request->client_name)->first();

        Document::create([
            'type'        => 'agreement',
            'client_id'   => $client?->id,
            'client_name' => $request->client_name,
            'firm'        => $request->firm,
            'services'    => $request->services,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'notes'       => $request->notes,
        ]);

        return redirect()->route('e.documents')->with('success', 'Agreement created successfully.');
    }

    public function view($id)
    {
        $doc = Document::findOrFail($id);
        $view = $doc->type === 'agreement' ? 'admin.documents.agreement' : 'admin.documents.proposal';
        return view($view, compact('doc'));
    }

    public function edit($id)
    {
        $doc = Document::findOrFail($id);

        // Sync: clear if file gone from disk
        if ($doc->signed_pdf && !Storage::exists($doc->signed_pdf)) {
            $doc->update(['signed_pdf' => null]);
        }

        $clients = Client::orderBy('name')->get();
        return view('admin.documents.edit', compact('doc', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name'     => 'required|string|max:255',
            'firm'            => 'required|in:0,1',
            'services'        => 'required|array|min:1',
            'services.*.name' => 'required|string|max:255',
            'services.*.fee'  => 'nullable|string|max:255',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'notes'           => 'nullable|string|max:2000',
        ]);

        $doc = Document::findOrFail($id);

        $duplicate = Document::where('type', 'agreement')
            ->where('firm', $request->firm)
            ->where('client_name', $request->client_name)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            $firmLabel = $request->firm == 1 ? 'H.A.M.D & Co' : 'Asif Associates';
            return back()
                ->withErrors(['firm' => "An agreement for \"{$request->client_name}\" with {$firmLabel} already exists."])
                ->withInput();
        }

        $client = Client::where('name', $request->client_name)->first();

        $doc->update([
            'client_id'   => $client?->id,
            'client_name' => $request->client_name,
            'firm'        => $request->firm,
            'services'    => $request->services,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'notes'       => $request->notes,
        ]);

        return redirect()->route('e.documents.edit', $id)->with('success', 'Agreement updated successfully.');
    }

    public function destroy($id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->signed_pdf) {
            Storage::delete($doc->signed_pdf);
        }

        $doc->delete();
        return redirect()->route('e.documents')->with('success', 'Document deleted.');
    }

    public function uploadSignedPdf(Request $request, $id)
    {
        $request->validate([
            'signed_pdf' => 'required|file|mimes:pdf|max:5120',
        ], [
            'signed_pdf.mimes' => 'Only PDF files are allowed.',
            'signed_pdf.max'   => 'PDF must not exceed 5 MB.',
        ]);

        $doc = Document::findOrFail($id);

        // Delete old file if exists
        if ($doc->signed_pdf && Storage::exists($doc->signed_pdf)) {
            Storage::delete($doc->signed_pdf);
        }

        $path = $request->file('signed_pdf')->store('documents/signed');
        $doc->update(['signed_pdf' => $path]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Signed agreement uploaded.');
    }

    public function downloadSignedPdf($id)
    {
        $doc = Document::findOrFail($id);

        if (!$doc->signed_pdf) {
            abort(404);
        }

        if (!Storage::exists($doc->signed_pdf)) {
            $doc->update(['signed_pdf' => null]);
            abort(404);
        }

        $filename = 'Signed-Agreement-' . str_replace(['/', '\\', ' '], '-', $doc->client_name) . '.pdf';
        return Storage::download($doc->signed_pdf, $filename);
    }

    public function destroySignedPdf($id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->signed_pdf && Storage::exists($doc->signed_pdf)) {
            Storage::delete($doc->signed_pdf);
        }

        $doc->update(['signed_pdf' => null]);

        return back()->with('success', 'Signed PDF removed.');
    }
}

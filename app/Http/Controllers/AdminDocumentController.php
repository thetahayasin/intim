<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'client_name'   => 'required|string|max:255',
            'firm'          => 'required|in:0,1',
            'services'      => 'required|array|min:1',
            'services.*.name' => 'required|string|max:255',
            'services.*.fee'  => 'nullable|string|max:255',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'notes'         => 'nullable|string|max:2000',
        ]);

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

    public function destroy($id)
    {
        Document::findOrFail($id)->delete();
        return redirect()->route('e.documents')->with('success', 'Document deleted.');
    }
}

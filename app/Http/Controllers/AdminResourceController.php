<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class AdminResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::where('status', 'approved')
            ->orderBy('category')->orderBy('name')->get()->groupBy('category');
        $pending = Resource::where('status', 'pending')->orderByDesc('created_at')->get();
        return view('admin.resources.index', compact('resources', 'pending'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Tax,Audit,Advisory,Corporate',
            'description' => 'nullable|string|max:1000',
            'file'        => 'required|file|max:20480',
        ]);

        $file = $request->file('file');
        $path = $file->store('resources', 'public');

        Resource::create([
            'name'              => $request->name,
            'category'          => $request->category,
            'description'       => $request->description,
            'file_path'         => $path,
            'original_filename' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('e.resources')->with('success', 'Resource uploaded successfully.');
    }

    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Tax,Audit,Advisory,Corporate',
            'description' => 'nullable|string|max:1000',
            'file'        => 'nullable|file|max:20480',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($resource->file_path);
            $file = $request->file('file');
            $resource->file_path         = $file->store('resources', 'public');
            $resource->original_filename = $file->getClientOriginalName();
        }

        $resource->name        = $request->name;
        $resource->category    = $request->category;
        $resource->description = $request->description;
        $resource->save();

        return redirect()->route('e.resources')->with('success', 'Resource updated.');
    }

    public function approve($id)
    {
        Resource::findOrFail($id)->update(['status' => 'approved']);
        return redirect()->route('e.resources')->with('success', 'Resource approved.');
    }

    public function reject($id)
    {
        $resource = Resource::findOrFail($id);
        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();
        return redirect()->route('e.resources')->with('success', 'Resource rejected and removed.');
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();

        return redirect()->route('e.resources')->with('success', 'Resource deleted.');
    }
}

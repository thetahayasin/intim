<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssociateResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::where('status', 'approved')
            ->orderBy('category')->orderBy('name')
            ->get()->groupBy('category');

        $myUploads = Resource::where('uploaded_by', Auth::guard('associate')->id())
            ->orderByDesc('created_at')->get();

        return view('associate.resources', compact('resources', 'myUploads'));
    }

    public function download($id)
    {
        $resource = Resource::where('status', 'approved')->findOrFail($id);
        $path = Storage::disk('public')->path($resource->file_path);
        return response()->download($path, $resource->original_filename);
    }

    public function upload(Request $request)
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
            'uploaded_by'       => Auth::guard('associate')->id(),
            'status'            => 'pending',
        ]);

        return redirect()->route('ass.resources')->with('success', 'File submitted for admin approval.');
    }
}

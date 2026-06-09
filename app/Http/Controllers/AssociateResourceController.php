<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class AssociateResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('associate.resources', compact('resources'));
    }

    public function download($id)
    {
        $resource = Resource::findOrFail($id);
        $path = Storage::disk('public')->path($resource->file_path);

        return response()->download($path, $resource->original_filename);
    }
}

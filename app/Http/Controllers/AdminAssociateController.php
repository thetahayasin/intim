<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Associate;
use Illuminate\Support\Facades\Hash;



class AdminAssociateController extends Controller
{
    public function index()
    {
        // Get all records from your model
        $records = Associate::orderByDesc('active')->orderBy('name')->get();
        
        // Pass the records to the view
        return view('admin.associates', ['records' => $records]);
    }

    //deactivate
    public function deactive($id)
    {
        $record = Associate::findOrFail($id);;
        $record->active = false;

        $record->update();

        return redirect()->route('e.associate')->with('success', 'Associate Deactivated');
    }

    //activate
    public function reactive($id)
    {
        $record = Associate::findOrFail($id);;
        $record->active = true;

        $record->update();

        return redirect()->route('e.associate')->with('success', 'Associate Reactivated');
    }

    //create
    public function create()
    {
        return view('admin.create');
    }
    //add
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:associates',
            'password' => 'required',
            'name' => 'required',
            'crn' => 'nullable|integer|unique:associates|min:0',
            'date_joined' => 'nullable|date|required',
            'fts' => 'nullable|integer|min:0',
            'period' => 'nullable|numeric',
            'opening_presents' => 'nullable|integer|min:0',
            'opening_leaves' => 'nullable|integer|min:0',
            'opening_absents' => 'nullable|integer|min:0',
        ]);
    
        $validatedData['password'] = Hash::make($validatedData['password']);
    
        $associate = Associate::create($validatedData);
    
        return redirect()->route('e.associate')->with('success', 'Associate Created Successfully');
    }

    //edit
    public function edit($id)
    {
        $associate = Associate::findOrFail($id);
        return view('admin.edit', compact('associate'));
    }
    public function update(Request $request, $id)
    {
        $associate = Associate::findOrFail($id);

        $validatedData = $request->validate([
            'email' => 'required|email|unique:associates,email,' . $associate->id,
            'password' => 'nullable|min:6',
            'name' => 'required',
            'crn' => 'nullable|integer|min:0|unique:associates,crn,' . $associate->id,
            'date_joined' => 'required|date',
            'fts' => 'nullable|integer|min:0',
            'period' => 'nullable|numeric',
            'opening_presents' => 'nullable|integer|min:0',
            'opening_leaves' => 'nullable|integer|min:0',
            'opening_absents' => 'nullable|integer|min:0',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $associate->update($validatedData);

        return redirect()->route('e.associate')->with('success', 'Associate Updated Successfully');
    }

}

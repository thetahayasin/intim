<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Receipt;




class AdminClientController extends Controller
{
    public function index()
    {
        return view('admin.bill.clients');
    }

    public function create()
    {
        return view('admin.bill.newclient');
    }

    public function add(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients',
            'client_representative' => 'nullable',
            'representative_contact' => 'nullable'
        ]);

        // Create and save the new client
        $client = new Client();
        $client->name = $validatedData['name'];
        $client->email = $validatedData['email'];
        $client->representative_contact = $validatedData['representative_contact'];
        $client->client_representative = $validatedData['client_representative'];
        $client->save();

        return redirect()->route('e.client')->with('success', 'Client Added Successfully');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.bill.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,'.$id,
            'client_representative' => 'nullable',
            'representative_contact' => 'nullable'
        ]);

        $client = Client::findOrFail($id);
        $client->name = $request->name;
        $client->email = $request->email;
        $client->client_representative = $request->client_representative;
        $client->representative_contact = $request->representative_contact;
        $client->save();

        return redirect()->route('e.client')->with('success', 'Client updated successfully.');
    }

    public function stats($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.bill.clientstats', compact('client'));
    }
    
    public function search(Request $request)
    {
        $search = trim($request->input('search'));
    
        $clients = Client::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('client_representative', 'like', "%{$search}%")
                      ->orWhere('representative_contact', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends(['search' => $search]); // Keep search value in pagination links
    
        return view('admin.bill.clients', compact('clients', 'search'));
    }


}

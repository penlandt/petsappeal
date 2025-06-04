<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = \App\Models\Client::with('company')->get();
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:25',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
    ]);

    $validated['company_id'] = auth()->user()->company_id;

    $client = Client::create($validated);

    // If this was an AJAX request, return JSON
    if ($request->ajax()) {
        return response()->json(['client' => $client]);
    }

    return redirect()->route('clients.index')->with('success', 'Client created successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $client = \App\Models\Client::findOrFail($id);

        $showAll = $request->query('show') === 'all';

        $client->load(['pets' => function ($query) use ($showAll) {
            if (! $showAll) {
                $query->where('inactive', false);
            }
        }]);

        return view('clients.show', compact('client', 'showAll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }

    public function ajaxStore(Request $request)
    {
        $user = auth()->user();
    
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:20',
        ]);
    
        $client = new \App\Models\Client($validated);
        $client->company_id = $user->company_id;
        $client->save();
    
        return response()->json([
            'id' => $client->id,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'phone' => $client->phone,
        ]);
    }

    public function json()
{
    $companyId = auth()->user()->company_id;

    $clients = \App\Models\Client::where('company_id', $companyId)
        ->orderBy('last_name')
        ->get()
        ->map(function ($client) {
            return [
                'id' => $client->id,
                'text' => $client->first_name . ' ' . $client->last_name,
            ];
        });

    return response()->json($clients);
}

}

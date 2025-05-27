<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
    
        $pets = \App\Models\Pet::whereHas('client', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->with('client')->orderBy('name')->get();
    
        return view('pets.index', compact('pets'));
    }
 
    public function create()
    {
        $user = \Auth::user();

        $clients = \App\Models\Client::where('company_id', $user->company_id)
            ->orderBy('last_name')
            ->get();

        return view('pets.create', compact('clients'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'species' => 'nullable|string|max:255',
            'breed' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'color' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'inactive' => 'nullable|boolean',
        ]);

        $data['inactive'] = $request->has('inactive'); // checkbox handling

        \App\Models\Pet::create($data);

        return redirect()->route('pets.index')
                 ->with('success', 'Pet added successfully!');

    }

    public function edit($id)
    {
        $user = \Auth::user();

        $pet = \App\Models\Pet::whereHas('client', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->with('client')->findOrFail($id);

        $clients = \App\Models\Client::where('company_id', $user->company_id)
            ->orderBy('last_name')
            ->get();

        return view('pets.edit', compact('pet', 'clients'));
    }


    public function update(Request $request, Pet $pet)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'species' => 'nullable|string|max:255',
        'breed' => 'nullable|string|max:255',
        'birthdate' => 'nullable|date',
        'color' => 'nullable|string|max:255',
        'gender' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'inactive' => 'nullable|boolean',
    ]);

    $data['inactive'] = $request->has('inactive'); // checkbox handling

    $pet->update($data);

    return redirect()
        ->route('pets.index')
        ->with('success', 'Pet updated successfully!');
    }
}

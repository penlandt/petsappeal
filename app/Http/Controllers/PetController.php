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

    $data['inactive'] = $request->has('inactive');

    $pet = \App\Models\Pet::create($data);

    if ($request->expectsJson()) {
        return response()->json($pet);
    }

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

    public function ajaxStore(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'sex' => 'required|in:Male,Female,Unknown',
            'weight' => 'nullable|numeric',
            'client_id' => 'required|exists:clients,id',
        ]);

        $user = auth()->user();

        $pet = new Pet();
        $pet->client_id = $request->input('client_id');
        $pet->name = $request->input('name');
        $pet->species = $request->input('species');
        $pet->breed = $request->input('breed');
        $pet->color = $request->input('color');
        $pet->birthdate = $request->input('birthdate');
        $pet->gender = $request->input('sex'); // still use 'sex' from the form
        $pet->save();

        return response()->json([
            'success' => true,
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->name,
            ],
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => 'Exception occurred',
            'message' => $e->getMessage(),
        ], 500);
    }
}
   
}

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $clientId = Auth::guard('client')->user()->client_id;

        $showInactive = $request->query('show_inactive', false);

        $pets = Pet::where('client_id', $clientId)
            ->when(!$showInactive, fn($query) => $query->where('inactive', false))
            ->orderBy('name')
            ->get();

        return view('client.pets.index', compact('pets', 'showInactive'));
    }

    public function edit(Pet $pet)
    {
        $clientId = Auth::guard('client')->user()->client_id;

        abort_if($pet->client_id !== $clientId, 403);

        return view('client.pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $clientId = Auth::guard('client')->user()->client_id;

        abort_if($pet->client_id !== $clientId, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'nullable|string|max:255',
            'breed' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $pet->update($validated);

        return redirect()->route('client.pets.index')->with('success', 'Pet updated successfully.');
    }

    public function create()
    {
        return view('client.pets.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'species' => 'nullable|string|max:255',
        'breed' => 'nullable|string|max:255',
        'birthdate' => 'nullable|date',
        'gender' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:100',
        'inactive' => 'nullable|boolean',
    ]);

    $clientUser = auth()->guard('client')->user();

    \App\Models\Pet::create([
        'client_id' => $clientUser->client_id,
        'name' => $request->input('name'),
        'species' => $request->input('species'),
        'breed' => $request->input('breed'),
        'birthdate' => $request->input('birthdate'),
        'gender' => $request->input('gender'),
        'color' => $request->input('color'),
        'inactive' => $request->has('inactive'),
    ]);

    return redirect()->route('client.pets.index')->with('success', 'Pet added successfully.');
}

}

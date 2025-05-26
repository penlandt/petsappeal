<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $showInactive = $request->boolean('show_inactive');

        $services = $company->services()
            ->when(!$showInactive, function ($query) {
                $query->where('inactive', false);
            })
            ->orderBy('name')
            ->get();

        return view('services.index', compact('services', 'showInactive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'inactive' => 'nullable|boolean',
        ]);

        $request->user()->company->services()->create([
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'price' => $validated['price'],
            'inactive' => $request->has('inactive'),
        ]);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $company = $user->company;

        $service = $company->services()->where('id', $id)->firstOrFail();

        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $company = $user->company;

        $service = $company->services()->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $service->name = $validated['name'];
        $service->duration = $validated['duration'];
        $service->price = $validated['price'];
        $service->inactive = $request->boolean('inactive');
        $service->save();

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::user()->company;
        $showInactive = $request->query('show_inactive', false);

        $locations = $company->locations()
            ->when(!$showInactive, fn ($query) => $query->where('inactive', false))
            ->orderBy('name')
            ->get();

        return view('locations.index', compact('locations', 'showInactive'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
    
        $data['inactive'] = $request->has('inactive');
        $data['company_id'] = auth()->user()->company->id;
    
        $validated = validator($data, [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'postal_code' => 'required|string|max:20',
            'timezone' => 'required|string|timezone',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'product_tax_rate' => 'nullable|numeric|min:0|max:100',
            'service_tax_rate' => 'nullable|numeric|min:0|max:100',
            'inactive' => 'boolean',
            'company_id' => 'required|exists:companies,id',
        ])->validate();
    
        \App\Models\Location::create($validated);
    
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }
    

    public function edit(Location $location)
    {
        // Make sure the location belongs to the logged-in user's company
        if ($location->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
{
    if ($location->company_id !== auth()->user()->company_id) {
        abort(403);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|size:2',
        'postal_code' => 'required|string|max:20',
        'timezone' => 'required|string|timezone',
        'phone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'product_tax_rate' => 'nullable|numeric|min:0|max:100',
        'service_tax_rate' => 'nullable|numeric|min:0|max:100',
        'inactive' => 'nullable|boolean',
    ]);

    $location->update([
        'name' => $validated['name'],
        'address' => $validated['address'],
        'city' => $validated['city'],
        'state' => $validated['state'],
        'postal_code' => $validated['postal_code'],
        'timezone' => $validated['timezone'],
        'phone' => $validated['phone'] ?? null,
        'email' => $validated['email'] ?? null,
        'product_tax_rate' => $validated['product_tax_rate'] ?? null,
        'service_tax_rate' => $validated['service_tax_rate'] ?? null,
        'inactive' => $request->has('inactive') ? 1 : 0,
    ]);

    return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
}

}

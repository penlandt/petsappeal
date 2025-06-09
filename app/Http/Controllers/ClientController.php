<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ClientPortalWelcome;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('company')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

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

        if (!empty($validated['email']) && filter_var($validated['email'], FILTER_VALIDATE_EMAIL)) {
            $plainPassword = Str::random(10);

            $clientUser = new ClientUser([
                'client_id' => $client->id,
                'company_id' => $client->company_id,
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
            ]);
            $clientUser->save();

            // Send welcome email with credentials
            Mail::to($clientUser->email)->send(
                new ClientPortalWelcome($clientUser, $plainPassword)
            );
        }

        if ($request->ajax()) {
            return response()->json(['client' => $client]);
        }

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $showAll = $request->query('show') === 'all';

        $client->load(['pets' => function ($query) use ($showAll) {
            if (!$showAll) {
                $query->where('inactive', false);
            }
        }]);

        return view('clients.show', compact('client', 'showAll'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

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

        $client = new Client($validated);
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

        $clients = Client::where('company_id', $companyId)
            ->orderBy('last_name')
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                ];
            });

        return response()->json($clients);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search'));
    }
    public function edit(\App\Models\User $user)
{
    $availableModules = ['pos', 'grooming', 'boarding', 'daycare', 'house'];

    $userModules = $user->company
        ? $user->company->moduleAccess()->pluck('module')->toArray()
        : [];

    return view('admin.users.edit', compact('user', 'availableModules', 'userModules'));
}

public function update(Request $request, \App\Models\User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'is_admin' => 'required|boolean',
        'modules' => 'array',
        'modules.*' => 'string',
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'is_admin' => $validated['is_admin'],
    ]);

    // Sync module access for the user's company
    if ($user->company) {
        $user->company->moduleAccess()->delete();

        foreach ($validated['modules'] ?? [] as $module) {
            \App\Models\CompanyModuleAccess::create([
                'company_id' => $user->company->id,
                'module' => $module,
            ]);
        }
    }

    return redirect()->route('admin.users')->with('success', 'User updated successfully.');
}




public function impersonate(\App\Models\User $user)
{
    // Store current user ID in session so we can switch back
    session()->put('impersonator_id', auth()->id());

    // Log in as the target user
    auth()->login($user);

    return redirect()->route('dashboard')->with('success', 'You are now impersonating ' . $user->name);
}

public function destroy(\App\Models\User $user)
{
    // Prevent deleting yourself
    if (auth()->id() === $user->id) {
        return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
    }

    $company = $user->company;

    if ($company) {
        $company->delete(); // This should cascade to clients, pets, staff, etc.
    }

    $user->delete();

    return redirect()->route('admin.users')->with('success', 'User and all associated company data deleted.');
}


}

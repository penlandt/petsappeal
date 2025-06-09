<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Update the client user's email and/or password.
     */
    public function update(Request $request)
    {
        $user = auth('client')->user();

        $request->validate([
            'email' => 'required|email|max:255|unique:client_users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->must_change_password = false;
        }

        $user->save();

        return redirect()->route('client.profile')->with('success', 'Your profile has been updated.');
    }
}

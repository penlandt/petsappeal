<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailSetting;

class EmailSettingsController extends Controller
{
    public function edit()
    {
        $company = auth()->user()->company;
        $settings = EmailSetting::firstOrNew(['company_id' => $company->id]);

        return view('settings.email', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'from_name'  => 'required|string|max:255',
            'from_email' => 'required|email|max:255',
            'host'       => 'required|string|max:255',
            'port'       => 'required|integer',
            'encryption' => 'nullable|string|in:ssl,tls',
            'username'   => 'required|string|max:255',
            'password'   => 'required|string|max:255',
        ]);

        $company = auth()->user()->company;

        EmailSetting::updateOrCreate(
            ['company_id' => $company->id],
            [
                'from_name'  => $request->input('from_name'),
                'from_email' => $request->input('from_email'),
                'host'       => $request->input('host'),
                'port'       => $request->input('port'),
                'encryption' => $request->input('encryption'),
                'username'   => $request->input('username'),
                'password'   => $request->input('password'), // Optional: encrypt here if desired
            ]
        );

        return redirect()->back()->with('success', 'Email settings updated successfully.');
    }
}

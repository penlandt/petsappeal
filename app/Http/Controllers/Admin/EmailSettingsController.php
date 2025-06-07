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
        'password'   => 'nullable|string|max:255',

        // new auto-email toggles
        'send_receipts_automatically' => 'nullable|boolean',
        'send_invoices_automatically' => 'nullable|boolean',
        'send_appointment_reminders' => 'nullable|boolean',
        'send_reservation_reminders' => 'nullable|boolean',
    ]);

    $company = auth()->user()->company;

    $existingSettings = \App\Models\EmailSetting::where('company_id', $company->id)->first();

    \App\Models\EmailSetting::updateOrCreate(
        ['company_id' => $company->id],
        [
            'from_name'  => $request->input('from_name'),
            'from_email' => $request->input('from_email'),
            'host'       => $request->input('host'),
            'port'       => $request->input('port'),
            'encryption' => $request->input('encryption'),
            'username'   => $request->input('username'),
            'password' => $request->filled('password')
                ? encrypt($request->input('password'))
                : ($existingSettings ? $existingSettings->password : null),

            // Save checkboxes (will be false if not sent)
            'send_receipts_automatically' => $request->boolean('send_receipts_automatically'),
            'send_invoices_automatically' => $request->boolean('send_invoices_automatically'),
            'send_appointment_reminders' => $request->boolean('send_appointment_reminders'),
            'send_reservation_reminders' => $request->boolean('send_reservation_reminders'),
        ]
    );

    return redirect()->back()->with('success', 'Email settings updated successfully.');
}

}

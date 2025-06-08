<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Mail\TestEmailTemplate;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        $templates = EmailTemplate::where('company_id', $companyId)
            ->orderBy('type')
            ->orderBy('template_key')
            ->get();

        return view('settings.email-templates.index', compact('templates'));
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        $this->authorize('view', $emailTemplate);

        return view('settings.email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $this->authorize('update', $emailTemplate);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_plain' => 'required|string',
        ]);

        $emailTemplate->update($validated);

        return redirect()
            ->route('settings.email-templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function sendTest(Request $request, EmailTemplate $emailTemplate)
{
    $this->authorize('view', $emailTemplate);

    $user = $request->user();

    $replacements = [
        '{{ client_name }}' => $user->name ?? 'Test Client',
        '{{ pet_name }}' => 'Buddy',
        '{{ service_name }}' => 'Full Groom',
        '{{ staff_name }}' => 'Jane Groomer',
        '{{ appointment_date }}' => now()->format('F j, Y'),
        '{{ appointment_time }}' => now()->format('g:i A'),
        '{{ location_name }}' => 'Downtown Salon',
        '{{ company_name }}' => $user->company->name ?? 'Your Company',
    ];

    $html = strtr($emailTemplate->body_html, $replacements);
    $plain = strtr($emailTemplate->body_plain, $replacements);

    Mail::to($user->email)->send(new \App\Mail\GenericEmailTemplate(
        strtr($emailTemplate->subject, $replacements),
        $html,
        $plain
    ));
    

    return redirect()
        ->route('settings.email-templates.edit', $emailTemplate)
        ->with('test_sent', 'Test email sent to ' . $user->email);
}
}

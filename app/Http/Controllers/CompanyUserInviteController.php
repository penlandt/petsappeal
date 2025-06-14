<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Services\CompanyMailer;

class CompanyUserInviteController extends Controller
{
    protected CompanyMailer $mailer;

    public function __construct(CompanyMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function create()
    {
        return view('company.invite-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role'  => ['required', 'in:delegate,staff_user'],
        ]);

        \Log::info('Invite role received:', ['role' => $request->role]);

        $companyId = Auth::user()->company_id;

        // Try to match a staff member with the same company and email
        $matchingStaff = Staff::where('company_id', $companyId)
                              ->where('email', $request->email)
                              ->first();

        // Check if user already exists
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->company_id === $companyId) {
                return redirect()->back()->with('error', 'This user already belongs to your company.');
            }

            // Reassign the user to this company
            $user->update([
                'name'              => $request->name,
                'company_id'        => $companyId,
                'role'              => $request->role,
                'staff_id'          => $matchingStaff?->id,
                'terms_accepted_at' => Carbon::now(),
            ]);

            // Apply company-specific SMTP settings before sending the reset link
            $this->mailer->applyCompanySmtpSettings();

            // Send the reset link so the user can choose a new password
            Password::sendResetLink(['email' => $user->email]);

            return redirect()->back()->with('success', 'Existing user has been reassigned to your company and invited successfully.');
        }

        // Create a new user if one doesn't already exist
        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make(Str::random(32)), // temporary, never used
            'company_id'         => $companyId,
            'role'               => $request->role,
            'staff_id'           => $matchingStaff?->id,
            'terms_accepted_at'  => Carbon::now(),
            'email_verified_at'  => now(),
        ]);

        // Apply company-specific SMTP settings before sending the reset link
        $this->mailer->applyCompanySmtpSettings();

        // Send the reset link so the user can choose a password
        Password::sendResetLink(['email' => $user->email]);

        return redirect()->back()->with('success', 'User invited successfully. They will receive an email with instructions to set their password.');
    }
}

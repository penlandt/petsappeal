<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\ClientUser;

class AuthController extends Controller
{
    public function showLoginForm(string $companySlug)
    {
        $company = Company::where('slug', $companySlug)->firstOrFail();

        return view('client.auth.login', [
            'company' => $company,
        ]);
    }

    public function login(Request $request, $companySlug)
    {
        $company = Company::where('slug', $companySlug)->where('active', true)->firstOrFail();

        $request->validate([
            'email'           => 'required|email',
            'password'        => 'required|string',
            'recaptcha_token' => 'required|string',
        ]);

        // reCAPTCHA v3 validation
        $recaptcha = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $request->recaptcha_token,
            'remoteip' => $request->ip(),
        ])->json();

        if (
            !$recaptcha['success'] ||
            !isset($recaptcha['score']) ||
            $recaptcha['score'] < 0.3 ||
            $recaptcha['action'] !== 'client_login'
        ) {
            return back()->withErrors([
                'recaptcha' => 'reCAPTCHA verification failed. Please try again.',
            ])->withInput();
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'company_id' => $company->id,
        ];

        if (Auth::guard('client')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $request->session()->put('company_slug', $companySlug);
            return redirect()->route('client.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ])->withInput();
    }

    public function showRegisterForm($companySlug)
    {
        $company = Company::where('slug', $companySlug)->where('active', true)->firstOrFail();

        return view('client.auth.register', compact('company'));
    }
}

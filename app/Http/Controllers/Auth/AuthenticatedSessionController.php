<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->validate([
            'recaptcha_token' => ['required', 'string'],
        ]);

        // reCAPTCHA v3 verification
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $request->recaptcha_token,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (
            !$result['success'] ||
            !isset($result['score']) ||
            $result['score'] < 0.3 ||
            $result['action'] !== 'login'
        ) {
            return back()->withErrors([
                'recaptcha' => 'reCAPTCHA verification failed. Please try again.',
            ])->withInput();
        }

        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $company = $user->company;

        if (!$company || $company->locations()->count() === 0 || $company->staff()->count() === 0 || $company->services()->count() === 0) {
            return redirect()->route('onboarding.index');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

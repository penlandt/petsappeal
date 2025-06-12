<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function about()
    {
        return view('public.about');
    }

    public function pricing()
    {
        return view('public.pricing');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'message'         => 'required|string',
            'recaptcha_token' => 'required|string',
        ]);

        // Verify reCAPTCHA token with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $request->recaptcha_token,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (
            !$result['success'] ||
            !isset($result['score']) ||
            $result['score'] < 0.5 ||
            $result['action'] !== 'contact'
        ) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.']);
        }

        // Send email
        Mail::raw($request->message, function ($message) use ($request) {
            $message->to('support@pets-appeal.com')
                    ->subject('New Contact Form Submission')
                    ->replyTo($request->email, $request->name);
        });

        return back()->with('success', 'Thank you! Your message has been sent.');
    }
}

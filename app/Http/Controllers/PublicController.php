<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // You can configure this to send to your real email later
        Mail::raw($request->message, function ($message) use ($request) {
            $message->to('support@petsappeal.com')
                    ->subject('New Contact Form Submission')
                    ->replyTo($request->email, $request->name);
        });

        return back()->with('success', 'Thank you! Your message has been sent.');
    }
}

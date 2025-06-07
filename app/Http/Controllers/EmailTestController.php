<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class EmailTestController extends Controller
{
    public function send()
    {
        $user = auth()->user();

        try {
            Mail::to('mtppublic@gmail.com')->send(new TestEmail());


            return back()->with('success', 'Test email sent to ' . $user->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}

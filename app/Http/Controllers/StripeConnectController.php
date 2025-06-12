<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Stripe\Stripe;
use Stripe\OAuth;

class StripeConnectController extends Controller
{
    public function index()
    {
        $location = Auth::user()->selectedLocation;

        return view('settings.stripe', [
            'location' => $location,
        ]);
    }

    public function redirectToStripe()
{
    $user = Auth::user();

    if (!$user->selected_location_id) {
        return redirect()->route('stripe.settings')->with('error', 'You must select a location before connecting a Stripe account.');
    }

    $location = \App\Models\Location::find($user->selected_location_id);

    if (!$location) {
        return redirect()->route('stripe.settings')->with('error', 'The selected location no longer exists.');
    }

    $url = 'https://connect.stripe.com/oauth/authorize?' . http_build_query([
        'response_type' => 'code',
        'client_id' => config('services.stripe.connect_client_id'),
        'scope' => 'read_write',
        'redirect_uri' => route('stripe.callback'),
    ]);

    return redirect($url);
}


public function handleStripeCallback(Request $request)
{
    $user = Auth::user();

    if (!$user->selected_location_id) {
        return redirect()->route('stripe.settings')->with('error', 'No location is selected. Please select a location before connecting Stripe.');
    }

    $location = \App\Models\Location::find($user->selected_location_id);

    if (!$location) {
        return redirect()->route('stripe.settings')->with('error', 'Selected location not found.');
    }

    if ($request->has('error')) {
        return redirect()->route('stripe.settings')->with('error', 'Stripe connection was cancelled.');
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        $response = OAuth::token([
            'grant_type' => 'authorization_code',
            'code' => $request->code,
        ]);
    } catch (\Exception $e) {
        return redirect()->route('stripe.settings')->with('error', 'Stripe error: ' . $e->getMessage());
    }

    $location->stripe_account_id = $response->stripe_user_id;
    $location->save();

    return redirect()->route('stripe.settings')->with('success', 'Stripe account connected successfully.');
}

}

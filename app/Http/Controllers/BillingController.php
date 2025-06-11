<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Laravel\Cashier\Subscription;
use Stripe\Subscription as StripeSubscription;


class BillingController extends Controller
{
    public function showPlans()
    {
        $plans = [
            [
                'name' => 'Starter',
                'price' => 49,
                'price_id' => config('services.stripe.price_starter'),
                'features' => ['1 Location', 'Basic Support'],
            ],
            [
                'name' => 'Pro',
                'price' => 99,
                'price_id' => config('services.stripe.price_pro'),
                'features' => ['Up to 3 Locations', 'Priority Support'],
            ],
            [
                'name' => 'Multi-Location',
                'price' => 149,
                'price_id' => config('services.stripe.price_multi'),
                'features' => ['Unlimited Locations', 'Premium Support'],
            ],
        ];

        $company = Auth::user()?->company;

        return view('billing.plans', compact('plans', 'company'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'price_id' => 'required|string',
        ]);

        $user = Auth::user();
        $company = $user->company;

        return $company->newSubscription('default', $request->price_id)
            ->checkout([
                'success_url' => route('billing.success'),
                'cancel_url' => url()->previous(),
            ]);
    }

    public function success(Request $request)
{
    $user = Auth::user();
    $company = $user->company;

    Stripe::setApiKey(config('cashier.secret'));

    // Fetch the latest Checkout session for this customer
    $sessions = StripeSession::all([
        'customer' => $company->stripe_id,
        'limit' => 1,
    ]);

    $latestSession = $sessions->data[0] ?? null;

    if ($latestSession && $latestSession->subscription) {
        $subscriptionId = $latestSession->subscription;

        // Fetch the actual subscription from Stripe to get the correct price ID
        $stripeSubscription = \Stripe\Subscription::retrieve($subscriptionId);
        $stripePriceId = $stripeSubscription->items->data[0]->price->id ?? null;

        // Map price ID to PETSAppeal plan name
        $planNames = [
            config('services.stripe.price_starter') => 'PETSAppeal Starter',
            config('services.stripe.price_pro')     => 'PETSAppeal Pro',
            config('services.stripe.price_multi')   => 'PETSAppeal Multi-Location',
        ];
        $planName = $planNames[$stripePriceId] ?? 'Unknown Plan';

        $company->subscriptions()->create([
            'name' => 'default',
            'stripe_id' => $subscriptionId,
            'stripe_status' => 'active',
            'stripe_price' => $stripePriceId,
            'quantity' => 1,
            'trial_ends_at' => null,
            'ends_at' => null,
        ]);

        $company->active = true;
        $company->stripe_plan_id = $stripePriceId;
        $company->plan_name = $planName;
        $company->save();
    }

    return view('billing.success');
}



    public function cancelSubscription(Request $request)
{
    $user = Auth::user();

    if ($user->subscribed('default')) {
        $user->subscription('default')->cancel();

        return redirect()->back()->with('success', 'Your subscription has been canceled. You will retain access until the end of your billing period.');
    }

    return redirect()->back()->with('error', 'No active subscription found.');
}


public function myPlan()
{
    $company = auth()->user()->company;

    $subscription = $company->subscription('default');
    $onTrial = $company->onTrial();
    $trialEndsAt = $company->trial_ends_at;
    $endsAt = $subscription?->ends_at;

    // Map Stripe price ID to readable plan names
    $priceId = $subscription?->stripe_price;
    $planNames = [
        config('services.stripe.price_starter') => 'PETSAppeal Starter',
        config('services.stripe.price_pro')     => 'PETSAppeal Pro',
        config('services.stripe.price_multi')   => 'PETSAppeal Multi-Location',
    ];
    $plan = $planNames[$priceId] ?? 'Unknown Plan';

    return view('billing.my-plan', compact('subscription', 'onTrial', 'trialEndsAt', 'endsAt', 'plan'));
}


    public function myHistory()
    {
        $company = auth()->user()->company;
        $invoices = $company->invoices();

        return view('billing.my-history', compact('invoices'));
    }
}

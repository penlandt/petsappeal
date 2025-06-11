<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Laravel\Cashier\Subscription;

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

            $company->subscriptions()->create([
                'name' => 'default',
                'stripe_id' => $subscriptionId,
                'stripe_status' => 'active',
                'stripe_price' => $latestSession->display_items[0]->price->id ?? null,
                'quantity' => 1,
                'trial_ends_at' => null,
                'ends_at' => null,
            ]);

            $company->is_active = true;
            $company->stripe_plan_id = $latestSession->display_items[0]->price->id ?? null;
            $company->plan_name = 'Unknown'; // You can map it if needed
            $company->save();
        }

        return view('billing.success');
    }

    public function cancel()
    {
        $company = Auth::user()->company;

        $subscription = $company->subscription('default');

        if ($subscription && $subscription->valid()) {
            $subscription->cancel(); // cancels at end of billing period
            $company->is_active = false;
            $company->save();
        }

        return redirect()->route('billing.plans')->with('status', 'Your subscription has been canceled. You will retain access until the end of your current billing period.');
    }

    public function myPlan()
    {
        $company = auth()->user()->company;

        $subscription = $company->subscription('default');
        $onTrial = $company->onTrial();
        $trialEndsAt = $company->trial_ends_at;
        $endsAt = $subscription?->ends_at;
        $plan = $subscription?->stripe_price;

        return view('billing.my-plan', compact('subscription', 'onTrial', 'trialEndsAt', 'endsAt', 'plan'));
    }

    public function myHistory()
    {
        $company = auth()->user()->company;
        $invoices = $company->invoices();

        return view('billing.my-history', compact('invoices'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Laravel\Cashier\Subscription;
use Stripe\Subscription as StripeSubscription;
use App\Mail\SubscriptionSuccessMail;
use App\Services\CompanyMailer;
use App\Mail\SubscriptionCanceledMail;

class BillingController extends Controller
{
    public function showPlans()
{
    $plans = [
        [
            'name' => 'Starter',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.price_starter'),
            'annual_price_id' => config('services.stripe.price_starter_annual'),
            'features' => ['1 Location', 'Basic Support'],
        ],
        [
            'name' => 'Pro',
            'monthly_price' => 99,
            'annual_price' => 999,
            'monthly_price_id' => config('services.stripe.price_pro'),
            'annual_price_id' => config('services.stripe.price_pro_annual'),
            'features' => ['Up to 3 Locations', 'Priority Support'],
        ],
        [
            'name' => 'Multi-Location',
            'monthly_price' => 149,
            'annual_price' => 1499,
            'monthly_price_id' => config('services.stripe.price_multi'),
            'annual_price_id' => config('services.stripe.price_multi_annual'),
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
    $newPriceId = $request->price_id;

    $planWeights = [
        config('services.stripe.price_starter') => 1,
        config('services.stripe.price_pro') => 2,
        config('services.stripe.price_multi') => 3,
    ];

    $currentSub = $user->subscription('default');

    if ($currentSub && $currentSub->valid()) {
        $currentPriceId = $currentSub->stripe_price ?? null;

        if ($currentPriceId === $newPriceId) {
            return redirect()->route('billing.my-plan')
                ->with('error', 'You are already subscribed to this plan.');
        }

        $currentWeight = $planWeights[$currentPriceId] ?? 0;
        $newWeight = $planWeights[$newPriceId] ?? 0;

        if ($newWeight > $currentWeight) {
            // Upgrade - apply immediately
            $currentSub->swap($newPriceId);
            return redirect()->route('billing.my-plan')
                ->with('success', 'Your subscription has been upgraded.');
        } else {
            // Downgrade - schedule for next billing cycle
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            \Stripe\Subscription::update(
                $currentSub->stripe_id,
                [
                    'cancel_at_period_end' => true,
                    'metadata' => [
                        'scheduled_downgrade_price_id' => $newPriceId,
                    ],
                ]
            );

            return redirect()->route('billing.my-plan')
                ->with('success', 'Your downgrade has been scheduled for the end of the current billing period.');
        }
    }

    // No current subscription - create new
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $session = \Stripe\Checkout\Session::create([
        'customer_email' => $user->email,
        'payment_method_types' => ['card'],
        'mode' => 'subscription',
        'line_items' => [[
            'price' => $newPriceId,
            'quantity' => 1,
        ]],
        'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('billing.cancel'),
        'metadata' => [
            'company_id' => $company->id,
            'price_id' => $newPriceId,
        ],
    ]);

    return redirect($session->url);
}


    public function success(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        Stripe::setApiKey(config('cashier.secret'));

        $sessions = StripeSession::all([
            'customer' => $company->stripe_id,
            'limit' => 1,
        ]);

        $latestSession = $sessions->data[0] ?? null;

        \Log::info('Billing success hit', ['session' => $latestSession]);

        if ($latestSession && $latestSession->subscription) {
            $subscriptionId = $latestSession->subscription;

            $stripeSubscription = \Stripe\Subscription::retrieve($subscriptionId);
            $stripePriceId = $stripeSubscription->items->data[0]->price->id ?? null;
            $currentPeriodEnd = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end);


            $planNames = [
                config('services.stripe.price_starter') => 'PETSAppeal Starter',
                config('services.stripe.price_pro')     => 'PETSAppeal Pro',
                config('services.stripe.price_multi')   => 'PETSAppeal Multi-Location',
            ];
            $planName = $planNames[$stripePriceId] ?? 'Unknown Plan';

            $existing = $company->subscriptions()
                ->where('stripe_id', $subscriptionId)
                ->first();

            if (!$existing) {
                $company->subscriptions()->create([
                    'name' => 'default',
                    'stripe_id' => $subscriptionId,
                    'stripe_status' => 'active',
                    'stripe_price' => $stripePriceId,
                    'quantity' => 1,
                    'trial_ends_at' => null,
                    'ends_at' => null,
                    'stripe_current_period_end' => $currentPeriodEnd,
                ]);
            }

            $company->active = true;
            $company->plan_name = $planName;
            $company->save();

            // ✅ Send welcome email
            CompanyMailer::to($company->email)->send(new SubscriptionSuccessMail($company, $planName));
        }

        return view('billing.success');
    }

    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();
    
        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();
    
            // ✅ Send cancellation confirmation email
            CompanyMailer::to($user->company->email)
                ->send(new SubscriptionCanceledMail($user->company));
    
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

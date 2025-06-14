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
use App\Models\CompanySubscribedModule;


class BillingController extends Controller
{
    public function showPlans()
{
    $plans = [
        [
            'type' => 'module',
            'slug' => 'grooming',
            'name' => 'Grooming',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.grooming.monthly'),
            'annual_price_id' => config('services.stripe.modules.grooming.annual'),
            'features' => ['Pet Grooming Scheduler', 'Client & Pet Management', 'Service Tracking & Notes'],
        ],
        [
            'type' => 'module',
            'slug' => 'boarding',
            'name' => 'Boarding',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.boarding.monthly'),
            'annual_price_id' => config('services.stripe.modules.boarding.annual'),
            'features' => ['Boarding Reservations', 'Multi-Pet Units', 'Check-In/Out Times'],
        ],
        [
            'type' => 'module',
            'slug' => 'daycare',
            'name' => 'Daycare',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.daycare.monthly'),
            'annual_price_id' => config('services.stripe.modules.daycare.annual'),
            'features' => ['Full/Half Day Check-Ins', 'Attendance Tracking', 'Capacity Management'],
        ],
        [
            'type' => 'module',
            'slug' => 'sitting',
            'name' => 'Pet & House Sitting',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.sitting.monthly'),
            'annual_price_id' => config('services.stripe.modules.sitting.annual'),
            'features' => ['In-Home Visit Scheduling', 'Client Instructions', 'Visit Logs & Notes'],
        ],
    ];

    $supportOptions = [
        [
            'name' => 'Priority Email/Chat Support',
            'slug' => 'chat',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.support.chat.monthly'),
            'annual_price_id' => config('services.stripe.support.chat.annual'),
        ],
        [
            'name' => 'Real-Time Phone/Screen-Sharing Support',
            'slug' => 'phone',
            'monthly_price' => 99,
            'annual_price' => 999,
            'monthly_price_id' => config('services.stripe.support.phone.monthly'),
            'annual_price_id' => config('services.stripe.support.phone.annual'),
        ],
    ];

    $company = Auth::user()?->company;

    return view('billing.plans', compact('plans', 'supportOptions', 'company'));
}


public function checkout(Request $request)
{
    \Log::debug('Checkout POST data', $request->all());

    $request->validate([
        'price_ids' => 'required|array|min:1',
        'price_ids.*' => 'string',
        'support_price_id' => 'nullable|string',
    ]);

    $user = Auth::user();
    $company = $user->company;

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $lineItems = [];

    foreach ($request->price_ids as $priceId) {
        $lineItems[] = [
            'price' => $priceId,
            'quantity' => 1,
        ];
    }

    if ($request->filled('support_price_id')) {
        $lineItems[] = [
            'price' => $request->support_price_id,
            'quantity' => 1,
        ];
    }

    $session = \Stripe\Checkout\Session::create([
        'customer_email' => $user->email,
        'payment_method_types' => ['card'],
        'mode' => 'subscription',
        'line_items' => $lineItems,
        'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('billing.plans', ['cancelled' => 1]),
        'metadata' => [
            'company_id' => $company->id,
        ],
    ]);

    return redirect($session->url);
}



public function success(Request $request)
{
    $user = Auth::user();
    $company = $user->company;

    \Stripe\Stripe::setApiKey(config('cashier.secret'));

    $sessions = StripeSession::all([
        'customer' => $company->stripe_id,
        'limit' => 1,
    ]);

    $latestSession = $sessions->data[0] ?? null;

    \Log::info('Billing success hit', ['session' => $latestSession]);

    if ($latestSession && $latestSession->subscription) {
        $subscriptionId = $latestSession->subscription;
        $isUpgrade = isset($latestSession->metadata['upgrade_flow']) && $latestSession->metadata['upgrade_flow'];

        $stripeSub = \Stripe\Subscription::retrieve($subscriptionId);
        $stripeItems = $stripeSub->items->data ?? [];
        $priceIds = collect($stripeItems)->pluck('price.id')->toArray();
        $currentPeriodEnd = \Carbon\Carbon::createFromTimestamp($stripeSub->current_period_end);

        if ($isUpgrade && $company->subscribed('default')) {
            $company->subscription('default')->cancelNow();
        }

        $existing = $company->subscriptions()
            ->where('stripe_id', $subscriptionId)
            ->first();

        if (!$existing) {
            $company->subscriptions()->create([
                'name' => 'default',
                'stripe_id' => $subscriptionId,
                'stripe_status' => 'active',
                'stripe_price' => json_encode($priceIds),
                'quantity' => count($priceIds),
                'trial_ends_at' => null,
                'ends_at' => null,
                'stripe_current_period_end' => $currentPeriodEnd,
            ]);
        }

        $company->active = true;
        $company->plan_name = 'Custom Plan';
        $company->save();

        // ✅ Store subscribed modules in company_subscribed_modules
        $moduleMap = [
            config('services.stripe.modules.grooming.monthly') => 'grooming',
            config('services.stripe.modules.grooming.annual') => 'grooming',
            config('services.stripe.modules.boarding.monthly') => 'boarding',
            config('services.stripe.modules.boarding.annual') => 'boarding',
            config('services.stripe.modules.daycare.monthly') => 'daycare',
            config('services.stripe.modules.daycare.annual') => 'daycare',
            config('services.stripe.modules.sitting.monthly') => 'sitting',
            config('services.stripe.modules.sitting.annual') => 'sitting',
        ];

        $moduleSlugs = collect($priceIds)
            ->map(fn($id) => $moduleMap[$id] ?? null)
            ->filter()
            ->unique();

        foreach ($moduleSlugs as $slug) {
            CompanySubscribedModule::updateOrCreate(
                ['company_id' => $company->id, 'module' => $slug],
                ['created_at' => now()]
            );
        }


        CompanyMailer::to($company->email)->send(new SubscriptionSuccessMail($company, 'Custom Plan'));
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

    $priceIds = json_decode($subscription?->stripe_price ?? '[]', true);

    $priceLabels = [
        config('services.stripe.modules.grooming.monthly') => 'Grooming (Monthly)',
        config('services.stripe.modules.grooming.annual')  => 'Grooming (Annual)',
        config('services.stripe.modules.boarding.monthly') => 'Boarding (Monthly)',
        config('services.stripe.modules.boarding.annual')  => 'Boarding (Annual)',
        config('services.stripe.modules.daycare.monthly')  => 'Daycare (Monthly)',
        config('services.stripe.modules.daycare.annual')   => 'Daycare (Annual)',
        config('services.stripe.modules.sitting.monthly')  => 'Pet & House Sitting (Monthly)',
        config('services.stripe.modules.sitting.annual')   => 'Pet & House Sitting (Annual)',
        config('services.stripe.support.chat.monthly')     => 'Priority Email/Chat Support (Monthly)',
        config('services.stripe.support.chat.annual')      => 'Priority Email/Chat Support (Annual)',
        config('services.stripe.support.phone.monthly')    => 'Real-Time Phone/Screen Sharing (Monthly)',
        config('services.stripe.support.phone.annual')     => 'Real-Time Phone/Screen Sharing (Annual)',
    ];

    $plan = collect($priceIds)
        ->map(fn($id) => $priceLabels[$id] ?? 'Unknown')
        ->join(', ');

    $plans = [
        [
            'type' => 'module',
            'slug' => 'grooming',
            'name' => 'Grooming',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.grooming.monthly'),
            'annual_price_id' => config('services.stripe.modules.grooming.annual'),
        ],
        [
            'type' => 'module',
            'slug' => 'boarding',
            'name' => 'Boarding',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.boarding.monthly'),
            'annual_price_id' => config('services.stripe.modules.boarding.annual'),
        ],
        [
            'type' => 'module',
            'slug' => 'daycare',
            'name' => 'Daycare',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.daycare.monthly'),
            'annual_price_id' => config('services.stripe.modules.daycare.annual'),
        ],
        [
            'type' => 'module',
            'slug' => 'sitting',
            'name' => 'Pet & House Sitting',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.sitting.monthly'),
            'annual_price_id' => config('services.stripe.modules.sitting.annual'),
        ],
    ];

    $supportOptions = [
        [
            'name' => 'Priority Email/Chat Support',
            'slug' => 'chat',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.support.chat.monthly'),
            'annual_price_id' => config('services.stripe.support.chat.annual'),
        ],
        [
            'name' => 'Real-Time Phone/Screen-Sharing Support',
            'slug' => 'phone',
            'monthly_price' => 99,
            'annual_price' => 999,
            'monthly_price_id' => config('services.stripe.support.phone.monthly'),
            'annual_price_id' => config('services.stripe.support.phone.annual'),
        ],
    ];

    $plans = [
        [
            'slug' => 'grooming',
            'name' => 'Grooming',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.grooming.monthly'),
            'annual_price_id' => config('services.stripe.modules.grooming.annual'),
        ],
        [
            'slug' => 'boarding',
            'name' => 'Boarding',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.boarding.monthly'),
            'annual_price_id' => config('services.stripe.modules.boarding.annual'),
        ],
        [
            'slug' => 'daycare',
            'name' => 'Daycare',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.daycare.monthly'),
            'annual_price_id' => config('services.stripe.modules.daycare.annual'),
        ],
        [
            'slug' => 'sitting',
            'name' => 'Pet & House Sitting',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.modules.sitting.monthly'),
            'annual_price_id' => config('services.stripe.modules.sitting.annual'),
        ],
    ];
    
    $supportOptions = [
        [
            'name' => 'Priority Email/Chat Support',
            'slug' => 'chat',
            'monthly_price' => 49,
            'annual_price' => 499,
            'monthly_price_id' => config('services.stripe.support.chat.monthly'),
            'annual_price_id' => config('services.stripe.support.chat.annual'),
        ],
        [
            'name' => 'Real-Time Phone/Screen-Sharing Support',
            'slug' => 'phone',
            'monthly_price' => 99,
            'annual_price' => 999,
            'monthly_price_id' => config('services.stripe.support.phone.monthly'),
            'annual_price_id' => config('services.stripe.support.phone.annual'),
        ],
    ];
    

    return view('billing.my-plan', compact('subscription', 'onTrial', 'trialEndsAt', 'endsAt', 'plan', 'plans', 'supportOptions'));

}


    public function myHistory()
    {
        $company = auth()->user()->company;
        $invoices = $company->invoices();

        return view('billing.my-history', compact('invoices'));
    }

    public function updateSubscription(Request $request)
{
    $request->validate([
        'price_ids' => 'required|array|min:1',
        'price_ids.*' => 'string',
        'support_price_id' => 'nullable|string',
    ]);

    $user = Auth::user();
    $company = $user->company;

    // Cancel current subscription at period end
    $existing = $company->subscription('default');
    if ($existing && $existing->valid()) {
        $existing->cancel();
    }

    // Set up new checkout session
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $lineItems = [];

    foreach ($request->price_ids as $priceId) {
        $lineItems[] = [
            'price' => $priceId,
            'quantity' => 1,
        ];
    }

    if ($request->filled('support_price_id')) {
        $lineItems[] = [
            'price' => $request->support_price_id,
            'quantity' => 1,
        ];
    }

    $session = \Stripe\Checkout\Session::create([
        'customer_email' => $user->email,
        'payment_method_types' => ['card'],
        'mode' => 'subscription',
        'line_items' => $lineItems,
        'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('billing.my-plan'),
        'metadata' => [
            'company_id' => $company->id,
            'upgrade_flow' => true,
        ],
    ]);

    return redirect($session->url);
}

public function downgradeSubscription(Request $request)
{
    $user = Auth::user();
    $company = $user->company;

    if ($company->subscribed('default')) {
        $company->subscription('default')->cancel(); // Grace period

        return redirect()->back()->with('success', 'Your subscription has been downgraded. You will retain access until the end of your billing cycle.');
    }

    return redirect()->back()->with('error', 'No active subscription found.');
}

    
}

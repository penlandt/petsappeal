<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
{
    $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $event = \Stripe\Webhook::constructEvent(
            $payload, $sigHeader, $endpointSecret
        );

        Log::info('✅ Stripe Webhook Validated: ' . $event->type);
    } catch (\Exception $e) {
        Log::error('❌ Stripe Webhook Error: ' . $e->getMessage());
        return response('Invalid webhook signature', 400);
    }

    return response('Webhook handled', 200);
}

}

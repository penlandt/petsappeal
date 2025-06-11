<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Stripe\Webhook;
use Stripe\Stripe;
use UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        if (empty($secret)) {
            Log::warning('Stripe webhook secret is not configured.');
            return response('Webhook secret not set', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Invalid Stripe webhook payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', Response::HTTP_BAD_REQUEST);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Invalid Stripe webhook signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', Response::HTTP_BAD_REQUEST);
        }

        // Handle the event types you care about
        switch ($event->type) {
            case 'checkout.session.completed':
                Log::info('Checkout session completed.', ['data' => $event->data->object]);
                break;
            case 'invoice.payment_failed':
                Log::info('Invoice payment failed.', ['data' => $event->data->object]);
                break;
            case 'customer.subscription.created':
                Log::info('Subscription created.', ['data' => $event->data->object]);
                break;
            // Add more cases as needed
            default:
                Log::info('Unhandled event type: ' . $event->type);
        }

        return response('Webhook handled', Response::HTTP_OK);
    }
}

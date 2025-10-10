<?php

// app/Http/Controllers/StripeWebhookController.php
namespace App\Http\Controllers;

use App\Models\StudentIntakeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentIntakeStripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $signature    = $request->header('Stripe-Signature');
        $payload      = $request->getContent();
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $pi = $event->data->object; // \Stripe\PaymentIntent
            $submissionId = $pi->metadata->submission_id ?? null;

            if ($submissionId) {
                $submission = StudentIntakeSubmission::find($submissionId);
                if ($submission && $submission->status !== 'paid') {
                    // Move documents from tmp to final
                    $finalDir = 'student_intake/final/'.$submission->id;
                    $paths = $submission->document_paths ?? [];

                    $newPaths = [];
                    foreach ($paths as $p) {
                        if (Storage::disk('public')->exists($p)) {
                            $filename = basename($p);
                            $newPath  = $finalDir.'/'.$filename;
                            Storage::disk('public')->makeDirectory($finalDir);
                            Storage::disk('public')->move($p, $newPath);
                            $newPaths[] = $newPath;
                        }
                    }

                    $submission->update([
                        'status'         => 'paid',
                        'submitted_at'   => now(),
                        'document_paths' => $newPaths,
                    ]);

                    // TODO: notify user/admin if you want (Mail, Notification, etc.)
                }
            }
        } elseif ($event->type === 'payment_intent.payment_failed') {
            $pi = $event->data->object;
            $submissionId = $pi->metadata->submission_id ?? null;
            if ($submissionId) {
                $submission = StudentIntakeSubmission::find($submissionId);
                if ($submission && $submission->status !== 'paid') {
                    $submission->update(['status' => 'failed']);
                }
            }
        }

        return response('OK', 200);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\AppointmentReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\GenericEmailTemplate;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send 1-week and 1-day appointment reminders to clients.';

    public function handle(): void
    {
        $now = Carbon::now();

        $datesToCheck = [
            '1_week' => $now->copy()->addDays(7)->toDateString(),
            '1_day' => $now->copy()->addDays(1)->toDateString(),
        ];

        foreach ($datesToCheck as $type => $targetDate) {
            \Log::info("🔍 Checking for {$type} reminders for date: {$targetDate}");
        
            $appointments = Appointment::with(['pet.client', 'service', 'staff', 'location.company'])
                ->whereDate('start_time', $targetDate)
                ->whereNotIn('status', ['Cancelled', 'No-Show'])
                ->get();
        
            \Log::info("Found {$appointments->count()} appointments for {$type} reminder.");
        

            foreach ($appointments as $appt) {
                \Log::info("⏳ Processing appointment ID {$appt->appointment_id}");

                $alreadySent = AppointmentReminder::where('appointment_id', $appt->appointment_id)
                    ->where('reminder_type', $type)
                    ->exists();

                if ($alreadySent) {
                    \Log::info("🔁 Reminder already sent for appointment {$appt->appointment_id} and type {$type}");
                    continue;
                }

                $client = $appt->pet->client ?? null;
                $service = $appt->service;
                $staff = $appt->staff;
                $location = $appt->location;

                if (!$client || !$client->email) {
                    continue;
                }

                $template = \App\Models\EmailTemplate::where('company_id', $appt->location->company_id ?? null)
                    ->where('type', 'grooming')
                    ->where('template_key', "appointment_{$type}")
                    ->first();

                if (!$template) {
                    \Log::info("⚠️ No template found for company {$appt->company_id} and type appointment_{$type}");
                    continue;
                }

                $replacements = [
                    '{{ client_name }}' => $client->first_name . ' ' . $client->last_name,
                    '{{ pet_name }}' => $appt->pet->name,
                    '{{ service_name }}' => $service->name,
                    '{{ staff_name }}' => $staff->first_name . ' ' . $staff->last_name,
                    '{{ appointment_date }}' => Carbon::parse($appt->start_time)->format('F j, Y'),
                    '{{ appointment_time }}' => Carbon::parse($appt->start_time)->format('g:i A'),
                    '{{ location_name }}' => $location->name ?? '',
                    '{{ company_name }}' => $appt->location->company->name ?? '',
                ];

                $html = strtr($template->body_html, $replacements);
                $plain = strtr($template->body_plain, $replacements);
                $subject = strtr($template->subject, $replacements);

                try {
                    \App\Services\CompanyMailer::to($client->email)->send(new GenericEmailTemplate($subject, $html, $plain));

                    AppointmentReminder::create([
                        'appointment_id' => $appt->appointment_id,
                        'reminder_type' => $type,
                        'sent_at' => now(),
                    ]);

                    $this->info("Sent {$type} reminder to {$client->email} for appointment {$appt->appointment_id}");
                } catch (\Throwable $e) {
                    \Log::error("Failed to send {$type} reminder: " . $e->getMessage());
                }
            }
        }
    }
}

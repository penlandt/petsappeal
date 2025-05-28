<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\AppointmentRecurrenceRule;
use App\Models\AppointmentRecurringConflict;
use App\Models\StaffAvailability;
use App\Models\StaffAvailabilityException;
use Carbon\Carbon;

class ExtendRecurringAppointments extends Command
{
    protected $signature = 'appointments:extend-recurring';

    protected $description = 'Extend recurring appointments to maintain a 6-month future window, skipping dates when staff are unavailable and logging conflicts';

    public function handle()
    {
        $now = Carbon::now();
        $horizon = $now->copy()->addMonths(6);

        $this->info("Extending recurring appointments through {$horizon->toDateString()}...");

        $rules = AppointmentRecurrenceRule::all();
        $totalCreated = 0;
        $totalSkipped = 0;
        $skippedAppointments = [];

        foreach ($rules as $rule) {
            $latest = Appointment::where('recurrence_group_id', $rule->recurrence_group_id)
                ->orderByDesc('start_time')
                ->first();

            if (!$latest) {
                $this->warn("No appointments found for recurrence group {$rule->recurrence_group_id}. Skipping.");
                continue;
            }

            $next = Carbon::parse($latest->start_time);

            if ($rule->repeat_type === 'weekly') {
                $next->addWeeks($rule->repeat_interval);
            } elseif ($rule->repeat_type === 'monthly') {
                $next->addMonths($rule->repeat_interval);
            } else {
                $this->warn("Unknown repeat_type '{$rule->repeat_type}' for group {$rule->recurrence_group_id}. Skipping.");
                continue;
            }

            while ($next->lte($horizon)) {
                $dayOfWeek = strtolower($next->format('l')); // e.g. "monday"
                $startTime = $next->format('H:i:s');
                $date = $next->toDateString();

                $availability = StaffAvailability::where('staff_id', $rule->staff_id)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('start_time', '<=', $startTime)
                    ->where('end_time', '>', $startTime)
                    ->first();

                $isException = StaffAvailabilityException::where('staff_id', $rule->staff_id)
                    ->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date)
                    ->exists();

                if (!$availability || $isException) {
                    $reason = !$availability ? 'Unavailable' : 'Exception';

                    AppointmentRecurringConflict::create([
                        'recurrence_group_id' => $rule->recurrence_group_id,
                        'staff_id' => $rule->staff_id,
                        'conflict_date' => $date,
                        'conflict_time' => $startTime,
                        'reason' => $reason,
                    ]);

                    $skippedAppointments[] = "{$date} at " . $next->format('g:i A') . " ({$reason})";
                    $totalSkipped++;

                    if ($rule->repeat_type === 'weekly') {
                        $next->addWeeks($rule->repeat_interval);
                    } elseif ($rule->repeat_type === 'monthly') {
                        $next->addMonths($rule->repeat_interval);
                    }

                    continue;
                }

                Appointment::create([
                    'location_id' => $rule->location_id,
                    'staff_id' => $rule->staff_id,
                    'pet_id' => $rule->pet_id,
                    'service_id' => $rule->service_id,
                    'price' => $rule->price,
                    'notes' => $rule->notes,
                    'start_time' => $next->copy(),
                    'recurrence_group_id' => $rule->recurrence_group_id,
                    'status' => 'Booked',
                ]);

                $this->info("Created appointment on {$date} at " . $next->format('g:i A') . " for group {$rule->recurrence_group_id}");
                $totalCreated++;

                if ($rule->repeat_type === 'weekly') {
                    $next->addWeeks($rule->repeat_interval);
                } elseif ($rule->repeat_type === 'monthly') {
                    $next->addMonths($rule->repeat_interval);
                }
            }
        }

        $this->info("✅ Created {$totalCreated} appointments.");
        if ($totalSkipped > 0) {
            $this->warn("⚠️ Skipped {$totalSkipped} appointments due to staff unavailability:");
            foreach ($skippedAppointments as $entry) {
                $this->line("  - {$entry}");
            }
        } else {
            $this->info("✅ No conflicts detected.");
        }

        $this->info('Recurring appointment extension complete.');
    }
}

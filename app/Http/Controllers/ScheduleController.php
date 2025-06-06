<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Staff;
use App\Models\StaffAvailability;
use App\Models\StaffAvailabilityException;
use App\Models\Client;
use App\Models\Service;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::check() ? Auth::user() : null;

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $companyId = $user->company_id;

        $locations = Location::where('company_id', $companyId)
            ->where('inactive', false)
            ->orderBy('name')
            ->get();

        $locationIds = $locations->pluck('id')->toArray();

        $staff = Staff::where('company_id', $companyId)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->orderBy('last_name')
            ->get();


        // If missing location or staff, skip calendar setup
        if ($locations->isEmpty() || $staff->isEmpty()) {
            return view('modules.grooming.schedule', [
                'canSchedule' => false,
                'locations' => $locations,
                'staff' => $staff,
            ]);
        }

        $defaultLocation = $locations->first();
        $selectedLocationId = $request->query('location_id', $defaultLocation->id);
        $selectedDate = $request->input('date', now()->toDateString());
        $dayOfWeek = Carbon::parse($selectedDate)->format('l');

        $clients = Client::where('company_id', $companyId)->orderBy('last_name')->get();
        $services = Service::where('company_id', $companyId)->orderBy('name')->get();

        $backgroundEvents = [];

        foreach ($staff as $member) {
            $weekly = StaffAvailability::where('staff_id', $member->id)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            if ($weekly) {
                $start = strtoupper(trim($weekly->start_time));
                $end = strtoupper(trim($weekly->end_time));

                if ($start === 'OFF' || $end === 'OFF' || empty($start) || empty($end)) {
                    $backgroundEvents[] = [
                        'resourceId' => $member->id,
                        'start' => $selectedDate . 'T00:00:00',
                        'end' => $selectedDate . 'T23:59:59',
                        'display' => 'background',
                        'color' => '#cccccc',
                    ];
                } else {
                    $backgroundEvents[] = [
                        'resourceId' => $member->id,
                        'start' => $selectedDate . 'T00:00:00',
                        'end' => $selectedDate . 'T' . $start,
                        'display' => 'background',
                        'color' => '#eeeeee',
                    ];
                    $backgroundEvents[] = [
                        'resourceId' => $member->id,
                        'start' => $selectedDate . 'T' . $end,
                        'end' => $selectedDate . 'T23:59:59',
                        'display' => 'background',
                        'color' => '#eeeeee',
                    ];
                }
            } else {
                $backgroundEvents[] = [
                    'resourceId' => $member->id,
                    'start' => $selectedDate . 'T00:00:00',
                    'end' => $selectedDate . 'T23:59:59',
                    'display' => 'background',
                    'color' => '#cccccc',
                ];
            }

            $exceptions = StaffAvailabilityException::where('staff_id', $member->id)
                ->whereDate('start_date', '<=', $selectedDate)
                ->whereDate('end_date', '>=', $selectedDate)
                ->get();

            foreach ($exceptions as $ex) {
                $isSameDay = $ex->start_date === $ex->end_date;
                $hasTimes = !empty($ex->start_time) && !empty($ex->end_time);

                if ($isSameDay && $hasTimes) {
                    $backgroundEvents[] = [
                        'resourceId' => $member->id,
                        'start' => $selectedDate . 'T' . $ex->start_time,
                        'end' => $selectedDate . 'T' . $ex->end_time,
                        'display' => 'background',
                        'color' => '#ffaaaa',
                    ];
                } else {
                    $backgroundEvents[] = [
                        'resourceId' => $member->id,
                        'start' => $selectedDate . 'T00:00:00',
                        'end' => $selectedDate . 'T23:59:59',
                        'display' => 'background',
                        'color' => '#ffaaaa',
                    ];
                }
            }
        }

        return view('modules.grooming.schedule', [
            'canSchedule' => true,
            'locations' => $locations,
            'defaultLocation' => $defaultLocation,
            'selectedLocationId' => $selectedLocationId,
            'selectedDate' => $selectedDate,
            'staff' => $staff,
            'clients' => $clients,
            'services' => $services,
            'backgroundEvents' => $backgroundEvents,
        ]);
    }
}

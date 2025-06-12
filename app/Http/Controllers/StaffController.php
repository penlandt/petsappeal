<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Location;
use App\Models\StaffAvailability;
use App\Models\StaffAvailabilityException;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $showPast = $request->query('showPast') === '1';
        $filter = $request->query('filter');

        $query = Staff::where('company_id', $user->company_id);

        if (!$showPast) {
            $query->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->toDateString());
            });
        }

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('first_name', 'like', "%{$filter}%")
                  ->orWhere('last_name', 'like', "%{$filter}%")
                  ->orWhere('job_title', 'like', "%{$filter}%")
                  ->orWhere('phone', 'like', "%{$filter}%")
                  ->orWhere('email', 'like', "%{$filter}%")
                  ->orWhere('type', 'like', "%{$filter}%")
                  ->orWhereHas('location', function ($q2) use ($filter) {
                      $q2->where('name', 'like', "%{$filter}%")
                         ->orWhere('city', 'like', "%{$filter}%")
                         ->orWhere('state', 'like', "%{$filter}%")
                         ->orWhere('postal_code', 'like', "%{$filter}%");
                  });
            });
        }

        $staff = $query->orderBy('last_name')->get();

        if ($request->ajax()) {
            return view('staff.partials.table', compact('staff', 'showPast'))->render();
        }

        return view('staff.index', compact('staff', 'showPast'));
    }

    public function create()
    {
        $user = auth()->user();

        $locations = \App\Models\Location::where('company_id', $user->company_id)
            ->where('inactive', false)
            ->orderBy('name')
            ->get();

        $states = $this->getStates();

        return view('staff.create', compact('locations', 'states'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'type'        => 'required|in:Employee,Independent Contractor',
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'job_title'   => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:255',
            'state'       => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:10',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'notes'       => 'nullable|string|max:1000',
            'availability' => 'array',
        ]);

        $staff = new Staff();
        $staff->company_id  = $user->company_id;
        $staff->type        = $request->input('type');
        $staff->first_name  = $request->input('first_name');
        $staff->last_name   = $request->input('last_name');
        $staff->job_title   = $request->input('job_title');
        $staff->address     = $request->input('address');
        $staff->city        = $request->input('city');
        $staff->state       = $request->input('state');
        $staff->postal_code = $request->input('postal_code');
        $staff->phone       = $request->input('phone');
        $staff->email       = $request->input('email');
        $staff->start_date  = $request->input('start_date');
        $staff->end_date    = $request->input('end_date');
        $staff->notes       = $request->input('notes');
        $staff->save();

        foreach ($request->input('availability', []) as $day => $times) {
            $start = ($times['start_time'] === 'OFF') ? '00:00:00' : $times['start_time'];
            $end   = ($times['end_time'] === 'OFF')   ? '00:00:00' : $times['end_time'];

            StaffAvailability::create([
                'staff_id'    => $staff->id,
                'day_of_week' => $day,
                'start_time'  => $start,
                'end_time'    => $end,
            ]);
        }

        if (session('onboarding') === true) {
            return redirect()->route('onboarding.step.service');
        }

        return redirect()->route('staff.index');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $staff = Staff::where('company_id', $user->company_id)
            ->with('availabilities', 'availabilityExceptions')
            ->findOrFail($id);

        $locations = Location::where('company_id', $user->company_id)->get();
        $states = $this->getStates();

        return view('staff.edit', compact('staff', 'states', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $staff = Staff::where('company_id', $user->company_id)->findOrFail($id);

        $request->validate([
            'type'        => 'required|in:Employee,Independent Contractor',
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'job_title'   => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:255',
            'state'       => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:10',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'notes'       => 'nullable|string|max:1000',
            'availability' => 'array',
        ]);

        $staff->update($request->only([
            'type', 'first_name', 'last_name', 'job_title',
            'address', 'city', 'state', 'postal_code',
            'phone', 'email', 'start_date', 'end_date', 'notes'
        ]));

        StaffAvailability::where('staff_id', $staff->id)->delete();

        foreach ($request->input('availability', []) as $day => $times) {
            if (isset($times['start_time']) && isset($times['end_time'])) {
                $start = ($times['start_time'] === 'OFF') ? '00:00:00' : $times['start_time'];
                $end   = ($times['end_time'] === 'OFF') ? '00:00:00' : $times['end_time'];

                StaffAvailability::create([
                    'staff_id'    => $staff->id,
                    'day_of_week' => $day,
                    'start_time'  => $start,
                    'end_time'    => $end,
                ]);
            }
        }

        return redirect()->route('staff.index');
    }

    public function storeAvailabilityException(Request $request)
    {
        $user = auth()->user();

        $staff = Staff::whereHas('location', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->findOrFail($request->input('staff_id'));

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        StaffAvailabilityException::create([
            'staff_id' => $staff->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('staff.edit', $staff->id);
    }

    public function destroyAvailabilityException($id)
    {
        $exception = StaffAvailabilityException::findOrFail($id);
        $staffId = $exception->staff_id;
        $exception->delete();

        return redirect()->route('staff.edit', $staffId);
    }

    private function getStates()
    {
        return [
            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
            'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia',
            'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana',
            'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana',
            'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan',
            'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana',
            'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
            'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota',
            'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota',
            'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
            'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
            'WI' => 'Wisconsin', 'WY' => 'Wyoming',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $showPast = $request->query('showPast') === '1';

        $query = \App\Models\Staff::query()
            ->whereHas('location', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            });

        if (!$showPast) {
            $query->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->toDateString());
            });
        }

        $staff = $query->orderBy('last_name')->get();

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
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:Employee,Independent Contractor',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        $staff = \App\Models\Staff::create($validated);

        // Save availability
        $availability = $request->input('availability', []);
        foreach ($availability as $day => $times) {
            $start = $times['start_time'] ?? null;
            $end = $times['end_time'] ?? null;

            // Validate logic: end must be later than start unless OFF
            if ($start !== 'OFF' && $end !== 'OFF' && $start >= $end) {
                return back()->withErrors(["availability.$day" => "On $day, end time must be after start time."])
                             ->withInput();
            }

            \App\Models\StaffAvailability::create([
                'staff_id' => $staff->id,
                'day_of_week' => $day,
                'start_time' => $start,
                'end_time' => $end,
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member added.');
    }

    private function getStates(): array
    {
        return [
            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
            'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii',
            'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
            'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine',
            'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota',
            'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska',
            'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico',
            'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
            'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island',
            'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas',
            'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington',
            'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming',
        ];
    }

    public function edit($id)
    {
        $user = auth()->user();

        $staff = \App\Models\Staff::with(['availabilities', 'availabilityExceptions'])
            ->whereHas('location', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })
            ->findOrFail($id);

        $locations = \App\Models\Location::where('company_id', $user->company_id)
            ->where('inactive', false)
            ->orderBy('name')
            ->get();

        $states = $this->getStates();

        return view('staff.edit', compact('staff', 'states', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $staff = \App\Models\Staff::with('availabilityExceptions')
            ->whereHas('location', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->findOrFail($id);

        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:Employee,Independent Contractor',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $staff->update($validated);

        // Replace availability records
        $staff->availabilities()->delete();
        $availability = $request->input('availability', []);
        foreach ($availability as $day => $times) {
            $start = $times['start_time'] ?? null;
            $end = $times['end_time'] ?? null;

            if ($start !== 'OFF' && $end !== 'OFF' && $start >= $end) {
                return back()->withErrors(["availability.$day" => "On $day, end time must be after start time."])
                             ->withInput();
            }

            \App\Models\StaffAvailability::create([
                'staff_id' => $staff->id,
                'day_of_week' => $day,
                'start_time' => $start,
                'end_time' => $end,
            ]);
        }

        // Save availability exception if present
        if (
            $request->filled('availability_exception.start_date') &&
            $request->filled('availability_exception.end_date')
        ) {
            $ex = $request->validate([
                'availability_exception.start_date' => 'required|date',
                'availability_exception.end_date' => 'required|date|after_or_equal:availability_exception.start_date',
                'availability_exception.start_time' => 'nullable|date_format:H:i',
                'availability_exception.end_time' => 'nullable|date_format:H:i|after:availability_exception.start_time',
            ]);

            \App\Models\AvailabilityException::create([
                'staff_id' => $staff->id,
                'start_date' => $ex['availability_exception']['start_date'],
                'end_date' => $ex['availability_exception']['end_date'],
                'start_time' => $ex['availability_exception']['start_time'] ?? null,
                'end_time' => $ex['availability_exception']['end_time'] ?? null,
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member updated.');
    }


}

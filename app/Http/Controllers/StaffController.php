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

        $query = Staff::with('location')
            ->whereHas('location', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            });

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

        $locations = Location::where('company_id', $user->company_id)
            ->where('inactive', false)
            ->orderBy('name')
            ->get();

        return view('staff.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'type' => 'required|in:Employee,Independent Contractor',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'location_id' => 'required|exists:locations,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $staff = new Staff($request->all());
        $staff->save();

        return redirect()->route('staff.index');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $staff = Staff::whereHas('location', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->with('availabilities', 'availabilityExceptions')
            ->findOrFail($id);

        $locations = Location::where('company_id', $user->company_id)
            ->where('inactive', false)
            ->orderBy('name')
            ->get();

        return view('staff.edit', compact('staff', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $staff = Staff::whereHas('location', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->findOrFail($id);

        $request->validate([
            'type' => 'required|in:Employee,Independent Contractor',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'location_id' => 'required|exists:locations,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.index');
    }

    public function updateAvailability(Request $request, $id)
    {
        $user = auth()->user();

        $staff = Staff::whereHas('location', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->findOrFail($id);

        StaffAvailability::where('staff_id', $staff->id)->delete();

        foreach ($request->input('availability', []) as $day => $slots) {
            foreach ($slots as $slot) {
                if (!empty($slot['start']) && !empty($slot['end'])) {
                    StaffAvailability::create([
                        'staff_id' => $staff->id,
                        'day_of_week' => $day,
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                    ]);
                }
            }
        }

        return redirect()->route('staff.edit', $staff->id);
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
}

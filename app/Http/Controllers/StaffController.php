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

    // ... the rest of the class remains unchanged ...
}

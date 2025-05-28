<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointmentRecurringConflict;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function recurringConflicts()
    {
        $companyId = Auth::user()->company_id;

        $conflicts = AppointmentRecurringConflict::whereHas('staff.location', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->with('staff.location')
            ->orderBy('conflict_date')
            ->orderBy('conflict_time')
            ->get();

        return view('reports.recurring-conflicts', compact('conflicts'));
    } // ← this closing brace was likely missing

    public function deleteConflict($id)
    {
        $conflict = AppointmentRecurringConflict::findOrFail($id);

        $companyId = Auth::user()->company_id;
        if ($conflict->staff->location->company_id !== $companyId) {
            abort(403, 'Unauthorized');
        }

        $conflict->delete();

        return redirect()->route('reports.recurring-conflicts')
            ->with('success', 'Conflict deleted.');
    }
}

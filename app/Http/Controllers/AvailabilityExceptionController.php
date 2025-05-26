<?php

namespace App\Http\Controllers;

use App\Models\AvailabilityException;
use Illuminate\Http\Request;

class AvailabilityExceptionController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'availability_exception' => 'required|array',
            'availability_exception.start_date' => 'required|date',
            'availability_exception.end_date' => 'required|date',
            'availability_exception.start_time' => 'nullable|string',
            'availability_exception.end_time' => 'nullable|string',
        ]);

        $startTime = $validated['availability_exception']['start_time'] ?? null;
        $endTime = $validated['availability_exception']['end_time'] ?? null;

        if ($startTime && $endTime && $startTime >= $endTime) {
            return back()
                ->withErrors(['availability_exception.end_time' => 'End time must be after start time.'])
                ->withInput();
        }

        AvailabilityException::create([
            'staff_id' => $validated['staff_id'],
            'start_date' => $validated['availability_exception']['start_date'],
            'end_date' => $validated['availability_exception']['end_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return back()->with('success', 'Availability exception added.');
    }

    public function destroy($id)
    {
        $exception = AvailabilityException::with('staff.location')->findOrFail($id);

        if ($exception->staff->location->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $exception->delete();

        return back()->with('success', 'Availability exception deleted.');
    }
}

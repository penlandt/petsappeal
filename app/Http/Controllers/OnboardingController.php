<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $company = $user->company;

    // ✅ If onboarding is already complete, redirect to dashboard
    if ($company && $company->onboarding_complete) {
        return redirect()->route('dashboard');
    }

    // Mark onboarding session as active
    session(['onboarding' => true]);

    if (!$company) {
        return redirect()->route('onboarding.step.company');
    }

    if ($company->locations()->count() === 0) {
        return redirect()->route('onboarding.step.location');
    }

    if ($company->staff()->count() === 0) {
        return redirect()->route('onboarding.step.staff');
    }

    if ($company->services()->count() === 0) {
        return redirect()->route('onboarding.step.service');
    }

    // ✅ All steps complete – mark onboarding complete
    if (!$company->onboarding_complete) {
        $company->onboarding_complete = true;
        $company->save();
    }

    // Clear onboarding session flag
    session()->forget('onboarding');

    return view('onboarding.steps.complete');
}


    public function showStep(string $step)
    {
        return view("onboarding.steps.$step");
    }
}

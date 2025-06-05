<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyProgram;

class CompanyLoyaltyProgramController extends Controller
{
    public function edit(Request $request)
    {
        $company = $request->user()->company;
        $program = $company->loyaltyProgram;

        return view('companies.loyalty-program', [
            'program' => $program,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'points_per_dollar' => 'required|numeric|min:0',
            'point_value' => 'required|numeric|min:0',
            'max_discount_percent' => 'required|numeric|min:0|max:100',
        ]);

        $company = $request->user()->company;

        $company->loyaltyProgram()->updateOrCreate(
            [], // no matching condition — it uses the relationship
            $request->only(['points_per_dollar', 'point_value', 'max_discount_percent'])
        );

        return redirect()
            ->route('companies.loyalty-program.edit')
            ->with('success', 'Loyalty program settings saved.');
    }
}

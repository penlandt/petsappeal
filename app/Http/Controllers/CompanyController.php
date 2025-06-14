<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\CompanyModuleAccess;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('id', Auth::user()->company_id)->get();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:companies,slug',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            $company = Company::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'website' => $validated['website'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'active' => true,
                'trial_ends_at' => now()->addDays(15),
            ]);

            // 🚀 Automatically grant all 5 modules during free trial
            $modules = ['grooming', 'boarding', 'daycare', 'sitting', 'pos'];
            foreach ($modules as $module) {
                CompanyModuleAccess::create([
                    'company_id' => $company->id,
                    'module' => $module,
                ]);
            }

            $user = auth()->user();
            $user->company_id = $company->id;
            $user->save();

            auth()->setUser($user->fresh());

            return redirect()->route('onboarding.index')->with('success', 'Company created successfully with a 15-day free trial.');
        } catch (\Exception $e) {
            \Log::error('Company creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('Failed to create company. Please try again.')->withInput();
        }
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:companies,slug,' . $id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $company = Company::findOrFail($id);

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $filename = 'company_' . $company->id . '_logo.' . $logoFile->getClientOriginalExtension();

            $image = \Image::make($logoFile)->resize(null, 80, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $path = storage_path('app/public/company-assets/' . $filename);
            $image->save($path);

            if ($company->logo_path && $company->logo_path !== 'company-assets/' . $filename) {
                \Storage::disk('public')->delete($company->logo_path);
            }

            $company->logo_path = 'company-assets/' . $filename;
        }

        $company->update($request->only([
            'name', 'slug', 'email', 'phone', 'website', 'notes'
        ]));

        return redirect()->route('companies.index')->with('success', 'Company updated.');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted.');
    }
}

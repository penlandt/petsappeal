<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

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
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'website' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    $company = new Company();
    $company->name = $request->name;
    $company->email = $request->email;
    $company->phone = $request->phone;
    $company->website = $request->website;
    $company->notes = $request->notes;
    $company->save();

    return redirect()->route('companies.index')->with('success', 'Company created successfully.');
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
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'website' => 'nullable|string|max:255',
        'notes' => 'nullable|string|max:1000',
    ]);

    $company = Company::findOrFail($id);
    $company->update($request->only([
        'name', 'email', 'phone', 'website', 'notes'
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

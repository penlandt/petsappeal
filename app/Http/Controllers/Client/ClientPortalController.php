<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class ClientPortalController extends Controller
{
    public function showLoginOrRegister($companySlug)
    {
        \Log::info('✅ Entered ClientPortalController@showLoginOrRegister');

        $company = Company::where('slug', $companySlug)->where('active', true)->firstOrFail();

        return view('client.portal.entry', compact('company'));
    }
}

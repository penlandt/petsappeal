<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use Laravel\Cashier\Billable;

class Company extends Model
{
    use HasFactory, Billable;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'notes',
        'logo_path',
        'active',
        'trial_ends_at',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'onboarding_complete', // ✅ Added
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function hasModuleAccess(string $module): bool
    {
        return $this->moduleAccess()->where('module', $module)->exists();
    }

    public function moduleAccess()
    {
        return $this->hasMany(\App\Models\CompanyModuleAccess::class);
    }

    public function hasModule(string $module): bool
    {
        return $this->moduleAccess()->where('module', $module)->exists();
    }

    public function loyaltyProgram()
    {
        return $this->hasOne(LoyaltyProgram::class);
    }

    public function settings()
    {
        return $this->hasOne(CompanySetting::class);
    }

    public function staff()
    {
        return $this->hasMany(\App\Models\Staff::class);
    }
}

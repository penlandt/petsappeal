<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Modules\Invoices\Invoice::class);
    }

    public function availableLoyaltyPoints()
    {
        return $this->loyaltyPointTransactions()
            ->selectRaw('SUM(CASE WHEN type = "earn" THEN points ELSE -points END) as balance')
            ->value('balance') ?? 0;
    }

    public function loyaltyPointTransactions()
    {
        return $this->hasMany(\App\Models\LoyaltyPointTransaction::class);
    }

    public function redeemablePointsFor(float $subtotal)
{
    $program = $this->company->loyaltyProgram;

    if (!$program || $subtotal <= 0) {
        return 0;
    }

    $maxDiscount = $subtotal * ($program->max_discount_percent / 100);
    $maxPoints = floor($maxDiscount / $program->point_value);
    $available = $this->availableLoyaltyPoints();

    return min($available, $maxPoints);
}

public function getFullNameAttribute()
{
    return "{$this->first_name} {$this->last_name}";
}

public function clientUser()
{
    return $this->hasOne(ClientUser::class);
}

}

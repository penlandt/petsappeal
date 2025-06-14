<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class CompanySubscribedModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'module',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

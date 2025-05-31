<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModuleAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'module',
    ];

    protected $table = 'company_module_access';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

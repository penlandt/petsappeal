<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'type',
        'template_key',
        'subject',
        'body_html',
        'body_plain',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

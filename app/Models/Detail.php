<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Detail extends Model
{
    /** @use HasFactory<\Database\Factories\DetailFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'office',
        'production',
        'email',
        'phone',
    ];

    public function company(): hasOne
    {
        return $this->hasOne(Company::class);
    }
}

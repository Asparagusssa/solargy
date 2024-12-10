<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(Detail::class);
    }

    public function customs(): HasMany
    {
        return $this->hasMany(CustomDetail::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBanner extends Model
{
    /** @use HasFactory<\Database\Factories\SubBannerFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'order',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}

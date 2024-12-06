<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBanner extends Model
{
    /** @use HasFactory<\Database\Factories\SubBannerFactory> */
    use HasFactory;

    protected $fillable = [
        'image',
        'order',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePlace extends Model
{
    /** @use HasFactory<\Database\Factories\PurchasePlaceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'image',
        'url',
    ];
}

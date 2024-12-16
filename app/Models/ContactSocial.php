<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'url',
        'image',
        'image_footer'
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function getImageFooterAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}

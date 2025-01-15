<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageLibrary extends Model
{
    protected $fillable = [
        'image',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patent extends Model
{
    protected $fillable = [
        'title',
        'date',
        'file',
        'file_name',
    ];

    public function getFileAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileLibrary extends Model
{
    protected $fillable = [
        'file', 'file_name',
    ];

    public function getFileAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}

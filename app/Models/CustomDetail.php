<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomDetail extends Model
{
    /** @use HasFactory<\Database\Factories\CustomDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'value',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

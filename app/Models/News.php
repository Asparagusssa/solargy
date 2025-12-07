<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    protected $fillable = [
        'title', 'image', 'video', 'date', 'type', 'html', 'promo_id', 'pinned_until',
    ];

    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }
}

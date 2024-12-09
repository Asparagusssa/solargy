<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    /** @use HasFactory<\Database\Factories\PageSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'page_id',
        'title',
        'html',
        'image',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}

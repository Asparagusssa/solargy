<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'url',
        'is_changeable'
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class);
    }

    public function seos(): HasMany
    {
        return $this->hasMany(Seo::class);
    }
}

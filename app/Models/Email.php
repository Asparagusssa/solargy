<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Email extends Model
{
    /** @use HasFactory<\Database\Factories\EmailFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
    ];

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(EmailType::class, 'email_email_type');
    }
}

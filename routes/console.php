<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Models\Promo;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Promo::query()
        ->whereNotNull('end')
        ->where('end', '<', now())
        ->where(function ($q) {
            $q->whereNull('is_archived')
              ->orWhere('is_archived', false);
        })
        ->update(['is_archived' => true]);
})->hourly();
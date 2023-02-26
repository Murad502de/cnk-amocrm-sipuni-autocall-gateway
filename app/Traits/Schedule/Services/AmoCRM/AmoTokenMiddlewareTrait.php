<?php

namespace App\Traits\Schedule\Services\AmoCRM;

use Illuminate\Support\Facades\Log;

trait AmoTokenMiddlewareTrait
{
    public static function boot()
    {
        parent::boot();

        Log::info(__METHOD__, ['AmoTokenMiddlewareTrait TEST']); //DELETE
    }
}

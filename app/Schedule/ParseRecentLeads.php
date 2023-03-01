<?php

namespace App\Schedule;

use Illuminate\Support\Facades\Log;
use App\Models\Lead;

// use App\Traits\Http\Middleware\Services\AmoCrm\amoTokenTrait;

class ParseRecentLeads
{
    // use amoTokenTrait;

    private const PARSE_COUNT = 20;

    public function __construct()
    {
        // self::amoToken();
    }
    public function __invoke()
    {
        Log::info(__METHOD__, [self::PARSE_COUNT]); //DELETE
    }

    
}

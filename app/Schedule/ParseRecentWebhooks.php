<?php

namespace App\Schedule;

use App\Schedule\Webhooks\ChangeStageWebhooks;
use Illuminate\Support\Facades\Log;

class ParseRecentWebhooks
{
    public function __construct()
    {
        Log::info(__METHOD__, ['qwertyuiop']); //DELETE
    }

    public function __invoke()
    {
        (new ChangeStageWebhooks)();
    }
}

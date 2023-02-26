<?php

namespace App\Schedule;

use App\Schedule\Webhooks\ChangeStageWebhooks;
use App\Traits\Http\Middleware\Services\AmoCrm\amoTokenTrait;
use Illuminate\Support\Facades\Log;

class ParseRecentWebhooks
{
    use amoTokenTrait;

    public function __construct()
    {
        self::amoToken();
    }

    public function __invoke()
    {
        (new ChangeStageWebhooks)();
    }
}

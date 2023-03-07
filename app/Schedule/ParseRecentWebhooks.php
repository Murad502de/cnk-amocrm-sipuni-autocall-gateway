<?php

namespace App\Schedule;

use App\Schedule\Webhooks\ChangeStageWebhooks;
use App\Schedule\Webhooks\SipuniCallWebhooks;
use App\Traits\Http\Middleware\Services\AmoCrm\amoTokenTrait;

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
        (new SipuniCallWebhooks)();
    }
}

<?php

namespace App\Schedule;

use App\Schedule\Webhooks\ChangeStageWebhooks;
use App\Traits\Schedule\Services\AmoCRM\AmoTokenMiddlewareTrait;

class ParseRecentWebhooks
{
    use AmoTokenMiddlewareTrait;

    public function __invoke()
    {
        (new ChangeStageWebhooks)();
    }
}

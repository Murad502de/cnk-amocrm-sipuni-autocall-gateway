<?php

namespace App\Schedule\Webhooks;

use App\Models\SipuniWebhooksEvent;

class SipuniCallWebhooks
{
    public function __invoke()
    {
        SipuniWebhooksEvent::parseRecentWebhooks();
    }
}

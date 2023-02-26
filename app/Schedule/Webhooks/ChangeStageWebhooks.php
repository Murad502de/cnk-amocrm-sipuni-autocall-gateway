<?php

namespace App\Schedule\Webhooks;

use App\Models\AmoWebhooksLead;

class ChangeStageWebhooks
{
    public function __invoke()
    {
        AmoWebhooksLead::parseRecentWebhooks();
    }
}

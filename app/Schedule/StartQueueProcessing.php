<?php

namespace App\Schedule;

class StartQueueProcessing
{
    private $localCommand = "cd /var/www/html/ && php artisan queue:work --stop-when-empty";
    private $command      = "cd ~/www/dev.sky-network.pro/api/courier_rec_center/amoCRM/cnk-amocrm-sipuni-autocall/cnk-amocrm-sipuni-autocall-gateway && /opt/php/7.4/bin/php artisan queue:work --stop-when-empty";

    public function __invoke(bool $isLocal = false): string
    {
        return $isLocal ? $this->localCommand : $this->command;
    }
}

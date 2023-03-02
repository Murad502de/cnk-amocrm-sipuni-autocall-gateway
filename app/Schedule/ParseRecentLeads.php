<?php

namespace App\Schedule;

use App\Models\Lead;
use App\Jobs\Sipuni\AddLeadToAutoCallListJob;
use Illuminate\Support\Facades\Log;

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

        $leads = self::getLeads();

        foreach ($leads as $lead) {
            Log::info(__METHOD__, [$lead]); //DELETE
            Log::info(__METHOD__, [$lead->call]); //DELETE

            if ($lead->isBusinessHours()) {
                Log::info(__METHOD__, ['ok']); //DELETE

                AddLeadToAutoCallListJob::dispatch($lead);
            } else {
                Log::info(__METHOD__, ['not ok']); //DELETE
            }
        }
    }

    public static function getLeads()
    {
        return Lead::whereAvailable(true)
            ->orderBy('id', 'asc')
            ->take(self::PARSE_COUNT)
            ->get();
    }
}

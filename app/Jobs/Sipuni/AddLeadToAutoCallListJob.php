<?php

namespace App\Jobs\Sipuni;

use App\Jobs\Middleware\AmoTokenExpirationControl;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddLeadToAutoCallListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $lead;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        Log::info(__METHOD__, [$lead]); //DELETE

        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(__METHOD__, [$this->lead]); //DELETE
    }

    public function middleware()
    {
        return [new AmoTokenExpirationControl];
    }
}

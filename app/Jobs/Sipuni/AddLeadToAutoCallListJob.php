<?php

namespace App\Jobs\Sipuni;

use App\Jobs\Middleware\AmoTokenExpirationControl;
use App\Models\Lead;
use App\Services\SipuniAPI\SipuniAPI;
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
    private $sipuni;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        Log::info(__METHOD__, [$lead]); //DELETE

        $this->lead   = $lead;
        $this->sipuni = new SipuniAPI(
            config('services.sipuni.user'),
            config('services.sipuni.client_secret')
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(__METHOD__); //DELETE

        $this->sipuni->makeCallNumber(
            $this->lead->main_contact_number,
            $this->lead->call->operator_extension_number
        );

        $this->lead->delete();
    }

    public function middleware()
    {
        return [new AmoTokenExpirationControl];
    }
}

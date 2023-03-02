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
        Log::info(__METHOD__, ['sipuni_call_id: ' . $this->lead->call->sipuni_call_id]); //DELETE
        Log::info(__METHOD__, ['main_contact_number: ' . $this->lead->main_contact_number]); //DELETE

        // $this->sipuni->addNumberToAutoCall($this->lead->call->sipuni_call_id, $this->lead->main_contact_number);
        // $this->sipuni->deleteNumberFromAutoCall(
        //     $this->lead->call->sipuni_call_id,
        //     $this->lead->main_contact_number
        // );

        $user       = '042485';
        $phone      = $this->lead->main_contact_number;
        $reverse    = '1';
        $antiaon    = '0';
        $sipnumber  = '206';
        $secret     = '0.sf43lo5l3gs';
        $hashString = join('+', array($antiaon, $phone, $reverse, $sipnumber, $user, $secret));
        $hash       = md5($hashString);
        $url        = 'https://sipuni.com/api/callback/call_number';
        $query      = http_build_query(array(
            'antiaon'   => $antiaon,
            'phone'     => $phone,
            'reverse'   => $reverse,
            'sipnumber' => $sipnumber,
            'user'      => $user,
            'hash'      => $hash,
        ));
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close($ch);

        $this->lead->delete();
    }

    public function middleware()
    {
        return [new AmoTokenExpirationControl];
    }
}

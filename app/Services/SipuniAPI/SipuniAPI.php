<?php

namespace App\Services\SipuniAPI;

use Illuminate\Support\Facades\Log;

class SipuniAPI
{
    private $user;
    private $secretKey;

    public function __construct(string $user, string $secretKey)
    {
        Log::info(__METHOD__, ['SipuniAPI/construct']); //DELETE
        Log::info(__METHOD__, [$user, $secretKey]); //DELETE

        $this->user      = $user;
        $this->secretKey = $secretKey;
    }

    public function addNumberToAutoCall(string $autocallId, string $number)
    {
        Log::info(__METHOD__, [$autocallId, $number]); //DELETE

        $again      = '1';
        $hashString = join('+', [$again, $autocallId, $number, $this->user, $this->secretKey]);
        $hash       = md5($hashString);
        $url        = 'https://sipuni.com/api/autocall/add_number';
        $query      = http_build_query([
            'again'      => $again,
            'autocallId' => $autocallId,
            'number'     => $number,
            'user'       => $this->user,
            'hash'       => $hash,
        ]);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close($ch);

        Log::info(__METHOD__, [$output]); //DELETE
    }
    public function deleteNumberFromAutoCall(string $autocallId, string $number)
    {
        Log::info(__METHOD__, [$autocallId, $number]); //DELETE

        $hashString = join('+', array($autocallId, $number, $this->user, $this->secretKey));
        $hash       = md5($hashString);
        $url        = 'https://sipuni.com/api/autocall/delete_number';
        $query      = http_build_query(array(
            'autocallId' => $autocallId,
            'number'     => $number,
            'user'       => $this->user,
            'hash'       => $hash,
        ));
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close($ch);

        Log::info(__METHOD__, [$output]); //DELETE
    }
    public function makeCallNumber(string $clientNumber, string $operatorNumber)
    {
        Log::info(__METHOD__, [$clientNumber, $operatorNumber]); //DELETE

        $url     = 'https://sipuni.com/api/callback/call_number';
        $reverse = '1';
        $antiaon = '0';
        $query   = http_build_query([
            'antiaon'   => $antiaon,
            'phone'     => $clientNumber,
            'reverse'   => $reverse,
            'sipnumber' => $operatorNumber,
            'user'      => $this->user,
            'hash'      => md5(join('+', [$antiaon, $clientNumber, $reverse, $operatorNumber, $this->user, $this->secretKey])),
        ]);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close($ch);

        Log::info(__METHOD__, [$output]); //DELETE
    }
}

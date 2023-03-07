<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\WebhooksSipuniEventRequest1;
use App\Models\SipuniWebhooksEvent;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhooksSipuniController1 extends Controller
{
    public function index(WebhooksSipuniEventRequest1 $request)
    {
        Log::info(__METHOD__, $request->all()); //DELETE

        if (isset($request->all()['status'])) {
            Log::info(__METHOD__, ['callStatus: ' . $request->all()['status']]); //DELETE

            $this->handle($request->all());
        }

        return response()->json(['success' => true], Response::HTTP_OK);
    }

    private function handle(array $data)
    {
        if (isset($data['dst_num'])) {
            if (SipuniWebhooksEvent::whereDstNum($data['dst_num'])->exists()) {
                SipuniWebhooksEvent::updateWebhook($data['dst_num'], $data);
            } else {
                SipuniWebhooksEvent::createWebhook($data['dst_num'], $data);
            }
        }
    }
}

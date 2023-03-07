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
        if (isset($data['call_id'])) {
            if (SipuniWebhooksEvent::whereCallId($data['call_id'])->exists()) {
                SipuniWebhooksEvent::updateWebhook($data['call_id'], $data);
            } else {
                SipuniWebhooksEvent::createWebhook($data['call_id'], $data);
            }
        }
    }
}

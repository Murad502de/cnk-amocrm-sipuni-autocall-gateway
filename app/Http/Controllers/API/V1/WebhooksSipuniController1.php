<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\WebhooksSipuniEventRequest1;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhooksSipuniController1 extends Controller
{
    public function index(WebhooksSipuniEventRequest1 $request)
    {
        Log::info(__METHOD__, $request->all()); //DELETE

        return response()->json(['success' => true], Response::HTTP_OK);
    }
}

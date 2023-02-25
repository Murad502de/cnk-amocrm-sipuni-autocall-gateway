<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\WebhooksLeads1;
use App\Http\Requests\API\V1\WebhooksLeadsAddRequest1;
use App\Http\Requests\API\V1\WebhooksLeadsChangeStageRequest1;
use App\Http\Requests\API\V1\WebhooksLeadsUpdateRequest1;
use App\Models\AmoWebhooksLead;
use Illuminate\Http\Response;

class WebhooksLeadsController1 extends Controller
{
    public function create(WebhooksLeadsAddRequest1 $request)
    {
        $this->webhookAdd($request->all());

        return response()->json(['message' => 'success by create'], Response::HTTP_OK);
    }
    public function update(WebhooksLeadsUpdateRequest1 $request)
    {
        $this->webhookStatus($request->all());

        return response()->json(['message' => 'success by update'], Response::HTTP_OK);
    }
    public function changeStage(WebhooksLeadsChangeStageRequest1 $request)
    {
        $this->handle($request->all()['leads']['status'][0]);

        return response()->json(['message' => 'success by changeStage'], Response::HTTP_OK);
    }
    public function index(WebhooksLeads1 $request)
    {
        if (isset($request->all()['leads']['add'])) {
            return $this->webhookAdd($request->all());
        }

        if (isset($request->all()['leads']['status'])) {
            return $this->webhookStatus($request->all());
        }

        return response()->json(['message' => 'OK'], Response::HTTP_OK);
    }
    private function handle(array $data)
    {
        if (isset($data['id'])) {
            $lead = AmoWebhooksLead::getLeadByAmoId($data['id']);

            if ($lead) {
                AmoWebhooksLead::updateLead($data['id'], time(), $data);
            } else {
                AmoWebhooksLead::createLead($data['id'], time(), $data);
            }
        }
    }
    private function webhookAdd(array $data)
    {
        $this->handle($data['leads']['add'][0]);
    }
    private function webhookStatus(array $data)
    {
        $this->handle($data['leads']['status'][0]);
    }
}

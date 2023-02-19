<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\WebhooksLeadsAddRequest1;
use App\Http\Requests\API\V1\WebhooksLeadsChangeStageRequest1;
use App\Http\Requests\API\V1\WebhooksLeadsUpdateRequest1;
use App\Models\AmoWebhooksLead;
use Illuminate\Http\Response;

class WebhooksLeadsController1 extends Controller
{
    public function create(WebhooksLeadsAddRequest1 $request)
    {
        $this->handle($request->all()['leads']['add'][0]);

        return response()->json(['message' => 'success by create'], Response::HTTP_OK);
    }
    public function update(WebhooksLeadsUpdateRequest1 $request)
    {
        $this->handle($request->all()['leads']['update'][0]);

        return response()->json(['message' => 'success by update'], Response::HTTP_OK);
    }
    public function changeStage(WebhooksLeadsChangeStageRequest1 $request)
    {
        $this->handle($request->all()['leads']['status'][0]);

        return response()->json(['message' => 'success by changeStage'], Response::HTTP_OK);
    }
    private function handle(array $data)
    {
        if (isset($data['id'])) {
            $lead = AmoWebhooksLead::getLeadByAmoId($data['id']);

            if ($lead) {
                if ($lead->last_modified < (int) $data['last_modified']) {
                    AmoWebhooksLead::updateLead($data['id'], $data['last_modified'], $data);
                }
            } else {
                AmoWebhooksLead::createLead($data['id'], $data['last_modified'], $data);
            }
        }
    }
}

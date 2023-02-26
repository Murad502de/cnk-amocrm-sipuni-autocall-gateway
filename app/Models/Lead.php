<?php

namespace App\Models;

use App\Traits\Model\generateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Lead extends Model
{
    use HasFactory, generateUuid;

    protected $fillable = [
        'uuid',
        'amo_id',
        'amo_pipeline_id',
        'main_contact_number',
        'call_id',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function call()
    {
        return $this->belongsTo(Call::class);
    }

    /* GETTERS-METHODS */
    public static function getByUuid(string $uuid): ?Lead
    {
        return self::whereUuid($uuid)->first();
    }
    public static function getByAmoId(int $id): ?Lead
    {
        return self::whereAmoId($id)->first();
    }

    public static function updateIfExist(AmoWebhooksLead $leadWebhook)
    {
        Log::info(__METHOD__); //DELETE

        $lead = self::getByAmoId((int) $leadWebhook->lead_id);

        Log::info(__METHOD__, [$lead]); //DELETE

        // if ($lead) {
        //     $lead->update([
        //         'main_contact_number' => self::getLeadWebhookMainContactNumber($leadWebhook),
        //         'amo_pipeline_id'     => self::getLeadWebhookPipelineId($leadWebhook),
        //     ]);
        // }
    }
}

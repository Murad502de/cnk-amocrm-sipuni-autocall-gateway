<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AmoWebhooksLead extends Model
{
    use HasFactory;

    private const PARSE_COUNT = 40;

    protected $fillable = [
        'lead_id',
        'last_modified',
        'data',
    ];
    protected $hidden = [
        'id',
    ];

    /* CRUD METHODS */
    public static function createLead(string $leadId, int $lastModified, array $data): void
    {
        self::create([
            'lead_id'       => $leadId,
            'last_modified' => (int) $lastModified,
            'data'          => json_encode($data),
        ]);
    }
    public static function updateLead(string $leadId, int $lastModified, array $data): void
    {
        self::where('lead_id', $leadId)->update([
            'last_modified' => (int) $lastModified,
            'data'          => json_encode($data),
        ]);
    }
    public static function getLeadByAmoId(string $leadId): ?AmoWebhooksLead
    {
        return self::all()->where('lead_id', $leadId)->first();
    }
    public static function getLeadWebhooks()
    {
        return self::orderBy('id', 'asc')
            ->take(self::PARSE_COUNT)
            ->get();
    }
    public static function getLeadWebhookData(AmoWebhooksLead $leadWebhook): array
    {
        return json_decode($leadWebhook->data, true);
    }
    public static function getLeadWebhookMainContactNumber(AmoWebhooksLead $leadWebhook): int
    {
        return (int) self::getLeadWebhookData($leadWebhook)['status_id'];
    }
    public static function getLeadWebhookPipelineId(AmoWebhooksLead $leadWebhook): int
    {
        return (int) self::getLeadWebhookData($leadWebhook)['pipeline_id'];
    }

    /* PROCEDURES-METHODS */
    public static function processWebhook(AmoWebhooksLead $leadWebhook)
    {
        Log::info(__METHOD__); //DELETE

        $lead = Lead::getByAmoId((int) $leadWebhook->lead_id);

        Log::info(__METHOD__, [$lead]); //DELETE
    }

    /* SCHEDULER-METHODS */
    public static function parseRecentWebhooks()
    {
        Log::info(__METHOD__); //DELETE

        // self::initStatic();

        $leadWebhooks = self::getLeadWebhooks();

        Log::info(__METHOD__, [$leadWebhooks]); //DELETE

        foreach ($leadWebhooks as $leadWebhook) {
            Log::info(__METHOD__, [$leadWebhook->lead_id]); //DELETE

            Lead::updateIfExist($leadWebhook);
            self::processWebhook($leadWebhook);

            Log::info(__METHOD__, ['delete leadWebhook: ' . $leadWebhook->lead_id]); //DELETE

            $leadWebhook->delete();
        }
    }
}

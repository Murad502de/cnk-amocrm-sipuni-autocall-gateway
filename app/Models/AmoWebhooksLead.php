<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public static function getLeadWebhookStatusId(AmoWebhooksLead $leadWebhook): int
    {
        return (int) self::getLeadWebhookData($leadWebhook)['status_id'];
    }
    public static function getLeadWebhookPipelineId(AmoWebhooksLead $leadWebhook): int
    {
        return (int) self::getLeadWebhookData($leadWebhook)['pipeline_id'];
    }
}

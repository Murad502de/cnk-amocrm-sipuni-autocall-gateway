<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Support\Facades\Log;

class SipuniWebhooksEvent extends Model
{
    use HasFactory;

    private const PARSE_COUNT = 30;

    protected $fillable = [
        'call_id',
        'data',
    ];
    protected $hidden = [
        'id',
    ];

    /* CRUD METHODS */
    public static function createWebhook(string $callId, array $data): void
    {
        self::create([
            'call_id' => $callId,
            'data'    => json_encode($data),
        ]);
    }
    public static function getWebhookByCallId(string $callId): ?SipuniWebhooksEvent
    {
        return self::whereCallId($callId)->first();
    }
    public static function updateWebhook(string $callId, array $data): void
    {
        self::whereCallId($callId)->update([
            'data' => json_encode($data),
        ]);
    }
}

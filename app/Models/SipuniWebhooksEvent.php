<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SipuniWebhooksEvent extends Model
{
    use HasFactory;

    private const PARSE_COUNT = 30;

    protected $fillable = [
        'dst_num',
        'data',
    ];
    protected $hidden = [
        'id',
    ];

    /* CRUD METHODS */
    public static function createWebhook(string $dstNum, array $data): void
    {
        self::create([
            'dst_num' => $dstNum,
            'data'    => json_encode($data),
        ]);
    }
    public static function getWebhookByCallId(string $dstNum): ?SipuniWebhooksEvent
    {
        return self::whereDstNum($dstNum)->first();
    }
    public static function updateWebhook(string $dstNum, array $data): void
    {
        self::whereDstNum($dstNum)->update([
            'data' => json_encode($data),
        ]);
    }

    /* GETTERS */
    public static function getCallWebhooks()
    {
        return self::orderBy('id', 'asc')
            ->take(self::PARSE_COUNT)
            ->get();
    }

    /* SCHEDULER-METHODS */
    public static function parseRecentWebhooks()
    {
        $callWebhooks = self::getCallWebhooks();

        foreach ($callWebhooks as $callWebhook) {
            Log::info(__METHOD__, [$callWebhook]); //DELETE

            // $callWebhook->delete(); //TODO
        }
    }
}

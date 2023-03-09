<?php

namespace App\Models;

use App\Jobs\Sipuni\AddLeadToAutoCallListJob;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SipuniWebhooksEvent extends Model
{
    use HasFactory;

    private const PARSE_COUNT    = 30;
    private const SUCCESS_STATUS = 'ANSWER';

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
    public static function getWebhookByDstNum(string $dstNum): ?SipuniWebhooksEvent
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

            if ($lead = Lead::whereMainContactNumber($callWebhook->dst_num)->first()) {
                Log::info(__METHOD__, [$lead]); //DELETE

                $callWebhookData = json_decode($callWebhook->data, true);

                Log::info(__METHOD__, $callWebhookData); //DELETE
                Log::info(__METHOD__, ['dst_num: ' . $callWebhookData['dst_num']]); //DELETE
                Log::info(__METHOD__, ['status: ' . $callWebhookData['status']]); //DELETE

                if (
                    $callWebhookData['status'] !== self::SUCCESS_STATUS &&
                    $lead->auto_redial_attempt <= $lead->call->auto_redial_attempts
                ) {
                    Log::info(__METHOD__, ['set job with delay (min): ' . $lead->call->auto_redial_delay]); //DELETE

                    AddLeadToAutoCallListJob::dispatch($lead)->delay(now()->addMinutes($lead->call->auto_redial_delay));
                } else {
                    $lead->delete();
                }
            }

            $callWebhook->delete();
        }
    }
}

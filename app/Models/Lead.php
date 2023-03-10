<?php

namespace App\Models;

use App\Exceptions\NotFoundException;
use App\Services\amoAPI\amoAPIHub;
use App\Traits\Model\generateUuidManualTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Lead extends Model
{
    use HasFactory, generateUuidManualTrait;

    public $AMO_API = null;

    protected $fillable = [
        'uuid',
        'amo_id',
        'amo_pipeline_id',
        'main_contact_number',
        'available',
        'when_available',
        'call_id',
        'processing',
        'auto_redial_attempt',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function __construct()
    {
        $this->AMO_API = new amoAPIHub(AmoCRM::getAuthData());
    }

    public function call()
    {
        return $this->belongsTo(Call::class);
    }

    public function isBusinessHours()
    {
        Log::info(__METHOD__, [$this]); //DELETE

        $startHours   = $this->call->start_work_hours;
        $startMinutes = $this->call->start_work_minutes;
        $startStr     = date('Y-m-d') . ' ' . $startHours . ':' . $startMinutes;
        $start        = strtotime($startStr);

        Log::info(__METHOD__, ['startStr: ' . $startStr . ' || ' . 'start: ' . $start]); //DELETE

        $endHours   = $this->call->end_work_hours;
        $endMinutes = $this->call->end_work_minutes;
        $endStr     = date('Y-m-d') . ' ' . $endHours . ':' . $endMinutes;
        $end        = strtotime($endStr);

        Log::info(__METHOD__, ['endStr: ' . $endStr . ' || ' . 'end: ' . $end]); //DELETE

        $currentStr = date('Y-m-d H:i');
        $current    = time();

        Log::info(__METHOD__, ['currentStr: ' . $currentStr . ' || ' . 'current: ' . $current]); //DELETE

        return time() >= $start && time() <= $end;
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

    /* FETCH-METHODS */
    public function fetchLeadById(int $id): ?array
    {
        $findLeadByIdResponse = $this->AMO_API->findLeadById($id);

        if ($findLeadByIdResponse['code'] !== Response::HTTP_OK) {
            // throw new NotFoundException('lead not found by id: ' . $id);

            return null;
        }

        return $findLeadByIdResponse['body'];
    }
    public function fetchContactById(int $id): ?array
    {
        $findLeadByIdResponse = $this->AMO_API->findContactById($id);

        if ($findLeadByIdResponse['code'] !== Response::HTTP_OK) {
            // throw new NotFoundException('main contact not found');
            
            return null;
        }

        return $findLeadByIdResponse['body'];
    }

    public static function updateIfExist(AmoWebhooksLead $leadWebhook)
    {
        Log::info(__METHOD__); //DELETE

        $lead = self::getByAmoId((int) $leadWebhook->lead_id);

        Log::info(__METHOD__, [$lead]); //DELETE

        if ($lead) {
            Log::info(__METHOD__, ['lead must update']); //DELETE

            // if ($lead) {
            //     $lead->update([
            //         'main_contact_number' => self::getLeadWebhookMainContactNumber($leadWebhook),
            //         'amo_pipeline_id'     => self::getLeadWebhookPipelineId($leadWebhook),
            //     ]);
            // }
        }
    }

    public static function initStatic(array $params)
    {
        // self::$STAGE_LOSS_ID         = (int) config('services.amoCRM.loss_stage_id');
        // self::$STAGE_SUCCESS_ID      = (int) config('services.amoCRM.successful_stage_id');
        // self::$BASIC_LEAD            = self::fetchLeadById($params['lead_amo_id']);
        // self::$BROKER_ID             = (int) $params['broker_amo_id'];
        // self::$BROKER_NAME           = $params['broker_amo_name'];
        // self::$MANAGER_ID            = (int) $params['manager_amo_id'];
        // self::$MANAGER_NAME          = $params['manager_amo_name'];
        // self::$CREATED_LEAD_TYPE     = $params['created_lead_type'];
        // self::$MESSAGE_FOR_BROKER    = $params['message_for_broker'];
        // self::$TASK_TYPE_CONTROLL_ID = (int) config('services.amoCRM.constant_task_type_id__controll');
        // self::$EXCLUDE_CF            = [
        //     (int) config('services.amoCRM.exclude_cf_utm_source_id'),
        //     (int) config('services.amoCRM.exclude_cf_utm_medium_id'),
        //     (int) config('services.amoCRM.exclude_cf_utm_campaign_id'),
        //     (int) config('services.amoCRM.exclude_cf_utm_term_id'),
        //     (int) config('services.amoCRM.exclude_cf_utm_content_id'),
        //     (int) config('services.amoCRM.exclude_cf_roistat_id'),
        //     (int) config('services.amoCRM.exclude_cf_roistat_marker_id'),
        //     (int) config('services.amoCRM.exclude_cf_source_id'),
        //     (int) config('services.amoCRM.exclude_cf_mortgage_created_id'),
        //     (int) config('services.amoCRM.exclude_cf_broker_selected_id'),
        //     (int) config('services.amoCRM.exclude_cf_lead_manager_id'),
        // ];
    }
}

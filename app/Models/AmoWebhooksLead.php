<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AmoWebhooksLead extends Model
{
    use HasFactory;

    private const PARSE_COUNT = 20;

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
    public static function getMainContactIdFromLeadBody(array $lead): ?int
    {
        foreach ($lead['_embedded']['contacts'] as $contact) {
            if ($contact['is_main']) {
                return $contact['id'];
            }
        }

        return null;
    }
    public static function getMainContactWorkNumber($contact): ?string
    {
        if (!$contact) {
            return null;
        }

        // if (!array_key_exists('custom_fields_values', $contact)) {
        //     return null;
        // }

        if (!$contact['custom_fields_values']) {
            return null;
        }

        Log::info(__METHOD__, ['contact']); //DELETE
        Log::info(__METHOD__, [$contact]); //DELETE
        Log::info(__METHOD__, ['custom_fields_values']); //DELETE
        Log::info(__METHOD__, [$contact['custom_fields_values']]); //DELETE

        foreach ($contact['custom_fields_values'] as $customField) {
            if ($customField['field_code'] === 'PHONE') {
                foreach ($customField['values'] as $customFieldValue) {
                    if ($customFieldValue['enum_code'] === 'WORK') {
                        return $customFieldValue['value'];
                    }
                }
            }
        }

        return null;
    }

    /* FETCH-METHODS */
    public static function fetchLeadById(int $id): ?array
    {
        if (!$id) {
            return null;
        }

        return (new Lead())->fetchLeadById($id);
    }
    public static function fetchContactById(int $id): ?array
    {
        if (!$id) {
            return null;
        }

        return (new Lead())->fetchContactById($id);
    }

    /* PROCEDURES-METHODS */
    public static function processWebhook(AmoWebhooksLead $leadWebhook, string $mainContactNumber)
    {
        $leadWebhookData = json_decode($leadWebhook->data, true);

        if (array_key_exists('pipeline_id', $leadWebhookData)) {
            if ($call = Call::whereAmoPipelineId((int) $leadWebhookData['pipeline_id'])->first()) {
                Log::info(__METHOD__, ['leadWebhook is target']); //DELETE

                $lead = new Lead();

                $lead->call_id = $call->id;

                if ($lead->isBusinessHours()) {
                    Log::info(__METHOD__, ['is business hours']); //DELETE

                    $lead->uuid                = Lead::generateUuid();
                    $lead->amo_id              = $leadWebhookData['id'];
                    $lead->amo_pipeline_id     = $leadWebhookData['pipeline_id'];
                    $lead->main_contact_number = $mainContactNumber;
                    $lead->available           = true;
                    $lead->processing          = false;
                    $lead->when_available      = time();
                    $lead->auto_redial_attempt = 1;

                    $lead->save();
                } else {
                    Log::info(__METHOD__, ['is not business hours']); //DELETE
                }
            }
        }
    }

    /* SCHEDULER-METHODS */
    public static function parseRecentWebhooks()
    {
        $leadWebhooks = self::getLeadWebhooks();

        foreach ($leadWebhooks as $leadWebhook) {
            $lead = self::fetchLeadById($leadWebhook->lead_id);

            if ($lead) {
                $mainContact = self::fetchContactById((int) self::getMainContactIdFromLeadBody($lead));

                if ($mainContact) {
                    $mainContactNumber = preg_replace('/\D/', '', (string) self::getMainContactWorkNumber($mainContact));

                    Log::info(__METHOD__, ['mainContactNumber before: ' . $mainContactNumber]); //DELETE

                    if ($mainContactNumber) {
                        if ($mainContactNumber[0] === '8') {
                            $mainContactNumber[0] = '7';
                        }

                        // $leadWebhookData   = json_decode($leadWebhook->data, true);

                        Log::info(__METHOD__, ['mainContactNumber after: ' . $mainContactNumber]); //DELETE

                        if ($lead = Lead::getByAmoId((int) $leadWebhook->lead_id)) {
                            Log::info(__METHOD__, ['lead must update']); //DELETE

                            $lead->update([
                                'main_contact_number' => $mainContactNumber, //FIXME
                                // 'amo_pipeline_id'     => (int) $leadWebhookData['pipeline_id'],
                            ]);
                        } else {
                            Log::info(__METHOD__, ['lead must process']); //DELETE

                            self::processWebhook($leadWebhook, $mainContactNumber);
                        }
                    } else {
                        Log::info(__METHOD__, ['main contact number not found']); //DELETE
                    }
                } else {
                    Log::info(__METHOD__, ['contact not found']); //DELETE
                }
            } else {
                Log::info(__METHOD__, ['lead not found']); //DELETE
            }

            $leadWebhook->delete(); //TODO
        }
    }
}

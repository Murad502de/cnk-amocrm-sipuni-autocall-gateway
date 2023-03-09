<?php

namespace App\Models;

use App\Traits\Model\generateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory, generateUuid;

    protected $fillable = [
        'uuid',
        'name',
        'amo_pipeline_id',
        'operator_extension_number',
        'start_work_hours',
        'start_work_minutes',
        'end_work_hours',
        'end_work_minutes',
        'auto_redial_delay',
        'auto_redial_attempts',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}

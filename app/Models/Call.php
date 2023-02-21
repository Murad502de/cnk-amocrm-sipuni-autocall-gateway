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
        'amo_target_status_id',
        'sipuni_call_id',
    ];
    protected $hidden = [
        'id',
    ];
}

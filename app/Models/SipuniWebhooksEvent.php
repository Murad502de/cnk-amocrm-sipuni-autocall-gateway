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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmoWebhooksLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'last_modified',
        'data',
    ];
    protected $hidden = [
        'id',
    ];
}
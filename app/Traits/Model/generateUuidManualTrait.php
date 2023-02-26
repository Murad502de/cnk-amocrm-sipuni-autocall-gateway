<?php

namespace App\Traits\Model;

use Ramsey\Uuid\Uuid;

trait generateUuidManualTrait
{
    public static function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}

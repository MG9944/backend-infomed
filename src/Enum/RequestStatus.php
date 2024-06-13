<?php

namespace App\Enum;

enum RequestStatus
{
    case SUCCESS;
    case DANGER;

    public function result(): string
    {
        return match ($this) {
            RequestStatus::SUCCESS => 'Success',
            RequestStatus::DANGER => 'Danger',
        };
    }
}

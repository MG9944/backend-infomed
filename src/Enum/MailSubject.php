<?php

namespace App\Enum;

enum MailSubject
{
    case EVENT_NEW;
    case EVENT_EDIT;
    case EVENT_REMOVE;

    public function result(): string
    {
        return match ($this) {
            MailSubject::EVENT_NEW => 'Nowe',
            MailSubject::EVENT_EDIT => 'Edytowno',
            MailSubject::EVENT_REMOVE => 'Usunęto',
        };
    }
}

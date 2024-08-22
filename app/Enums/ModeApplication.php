<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ModeApplication: string implements HasLabel
{
    // Types of Insurance
    case FBOL = 'facebook/online';
    case CALL = 'call';
    case DROPBY = 'dropby';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FBOL =>'FACEBOOK/ONLINE',
            self::CALL =>'CALL',
            self::DROPBY =>'DROPBY',
        };
    }


}
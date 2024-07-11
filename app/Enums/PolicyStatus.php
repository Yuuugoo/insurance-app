<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PolicyStatus: string implements HasLabel
{
    case NEW = 'new';
    case RENEWAL = 'renewal';
   

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NEW =>'NEW',
            self::RENEWAL =>'RENEWAL',
            
        };
    }


}
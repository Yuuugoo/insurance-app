<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PolicyStatus: string implements HasLabel, HasColor
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

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW =>'success',
            self::RENEWAL =>'danger',
        };
    }


}
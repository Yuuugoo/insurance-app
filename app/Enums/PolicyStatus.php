<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PolicyStatus: string implements HasLabel, HasColor, HasIcon
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
            self::NEW => 'success',
            self::RENEWAL =>'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::NEW => 'heroicon-m-check-badge',
            self::RENEWAL => 'heroicon-m-x-circle',
        };
    }


}
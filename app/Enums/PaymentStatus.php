<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel, HasColor
{
    case PAID = 'paid';
    case PENDING = 'pending';
   
    public function getLabel(): ?string
    {
        return match ($this) {
            self::PAID =>'PAID',
            self::PENDING =>'PENDING',
        };
    }


    // Add color to payment status
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PAID =>'success',
            self::PENDING =>'warning',
        };
    }
    
}
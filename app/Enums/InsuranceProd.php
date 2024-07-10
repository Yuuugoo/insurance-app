<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InsuranceProd: string implements HasLabel
{
    // Insurance Providers
    case MCT = 'mct';
    case OONA = 'oona';
    case FPG = 'fpg';
    case OAC = 'oac';
    case CIBELES = 'cibeles';
    case OTHERS = 'others';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MCT =>'MCT',
            self::OONA =>'OONA',
            self::FPG =>'FPG',
            self::OAC =>'OAC',
            self::CIBELES =>'CIBELES',
            self::OTHERS =>'OTHERS',
        };
    }


}
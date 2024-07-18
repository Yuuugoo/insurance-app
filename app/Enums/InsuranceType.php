<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InsuranceType: string implements HasLabel
{
    // Types of Insurance
    case COMPRE = 'compre';
    case TPL = 'tpl';
    case FIRE = 'fire';
    case TRAVEL = 'travel';
    case PA = 'pa';
    case HOME = 'home';
    case CASUALTY = 'casualty';
    case MARINE = 'marine';
    case CGL = 'cgl';
    case OTHERS = 'others';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::COMPRE =>'COMPRE',
            self::TPL =>'TPL',
            self::FIRE =>'FPG',
            self::TRAVEL =>'TRAVEL',
            self::PA =>'PA',
            self::HOME =>'HOME',
            self::CASUALTY =>'CASUALTY',
            self::MARINE =>'MARINE',
            self::CGL => 'CGL',
            self::OTHERS =>'OTHERS',
        };
    }


}
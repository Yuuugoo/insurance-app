<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CostCenter: string implements HasLabel
{
    // Cost Centers
    case AURORA = 'aurora';
    case FAIRVIEW = 'fairview';
    case FELIZ = 'feliz';
    case MNLBAY = 'mnlbay';
    case MAKATI = 'makati';
    case MARKET = 'market';
    case ROBMANILA = 'robmanila';
    case ALABANG = 'alabang';
    case DASMA = 'dasma';
    case PAMPANGA = 'pampanga';
    case MARQUEE = 'marquee';
    case BALIWAG = 'baliwag';
    case LAUNION = 'launion';
    case LIPA = 'lipa';
    case CALAMBA = 'calamba';
    case CEBU = 'cebu';
    case DAV = 'davao';
    case ABREEZA = 'abreeza';

    public function getLabel(): ?string
    {
        return match ($this) {
            // Arrange Alphabetically
            self::AURORA =>'Aurora',
            self::FAIRVIEW =>'Fairview',
            self::FELIZ =>'Feliz',
            self::MNLBAY =>'Manila Bay',
            self::MAKATI =>'Makati',
            self::MARKET =>'Market',
            self::ROBMANILA =>'Robinsons Manila',
            self::ALABANG =>'Alabang',
            self::DASMA =>'Dasmariñas',
            self::PAMPANGA =>'Pampanga',
            self::MARQUEE =>'Marquee',
            self::BALIWAG =>'Baliwag',
            self::LAUNION =>'La Union',
            self::LIPA =>'Lipa',
            self::CALAMBA =>'Calamba',
            self::CEBU =>'Cebu',
            self::DAV =>'Davao',
            self::ABREEZA =>'Abreeza',
        };
    }


}
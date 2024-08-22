<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Terms: string implements HasLabel
{
    
    case STRAIGHT = 'STRAIGHT';
    case TWO = '2 Terms';
    case THREE = '3 Terms';
    case SIX = '6 Terms';
    // case ONE = '1/2';   
    // case TWO = '2/2';
    // case THREE = '1/3';
    // case FOUR = '2/3';
    // case FIVE = '3/3';
    // case SIX = '1/4';
    // case SEVEN = '2/4';
    // case EIGHT = '3/4';
    // case NINE = '4/4';
    // case TEN = '1/5';
    // case ELEVEN = '2/5';
    // case TWELVE = '3/5';
    // case THIRTEEN = '4/5';
    // case FOURTEEN = '5/5';
    // case FITHTEEN = '1/6';
    // case SIXTEEN = '2/6';
    // case SEVENTEEN = '3/6';
    // case EIGHTEEN = '4/6';
    // case NINETEEN = '5/6';
    // case TWENTY = '6/6';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::STRAIGHT => 'STRAIGHT',
            self::TWO => '2 Terms',
            self::THREE => '3 Terms',
            self::SIX => '6 Terms',
            // self::ONE => '1/2',
            // self::TWO => '2/2',
            // self::THREE => '1/3',
            // self::FOUR => '2/3',
            // self::FIVE => '3/3',
            // self::SIX => '1/4',
            // self::SEVEN => '2/4',
            // self::EIGHT => '3/4',
            // self::NINE => '4/4',
            // self::TEN => '1/5',
            // self::ELEVEN => '2/5',
            // self::TWELVE => '3/5',
            // self::THIRTEEN => '4/5',
            // self::FOURTEEN => '5/5',
            // self::FITHTEEN => '1/6',
            // self::SIXTEEN => '2/6',
            // self::SEVENTEEN => '3/6',
            // self::EIGHTEEN => '4/6',
            // self::NINETEEN => '5/6',
            // self::TWENTY => '6/6',
        };
    }


}
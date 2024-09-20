<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Terms: string implements HasLabel
{
    case STRAIGHT = 'STRAIGHT';
    case TWO = '2 Terms';
    case THREE = '3 Terms';
    case FOUR = '4 Terms';
    case FIVE = '5 Terms';
    case SIX = '6 Terms';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::STRAIGHT => 'STRAIGHT',
            self::TWO => '2 Terms',
            self::THREE => '3 Terms',
            self::FOUR => '4 Terms',
            self::FIVE => '5 Terms',
            self::SIX => '6 Terms',
        };
    }

    public static function fromString(string $value): self
    {
        return match (strtoupper($value)) {
            'STRAIGHT' => self::STRAIGHT,
            '2 TERMS', '1/2', '2/2' => self::TWO,
            '3 TERMS', '1/3', '2/3', '3/3' => self::THREE,
            '4 TERMS', '1/4', '2/4', '3/4', '4/4' => self::FOUR,
            '5 TERMS', '1/5', '2/5', '3/5', '4/5', '5/5' => self::FIVE,
            '6 TERMS', '1/6', '2/6', '3/6', '4/6', '5/6', '6/6' => self::SIX,
            default => throw new \ValueError("\"$value\" is not a valid backing value for enum " . self::class),
        };
    }
}
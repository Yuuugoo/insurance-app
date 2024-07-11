<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Payment: string implements HasLabel
{
    case CASH = 'cash';
    case CHECK = 'check';
    case PAYMENT = 'payment';
    case ONLINE = 'online';
    case GCASH = 'gcash';   
    case PAYNAMICS = 'paynamics';   
    case BDOCC = 'BDO CC';   
   

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CASH =>'CASH',
            self::CHECK =>'CHECK',
            self::PAYMENT =>'PAYMENT',
            self::ONLINE =>'ONLINE',
            self::GCASH =>'GCASH',
            self::PAYNAMICS =>'PAYNAMICS',
            self::BDOCC =>'BDO CC',
            
        };
    }


}
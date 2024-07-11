<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'terms',
        'gross_premium',
        'payment_mode',
        'total_payment',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }
}

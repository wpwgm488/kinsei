<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'postal_code',
        'address',
        'registration_number',
        'billing_type',
        'hourly_rate',
        'monthly_rate',
        'settlement_lower_hours',
        'settlement_upper_hours',
        'overtime_unit_price',
        'deduction_unit_price',
        'price_tax_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
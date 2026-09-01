<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// # Attribute（属性）
// 項目をmass assignment可能にする
#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'memo',
    'registration_number',
    'postal_code',
    'address',
    'phone_number',
    'bank_name',
    'bank_branch',
    'bank_account_type',
    'bank_account_number',
    'bank_account_holder',
    'billing_type',
    'hourly_rate',
    'monthly_rate',
    'settlement_lower_hours',
    'settlement_upper_hours',
    'overtime_unit_price',
    'deduction_unit_price',
    'price_tax_type',
])]
// モデルをJSON化するときなどに隠す
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
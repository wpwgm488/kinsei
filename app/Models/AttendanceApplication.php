<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceApplication extends Model
{
    protected $fillable = [
        'user_id',
        'manager_id',
        'month',
        'status',
        'applied_at',
        'approved_at',
        'rejected_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
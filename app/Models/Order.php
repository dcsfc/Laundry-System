<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'staff_id',
        'service_id',
        'dropoff_date',
        'dropoff_time',
        'pickup_date',
        'pickup_time',
        'total_price',
        'payment_status',
        'payment_method',
        'status',
        'notes',
        'created_by',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'weight'
    ];

    protected $casts = [
        'dropoff_date' => 'date',
        'pickup_date' => 'date',
        'dropoff_time' => 'datetime:H:i',
        'pickup_time' => 'datetime:H:i',
        'total_price' => 'decimal:2',
        'approved_at' => 'datetime',
        'weight' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

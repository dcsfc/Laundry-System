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
        'dropoff_date',
        'pickup_date',
        'total_price',
        'payment_status',
        'payment_method',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'dropoff_date' => 'date',
        'pickup_date' => 'date',
        'total_price' => 'decimal:2'
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

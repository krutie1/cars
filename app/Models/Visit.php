<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'comment',
        'start_time',
        'end_time',
        'cost',
        'discount',
        'user_id',
        'car_id',
        'payment_date'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'payment_date' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function carTrashed()
    {
        return $this->car()->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userTrashed()
    {
        return $this->user()->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientTrashed()
    {
        return $this->client()->withTrashed();
    }

    public function payment()
    {
        return $this->belongsToMany(Payment::class, 'transactions');
    }

    public function paymentsTrashed()
    {
        return $this->payment()->withTrashed();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'comment',
        'start_time',
        'end_time',
        'cost',
        'payment_id',
        'user_id'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsTo(Payment::class);
    }

    public function paymentsTrashed()
    {
        return $this->payment()->withTrashed();
    }
}

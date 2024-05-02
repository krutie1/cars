<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['visit_id', 'payment_id', 'amount'];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
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

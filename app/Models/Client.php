<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'phone_number',
        'first_name',
        'last_name',
        'patronymic',
        'visits_count'
    ];

    protected $dates = ['deleted_at'];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function lastVisit()
    {
        return $this->hasOne(Visit::class)->whereNull('deleted_at')->whereNotNull('payment_date')->latest();
    }

}

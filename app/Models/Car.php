<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'active',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}

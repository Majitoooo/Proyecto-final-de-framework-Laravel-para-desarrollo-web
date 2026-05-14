<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Space;

class Reservation extends Model
{
    protected $fillable = [
        'space_id',
        'start_time',
        'end_time',
        'status',
        'user_name',
        'user_email',
        'notes',
        'admin_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function review()
    {
        return $this->hasOne(
            Review::class
        );
    }
}


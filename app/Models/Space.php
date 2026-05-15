<?php

namespace App\Models;

use App\Models\Availability;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\BlockedSlot;
use App\Models\Reservation;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'capacity',
        'description',
        'usage_rules',
        'price_per_hour',
        'is_active',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($space) {

            $slug = Str::slug($space->name);

            $count = Space::where('slug', 'LIKE', "{$slug}%")->count();

            $space->slug = $count
                ? "{$slug}-{$count}"
                : $slug;
        });
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function blockedSlots()
    {
        return $this->hasMany(
            BlockedSlot::class
        );
    }

    public function reviews()
    {
        return $this->hasMany(
            Review::class
        );
    }

    public function reservations()
    {
        return $this->hasMany(
            Reservation::class
        );
    }
}
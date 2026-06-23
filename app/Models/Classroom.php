<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'capacity', 'equipment', 'is_active'];

    protected $casts = [
        'equipment' => 'array',
        'is_active' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favouritedBy()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function scopeAvailableBetween($query, $startTime, $endTime)
    {
        return $query->whereDoesntHave('reservations', function ($q) use ($startTime, $endTime) {
            $q->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime)
                      ->where('status', '!=', 'cancelled');
            });
        });
    }

    public function isAvailable($startTime, $endTime)
    {
        return !$this->reservations()
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
}

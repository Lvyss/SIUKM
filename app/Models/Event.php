<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'ukm_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'poster_image',
        'registration_link',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function ukm()
    {
        return $this->belongsTo(Ukm::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now());
    }
}
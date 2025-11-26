<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukm extends Model
{
    use HasFactory;

    protected $table = 'ukms';

    protected $fillable = [
        'name',
        'description',
        'logo',
        'category_id',
        'vision',
        'mission',
        'contact_person',
        'instagram',
        'email_ukm',
        'status',
        'created_by',
    ];

    // RELASI
    public function category()
    {
        return $this->belongsTo(UkmCategory::class, 'category_id'); // TAMBAH PARAMETER INI
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'ukm_staff', 'ukm_id', 'user_id')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function pendingRegistrations()
    {
        return $this->hasMany(Registration::class)->where('status', 'pending');
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // HAPUS METHOD INI - INI SALAH & DUPLICATE!
    // public function ukms()
    // {
    //     return $this->hasMany(Ukm::class, 'category_id');
    // }
}
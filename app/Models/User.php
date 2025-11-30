<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'nim',
        'fakultas',
        'jurusan',
        'angkatan',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // RELASI
    public function managedUkms()
    {
        return $this->hasMany(Ukm::class, 'created_by');
    }

    // ✅ STANDARDIZE - PAKAI ukmStaff() SAJA
    public function ukmStaff()
    {
        return $this->hasMany(UkmStaff::class);
    }

    public function managedUkmsList()
    {
        return $this->belongsToMany(Ukm::class, 'ukm_staff', 'user_id', 'ukm_id')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class, 'created_by');
    }

public function registrations()
{
    return $this->hasMany(Registration::class);
}

public function approvedUkms()
{
    return $this->belongsToMany(Ukm::class, 'registrations')
                ->wherePivot('status', 'approved')
                ->withTimestamps();
}
    // SCOPES
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeUser($query)
    {
        return $query->where('role', 'user');
    }

    // METHODS
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function canManageUkm($ukmId)
    {
        if ($this->isAdmin()) return true;
        
        return $this->managedUkmsList()->where('ukm_id', $ukmId)->exists();
    }
}
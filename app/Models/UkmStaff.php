<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkmStaff extends Model
{
    use HasFactory;

    protected $table = 'ukm_staff';

    protected $fillable = [
        'user_id',
        'ukm_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ukm()
    {
        return $this->belongsTo(Ukm::class);
    }
}
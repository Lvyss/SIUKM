<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkmCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // FIX RELASI - TAMBAH PARAMETER FOREIGN KEY
    public function ukms()
    {
        return $this->hasMany(Ukm::class, 'category_id'); // SPECIFY FOREIGN KEY
    }
}
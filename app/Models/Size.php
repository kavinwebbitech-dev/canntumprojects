<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory, SoftDeletes;

    // Optional: specify the table name if it doesn't follow Laravel convention
    // protected $table = 'sizes';

    // Mass assignable fields
    protected $fillable = [
        'name',         // Size name, e.g., "Small", "Medium"
        'value',        // Optional value, e.g., "S", "M"
        'description',  // Description about the size
        'status'        // 1 = active, 0 = inactive
    ];

    // Casts
    protected $casts = [
        'status' => 'boolean',
    ];
}
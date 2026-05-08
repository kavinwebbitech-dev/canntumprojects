<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    // Optional: specify the table name if it doesn't follow Laravel convention
    // protected $table = 'colors';

    // Mass assignable fields
    protected $fillable = [
        'name',   // Color name, e.g., "Red"
        'code',   // Hex code, e.g., "#FF0000"
        'status'  // 1 = active, 0 = inactive
    ];

    // Casts
    protected $casts = [
        'status' => 'boolean',
    ];
}
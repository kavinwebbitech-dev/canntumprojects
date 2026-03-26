<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'country',
        'state_code',
        'state',
        'city',
        'pincode',
        'make_default',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

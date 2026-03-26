<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Color;
use App\Models\Size;

class ProductDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function colordetails()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
    public function sizedetails()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}

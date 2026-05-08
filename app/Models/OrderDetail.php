<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    public function productDetail(){
        return $this->belongsTo(ProductDetail::class, 'product_id','id');
    }
    
    public function orderProductDetail(){
        return $this->belongsTo(ProductDetail::class, 'product_id','product_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductCategory;

class Product extends Model
{
  use HasFactory;
  protected $guarded = [];

  public function details()
  {
    return $this->hasMany(ProductDetail::class, 'product_id', 'id');
  }
  public function uploads()
  {
    // Make sure 'product_id' matches your foreign key in the uploads table
    return $this->hasMany(Upload::class, 'product_id');
  }
  public function category()
  {
    return $this->belongsTo(ProductCategory::class, 'category_id');
  }
}

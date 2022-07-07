<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'invoice_id', 'product_id', 'qty', 'price',
  ];

  /**
   * reviews
   * one to many
   * @return void
   */
  public function reviews()
  {
    return $this->hasMany(Review::class);
  }

  /**
   * product
   * one to many (just use belongsto)
   * @return void
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}

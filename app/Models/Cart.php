<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'product_id', 'customer_id', 'qty', 'price', 'weight'
  ];

  /**
   * product
   * one to many (just use belongsTo)
   * @return void
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * customer
   * one to many (just use belongsTo)
   * @return void
   */
  public function customer()
  {
    return $this->belongsTo(customer::class);
  }
}

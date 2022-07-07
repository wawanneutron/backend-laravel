<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'rating', 'review', 'product_id',
    'order_id', 'customer_id',
  ];

  /**
   * product
   * one to many (dua arah)
   * @return void
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * customer
   * one to many (dua arah)
   * @return void
   */
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
}

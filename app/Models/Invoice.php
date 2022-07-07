<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'invoice', 'customer_id', 'courier', 'courier_service',
    'courier_cost', 'weight', 'name', 'phone', 'city_id',
    'province_id', 'address', 'status', 'grand_total', 'snap_token',
  ];

  /**
   * orders
   * one to many
   * @return void
   */
  public function orders()
  {
    return $this->hasMany(Order::class);
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

  /**
   * city
   * one to many (just use belongsTo)
   * @return void
   */
  public function city()
  {
    return $this->belongsTo(City::class, 'city_id', 'city_id');
  }

  /**
   * province
   * one to many (just use belongsTo)
   * @return void
   */
  public function province()
  {
    return $this->belongsTo(Province::class, 'province_id', 'province_id');
  }
}

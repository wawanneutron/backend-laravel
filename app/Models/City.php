<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'province_id', 'city_id', 'name'
  ];

  /**
   * provice
   * one to many (dua arah)
   * @return void
   */
  public function provice()
  {
    return $this->belongsTo(Province::class, 'province_id');
  }
}

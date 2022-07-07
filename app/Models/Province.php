<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'province_id', 'name'
  ];

  /**
   * cities
   * one to many (dua arah)
   * @return void
   */

  public function cities()
  {
    return $this->hasMany(City::class, 'province_id');
  }
}

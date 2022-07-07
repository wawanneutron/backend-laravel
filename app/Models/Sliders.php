<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = ['image', 'link'];

  /**
   * Accessor get image
   *
   * @return Attribute
   */
  protected function image(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => asset('/storage/sliders/' . $value),
    );
  }
}

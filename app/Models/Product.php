<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  /**
   * fillable
   *
   * @var array
   */
  protected $fillable = [
    'image', 'title', 'slug', 'category_id', 'user_id', 'description', 'weight', 'price', 'stock', 'discount'
  ];

  /**
   * Accessor get image
   *
   * @return Attribute
   */
  protected function image(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => asset('/storage/products/' . $value),
    );
  }

  /**
   * Accessor reviewAvgRating
   *
   * @return Attribute
   */
  protected function reviewAvgRating(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => $value ? substr($value, 0, 3) : 0,
    );
  }

  /**
   * category
   * one to many (Inverse / dua arah)
   * @return void
   */
  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  /**
   * reviews
   * one to many (dua arah)
   * @return void
   */
  public function reviews()
  {
    return $this->hasMany(Review::class);
  }
}

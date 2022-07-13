<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  use HasFactory;

  /**
   * fillable
   * mass assigment
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'email_verified_at', 'password', 'remember_token'
  ];

  /**
   * createdAt
   *
   * @return Attribute
   */
  // protected function createdAt(): Attribute
  // {
  //   return Attribute::make(
  //     get: fn ($value) => \Carbon\Carbon::locale('id')->parse($value)->translatedFormat('l, d F Y'),
  //   );
  // }


  /**
   * invoices
   * one to many (dua arah)
   * @return void
   */
  public function invoices()
  {
    return $this->hasMany(Invoice::class);
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

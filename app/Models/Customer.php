<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
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
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];


  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }

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

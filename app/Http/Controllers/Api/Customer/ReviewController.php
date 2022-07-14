<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
  /**
   * store
   * * customer memberika ulasan product yang dibeli
   * @return void
   */
  public function store(Request $request)
  {
    // * check review already
    $check_review = Review::where('order_id', $request->order_id)
      ->where('product_id', $request->product_id)
      ->first();

    if ($check_review) {
      // * jika sudah ada return with Api Resource
      return new ReviewResource(true, 'Data Review Sudah Ada!', $check_review);
    }

    // * jika belum ada ulasan
    $review = Review::create([
      'rating'      => $request->rating,
      'review'      => $request->review,
      'product_id'  => $request->product_id,
      'order_id'    => $request->order_id,
      'customer_id' => auth()->guard('api_customer')->user()->id,
    ]);

    if ($review) {
      // * jika success return with Api Resource
      return new ReviewResource(true, 'Data Review Berhasil Disimpan', $review);
    }

    // * jika failed return with Api Resource
    return new ReviewResource(false, 'Data Review Gagal Disimpan', null);
  }
}

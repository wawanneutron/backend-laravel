<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  /**
   * index
   * get all data products
   * @return void
   */
  public function index()
  {
    // * get products
    $products = Product::with('category')
      // count and average
      ->withAvg('reviews', 'rating')
      ->withCount('reviews')
      // search
      ->when(request()->q, function ($products) {
        $products = $products->where('title', 'like', '%' . request()->q . '%');
      })->latest()->paginate(8);

    // * return with Api Resource
    return new ProductResource(true, 'List Data Products', $products);
  }

  /**
   * show
   * show detail product
   * @param  mixed $slug
   * @return void
   */
  public function show($slug)
  {
    $product = Product::with(['category', 'reviews.customer'])
      // count and average
      ->withAvg('reviews', 'rating')
      ->withCount('reviews')
      ->where('slug', $slug)
      ->first();

    if ($product) {
      // * return success with Api Resource
      return new ProductResource(true, 'Detail Data Product!', $product);
    }
    // * return failed with Api Resource
    return new ProductResource(false, 'Detail Data Product Tidak Ditemukan!', null);
  }
}

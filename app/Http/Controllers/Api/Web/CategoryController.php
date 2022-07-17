<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  /**
   * index
   * API public
   * menampilkan data category di halaman web
   * @return void
   */
  public function index()
  {
    $categories = Category::latest()->get();

    // * return with Api Resource
    return new CategoryResource(true, 'List Data Categories', $categories);
  }

  /**
   * show
   * show detail category
   * @param  mixed $slug
   * @return void
   */
  public function show($slug)
  {
    $category = Category::with('products.category')
      // * extract product with query and count review and average review
      ->with('products', function ($query) {
        $query->withCount('reviews');
        $query->withAvg('reviews', 'rating');
      })
      ->where('slug', $slug)->first();


    // * return success with Api Resource
    if ($category) {
      return new CategoryResource('true', 'Data Product By Category: ' . $category->name . '', $category);
    }

    return new CategoryResource(false, 'Detail Data Category Tidak Ditemukan!', null);
  }
}

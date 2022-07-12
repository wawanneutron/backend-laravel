<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\support\Str;

class CategoryController extends Controller
{
  /**
   * index
   * * get categories
   * @return void
   */
  public function index()
  {
    $categories = Category::when(request()->q, function ($categories) {
      $categories = $categories->where('name', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);

    //  * return with Api Resource
    return new CategoryResource(true, 'List Data Categories', $categories);
  }

  /**
   * store category to database
   * and return with API Resource
   * @param  mixed $request
   * @return void
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'image' => 'required|image|mimes:jpeg,png,jpg|max:1000',
      'name'  => 'required|unique:categories'
    ]);

    //  * jika validasi gagal
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // * upload image
    $image = $request->file('image');
    $image->storeAs('public/categories', $image->hashName());

    // * create categories
    $category = Category::create([
      'image' => $image->hashName(),
      'name' => $request->name,
      'slug' => Str::slug($request->name, '-')
    ]);

    if ($category) {
      // * jika berhasil, return success with API Resource
      return new CategoryResource(true, 'Data Category Berhasil Disimpan!', $category);
    }
    // * jika gagal, return failed with API Resource
    return new CategoryResource(false, 'Data Category Gagal Disimpan!', null);
  }

  /**
   * show
   *
   * @param  mixed $id
   * @return void
   */
  public function show($id)
  {
    $category = Category::whereId($id)->first();

    // * return success with API Resource
    if ($category) {
      return new CategoryResource(true, 'Detail Data Category!', $category);
    }

    // * return failed with API Resource
    return new CategoryResource(true, 'Detail Data Category Tidak Ditemukan!', null);
  }

  /**
   * update data category
   * and return with API Resource
   * @param  mixed $request
   * @param  mixed $category
   * @return void
   */
  public function update(Request $request, Category $category)
  {
    $validator = Validator::make($request->all(), [
      'name' =>  'required|unique:categories,name,' . $category->id,
    ]);

    //  * jika validasi gagal
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // * check image update
    if ($request->file('image')) {
      // * hapus gambar lama
      Storage::disk('local')->delete('public/categories/' . basename($category->image));

      // * upload new image
      $image = $request->file('image');
      $image->storeAs('public/categories', $image->hashName());

      // * update category with new image
      $category->update([
        'image' => $image->hashName(),
        'name' => $request->name,
        'slug' => Str::slug($request->name, '-')
      ]);
    }

    // * update category without image
    $category->update([
      'name' => $request->name,
      'slug' => Str::slug($request->name, '-')
    ]);

    // * return success with API Resource
    if ($category) {
      return new CategoryResource(true, 'Data Category Berhasil Di Update!', $category);
    }

    // * return failed with API Resource
    return new CategoryResource(false, 'Data Category Gagal Di Update!', null);
  }

  /**
   * destory
   * delete category
   * @param  mixed $category
   * @return void
   */
  public function destroy(Category $category)
  {
    // * remove image
    Storage::disk('local')->delete('public/categories/' . basename($category->image));

    //  * return success with API Resource
    if ($category->delete()) {
      return new CategoryResource(true, 'Data Category Berhasil Dihapus!', null);
    }

    //  * return failed with API Resource
    return new CategoryResource(true, 'Data Category Gagal Dihapus!', null);
  }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    // * get products
    $prodcuts = Product::with('category')->when(request()->q, function ($prodcuts) {
      $prodcuts = $prodcuts->where('title', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);

    // * return with API Resource
    return new ProductResource(true, 'List Data Products', $prodcuts);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    abort(404);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'image'       => 'required|image|mimes:png,jpg, jpeg|max:1000',
      'title'       => 'required|unique:products',
      'category_id' => 'required',
      'description' => 'required',
      'weight'      => 'required',
      'price'       => 'required',
      'stock'       => 'required',
      'discount'    => 'required',
    ]);

    // * jika validasi gagal
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // * upload image
    $image = $request->file('image');
    $image->storeAs('public/products', $image->hashName());

    // * create product
    $product = Product::create([
      'image'       => $image->hashName(),
      'title'       => $request->title,
      'slug'        => Str::slug($request->title, '-'),
      'category_id' => $request->category_id,
      'user_id'     => auth()->guard('api_admin')->user()->id,
      'description' => $request->description,
      'weight'      => $request->weight,
      'price'       => $request->price,
      'stock'       => $request->stock,
      'discount'    => $request->discount
    ]);

    // * jika berhasil return with API Resource
    if ($product) {
      return new ProductResource(true, 'Data Product Berhasil Disimpan!', $product);
    }
    // * jika gagal return with API Resource
    return new ProductResource(false, 'Data Product Gagal Disimpan!', null);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $product = Product::whereId($id)->first();

    // * jika ditemukan return success with API Resource
    if ($product) {
      return new ProductResource(true, 'Detail Data Product!', $product);
    }

    // * jika tidak ditemukan return failed with API Resource
    return new ProductResource(false, 'Detail Data Product Tidak Ditemukan!', null);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    abort(404);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Product $product)
  {
    $validator = Validator::make($request->all(), [
      'title'       => 'required|unique:products,title,' . $product->id,
      'category_id' => 'required',
      'description' => 'required',
      'weight'      => 'required',
      'price'       => 'required',
      'stock'       => 'required',
      'discount'    => 'required'
    ]);

    // * jika validasi gagal
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    //  * check image, return true jika ada
    if ($request->file('image')) {
      // * remove old image
      Storage::disk('local')->delete('public/products/' . basename($product->image));

      // * upload new image
      $image = $request->file('image');
      $image->storeAs('public/products', $image->hashName());

      // * update product with new image
      $product->update([
        'image'       => $image->hashName(),
        'title'       => $request->title,
        'slug'        => Str::slug($request->title, '-'),
        'category_id' => $request->category_id,
        'user_id'     => auth()->guard('api_admin')->user()->id,
        'description' => $request->description,
        'weight'      => $request->weight,
        'price'       => $request->price,
        'stock'       => $request->stock,
        'discount'    => $request->discount
      ]);
    }

    // ? return false jika tidak ada request image
    // * update product without image

    $product->update([
      'title'       => $request->title,
      'slug'        => Str::slug($request->title, '-'),
      'category_id' => $request->category_id,
      'user_id'     => auth()->guard('api_admin')->user()->id,
      'description' => $request->description,
      'weight'      => $request->weight,
      'price'       => $request->price,
      'stock'       => $request->stock,
      'discount'    => $request->discount
    ]);

    // * jika berhasil return with API Resource
    if ($product) {
      return new ProductResource(true, 'Data Product Berhasil Diupdate!', $product);
    }
    // * jika gagal return with API Resource
    return new ProductResource(false, 'Data Product Gagal Diupdate!', null);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product)
  {
    // * remove image
    Storage::disk('local')->delete('public/products/' . basename($product->image));

    // * return success with Api Resource
    if ($product->delete()) {
      return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
    }

    // * return failed with Api Resource
    return new ProductResource(false, 'Data Product Gagal Dihapus!', null);
  }
}

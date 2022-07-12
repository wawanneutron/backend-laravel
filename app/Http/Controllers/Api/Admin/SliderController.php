<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
  /**
   * Display a listing of the resource.
   * show data sliders
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $sliders = Slider::latest()->paginate(5);

    // *return with Api Resource
    return new SliderResource(true, 'List Data Sliders', $sliders);
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
      'image' => 'required|image|mimes:png,jpg,jpeg|max:1000',
    ]);

    // * jika validation gagal return error with json format
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // * upload image
    $image = $request->file('image');
    $image->storeAs('public/sliders', $image->hashName());

    // * create slider
    $slider = Slider::create([
      'image' => $image->hashName(),
      'link'  => $request->link
    ]);

    // * jika true, return success with Api Resource
    if ($slider) {
      return new SliderResource(true, 'Data Slider Berhasil Disimpan!', $slider);
    }

    // * jika false, return failed with Api Resource
    return new SliderResource(false, 'Data Slider Gagal Disimpan!', null);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Slider $slider)
  {
    // * remove image
    Storage::disk('local')->delete('public/sliders/' . basename($slider->image));

    // * delete data slider di databse
    if ($slider->delete()) {
      // *return success with Api Resource
      return new SliderResource(true, 'Data Slider Berhasil Dihapus!', null);
    }

    // *return failed with Api Resource
    return new SliderResource(false, 'Data Slider Gagal Dihapus!', null);
  }
}

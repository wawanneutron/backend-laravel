<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\RajaOngkirResource;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
  /**
   * getProvinces
   * ? mendapatka semua data provinsi
   * @return void
   */
  public function getProvinces()
  {
    // * get all provinces
    $provinces = Province::all();

    // * return with Api Resource
    return new RajaOngkirResource(true, 'List Data Provinces', $provinces);
  }

  /**
   * getCities
   * ? dapatkan nama provinsinya
   * ? mendapatkan data kota berdasarkan id provinsi
   * @return void
   */
  public function getCities(Request $request)
  {
    // * get province name
    $province = Province::where('province_id', $request->province_id)->first();

    // * get city by province
    $cities = City::where('province_id', $request->province_id)->get();

    // * return with Api Resource
    return new RajaOngkirResource(true, 'List Data City By Province: ' . $province->name . '', $cities);
  }

  /**
   * checkOngkir
   * ? menghitung biaya ongkos kirim
   * ? berdasarkan exspedisi yangdipilih
   * ? dan berat nya barang
   * @return void
   */
  public function checkOngkir(Request $request)
  {
    // * Fetch Rest API
    $response = Http::withHeaders([
      'key' => config('services.rajaongkir.key'),
    ])->post('https://api.rajaongkir.com/starter/cost', [

      // send data
      'origin'      => 455, //! ID kota/Kabupaten Asal (Kab. Tangerang)
      'destination' => $request->destination, //! ID kota/Kabupaten Tujuan
      'weight'      => $request->weight,
      'courier'     => $request->courier
    ]);

    if ($response['rajaongkir']) {
      // * return with Api Resource
      return new RajaOngkirResource(true, 'List Data Biaya Ongkos Kirim: ' . $request->courier . '', $response['rajaongkir']);
    }
    // * rerturn failed
    return new RajaOngkirResource(false, 'Data Tidak Ditemukan', null);
  }
}

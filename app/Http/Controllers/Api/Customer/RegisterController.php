<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'     => 'required',
      'email'    => 'required|email|unique:customers',
      'password' => 'required|confirmed'
    ]);

    //  * jika proses validasi gagal
    if ($validator->fails()) {
      //  *return response json error validasi
      return response()->json($validator->errors(), 400);
    }

    // * create account customer
    $customer = Customer::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => Hash::make($request->password)
    ]);

    // * jika bernilai true 
    if ($customer) {
      // * return success with Api Resource
      return new CustomerResource(true, 'Register Customer Berhasil', $customer);
    }

    // * jika false kemudian return failed  with Api Resource
    return new CustomerResource(false, 'Register Customer Gagal!', null);
  }
}

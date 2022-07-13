<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  /**
   * index
   * proses login
   * @param  mixed $request
   * @return void
   */
  public function index(Request $request)
  {
    // * set validasi
    $validator = Validator::make($request->all(), [
      'email'    => 'required|email',
      'password' => 'required'
    ]);

    // * response error validasi
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    //  * get "email" dan "password" dari input
    $credentials = $request->only('email', 'password');

    // * check jika "email" dan "password" tidak sesuai
    $token = auth()->guard('api_admin')->attempt($credentials);
    if (!$token) {
      // response login failed
      return response()->json([
        'success' => false,
        'message' => 'Email atau password salah'
      ], 401);
    }

    // * response login "success" dengan generate "Token"
    return response()->json([
      'success' => true,
      'user'    => auth()->guard('api_admin')->user(),
      'token'   => $token
    ], 200);
  }

  /**
   * getUser
   *
   * @return void
   */
  public function getUser()
  {
    // * response data "user" yang sedang login
    return response()->json([
      'success' => true,
      'user'    => auth()->guard('api_admin')->user(),
    ], 200);
  }

  /**
   * refreshToken
   * ketika token expired, otomatis generate "token" baru
   * @param  mixed $request
   * @return void
   */
  public function refreshToken(Request $request)
  {
    // * refresh "token"
    $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

    //  * set user yang sedang login dengan token baru
    $setNewToken = JWTAuth::setToken($refreshToken)->toUser();

    //  * set header "Authorization" dengan type Bearer + "token" baru
    $request->headers->set('Authorization', 'Bearer ' . $refreshToken);

    //  * response data "user" dengan "token" baru
    return response()->json([
      'success' => true,
      'user'  => auth()->guard('api_admin')->user(),
      'token' => $refreshToken
    ], 200);
  }

  /**
   * logout
   * remove "token"
   * @return void
   */
  public function logout()
  {
    // * remove "token" JWT
    $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

    //  * response "success" logout
    return response()->json([
      'success' => true
    ], 200);
  }
}

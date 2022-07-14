<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  /**
   * index
   * login customer
   * @return void
   */
  public function index(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email'    => 'required|email',
      'password' => 'required'
    ]);

    // * check validation
    if ($validator->fails()) {
      // * return error with response api
      return response()->json($validator->errors(), 422);
    }

    //  * get request only "email" and "password"
    $credentials = $request->only('email', 'password');

    $token = auth()->guard('api_customer')->attempt($credentials);
    // * check jika "email" dan "password" tidak sesuai dengan di databse
    if (!$token) {
      return response()->json([
        'success' => false,
        'message' => 'Email or Password is incorrect'
      ], 401);
    }
    // * jika sesuai, return "success" with response with json and generate "Token JWT"
    return response()->json([
      'success' => true,
      'user'    => auth()->guard('api_customer')->user(),
      'token'   => $token
    ]);
  }

  /**
   * getUser
   * get data user yang sedang login
   * @return void
   */
  public function getUser()
  {
    // * response data "user" yang sedang login
    return response()->json([
      'success' => true,
      'user' => auth()->guard('api_customer')->user()
    ]);
  }


  /**
   * refreshToken
   * ? generate token baru dan  set user dengan token baru
   * @param  mixed $request
   * @return void
   */
  public function refreshToken(Request $request)
  {
    // * refresh "token"
    $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

    // * set user dengan "token" baru
    $user = JWTAuth::setToken($refreshToken)->toUser();

    // * set header "Authorization" dengan type Bearer + "token" baru
    $request->headers->set('Authorization', 'Bearer ' . $refreshToken);

    // * response data "user" dengan "token" baru
    return response()->json([
      'success' => true,
      'user'    => $user,
      'token'   => $refreshToken,
    ], 200);
  }

  /**
   * logout
   *
   * @return void
   */
  public function logout()
  {
    // * remove "token" JWT
    $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

    // * response "success" logout
    return response()->json([
      'success' => true,
    ], 200);
  }
}

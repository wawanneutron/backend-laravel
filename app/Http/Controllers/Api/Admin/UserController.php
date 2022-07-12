<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   * get data user (admin)
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = User::when(request()->q, function ($users) {
      $users = $users->whereName('like', '%' . request()->q . '%');
    })->latest()->paginate(5);

    return new UserResource(true, 'List Data Users', $users);
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
      'name'     => 'required',
      'email'    => 'required|unique:users',
      'password' => 'required|confirmed'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // * create user
    $user = User::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => bcrypt($request->password)
    ]);

    // * jika true return success with Api Resource
    if ($user) {
      return new UserResource(true, 'Data User Berhasil Disimpan!', $user);
    }

    // * jika false return failed with Api Resource
    return new UserResource(false, 'Data User Gagal Disimpan!', null);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::whereId($id)->first();

    // jika bernilai true
    if ($user) {
      // *return success with APi Resource
      return new UserResource(true, 'Detail Data User!', $user);
    }
    // jika bernilai false
    // *return failed with APi Resource
    return new UserResource(false, 'Detail Data User Tidak Ditemukan!', null);
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
  public function update(Request $request, User $user)
  {

    $validator = Validator::make($request->all(), [
      'name'     => 'required',
      'email'    => 'required|unique:users,email,' . $user->id,
      'password' => 'confirmed'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // * jika user tidak memasukan password
    if ($request->password == "") {

      // * update user without password
      $user->update([
        'name'      => $request->name,
        'email'     => $request->email,
      ]);
    }

    // * update user with new password
    $user->update([
      'name'      => $request->name,
      'email'     => $request->email,
      'password'  => bcrypt($request->password)
    ]);

    if ($user) {
      // * return success with Api Resource
      return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
    }

    // * return failed with Api Resource
    return new UserResource(false, 'Data User Gagal Diupdate!', null);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    if ($user->delete()) {
      //  * return success with Api Rsource
      return new UserResource(true, 'Data User Berhasil Dihapus!', null);
    }

    //  * return failed with Api Rsource
    return new UserResource(false, 'Data User Gagal Dihapus!', null);
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
  public function signup(Request $request){

     $user = new User([
       'id' => $request->input('id'),
       'password' => bcrypt($request ->input('password')),
       'phone'=> $request -> input('phone')
    ]);
    $post->save();

    $message = 1;     // 가입 성공

    return response()->json(['data'=> $message]);
  }
}

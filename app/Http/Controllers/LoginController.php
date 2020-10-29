<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginController extends Controller
{
  //
  public function login(Request $request){
    $userid = $request->id;
    $userpw = $request->pw;
    $token = $request->token;

    if(!auth()->attempt(['id' => $userid, 'password' => $userpw])) {
      return 0;
      //로그인 실패 리턴값
    }
    DB::table('users')->where('id',$userid)->update([
      'Token' => $token
    ]);

    return 1;

  }
  public function logout(Request $request){
    $userid = $request->id;
    DB::table('users')->where('id',$userid)->update([
      'Token' => 0
    ]);
    return 1;
  }
}

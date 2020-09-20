<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
  //
  public function login(Request $request){
    $userid = $request->id;
    $userpw = $request->pw;

    if(!auth()->attempt(['id' => $userid, 'password' => $userpw])) {
      return 0;
      //로그인 실패 리턴값
    }
    //로그인 성공 리턴값
session()->put('testid',54);
    return 1;

    return DB::table('users')->get();

  }
}

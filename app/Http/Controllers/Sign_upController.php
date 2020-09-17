<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class Sign_upController extends Controller
{

  public function signup(Request $request){

     $users = new User([
       'id' => $request->input('id'),
       'password' => bcrypt($request ->input('password')),
       'phone'=> $request -> input('phone'),
       'user_activation' => 1
    ]);
    $users->save();

    $message = 1;     // 가입 성공

    return response()->json(['data'=> $message]);
  }

    public function idcheck(Request $request){
      $id = $request->id;

      $data = User::select('*')->where(["ID"=>$id])->get();

      if(count($data)>0){
        $message = 0;     // 사용중인 아이디
      }
      else {
        $message = 1;     // 사용 가능한 아이디
      }
      return $message;
    }
}

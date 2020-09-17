<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class Sign_upController extends Controller
{

    public function signup(Request $request){

      return 0;

      // $user = new user([
      //   'id' => $request->input('id'),
      //   'password' => decrypt($request ->input('password')),
      // 	'phone'=> $request -> input('phone')
      // ]);
      // $post->save();
    }

    public function idcheck(Request $request){
      $id = $request->input("id");

      $data = User::select('*')->where(["ID"=>$id])->get();

      if(count($data)>0){
        $message = 0;     // 사용중인 아이디
      }
      else {
        $message = 1;     // 사용 가능한 아이디
      }
      return response()->json(['data'=> $message]);
    }
}

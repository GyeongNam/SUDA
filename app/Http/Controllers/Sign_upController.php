<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}

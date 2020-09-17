<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Sign_upController extends Controller
{

  public function signup(Request $request){
    
    $user = new user([
      'id' => $request->input('id'),
      'password' => bcrypt($request ->input('password')),
      'phone'=> $request -> input('phone')
    ]);
    $post->save();
  }
}

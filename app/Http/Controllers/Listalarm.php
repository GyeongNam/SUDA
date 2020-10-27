<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Listalarm extends Controller
{
  public function alarm(Request $request) {
    $data = DB::table('post_notification')->where('user_id',$request->userinfo)->where('categorie_num',$request->KEY)->get()->count();

    if($data == 1){
      DB::table('post_notification')->where('user_id',$request->userinfo)->where('categorie_num',$request->KEY)->delete();
    }
    else{
      DB::table('post_notification')->insert(['user_id' => $request->userinfo, 'categorie_num' => $request->KEY ]);
  }
  return 0;
 }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class NotiController extends Controller
{
  //
  public function comment_push(Request $request){
    // return $request;
    $data = DB::table('comment_notification')->where('user_id',$request->userinfo)->where('post_num',$request->KEY)->get()->count();
    if($data == 1){
      DB::table('comment_notification')->where('user_id',$request->userinfo)->where('post_num',$request->KEY)->delete();
    }
    else{
      DB::table('comment_notification')->insert([
        'user_id' => $request->userinfo,
        'post_num' => $request->KEY
      ]);
    }

    return 0;

  }
}

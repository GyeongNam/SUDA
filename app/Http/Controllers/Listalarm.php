<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class Listalarm extends Controller
{
  public function alarm(Request $request) {
    $data = DB::table('post_notification')->where('user_id',$request->user_id)->where('categorie_num',$request->pkey)->get()->count();

    if($data == 1){
     DB::table('post_notification')->where('user_id',$request->user_id)->where('categorie_num',$request->pkey)->delete();
    }
    else{

      DB::table('post_notification')->insert([
        'user_id' => $request->user_id,
        'categorie_num' => $request->pkey
      ]);
        return $request;
    }
  return 0;
 }
}

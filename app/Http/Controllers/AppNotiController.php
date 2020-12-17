<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Redirect;

class AppNotiController extends Controller
{
  //
  public function noti(Request $request){
    // return 0;
    $data = json_decode($request->key);
    // return $data;
    foreach ($data as $value) {
      DB::table('app_notification')->insert([
        'app_name' => $value->name,
        'app_title' => $value->title,
        'app_text' => $value->text,
        'app_bigtext' => $value->bigtext,
        'app_date' => $value->date
      ]);
    }
    $fileter = DB::table('app_notification_filter')->get();
    return json_encode($fileter,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  }
  public function manager_site(Request $request){

    $data = DB::table('app_notification')->get();
    return view('test',compact('data'));
  }
  public function filter(Request $request){
    $data = json_decode($request->array);
    foreach ($data as  $value) {
      $move =  DB::table('app_notification')->where('idx',$value)->get();
      DB::table('app_notification_filter')->insert([
        'app_name' => $move[0]->app_name,
        'app_title' => $move[0]->app_title,
        'app_text' => $move[0]->app_text,
        'app_bigtext' => $move[0]->app_bigtext,
        'app_date' => $move[0]->app_date
    ]);
    DB::table('app_notification')->where('idx',$value)->delete();
    // return $value;
  }
  return redirect('noti_manager');
}
}

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
    $data = json_decode(preg_replace("/\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2}/", "",$request->key));
      foreach ($data as $value) {
        DB::table('app_notification')->insert([
          'packagename' =>$value->packagename,
          'app_name' => $value->name,
          'app_title' => $value->title,
          'app_text' => $value->text,
          'app_bigtext' => $value->bigtext,
          'app_date' => $value->date
        ]);
      }


    // return 0;
    $fileter = DB::table('app_notification')->where('filter_status',0)->get();
    return json_encode($fileter,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  }
  public function manager_site(Request $request){

    $data = DB::table('app_notification')->where('filter_status',1)->get();
    return view('test',compact('data'));
  }
  public function filter(Request $request){
    $data = json_decode($request->array);
    foreach ($data as  $value) {
      DB::table('app_notification')->where('idx',$value)->update([
        'filter_status' => 0
      ]);
      // return $value;
    }
    return redirect('noti_manager');
  }
}

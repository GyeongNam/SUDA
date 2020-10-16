<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  public function main(Request $request){
    // return $request->userid;
    $userdata = DB::table('users')->where('id',$request->userid)->get();
    // $lastword = substr($userdata,1,-1);
    $alluserdata = DB::table('post')->where('post_activation',1)->join('categorie','post.categorie_num','categorie.categorie_num')->orderBy('desc')->get();
    // $postlist = json_encode(DB데이터 들어가야함,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    $jsondata = json_encode($alluserdata,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);//jsondata 쉽게 만들기

    return $jsondata;

  }
  public function get_categorie_list(){
    $data = DB::table('categorie')->get();

    return json_encode($data,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  }
  public function get_logfile(Request $request){
    $androidid = $request->androidid;
    $datea = date("y-m-d-H-s");

    if($request->hasfile('logfile')){
      $file = $request->file('logfile');
      $filename = $file->getClientOriginalName();
      $file->move(public_path('/log/'), $datea."_".$androidid.$filename);
      return 1;
    }
    return 0;
  }

}

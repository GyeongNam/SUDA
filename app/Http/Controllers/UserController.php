<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  public function main(Request $request){
    // return $request->userid;
    $userdata = DB::table('users')->where('id',$request->userid)->get();
    // $lastword = substr($userdata,1,-1);
    $alluserdata = DB::table('post')->where('post_activation',1)->join('categorie','post.categorie_num','categorie.categorie_num')->orderBydesc('post_num')->get();
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
    $datea = date("y-m-d-H-i-s");

    if($request->hasfile('logfile')){
      $file = $request->file('logfile');
      $filename = $file->getClientOriginalName();
      $file->move(public_path('/log/'), $datea."_".$androidid.$filename);
      return 1;
    }
    return 0;
  }
  public function statistics(){
    $data = (File::allFiles(public_path('log')));
    // return $data;

    for($i = 0; $i < count($data); $i++){
      $array[] = $data[$i]->getFilename();
      $data1[] =  File::get(public_path('/log/'.$array[$i]));
    }
    for($i = 0; $i < count($data1); $i++){
      $log[] = preg_split('/\/|\n/',$data1[$i]);
    }


    // 시간
    for($j =0; $j <count($log);$j++){
      for($i = 0; $i <(count($log[$j])-1)/4;$i++){
        $date[] = $log[$j][$i*4];
      }
      //crud
      for($i = 1; $i <(count($log[$j])-1);$i+=4){
        $crud[] = $log[$j][$i];
      }

      //location
      for($i = 2; $i <(count($log[$j])-1);$i+=4){
        $location[] = $log[$j][$i];
      }
      //key
      for($i = 3; $i <(count($log[$j])-1);$i+=4){
        $key[] = $log[$j][$i];
      }
    }
    //오늘
    $today_visitors = array_count_values($location)["login"];

    $datecount = 0;
    // 월별
    for($i=0;$i<count($date);$i++){
      $datecount += substr_count($date[$i],"2020-10");
    }
    return array_search("2020-10",$date);
    return $datecount;


    // return ;
  }

}

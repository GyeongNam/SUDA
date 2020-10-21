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
    $categorie = DB::table('categorie')->get();
    $timestamp = strtotime(date("Y-m-d")."-1 days");
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
        $key[] = trim($log[$j][$i]);
      }
    }
    //오늘
    $today_visitors = array_count_values($location)["login"];
    //로그인 배열 방 번호
    $today_visitors_key = array_keys($location,"login");
    for($i=0;$i<count($today_visitors_key);$i++){
      //$array1 = 로그인한 날짜
      $array1[] = $date[$today_visitors_key[$i]];
      //두번째 문자열엔 오늘 날짜 들어가야함

      // return date("Y-m-d",$timestamp);
      $today_strpos[] = (String)strpos($array1[$i],date("Y-m-d",$timestamp));
      $month_strpos[] = (String)strpos($array1[$i],date("Y-m"));
    }


    //월간 방문자수

    if(isset(array_count_values($today_strpos)["0"])){
      //어제 방문자수
      $yesterday_visitors_count = array_count_values($today_strpos)["0"];
    }
    else{

      $yesterday_visitors_count = 0;
    }
    if(isset(array_count_values($month_strpos)["0"])){
      //이번달 방문자수
      $month_visitors_count = array_count_values($month_strpos)["0"];
    }
    else{
      $month_visitors_count = 0;
    }
    $error_index = array_keys($key,"com.android.volley.error.NetworkError");
    for($i=0;$i<count($error_index);$i++){
      $error_date[] = $date[$error_index[$i]];

      $today_error_strpos[] = (String)strpos($error_date[$i],date("Y-m-d",$timestamp));
      $month_error_strpos[] = (String)strpos($error_date[$i],date("Y-m"));

    }
    if(isset(array_count_values($today_error_strpos)["0"])){
      //어제 에러
      $yesterday_error_count = array_count_values($today_error_strpos)["0"];
    }
    else{

      $yesterday_error_count = 0;
    }
    if(isset(array_count_values($month_error_strpos)["0"])){
      //이번달 에러
      $month_error_count = array_count_values($month_error_strpos)["0"];
    }
    else{
      $month_error_count = 0;
    }
    //자주들어가는 게시판 찾기
    $board_visit = array_keys($location,"PostdetailActivity");
    for($i=0;$i<count($board_visit);$i++){
      $board_visit_where[] = $key[$board_visit[$i]];
      $post_number_condition[] = is_numeric($board_visit_where[$i]);
      //게시판 방문 시간
      $post_visitor_date[] = $date[$board_visit[$i]];

    }
    $i_want_true = array_keys($post_number_condition,true);
    for($i=0;$i<count($i_want_true);$i++){
      //게시판 찾기
      $post_number[] = $board_visit_where[$i_want_true[$i]];
      //방문 게시판 시간
      $post1[] =  $post_visitor_date[$i_want_true[$i]];
      //어제 게시판 시간 판별
      $today_post_strpos[] = (String)strpos($post1[$i],date("Y-m-d",$timestamp));
      //한달 게시판 시간 판별
      $month_post_strpos[] = (String)strpos($post1[$i],date("Y-m"));

    }
    //어제 방문게시판 키
    $yesterday_visitors_key = array_keys($today_post_strpos,"0");
    //이번달 방문게시판 키
    $month_visitors_key = array_keys($month_post_strpos,"0");
    for($i=0;$i<count($yesterday_visitors_key);$i++){
      $yesterday_post_key[] = $post_number[$yesterday_visitors_key[$i]];
      //어제 방문게시판 통계
      $yesterday_visitors_board[] = DB::table('post')->select('categorie')->where('post_num',$yesterday_post_key[$i])
      ->join('categorie','post.categorie_num','categorie.categorie_num')->get();

    }
    for($i=0;$i<count($month_visitors_key);$i++){
      $month_post_key[] = $post_number[$month_visitors_key[$i]];
      $month_visitors_board[] = DB::table('post')->select('categorie')->where('post_num',$month_post_key[$i])
      ->join('categorie','post.categorie_num','categorie.categorie_num')->get();
    }
    if(isset(array_count_values($today_post_strpos)["0"])){
      //어제 방문 게시판 통계
      $today_post_count = array_count_values($today_post_strpos)["0"];
    }
    else{
      $today_post_count = 0;
    }
    if(isset(array_count_values($month_post_strpos)["0"])){
      //이번달 방문게시판 통계
      $month_post_count = array_count_values($month_post_strpos)["0"];
    }
    else{
      $month_post_count = 0;
    }
    for($i=0;$i<count($yesterday_visitors_board);$i++){
      $board_array[] =  $yesterday_visitors_board[$i][0]->categorie;
    }
    for($i=0;$i<count($categorie);$i++){
      $categorie_for [] = $categorie[$i]->categorie;
    }
    for($i=0;$i<count($categorie_for);$i++){
      if(isset(array_count_values($board_array)[$categorie_for[$i]])){
        $yesterday_json_board[$categorie_for[$i]] = (array_count_values($board_array)[$categorie_for[$i]]);
      }
      else{
        $yesterday_json_board[$categorie_for[$i]] = 0;
      }

    }
    for($i=0;$i<count($month_visitors_board);$i++){
      $board_array1[] =  $month_visitors_board[$i][0]->categorie;
    }
    for($i=0;$i<count($categorie_for);$i++){
      if(isset(array_count_values($board_array1)[$categorie_for[$i]])){
        $month_json_board[$categorie_for[$i]] = (array_count_values($board_array1)[$categorie_for[$i]]);
      }
      else{
        $month_json_board[$categorie_for[$i]] = 0;
      }

    }

    return view('manager',compact("yesterday_visitors_count","month_visitors_count","yesterday_error_count","month_error_count","yesterday_json_board","month_json_board"));
  }



}

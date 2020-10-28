<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Controllers\FCMController;
use DB;
class boardController extends Controller
{
    // public function mainlist(Request $request){ // 게시판 글 목록 리스트
    //   $id = Auth::user()->id;;
    //   $data_j = Post::select("*")->where(["kategorie"=>"자유게시판"])->get();
    //   $data_i = Post::select("*")->where(["kategorie"=>"일상게시판"])->get();
    //   $data_b = Post::select("*")->where(["kategorie"=>"비밀게시판"])->get();
    //   $data_bb = Post::select("*")->where(["kategorie"=>"뻘글게시판"])->get();
    //   $my_comment = Comment::select("*")->where(["c_writer"=>$id])->get();
    //
    //   return $data_j, $data_i, $data_b, $data_bb, $my_comment;
    // }
    //
    // public function j_list(Request $request){   // 자유게시판
    //   $data = Post::select("*")->where(["kategorie"=>"자유게시판"])->get();
    //   return $data;
    // }
    //
    // public function i_list(Request $request){   // 일상게시판
    //   $data = Post::select("*")->where(["kategorie"=>"일상게시판"])->get();
    //   return $data;
    // }
    //
    // public function b_list(Request $request){   // 비밀게시판
    //   $data = Post::select("*")->where(["kategorie"=>"비밀게시판"])->get();
    //   return $data;
    // }

    public function bb_list(Request $request){    // 뻘글게시판
      //use App\Http\Controllers\FCMController;
      $k = DB::table('users')->select('Token')->get();

      FCMController::fcm("바보", "쵲니웅", $k);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Recomment;

class CommentController extends Controller
{
    public function comment_llit(Request $request){
      $post_num = $request->post_num
      $comment = Comment::select("*")->where(["post_num"=>$post_num])->get();
      $recomment = Recomment::select("*")->where(["post_num"=>$post_num])->get();

    }
}

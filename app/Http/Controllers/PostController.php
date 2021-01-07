<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Recomment;
use Image;
use DB;
use DateTime;
use App\Http\Controllers\FCMController;


class PostController extends Controller
{
  public function add_post(Request $request){   // 게시글 추가 함수
    // return $request->file('image');
    if($request->hasFile('image')){
      $image = $request->file('image');
      $picture= $image->getClientOriginalName();
      Image::make($image)->save(public_path('/img/'.$picture));
    }
    else {
      $picture = null;
    }

    $post_num = Post::insertGetId([
      'categorie_num' => $request->categorie+1,
      'Title' => $request->Title,
      'Text' => $request->Text,
      'image' => $picture,
      'post_activation' => 1,
      'writer' => $request->writer
    ]);

    $keyword = DB::table('keyword')
    ->where(['pushkeyword'=>1])
    ->join('users', 'users.id', '=', 'keyword.userid')
    ->get();

    foreach($keyword as $key => $value){
      if(strpos($request->Title, $value->text) !== false) {
        $token = DB::table('users')->select('Token')->where(['id'=>$value->id])->get();
        if ($value->id != $request->writer) {
          FCMController::fcm("키워드와 관련된 글이 올라왔어요.",$request->Title, $token, "1", $post_num);
        }
      }
      if(strpos($request->Text, $value->text) !== false) {
        $token = DB::table('users')->select('Token')->where(['id'=>$value->id])->get();
        if ($value->id != $request->writer) {
          FCMController::fcm("키워드와 관련된 글이 올라았어요.",$request->Title, $token, "1", $post_num);
        }
      }
    }

    $board = DB::table('post_notification')
    ->where(['pushboard'=>1])
    ->join('users', 'users.id', '=', 'post_notification.user_id')
    ->get();
    // return $board;
    foreach ($board as $key => $value) {
      // return $request;
      $categorie = DB::table('categorie')->select('categorie')->where('categorie_num', $value->categorie_num)->get();
      if ($request->categorie+1 == $value->categorie_num) {
        $token = DB::table('users')->select('Token')->where(['id'=>$value->id])->get();
        //return $token;
        if ($value->id != $request->writer) {
          FCMController::fcm($categorie[0]->categorie."에 글이 올라왔어요.",$request->Title, $token, "1");
        }
      }
    }


    $message = 1;     // 등록 성공

  }

  public function up_post(Request $request){      // 게시글 수정 화면 데이터 바인딩 함수
    // return 0;
    $post_num = $request->post_num;
    // return $request;
    $data = Post::select('*')->where(['post_num'=>$post_num])
    ->join('categorie','post.categorie_num','categorie.categorie_num')->get();

    return json_encode($data,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  }

  public function update_post(Request $request){      // 게시글 업데이트 함수
    $post_num = $request->post_num;
    // return $request;
    $data = Post::select('*')->where('post_num',$post_num)->get();
    $pic = $data[0]->image;
    // return $request;
    if(true){      // 해당 게시글 번호에 대한 글이 있다면
      // return $request;
      if($request->hasFile('image')){
        $image = $request->file('image');
        $picture= $image->getClientOriginalName();
        Image::make($image)->save(public_path('/img/'.$picture));
        if(File::exists(public_path('/img/'.$pic))) {
          File::delete(public_path('/img/'.$pic));
        }
        Post::where('post_num', $post_num)->update([
          'categorie_num' => $request->categorie,
          'Title' => $request->Title,
          'Text' => $request->Text,
          'image' => $picture
        ]);
      }
      else {
        $picture = null;
        Post::where('post_num', $post_num)->update([
          'categorie_num' => $request->categorie,
          'Title' => $request->Title,
          'Text' => $request->Text,
          'image' => $picture
        ]);
      }

      $message = 1;     // 업데이트하고 성공
    }
    else {
      $message = 0;      // 업데이트 할것이 없어 실패
    }

    return $message;
  }

  public function delete_post(Request $request){    // 게시글 비활성화 함수
    DB::table('post')->where('post_num',$request->post_num)->update(['post_activation' => 0]);
    return 0;
  }

  public function post_detail(Request $request){    // 상세 글 조회
    // return 0;
    $post_num = $request->post_num;
    $userinfo = $request->userinfo;
    $comment = DB::table('comment')->where('post_num',$post_num)
    ->orderByRaw("IF(ISNULL(parent), c_num, parent), seq")->get();
    $data = DB::table('post')->where('post_num',$post_num)->get();

    $comment_push = DB::table('comment_notification')->where('user_id',$userinfo)->where('post_num',$post_num)->get()->count();
    return json_encode(compact("data","comment","comment_push"),JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  }
  public function post_reply(Request $request){//댓글달기
    $now = new DateTime;
    //게시글 작성자에게
    $k = DB::table('post')->select('Token')->where('post_num',$request->post_num)
    ->where('pushcomment',1)
    ->join('users','post.writer','users.id')->get();
    $writer = DB::table('post')->select('writer')->where('post_num',$request->post_num)->get();

    //대댓글
    if($request->comment_num!="null"){
      //대댓글 없을때
      $data = DB::table('comment')->where('parent',$request->comment_num)->orderBydesc('c_num')->get();
      $data1 = DB::table('comment')->where('parent',$request->comment_num)->get()->count(); // 대댓글 없을때
      // return $data1;
      //댓글일 경우
      $a = DB::table('comment')->select('Token')->where('c_num',$request->comment_num)
      ->where('pushcomment',1)
      ->where('c_activation',1)
      ->join('users','comment.c_writer','users.id')->get();
      // 대댓글일 경우
      $variable = $request->comment_num;
      $b = DB::table('comment')->select('Token')
      ->where(function($q) use ($variable){
        $q->where('c_num',$variable)
        ->orWhere('parent',$variable);
      })
      ->where('c_writer','!=',$request->writer)
      ->where('pushcomment',1)
      ->where('c_activation',1)
      ->join('users','comment.c_writer','users.id')->get()->unique('Token');
      // return $b;
      $comment_writer = DB::table('comment')->where('c_num',$request->comment_num)->get();
      $parent = DB::table('comment')->where('parent',$request->comment_num)->get();

      //첫 대댓글 달때
      if($data1==0){
        Comment::insert([
          'comment' => $request->reply,
          'c_writer' => $request->writer,
          'post_num'=> $request->post_num,
          'c_activation' => 1,
          'seq' => 1,
          'created_at' => $now->format('Y-m-d H:i:s'),
          'parent'=>$request->comment_num
        ]);
        //!댓글 작성자 == 대댓글작성자
        if($comment_writer[0]->c_writer!=$request->writer){
          FCMController::fcm("내 댓글에 답글이 달렸습니다.",$request->reply, $a, "1", $request->post_num);
        }

      }
      // 대댓글이 하나 이상 있을때
      else {
        Comment::insert([
          'comment' => $request->reply,
          'c_writer' => $request->writer,
          'post_num'=> $request->post_num,
          'c_activation' => 1,
          'seq' => $data[0]->seq+1,
          'created_at' => $now->format('Y-m-d H:i:s'),
          'parent'=>$request->comment_num
        ]);
        //!댓글 작성자 == 대댓글작성자
        FCMController::fcm("내 댓글에 답글이 달렸습니다.",$request->reply, $b, "1", $request->post_num);
      }

    }
    // 댓글
    else{
      Comment::insert([
        'comment' => $request->reply,
        'c_writer' => $request->writer,
        'post_num'=> $request->post_num,
        'created_at' => $now->format('Y-m-d H:i:s'),
        'c_activation' =>1,
        'seq' => 1,
      ]);
      //게시글 작성자에게 푸시알림
      //!글 작성자 == 댓글작성자
      if($writer[0]->writer != $request->writer){
        FCMController::fcm("내 게시글에 댓글이 달렸습니다.",$request->reply, $k, "1", $request->post_num);
      }

    }

    return 0;
  }
  public function post_rereply(Request $request){
    return $request;
  }
  public function del_reply(Request $request){

    DB::table('comment')->where('c_num',$request->c_num)->update(['c_activation' => 0]);
    return 0;
  }

  public function board_list(Request $request){
    $categorie = $request->categorie;
    $mypost = preg_replace("/\s+/","",$request->mypost);
    //저봐저 츤데레
    $pdata = DB::table('post_notification')->where('user_id',$request->userid)->where('categorie_num',$request->categorie)->get()->count();
    // return $pdata;
    //최진웅
    if($mypost=="내가쓴글"){
      $data = DB::table('post')->where('writer',$request->userid)->where('post_activation',1)->orderBydesc('post_num')->get();
      return json_encode(compact("data","pdata"),JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    }
    $data = DB::table('post')->where('categorie_num',$categorie)->where('post_activation',1)->orderBydesc('post_num')->get();
    return json_encode(compact("data","pdata"),JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

  }


  public function post_like(Request $request){
    $data = DB::table('postlike')->where('writer',$request->writer)->where('post_num',$request->post_num)->get()->count();

    if($data == 0){
      DB::table('postlike')->insert([
        'writer' =>$request->writer,
        'post_num' => $request->post_num
      ]);
      DB::table('post')->where('post_num',$request->post_num)->increment('like', 1);

    }
    else{
      DB::table('postlike')->where('writer',$request->writer)->where('post_num',$request->post_num)->delete();
      DB::table('post')->where('post_num',$request->post_num)->decrement('like', 1);
    }

    return 0;
  }
  public function get_categorie(){
    $data = DB::table('categorie')->get();
    return json_encode($data,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  }
  public function test(){
    // $collection = collect([
    //     ['brand' => 'Tesla',  'color' => 'red'],
    //     ['brand' => 'Pagani', 'color' => 'white'],
    //     ['brand' => 'Tesla',  'color' => 'black'],
    //     ['brand' => 'Pagani', 'color' => 'orange'],
    // ]);
    //
    // $plucked = $collection;
    // return $plucked;
    // ['Tesla' => 'black', 'Pagani' => 'orange']
    // $a = DB::table('comment')->get()->pluck('c_num','c_writer');
    $a = DB::table('comment')->select('Token')->where('c_num',220)->where('pushcomment',1)
    ->join('users','comment.c_writer','users.id')->get();
    $k = DB::table('users')->where('id','wlsdnd12')->get();
    // $b = DB::table('comment')->select('Token')->where('parent',220)
    // ->join('users','comment.c_writer','users.id')->get()->unique('Token');
    // DB::enableQueryLog();
    $b = DB::table('comment')
    ->select('Token')
    ->where(function($q){
      $q->where('c_num', 391)
      ->orWhere('parent', 391);
    })
    ->where('c_writer','!=','admin')
    ->where('pushcomment',1)
    ->where('c_activation',1)
    ->join('users','comment.c_writer','users.id')->get()->unique('Token');



    // where(function($q) use ($variable){
    //       $q->where('Cab', 2)
    //         ->orWhere('Cab', $variable);
    //   })
    return $b;
    FCMController::fcm("내 게시글에 댓글이 달렸습니다.","이잉 기모", $a);
    return 0;
  }
}

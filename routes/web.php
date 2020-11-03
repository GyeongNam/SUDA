<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Sign_upController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\NotiController;
use App\Http\Controllers\Listalarm;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\boardController;
use Illuminate\Http\Request;
// use DateTime;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/chartEvent', function(){
	broadcast(new \App\Events\chartEvent());
	echo "<script src ='js/app.js'></script>";
	// echo "<script type='text/javascript'>";
	// echo "window.Echo.channel('ccit')
  //     .listen('WebsocketEvent', (e) => {
  //       console.log(e); });";
	// echo "</script>";
});

Route::get('/', function () {
  return view('welcome');
});

Route::post('/main', [UserController::class, 'main']); // 로그인 후 첫 진입화면
Route::get('/test', function () {
  $now = new DateTime;
  $data = DB::table('post')->where('post.post_num',1)
  ->join('comment','post.post_num','comment.post_num')->get();
  $data1 = DB::table('comment')->where('parent',76)->orderBydesc('c_num')->get();

  // 정렬하기
  $data = DB::table('post')->where('post.post_num',103)->join('comment','post.post_num','comment.post_num')
  ->orderByRaw("IF(ISNULL(parent), c_num, parent), seq")->get();
  $data2 = DB::table('comment')->get();
  // return $data2[0]->created_at;
  return json_encode(compact("data","data2"),JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  // return urldecode(json_encode($test));
});
Route::post('/login', [LoginController::class, 'Login']); //로그인
Route::post('/logout', [LoginController::class, 'logout']); //로그인
Route::post('/idcheck', [Sign_upController::class, 'idcheck']); //로그인
Route::post('/signup', [Sign_upController::class, 'signup']);  //회원가입
//통계 페이지
Route::get('/manager', [UserController::class, 'statistics']);
Route::get('/PostCharts', function(){
  return view('PostCharts');
});
Route::get('/manager_layout', function(){
  return view('manager_layout');
});
Route::get('/manager_page',function(){
  return view('manager_page');
});
Route::get('/PostList', function(){
  return view('PostList');
});

Route::post('/add_post', [PostController::class, 'add_post']);        // 게시글 추가
Route::post('/up_post', [PostController::class, 'up_post']);       // 게시글 수정을 위한 데이터 바인딩
Route::post('/update_post', [PostController::class, 'update_post']);    // 게시글 수정
Route::post('/delete_post', [PostController::class, 'delete_post']);    // 게시글 비활성화(삭제)
Route::post('/SendMessage', [SMSController::class, 'SendMessage']); //SMS 인증
Route::post('/post_detail', [PostController::class, 'post_detail']);//게시글 상세보기
Route::post('/post_reply', [PostController::class, 'post_reply']);
Route::post('/post_rereply', [PostController::class, 'post_rereply']);
Route::post('/del_reply', [PostController::class, 'del_reply']);// 댓글 삭제
Route::post('/board_list', [PostController::class, 'board_list']);//게시판 리스트
Route::post('/post_like', [PostController::class, 'post_like']); //게시글 좋아요
Route::get('/get_categorie', [PostController::class, 'get_categorie']);
Route::get('/get_categorie_list', [UserController::class, 'get_categorie_list']);
Route::post('/get_logfile', [UserController::class, 'get_logfile']); // 로그파일 저장

Route::post('/comment_push', [NotiController::class, 'comment_push']);
Route::post('/alarm', [Listalarm::class, 'alarm']);
Route::get('/fcm', [FCMController::class, 'fcm']);

Route::get('/fcm1', [boardController::class, 'bb_list']);
Route::get('/t1', [PostController::class, 'test']);
Route::post('/alsetting', [FCMController::class, 'alsetting']);
Route::post('/keywordadd', [FCMController::class, 'keywordadd']);
Route::post('/getkeyword', [FCMController::class, 'getkeyword']);
Route::post('/removekeyword', [FCMController::class, 'removekeyword']);

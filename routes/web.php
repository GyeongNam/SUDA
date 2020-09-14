<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/add_post', [PostController::class, 'add_post']);        // 게시글 추가
Route::get('/up_post', [PostController::class, 'up_post']);       // 게시글 수정을 위한 데이터 바인딩
Route::post('/update_post', [PostController::class, 'update_post']);    // 게시글 수정
Route::post('/delete_post', [PostController::class, 'delete_post']);    // 게시글 비활성화(삭제)

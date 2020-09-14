<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/add_post', 'PostController@add_post');        // 게시글 추가
Route::post('/up_post', 'PostController@up_post');          // 게시글 수정을 위한 데이터 바인딩
Route::post('/update_post', 'PostController@update_post');    // 게시글 수정
Route::post('/delete_post', 'PostController@delete_post');    // 게시글 비활성화(삭제)

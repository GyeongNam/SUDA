<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function add_post(Request $request){   // 게시글 추가 함수
      if($request->hasFile('image')){
        $image = $request->file('image');
        $picture= $image->getClientOriginalName();
        Image::make($image)->save(public_path('/img/'.$picture));
      }
      else {
        $image = null;
      }

      $post = new Post([
    	'Kategorie' => $request->intput('kategorie'),
    	'Title' => $request->intput('Title'),
      'Text' => $request->input('Text'),
    	'image' => $image
    	'post_activation' = 1,
    	'writer' => $request->intput('writer')
      ]);
      $post->save();

      $message = 1;     // 등록 성공

      return response()->json(['data'=> $message]);
    }

    public function up_post(Request $request){      // 게시글 수정 화면 데이터 바인딩 함수
      $post_num = $request->input('post_num');
      $data = Post::select('*')->where(['post_num']=>$post_num)->first();

      if(count($data) > 0){      // 해당 게시글 번호에 대한 글이 있다면
        $Kategorie = $data->Kategorie;
        $Title = $data->Title;
        $Text = $data->Text;
        $image = $data->image;
        $message = 1      // 데이터 가져오기 성공
      }
      else {
        $message = 0      // 데이터를 가져올것이 없어 실패
      }

      return response()->json(['data'=> $message]);
    }

    public function update_post(Request $request){      // 게시글 업데이트 함수
      $post_num = $request->input('post_num');
      $data = Post::select('*')->where(['post_num']=>$post_num)->first();

      if(count($data) > 0){      // 해당 게시글 번호에 대한 글이 있다면
        if($request->hasFile('image')){
          $image = $request->file('image');
          $picture= $image->getClientOriginalName();
          Image::make($image)->save(public_path('/img/'.$picture));
        }
        else {
          $image = $data->image;
        }
        Post::where(['post_num'=> $post_num])->update([
          'Kategorie' => $request->input('Kategorie'),
        	'Title' => $request->input('Title'),
          'Text' => $request->input('Text'),
        	'image' => $image
        ]);
        $message = 1      // 업데이트하고 성공
      }
      else {
        $message = 0      // 업데이트 할것이 없어 실패
      }

      return response()->json(['data'=> $message]);
    }

    public function delete_post(Request $request){    // 게시글 비활성화 함수
    	$post_num = $request->input('post_num');
      $data = Post::select('*')->where(['post_num']=>$post_num)->first();

      if(count($data) > 0){      // 해당 게시글 번호에 대한 글이 있다면
        Post::where(['post_num'=> $post_num])->update([
          'post_activation' => 0]);
        $message = 1      // 업데이트하고 성공
      }
      else {
        $message = 0      // 업데이트 할것이 없어 실패
      }

      return response()->json(['data'=> $message]);
    }
}

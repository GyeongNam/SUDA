<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FCMController;
use Illuminate\Http\Request;
use DB;
use DateTime;
class SMSController extends Controller
{
  function SendMessage(Request $request){
    $sendNumber = $request->phone;
    $random = $request->random;
    // $smsdata['sendNumber'];  // 받는 전화번호
    // $sPhone = $smsdata['sPhone'];
    // $subject = $smsdata['subject'];
    // $sPhoneArray = explode("-", $sPhone);
    $sPhone1 = '010';   //처음 번호
    $sPhone2 = '9285';   //중간 번호
    $sPhone3 = '0290';   //마지막 번호


    /******************** 인증정보 ********************/
    $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL

    $sms['user_id'] = base64_encode("ccitsms"); //SMS 아이디.
    $sms['secure'] = base64_encode("1cc4bc9ea5d24c9811d2cf30d430276f") ;//인증키
    $sms['msg'] = base64_encode(stripslashes('[SUDA] 인증번호는"'.$random.'"입니다.')); //인증번호가 들오가야하는 곳
    $sms['rphone'] = base64_encode($sendNumber); // 받는 전화번호
    $sms['sphone1'] = base64_encode($sPhone1);
    $sms['sphone2'] = base64_encode($sPhone2);
    $sms['sphone3'] = base64_encode($sPhone3);
    $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
    //$sms['returnurl'] = base64_encode($_POST['returnurl']);
    // $sms['testflag'] = base64_encode($testflag);
    //$sms['destination'] = strtr(base64_encode($POST['destination']), '+/=', '-,');
    //$returnurl = $_POST['returnurl'];
    //$sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);
    //$sms['repeatNum'] = base64_encode($_POST['repeatNum']);
    //$sms['repeatTime'] = base64_encode($_POST['repeatTime']);
    //$nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

    $host_info = explode("/", $sms_url);
    $host = $host_info[2];
    $path = $host_info[3];
    //$path = $host_info[3]."/".$host_info[4];

    srand((double)microtime()*1000000);
    $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
    //print_r($sms);
    // 헤더 생성
    $header = "POST /".$path ." HTTP/1.0\r\n";
    $header .= "Host: ".$host."\r\n";
    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
    $data = "";
    // 본문 생성
    foreach($sms AS $index => $value){
      $data .="--$boundary\r\n";
      $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
      $data .= "\r\n".$value."\r\n";
      $data .="--$boundary\r\n";
    }
    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

    $fp = fsockopen($host, 80);

    if ($fp) {
      fputs($fp, $header.$data);
      $rsp = '';
      while(!feof($fp)) {
        $rsp .= fgets($fp,8192);
      }
      fclose($fp);
      $msg = explode("\r\n\r\n",trim($rsp));
      $rMsg = explode(",", $msg[1]);
      $Result= $rMsg[0]; //발송결과
      $this->Count= $rMsg[1]; //잔여건수
      $this->ResultCode = 0;

    } else {
      $this->ResultMsg = "Connection Failed";
    }
    return $rMsg[0];
    // return response()->json(['data'=>$rMsg]);
    // $message = 1;
  }

  function chatting(Request $request){
    $date = new DateTime();
    $user = $request->user;
    $room = $request->room;
    $message = $request->sendmsg;

    DB::table('chat_list')->insert([
      'message' => $message,
      'user' => $user,
      'ch_idx' => $room,
      'created_at' =>$date->format('yy-m-d H:i:s')
    ]);

    $talktoken = DB::table('chat_room')->select('users.Tocken')->join('users', 'chat_room.user','=', 'users.id')->where('user', '!=', $user)->where('chat_room','=',$room)->get();
    FCMController::fcm($message, $user, $talktoken);
    broadcast(new \App\Events\chartEvent($request->user, $request->room, $request->sendmsg));
    return $talktoken;
  }

  public function search(Request $request){
    $data = DB::select("SELECT
      id,follow
      FROM
      (SELECT * FROM CCIT_SUDA.users WHERE id LIKE '%$request->user2%' AND id !='$request->user1') AS A
      LEFT OUTER JOIN follow AS B
      ON B.f_user_id='$request->user1' AND A.id = B.follow;");
      return json_encode($data,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    }

    public function follows(Request $request){
      $id1 = $request->user1;
      $id2 = $request->user2;
      $date = new DateTime();
      $data = DB::table('follow')->where('f_user_id', $id1)->where('follow', $id2)->get()->count();
      $data3 = DB::table('follow')->where('f_user_id', $id2)->where('follow', $id1)->get()->count();
      //팔로우 되어있는지 체크 0은 안되어잇음을 나타냄
      if($data == 0) {

        //서로 팔로우가 안되어있을때
        if($data3<1){
          $room_idx = DB::table('room_idx')->insertGetId([
            "nothing" => ""
          ]);
          DB::table('follow')->insert([
            'f_user_id' => $id1,
            'follow' => $id2,
            'room_idx' => $room_idx
          ]);
          $idx = DB::table('follow')->select('room_idx')->where('f_user_id', $id1)->where('follow', $id2)->get();

          DB::table('chat_room')->insert([
            'user' => $id1,
            'chat_room' => $room_idx,
          ]);

          DB::table('chat_room')->insert([
            'user' => $id2,
            'chat_room' => $room_idx
          ]);
        }
        //한쪽은 팔로우가 되어있을
        else{
          $idx = DB::table('follow')->select('room_idx')->where('f_user_id', $id2)->where('follow', $id1)->get();
          DB::table('follow')->insert([
            'f_user_id' => $id1,
            'follow' => $id2,
            'room_idx' => $idx[0]->room_idx
          ]);
        }




      }
      else {
        $idx = DB::table('follow')->select('room_idx')->where('f_user_id', $id1)->where('follow', $id2)->get();
        DB::table('follow')->where('f_user_id', $id1)->where('follow', $id2)->delete();
        if($data3<1){
          DB::table('chat_room')->where('chat_room',$idx[0]->room_idx)->delete();
        }

      }
      return $data.",".$idx[0]->room_idx;
    }

    public function friendlist(Request $request) {
      $id1 = $request->user1;
      $data = DB::table('follow')->where('f_user_id', $request->user1)->get();

      // $data2 = DB::table('chat_room')->where('user', $request->user1)->select('chat_room')->get();


      return json_encode($data,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    }

    public function getroom(Request $request){
      $id = $request->userinfo;
      $room = $request->roomname;
      $data = preg_replace("/\s+/", "",$request->key);
      $data = str_replace('[','',$data);
      $data = str_replace(']','',$data);
      $data = explode(',', $data);

      if(count($data)>1){
        $idx = DB::table('room_idx')->insertGetId([
          "nothing" => ""
        ]);

        DB::table('chat_room')->insert([
          'user' => $id,
          'chat_room' => $idx,
          'room_name' => $room
        ]);

        foreach($data as $key => $value){
          DB::table('chat_room')->insert([
            'user' => $data[$key],
            'chat_room' => $idx,
            'room_name' => $room
          ]);
        }
        $redata = DB::table('chat_room')->where('chat_room',$idx)->get();
      }
      return $redata;
    }
    public function echoroom(Request $request){
      $user_list = DB::table('follow')->where('f_user_id', $request->userinfo)->get();
      $room_list = DB::select("SELECT * FROM
chat_room WHERE chat_room = ANY(SELECT chat_room FROM
chat_room WHERE USER = '$request->userinfo');");
      return json_encode(compact('user_list','room_list'),JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    }
    public function group_room(Request $request){
      $user = $request->user;
      $data = DB::select("SELECT b.* FROM
        chat_room AS b JOIN (SELECT *
          FROM chat_room WHERE user = '$user' AND room_name IS NOT NULL) AS a ON b.chat_room = a.chat_room;
          ");
          return json_encode($data,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

        }
      }

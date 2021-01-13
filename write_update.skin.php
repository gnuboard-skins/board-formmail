<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$bo = [];
for($idx=1; $idx<=10; $idx++) {
    $key = 'bo_'.$idx.'_subj';
    if($board[$key]=='') {
        $bo['bo_'.$idx] = $board['bo_'.$idx];
    } else {
        $bo[$board[$key]] = $board['bo_'.$idx];
    }
}

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

include('PHPMailer/Exception.php');
include('PHPMailer/SMTP.php');
include('PHPMailer/PHPMailer.php');

// PHPMailer 선언
$mail = new PHPMailer(true);

// 디버그 모드(production 환경에서는 주석 처리한다.)
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

// SMTP 서버 세팅
$mail->isSMTP();
try {

    if(array_key_exists('gmail', $bo)) {

        $gmail = explode(',',$bo['gmail']);
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->Username = $gmail[0];
        $mail->Password = $gmail[1];

    } else if(array_key_exists('naver', $bo)) {

        $naver = explode(',',$bo['naver']);
        $mail->Host = "smtp.naver.com";
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->Username = $naver[0];
        $mail->Password = $naver[1];

    }
    // 인코딩 셋
    $mail->CharSet = 'utf-8';
    $mail->Encoding = "base64";

    // 보내는 사람
    if(!array_key_exists('보내는사람', $bo)) throw new Exception("보내는사람을 설정해야 합니다.");
    $sender = explode(',',$bo['보내는사람']);
    $mail->setFrom($sender[0], $sender[1]);

    // 받는 사람
    if(!array_key_exists('받는사람', $bo)) throw new Exception("받는사람을 설정해야 합니다.");
    $receiver = explode(',',$bo['받는사람']);
    $mail->AddAddress($receiver[0], $receiver[1]);

    // 본문 html 타입 설정
    $mail->isHTML(true);

    // 제목
    $mail->Subject = "{$_POST['wr_name']}님으로부터 문의메일이 도착했습니다.";

    // 본문 (HTML 전용)
    $mail->Body = $_POST['wr_content'];
    // 본문 (non-HTML 전용)
    $mail->AltBody = strip_tags($_POST['wr_content']);

    // 첨부파일
    $file = get_file($bo_table, $wr_id);
    if($file['count']>0) {
        for ($i=0; $i<$file['count']; $i++) {
            $path = G5_DATA_PATH."/file/{$bo_table}/".$file[$i]['file'];
            if(!$mail->AddAttachment($path, $file[$i]['source'])) throw new Exception("파일 첨부에 실패 하였습니다.");
        }
    }

    $mail->Send();
    echo "Message has been sent";
} catch (phpmailerException $e) {
    echo $e->errorMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}

alert("문의를 성공적으로 전송하였습니다.\\n빠른시일내에 답변 드리겠습니다. ","/");
exit;

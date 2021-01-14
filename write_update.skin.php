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

if(array_key_exists('gmail', $bo) || array_key_exists('naver', $bo)) {
    // SMTP 서버 세팅
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->CharSet = 'utf-8';
    $mail->Encoding = "base64";
    $receiver = explode(',',$bo['받는사람']);

    // 제목
    $mail->Subject = "{$_POST['wr_name']}님으로부터 문의메일이 도착했습니다.";
    // 본문 (HTML 전용)
    $mail->Body = $wr_content;
    // 본문 (non-HTML 전용)
    $mail->AltBody = strip_tags($wr_content);

    // 본문 html 타입 설정
    $mail->isHTML(true);

    // 첨부파일
    $file = get_file($bo_table, $wr_id);
    if($file['count']>0) {
        for ($i=0; $i<$file['count']; $i++) {
            $path = G5_DATA_PATH."/file/{$bo_table}/".$file[$i]['file'];
            if(!$mail->AddAttachment($path, $file[$i]['source'])) throw new Exception("파일 첨부에 실패 하였습니다.");
        }
    }

    // 받는 사람
    if(!array_key_exists('받는사람', $bo))
        alert("관리자 페이지에서 받는 사람을 설정해야 합니다.");

    $username = '';
    $password = '';
    $sender = '';
    // gmail로 보내기
    if(array_key_exists('gmail', $bo)) {
        $mail->Host = "smtp.gmail.com";
        list($username, $password, $sender) = explode(',',$bo['gmail']);
    }
    // naver로 보내기
    else if(array_key_exists('naver', $bo)) {
        $mail->Host = "smtp.naver.com";
        list($username, $password, $sender) = explode(',',$bo['naver']);
    }

    // 필수입력 체크
    if(!$username || !$password || !$sender)
        alert("메일 발송을 위해서는 gmail|naver 필드에 쉼표(,)로 구분된 메일발송서버 email|password|발송자명 을 입력해야 합니다.");

    try {
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->setFrom($username, $sender);
        $mail->AddAddress($receiver[0], $receiver[1]);
        $mail->Send();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

alert("문의를 성공적으로 전송하였습니다.\\n빠른시일내에 답변 드리겠습니다. ","/");
exit;

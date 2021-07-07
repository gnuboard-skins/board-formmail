<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/**
 * 관리자 설정값 불러오기
 * - 발송메일: 네이버|구글
 * - 받는사람: receiver@sample.com|받는사람이름
 * - 보내는사람: sender@sample.com|보내는사람이름
 * - 인증: 아이디|비밀번호
 */
$cfg = [];
for($idx=1; $idx<=10; $idx++) {
    $key = 'bo_'.$idx.'_subj';
    if($board[$key]) $cfg[$board[$key]] = $board['bo_'.$idx];
}

$smtp_servers = [
    '네이버'=>'smtp.naver.com',
    '구글'=>'smtp.gmail.com'
];

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

include('PHPMailer/Exception.php');
include('PHPMailer/SMTP.php');
include('PHPMailer/PHPMailer.php');

$msg = "문의를 성공적으로 전송하였습니다.\\n빠른 시일내에 답변 드리겠습니다. ";
$url = $_POST['ret_url']?$_POST['ret_url']:G5_BBS_URL. "/write.php?bo_table={$bo_table}";

try {
    // 발송메일 정보가 없으면 메일 발송은 안함
    if(!$cfg['발송메일']) {
        throw new \Exception("문의를 성공적으로 전송하였습니다.\\n빠른 시일내에 답변 드리겠습니다. ");
    }

    // 발송메일 서버정보 확인
    if(!in_array($cfg['발송메일'], array_keys($smtp_servers))) {
        throw new \Exception("관리자 설정에서 발송메일 서버를 확인해 주세요. 지원서버[네이버|구글]");
    }

    // 받는사람 정보 확인
    // 2021.07.07 받는 사람을 여러명 지정 가능하도록 함
    $receivers = [];
    foreach(explode(',',$cfg['받는사람']) as $receiver) {
        list($_email, $_name) = explode('|', $receiver);
        if(!$_email || !$_name) throw new \Exception("관리자 설정에서 받는사람 정보를 확인해 주세요.");
        $receivers[] = [
            'email'=>$_email,
            'name'=>$_name
        ];
    }

    // 보내는사람 정보확인
    list($sender, $sender_name) = explode('|', $cfg['보내는사람']);
    if(!$sender || !$sender_name) throw new \Exception("관리자 설정에서 보내는사람 정보를 확인해 주세요.");

    // 인증정보 정보확인
    list($username, $password) = explode('|', $cfg['인증정보']);
    if(!$username || !$password) throw new \Exception("관리자 설정에서 인증정보를 확인해 주세요.");

    // PHPMailer 선언
    $mail = new PHPMailer(true);
    // 디버그 모드(production 환경에서는 주석 처리한다.)
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    // SMTP 서버 세팅
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->CharSet = 'utf-8';
    $mail->Encoding = "base64";
    $mail->Host = $smtp_servers[$cfg['발송메일']];

    //if($cfg['발송메일']=='네이버')
    //else if($cfg['발송메일']=='구글') $mail->Host = "smtp.gmail.com";

    // 제목
    $mail->Subject = "{$_POST['wr_name']}님으로부터 문의메일이 도착했습니다.";
    // 본문 (HTML 전용)
    $mail->Body = $content_sender;

    // 로고 이미지 추가
    $mail->addEmbeddedImage($board_skin_path.'/img/logo-formmail.png', 'logo');
    // 본문 (non-HTML 전용)
    $mail->AltBody = strip_tags($content_sender);

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

    $mail->Username = $username;
    $mail->Password = $password;
    $mail->setFrom($sender, $sender_name);
    foreach($receivers as $receiver) {
        $mail->AddAddress($receiver['email'], $receiver['name']);
    }
    $mail->Send();

    // 문의내용 접수 확인 발송
    if($_POST['wr_email'] && $_POST['wr_name']) {
        $mail->Body = $content_requester;
        $mail->Subject = "[{$sender_name}] {$_POST['wr_name']}님 문의내용이 접수 되었습니다.";
        $mail->clearAddresses();
        $mail->AddAddress($_POST['wr_email'], $_POST['wr_name']);
        $mail->Send();
    }

} catch (\Exception $e) {
    $msg = $e->getMessage();
}

alert($msg,$url);
exit;
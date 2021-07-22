<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 캡챠 무조건 검사
if (!chk_captcha()) {
    alert('자동등록방지 숫자가 틀렸습니다.');
}

$cfg = array();
for($idx=1; $idx<=10; $idx++) {
    $key = 'bo_'.$idx.'_subj';
    if($board[$key]) $cfg[$board[$key]] = $board['bo_'.$idx];
}

$contents_info = array();
if(!is_array($_POST['contents_info'])) {
    $contents_info['wr_content'] = '문의내용';
} else {
    foreach ($_POST['contents_info'] as $v) {
        $info = explode('|', $v);
        $contents_info[$info[0]] = $info[1];
    }
}

/**
 * 홈페이지 관리자의 메일 내용과
 * 문의 요청자의 메일 내용을 다르게 생성함
 */
$contents = "";
foreach ($contents_info as $k=>$v) {
    $contents.='<tr>
<th style="background-color: rgb(245, 245, 247); border: 1px solid rgb(226, 226, 226); padding:10px">'.$v.'</th>
<td style="border: 1px solid rgb(226, 226, 226); padding:10px">'.$_POST[$k].'</td>
</tr>';
}

$header = '<div style="width:520px; margin:0 auto;">
<h1 style="padding:15px 0; text-align: center"><img src="cid:logo" alt="logo"/></h1>';

$content_sender = '
<p>홈페이지로 부터 문의가 접수되었습니다.</p>
<p>본 문의 내용은 홈페이지에서도 확인 하실 수 있습니다.</p>';
$content_requester = '
<p>'.$cfg['타이틀'].'를 이용해 주셔서 감사합니다.</p>
<p>고객님께서 <strong>'.date("Y년 m월 d일 H:i").'</strong>에 문의한 내용이 정상적으로 접수되었습니다.</p>
<p>접수된 문의 내용은 아래와 같습니다.</p>
<p>영업일 기준 2~3일 내에 전화 또는 메일로 답변 드리도록 하겠습니다.</p>';

$footer = '<table style="border-collapse: collapse; width:100%;">
<thead><tr>
<th scope="col" colspan="2" style="background-color: rgb(245, 245, 247); padding:10px; border: 1px solid rgb(226, 226, 226); border-top:2px solid rgb(105, 151, 206)">문의내용</th>
</tr></thead>
<tbody>'.$contents.'</tbody>
</table>
<div style="color:rgb(88,88,88); font-size:12px">
<p>ㆍ 본 메일은 '.$cfg['타이틀'].'에서 이메일 서비스 수신동의 하에 발송된 메일입니다.</p>
<p>ㆍ (문의) '.$cfg['타이틀'].'('.$cfg['홈페이지'].') / E-mail: '.$cfg['이메일'].'</p>
</div>
</div>';

$wr_content = $content_sender = $header.$content_sender.$footer;
$content_requester = $header.$content_requester.$footer;

<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$contents_info = [];
if(!is_array($_POST['contents_info'])) {
    $contents_info['wr_content'] = '문의내용';
} else {
    foreach ($_POST['contents_info'] as $v) {
        $info = explode('|', $v);
        $contents_info[$info[0]] = $info[1];
    }
}

$contents = "";
foreach ($contents_info as $k=>$v) {
    $contents.='<tr>
<th style="background-color: rgb(245, 245, 247); border: 1px solid rgb(226, 226, 226); padding:10px">'.$v.'</th>
<td style="border: 1px solid rgb(226, 226, 226); padding:10px">'.$_POST[$k].'</td>
</tr>';
}

$wr_content = '<div style="width:520px; margin:0 auto;">
<h1 style="padding:15px 0; text-align: center"><img src="cid:logo" alt="logo"/></h1>
<p>홈페이지로 부터 문의가 접수되었습니다.</p>
<p>본 문의 내용은 홈페이지에서도 확인 하실 수 있습니다.</p>
<table style="border-collapse: collapse; width:100%;">
<thead><tr>
<th scope="col" colspan="2" style="background-color: rgb(245, 245, 247); padding:10px; border: 1px solid rgb(226, 226, 226); border-top:2px solid rgb(105, 151, 206)">문의내용</th>
</tr></thead>
<tbody>'.$contents.'</tbody>
</table>
<div style="color:rgb(88,88,88); font-size:12px">
<p>ㆍ 본 메일은 '.$config['cf_title'].'에서 이메일 서비스 수신동의 하에 발송된 메일입니다.</p>
<p>ㆍ (문의) '.$config['cf_title'].'(https://gnuskins.w3p.kr/) / E-mail: help@daium.com</p>
</div>
</div>';
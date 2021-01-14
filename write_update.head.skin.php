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


$new_contents = '<table border="1" cellspacing="0" cellpadding="10" style="width:500px;"><thead><tr><th scope="col" colspan="2" style="background-color: rgb(187, 187, 187); border-color: rgb(187, 187, 187);">문의내용</th></tr></thead><tbody>';
foreach ($contents_info as $k=>$v) {
    $new_contents.='<tr>
<th style="background-color: rgb(221, 221, 221); border-color: rgb(187, 187, 187);">'.$v.'</th>
<td style="border-color: rgb(187, 187, 187);">'.$_POST[$k].'</td>
</tr>';
}
$new_contents.= '</tbody></table>';

$wr_content = $new_contents;




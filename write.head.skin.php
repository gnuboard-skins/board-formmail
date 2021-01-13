<?php if (!defined('_GNUBOARD_')) exit;

// 로그인 한 상태라면 목록으로
// 로그인 하지 않은 사람만 문의를 남길 수 있다.
if($member['mb_id']) {
    goto_url("/bbs/board.php?bo_table={$bo_table}");
}

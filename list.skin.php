<?php
/*
 * 게시판 목록
 */
if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가
// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 3;

if ($is_checkbox) {
    $colspan++;
}
if ($is_good) {
    $colspan++;
}
if ($is_nogood) {
    $colspan++;
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/board-basic.css">', 10);
?>
<div class="content-title-wrap " data-stellar-background-ratio='0.5'>
    <div class='container'>
        <div class='row'>
            <div class='col-sm-12 col-md-6 col-lg-6'>
                <h2><?php echo $board['bo_subject'] ?></h2>
            </div>
            <div class='col-sm-12 col-md-6 col-lg-6'>
                <div class="text-right">
                    <span>Total</span> <?php echo number_format($total_count) ?>건<?php echo $page ?> 페이지
                </div>
            </div>
        </div>
    </div>
</div>
<section class="board-list-wrap container">
    <!-- 게시판 목록 시작 { -->
    <div id="bo_list" style="width:<?php echo $width; ?>">
        <div class="board-list-head">
            <div class="pull-left">
                <!-- 게시판 카테고리 시작 { -->
                <?php if ($is_category) { ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-th-list" aria-hidden="true"></i> <span>분류</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <?php
                                echo $_ASKTHEME->category($category_option);
                                ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- } 게시판 카테고리 끝 -->
            </div>
            <div class="pull-right">
                <!-- 게시판 페이지 정보 및 버튼 시작 { -->
                <div class="board-info-wrap">
                    <div class="pull-right">
                        <?php if ($rss_href || $write_href) { ?>
                            <?php if ($rss_href) { ?><a href="<?php echo $rss_href ?>" class="btn btn-sm btn-secondary"><i class="fa fa-rss" aria-hidden="true"></i> <span>RSS</span></a><?php } ?>
                            <?php if ($admin_href) { ?><a href="<?php echo $admin_href ?>" class="btn btn-sm btn-danger"><i class="fa fa-cog" aria-hidden="true"></i> <span>관리자</span></a><?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <!-- } 게시판 페이지 정보 및 버튼 끝 -->
            </div>
        </div>

        <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="sw" value="">

            <div class="board-table-wrap">
                <table class="table table-hover">
                    <caption class="sr-only"><?php echo $board['bo_subject'] ?> 목록</caption>
                    <thead class="thead-default">
                        <tr>
                            <th class="co-1">번호</th>
                            <?php if ($is_checkbox) { ?>
                                <th class="co-2">
                                    <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                                    <input type="checkbox" id="chkall" onclick="if (this.checked) {
                                                all_checked(true);
                                            } else {
                                                all_checked(false);
                                            }" autocomplete="off">
                                </th>
                            <?php } ?>
                            <th class="co-3">문의</th>
                            <th class="co-4">성함</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($list); $i++) {
                            ?>
                            <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
                                <td class="td_num co-1">
                                    <?php
                                        if ($list[$i]['is_notice']) { // 공지사항
                                            echo '<strong>공지</strong>';
                                        } else if ($wr_id == $list[$i]['wr_id']) {
                                            echo "<span class=\"bo_current\">열람중</span>";
                                        } else {
                                            echo $list[$i]['num'];
                                        }
                                        ?>
                                </td>
                                <?php if ($is_checkbox) { ?>
                                    <td class="td_chk co-2">
                                        <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                                        <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
                                    </td>
                                <?php } ?>
                                <td class="td_subject co-3">
                                    <?php
                                        echo $list[$i]['icon_reply'];
                                        if ($is_category && $list[$i]['ca_name']) {
                                            ?>
                                        <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link badge badge-medium-light"><?php echo $list[$i]['ca_name'] ?></a>
                                    <?php } ?>

                                    <a href="<?php echo $list[$i]['href'] ?>">
                                        <?php echo $list[$i]['subject'] ?>
                                    </a>
                                    <?php
                                        if (isset($list[$i]['icon_new'])) {echo $list[$i]['icon_new'];}
                                        if (isset($list[$i]['icon_hot'])) {echo $list[$i]['icon_hot'];}
                                        if (isset($list[$i]['icon_file'])) {echo $list[$i]['icon_file'];}
                                        if (isset($list[$i]['icon_link'])) {echo $list[$i]['icon_link'];}
                                        if (isset($list[$i]['icon_secret'])) {echo $list[$i]['icon_secret'];}
                                        ?>
                                    <div class="sm-show">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><?php echo $list[$i]['name'] ?></li>
                                        </ol>
                                    </div>
                                </td>
                                <td class="td_name sv_use co-4"><?php echo $list[$i]['name'] ?></td>
                            </tr>
                        <?php } ?>
                        <?php
                        if (count($list) == 0) {
                            echo '<tr><td colspan="' . $colspan . '" class="empty_table">게시물이 없습니다.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if ($list_href || $is_checkbox || $write_href) { ?>
                <div class="board-list-head">
                    <div class="bo_fx">
                        <?php if ($is_checkbox) { ?>
                            <div class="pull-left">
                                <script type="text/javascript">
                                    $(function() {
                                        $('.sel-delete').click(function() {
                                            $('.submit-delete').trigger('click');
                                        });
                                        $('.sel-copy').click(function() {
                                            $('.submit-copy').trigger('click');
                                        });
                                        $('.sel-move').click(function() {
                                            $('.submit-move').trigger('click');
                                        });
                                    });
                                </script>

                                <div class="hidden">
                                    <input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed = this.value" class="submit-delete">
                                    <input type="submit" name="btn_submit" value="선택복사" onclick="document.pressed = this.value" class="submit-copy">
                                    <input type="submit" name="btn_submit" value="선택이동" onclick="document.pressed = this.value" class="submit-move">
                                </div>
                                <div class="btn-manage">
                                    <a href="#delete" class="btn btn-sm btn-danger sel-delete"><i class="fa fa-trash"></i> <span>선택삭제</span></a>
                                    <a href="#copy" class="btn btn-sm btn-danger sel-copy"><i class="fa fa-files-o" aria-hidden="true"></i> <span>선택복사</span></a>
                                    <a href="#move" class="btn btn-sm btn-danger sel-move"><i class="fa fa-scissors"></i> <span>선택이동</span></a>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($list_href || $write_href) { ?>
                            <div class="pull-right">
                                <?php if ($list_href) { ?><a href="<?php echo $list_href ?>" class="btn btn-sm btn-secondary"><i class="fa fa-list" aria-hidden="true"></i> 목록</a><?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </form>
    </div>

    <?php if ($is_checkbox) { ?>
        <noscript>
            <p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
        </noscript>
    <?php } ?>

    <div class="paging-wrap">
        <?php
        if (defined('THEME_SHORT_URL') && THEME_SHORT_URL == true) {
            //짧은 주소 사용  get_pretty_url($bo_table, '', $qstr.'&amp;page=')
            echo $_ASKTHEME->get_paging($page, $total_page, get_pretty_url($bo_table, '', $qstr . '&amp;page='));
        } else {
            //일반 주소, 5.3x 버전
            echo $_ASKTHEME->get_paging($page, $total_page, './board.php?bo_table=' . $bo_table . $qstr . '&amp;page=');
        }
        ?>
    </div>

    <!-- 게시판 검색 시작 { -->
    <fieldset id="bo_sch" class="search-board-wrap">
        <legend class="sr-only">게시물 검색</legend>

        <form name="fsearch" method="get">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sop" value="and">
            <label for="sfl" class="sound_only">검색대상</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <select name="sfl" id="sfl">
                        <option value="wr_content" <?php echo get_selected($sfl, 'wr_content')?>>내용</option>
                        <option value="wr_name,1" <?php echo get_selected($sfl, 'wr_name,1')?>>글쓴이</option>
                    </select>
                </div>
                <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="form-control  required" size="15" maxlength="20">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-search" aria-hidden="true"></i> <span>검색</span></button>
                </div>
            </div>
        </form>
    </fieldset>
    <!-- } 게시판 검색 끝 -->

    <?php if ($is_checkbox) { ?>
        <script>
            function all_checked(sw) {
                var f = document.fboardlist;

                for (var i = 0; i < f.length; i++) {
                    if (f.elements[i].name == "chk_wr_id[]")
                        f.elements[i].checked = sw;
                }
            }

            function fboardlist_submit(f) {
                var chk_count = 0;

                for (var i = 0; i < f.length; i++) {
                    if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
                        chk_count++;
                }

                if (!chk_count) {
                    alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
                    return false;
                }

                if (document.pressed == "선택복사") {
                    select_copy("copy");
                    return;
                }

                if (document.pressed == "선택이동") {
                    select_copy("move");
                    return;
                }

                if (document.pressed == "선택삭제") {
                    if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
                        return false;

                    f.removeAttribute("target");
                    f.action = g5_bbs_url + "/board_list_update.php";
                }

                return true;
            }

            // 선택한 게시물 복사 및 이동
            function select_copy(sw) {
                var f = document.fboardlist;

                if (sw == "copy")
                    str = "복사";
                else
                    str = "이동";

                var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

                f.sw.value = sw;
                f.target = "move";
                f.action = g5_bbs_url + "/move.php";
                f.submit();
            }
        </script>
    <?php } ?>
    <!-- } 게시판 목록 끝 -->
</section>
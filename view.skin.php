<?php
/*
 * 게시물 보기 
 */
if (!defined("_GNUBOARD_")) {
    exit;
} // 개별 페이지 접근 불가
include_once(G5_LIB_PATH . '/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_CSS_URL . '/board-basic.css">', 10);
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<div class="content-title-wrap " data-stellar-background-ratio='0.5'>
    <div class='container'>
        <div class='row'>
            <div class='col-sm-12 col-md-6 col-lg-6 left-side'>
                <h2><?php echo $board['bo_subject'] ?></h2>
                <ul class='board-info'>
                    <li class='d-inline-block'>Read Level:<?php echo $board['bo_read_level'] ?></li>
                    <li class='d-inline-block'>Write Level:<?php echo $board['bo_write_level'] ?></li>
                    <li class='d-inline-block'>Upload Size:<?php echo $board['bo_upload_size'] / 1024 / 1024 ?>Mb</li>
                </ul>
            </div>
        </div>
        <div class="mobile-share-wrap row">
            <div class="col-sm-12">
                <nav class="fab-menu">
                    <input type="checkbox" href="#" class="fab-menu-open" name="fab-menu-open" id="fab-menu-open" />
                    <label class="fab-menu-open-button" for="fab-menu-open">
                        <span class="hamburger hamburger-1"></span>
                        <span class="hamburger hamburger-2"></span>
                        <span class="hamburger hamburger-3"></span>
                    </label>
                    <a href="#" class="fab-menu-item item-twitter" onclick="javascript:window.open('https://twitter.com/intent/tweet?text=[%EA%B3%B5%EC%9C%A0]%20' + encodeURIComponent(document.URL) + '%20-%20' + encodeURIComponent(document.title), 'twittersharedialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600'); return false;">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/Twitter.png">
                    </a>
                    <a href="#" class="fab-menu-item item-facebook" onclick="javascript:window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&amp;t=' + encodeURIComponent(document.title), 'facebooksharedialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600'); return false;">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/Facebook.png">
                    </a>
                    <a href="#" class="fab-menu-item item-google" onclick="javascript:window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL), 'googleplussharedialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=600'); return false;" target="_blank">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/Google_Plus.png">
                    </a>
                    <a href="#" class="fab-menu-item item-kakao" onclick="javascript:window.open('https://story.kakao.com/s/share?url=' + encodeURIComponent(document.URL), 'kakaostorysharedialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=600'); return false;">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/Kakao_Story.png">
                    </a>
                    <a href="#" class="fab-menu-item item-naver" onclick="javascript:window.open('http://share.naver.com/web/shareView.nhn?url=' + encodeURIComponent(document.URL) + '&amp;title=' + encodeURIComponent(document.title), 'naversharedialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;">
                        <img src="<?php echo G5_THEME_IMG_URL ?>/Naver.png">
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="board-view-wrap container">
    <!-- 게시물 상단 버튼 시작 { -->
    <div id="bo_v_top" class="view-buttons">
        <?php ob_start(); ?>
        <?php if ($prev_href || $next_href) { ?>
            <div class="btn-group pull-left">
                <?php if ($prev_href) { ?><a href="<?php echo $prev_href ?>" class="btn btn-sm btn-secondary"><i class="fa fa-chevron-left" aria-hidden="true"></i> <span>이전글</span></a><?php } ?>
                <?php if ($next_href) { ?><a href="<?php echo $next_href ?>" class="btn btn-sm btn-secondary"><span>다음글 </span><i class="fa fa-chevron-right" aria-hidden="true"></i> </a><?php } ?>
            </div>
        <?php } ?>

        <div class="btn-group pull-left">
            <?php if ($copy_href) { ?><a href="<?php echo $copy_href ?>" class="btn btn-sm btn-secondary" onclick="board_move(this.href); return false;"><i class="fa fa-files-o" aria-hidden="true"></i> <span>복사</span></a><?php } ?>
            <?php if ($move_href) { ?><a href="<?php echo $move_href ?>" class="btn btn-sm btn-secondary" onclick="board_move(this.href); return false;"><i class="fa fa-arrows" aria-hidden="true"></i> <span>이동</span></a><?php } ?>
            <?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" class="btn btn-sm btn-danger" onclick="del(this.href); return false;"><i class="fa fa-trash" aria-hidden="true"></i> <span>삭제</span></a><?php } ?>
        </div>
        <div class="pull-right">
            <a href="<?php echo $list_href ?>" class="btn btn-sm btn-secondary"><i class="fa fa-list" aria-hidden="true"></i> <span>목록</span></a>
            <?php if ($update_href) { ?><a href="<?php echo $update_href ?>" class="btn btn-sm btn-secondary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span>수정</span></a><?php } ?>
            <?php if ($search_href) { ?><a href="<?php echo $search_href ?>" class="btn btn-sm btn-secondary"><i class="fa fa-search" aria-hidden="true"></i> <span>검색</span></a><?php } ?>
            <?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>" class="btn btn-sm btn-secondary"><i class="fa fa-reply" aria-hidden="true"></i> <span>답변</span></a><?php } ?>
            <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i> <span>글쓰기</span></a><?php } ?>
        </div>
        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
        ?>
    </div>
    <!-- } 게시물 상단 버튼 끝 -->
    <!-- 게시물 읽기 시작 { -->
    <article id="bo_v" style="width:<?php echo $width; ?>">
        <header class="view-header">
            <h1 id="bo_v_title">
                <?php if ($category_name) { ?>
                    <span>[<?php echo $view['ca_name'] ?>]</span>
                <?php } ?>
                <?php echo cut_str(get_text($view['wr_subject']), 90); // 글제목 출력   
                ?>
            </h1>
        </header>

        <section id="bo_v_info" class="writer-info">
            <h3 class="sr-only">페이지 정보</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo $view['name'] ?>
                    <?php
                    if ($is_ip_view) {
                        echo "&nbsp;($ip)";
                    }
                    ?>
                </li>
                <li class="breadcrumb-item"><span class="sound_only">작성일</span><?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></li>
                <li class="breadcrumb-item">조회<?php echo number_format($view['wr_hit']) ?>회</li>
                <li class="breadcrumb-item">댓글<?php echo number_format($view['wr_comment']) ?>건</li>
            </ol>
        </section>

        <?php
        if ($view['file']['count']) {
            $cnt = 0;
            for ($i = 0; $i < count($view['file']); $i++) {
                if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
                    $cnt++;
                }
            }
        }
        ?>

        <?php if ($cnt) { ?>
            <!-- 첨부파일 시작 { -->
            <section id="bo_v_file" class="file-attach">
                <h3 class="sr-only">첨부파일</h3>
                <ul class="list-group">
                    <?php
                        // 가변 파일
                        for ($i = 0; $i < count($view['file']); $i++) {
                            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
                                ?>
                            <li class="list-group-item justify-content-between">
                                <a href="<?php echo $view['file'][$i]['href']; ?>" class="view_file_download">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                    <?php echo $view['file'][$i]['source'] ?>
                                    <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                                    <span>DATE : <?php echo $view['file'][$i]['datetime'] ?></span>
                                </a>
                                <?php if ($view['file'][$i]['download']) { ?>
                                    <span class="badge badge-secondary badge-pill"><?php echo $view['file'][$i]['download'] ?></span>
                                <?php } ?>

                            </li>
                    <?php
                            }
                        }
                        ?>
                </ul>
            </section>
            <!-- } 첨부파일 끝 -->
        <?php } ?>

        <?php
        if ($view['link']) {
            ?>
            <!-- 관련링크 시작 { -->
            <section id="bo_v_link" class="link-list">
                <h3 class="sr-only">관련링크</h3>
                <ul class="list-group">
                    <?php
                        // 링크
                        $cnt = 0;
                        for ($i = 1; $i <= count($view['link']); $i++) {
                            if ($view['link'][$i]) {
                                $cnt++;
                                $link = cut_str($view['link'][$i], 70);
                                ?>
                            <li class="list-group-item justify-content-between">
                                <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <?php echo $link ?>
                                </a>
                                <?php if ($view['link_hit'][$i]) { ?>
                                    <span class="badge badge-secondary badge-pill"><?php echo $view['link_hit'][$i] ?></span>
                                <?php } ?>
                            </li>
                    <?php
                            }
                        }
                        ?>
                </ul>
            </section>
            <!-- } 관련링크 끝 -->
        <?php } ?>

        <section id="bo_v_atc" class="view-contents">
            <h3 id="bo_v_atc_title" class="sr-only">본문</h3>

            <?php
            // 파일 출력
            $v_img_count = count($view['file']);
            if ($v_img_count) {
                echo "<div class='board-attach-image'>\n";

                for ($i = 0; $i <= count($view['file']); $i++) {
                    if ($view['file'][$i]['view']) {
                        //echo $view['file'][$i]['view'];
                        echo get_view_thumbnail($view['file'][$i]['view']);
                    }
                }

                echo "</div>\n";
            }
            ?>

            <!-- 본문 내용 시작 { -->
            <div id="bo_v_con"><?php echo $view['wr_content']?></div>
            <?php //echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우    
            ?>
            <!-- } 본문 내용 끝 -->
        </section>

        <!-- 링크 버튼 시작 { -->
        <div id="bo_v_bot" class="view-buttons">
            <?php echo $link_buttons ?>
        </div>
        <!-- } 링크 버튼 끝 -->

    </article>
    <!-- } 게시판 읽기 끝 -->

    <script type="text/javascript">
        <?php if ($board['bo_download_point'] < 0) { ?>
            $(function() {
                $("a.view_file_download").click(function() {
                    if (!g5_is_member) {
                        alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
                        return false;
                    }

                    var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

                    if (confirm(msg)) {
                        var href = $(this).attr("href") + "&js=on";
                        $(this).attr("href", href);

                        return true;
                    } else {
                        return false;
                    }
                });
            });
        <?php } ?>

        function board_move(href) {
            window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
        }

        $(function() {
            $("a.view_image").click(function() {
                window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
                return false;
            });

            // 추천, 비추천
            $("#good_button, #nogood_button").click(function() {
                var $tx;
                if (this.id == "good_button")
                    $tx = $("#bo_v_act_good");
                else
                    $tx = $("#bo_v_act_nogood");

                excute_good(this.href, $(this), $tx);
                return false;
            });

            // 이미지 리사이즈
            $("#bo_v_atc").viewimageresize();
        });

        function excute_good(href, $el, $tx) {
            $.post(
                href, {
                    js: "on"
                },
                function(data) {
                    if (data.error) {
                        alert(data.error);
                        return false;
                    }

                    if (data.count) {
                        $el.find("strong").text(number_format(String(data.count)));
                        if ($tx.attr("id").search("nogood") > -1) {
                            $tx.text("이 글을 비추천하셨습니다.");
                            $tx.fadeIn(200).delay(2500).fadeOut(200);
                        } else {
                            $tx.text("이 글을 추천하셨습니다.");
                            $tx.fadeIn(200).delay(2500).fadeOut(200);
                        }
                    }
                }, "json"
            );
        }
    </script>
    <!-- } 게시글 읽기 끝 -->
</div>
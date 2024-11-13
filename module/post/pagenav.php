<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$thePrev = Joe\thePrev($this);
$theNext = Joe\theNext($this);
if ($thePrev || $theNext) {
?>
    <div style="height:99px;margin-bottom: 20px;line-height: 1.42857143;">
        <nav class="article-nav">
            <!-- 上一篇 -->
            <?php if (!empty($thePrev)) : ?>
                <div class="box-body">
                    <a href="<?= $thePrev['permalink']; ?>">
                        <p class="muted-2-color"><i class="fa fa-angle-left em12"></i><i class="fa fa-angle-left em12 mr6"></i> 上一篇</p>
                        <div class="text-ellipsis-2"><?= $thePrev['title']; ?></div>
                    </a>
                </div>
            <?php endif; ?>
            <!-- 下一篇 -->
            <?php if (!empty($theNext)) : ?>
                <div class="box-body">
                    <a href="<?= $theNext['permalink']; ?>">
                        <p class="muted-2-color">下一篇 <i class="fa fa-angle-right em12 ml6"></i><i class="fa fa-angle-right em12"></i></p>
                        <div class="text-ellipsis-2"><?= $theNext['title']; ?></div>
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
<?php
}

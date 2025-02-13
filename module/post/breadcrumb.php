<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<div class="joe_bread">
    <ul class="joe_bread__bread breadcrumb">
        <li class="item">
            <a href="/" class="link" title="首页"><i class="fa fa-map-marker"></i> 首页</a>
        </li>
        <?php if (sizeof($this->categories) > 0) : ?>
            <li class="item">
                <a class="link" href="<?= joe\root_relative_link($this->categories[0]['permalink']); ?>" title="<?= $this->categories[0]['name']; ?>"><?= $this->categories[0]['name']; ?></a>
            </li>
        <?php endif; ?>
        <li class="item"> 正文</li>
    </ul>
</div>
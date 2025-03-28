<?php

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<div class="joe_bottom">
	<?php
	if ($this->is('index') && ($this->options->JFriendsSpiderHide != 'on' || !joe\detectSpider())) {
		$friends = Db::name('friends')->where('status', 1)->whereFindInSet('position', 'index_bottom')->order('order', 'desc')->select()->toArray();
		if (sizeof($friends) > 0) : ?>
			<?php
			$friends_page_url = null;
			$friends_page = Db::name('contents')->where(['type' => 'page', 'template' => 'friends.php', 'status' => 'publish'])->find();
			if ($friends_page) {
				$friends_page_pathinfo = Typecho\Router::url('page', $friends_page);
				$friends_page_url = Typecho\Common::url($friends_page_pathinfo, $this->options->index);
				$friends_page_url = joe\root_relative_link($friends_page_url);
			}
			?>
			<div class="joe_container fluid-widget">
				<div class="links-widget mb20" style="min-width: 100%;">
					<div class="box-body notop">
						<div class="title-theme">友情链接<?= $friends_page_url ? ('<div class="pull-right em09 mt3"><a href="' . $friends_page_url . '" class="muted-2-color"><i class="fa fa-angle-right fa-fw"></i>申请友链</a></div>') : null ?></div>
					</div>
					<div style="min-width: 100%;" class="links-box links-style-simple zib-widget">
						<?php
						if ($this->options->JFriends_shuffle == 'on') shuffle($friends);
						$friends = array_values($friends);
						foreach ($friends as $key => $item) echo '<a rel="' . $item['rel'] . '" target="_blank" class="' . ($key ? 'icon-spot' : null) . '" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' . ($item['description'] ?? '暂无简介') . '" referrer="unsafe-url" href="' . $item['url'] . '" title="' . $item['title'] . '">' . $item['title'] . '</a>';
						if ($friends_page_url) echo '<a class="icon-spot" href="' . $friends_page_url . '">查看更多</a>';
						?>
					</div>
				</div>
			</div>
	<?php endif;
	}
	?>
</div>
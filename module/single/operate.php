<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<div style="padding: 15px;font-size: .9em;color: var(--muted-3-color);text-align: center;">喜欢就支持一下吧</div>
<div class="post-actions">
	<a href="javascript:;" data-action="like" class="action action-like">
		<svg class="icon" aria-hidden="true">
			<use xlink:href="#icon-like"></use>
		</svg>
		<text>点赞</text>
		<count><?= joe\getAgree($this) ?></count>
	</a>
	<span class="hover-show dropup action action-share">
		<svg class="icon" aria-hidden="true">
			<use xlink:href="#icon-share"></use>
		</svg><text>分享</text>
		<div class="zib-widget hover-show-con share-button dropdown-menu">
			<div>
				<?php
				$description = $this->fields->description ? $this->fields->description : joe\markdown_filter($this->description);
				$description = urlencode($description);
				$pic = urlencode(joe\getThumbnails($this)[0]);
				$title = urlencode($this->title . ' - ' . $this->options->title);
				$permalink = urlencode(joe\root_relative_link($this->permalink));
				?>
				<a rel="nofollow" class="share-btn qzone" target="_blank" title="QQ空间" href="https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?= $permalink ?>&title=<?= $title ?>&pics=<?= $pic ?>&summary=<?= $description ?>">
					<icon><svg class="icon" aria-hidden="true">
							<use xlink:href="#icon-qzone-color"></use>
						</svg></icon><text>QQ空间<text></text></text>
				</a>
				<a rel="nofollow" class="share-btn weibo" target="_blank" title="微博" href="https://service.weibo.com/share/share.php?url=<?= $permalink ?>&title=<?= $title ?>&pic=<?= $pic ?>&searchPic=false">
					<icon><svg class="icon" aria-hidden="true">
							<use xlink:href="#icon-weibo-color"></use>
						</svg></icon><text>微博<text></text></text>
				</a>
				<a rel="nofollow" class="share-btn qq" target="_blank" title="QQ好友" href="https://connect.qq.com/widget/shareqq/index.html?url=<?= $permalink ?>&title=<?= $title ?>&pics=<?= $pic ?>&desc=<?= $description ?>">
					<icon><svg class="icon" aria-hidden="true">
							<use xlink:href="#icon-qq-color"></use>
						</svg></icon><text>QQ好友<text></text></text>
				</a>
				<!-- 如果你有海报插件，将下面的注释解开，通过点击下方的图标调用生成海报 -->
				<!-- <a rel="nofollow" class="share-btn poster" title="海报分享" href="javascript:;">
					<icon>
						<svg class="icon" aria-hidden="true">
							<use xlink:href="#icon-poster-color"></use>
						</svg>
					</icon>
					<text>海报分享<text></text></text>
				</a> -->
				<!-- 如果你有海报插件，将下面的注释解开，通过点击下方的图标调用生成海报 -->
				<a rel="nofollow" class="share-btn copy" data-clipboard-text="<?= $this->permalink ?>" data-clipboard-tag="链接" title="复制链接" href="javascript:;">
					<icon>
						<svg class="icon" aria-hidden="true">
							<use xlink:href="#icon-copy-color"></use>
						</svg>
					</icon>
					<text>复制链接<text></text></text>
				</a>
			</div>
		</div>
	</span>

	<?php
	if ($this->options->JWeChatRewardImg || $this->options->JAlipayRewardImg || $this->options->JQQRewardImg) {
	?>
		<a href="javascript:;" data-toggle="RefreshModal" data-target="#rewards-modal-<?= $this->cid ?>" data-remote="<?= joe\root_relative_link($this->options->index . '/joe/api/user_rewards_modal?cid=' . $this->cid) ?>" class="rewards action action-rewards">
			<svg class="icon" aria-hidden="true">
				<use xlink:href="#icon-money"></use>
			</svg>
			<text>赞赏</text>
		</a>
	<?php
	} else {
	?>
		<a href="javascript:;" data-action="favorite" class="action action-favorite">
			<svg class="icon" aria-hidden="true">
				<use xlink:href="#icon-favorite"></use>
			</svg>
			<text>收藏</text>
			<count></count>
		</a>
	<?php
	}
	?>
</div>
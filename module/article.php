<?php

/**
 * 文章模块
 */
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<article class="joe_detail__article">
	<?php if (!$this->hidden && $this->fields->video) : ?>
		<div class="joe_detail__article-video">
			<style>
				.featured-video-episode {
					margin-bottom: 0;
					line-height: 1.4;
					font-size: 14px;
				}

				.featured-video-episode .switch-video {
					line-height: 1.6;
					border-radius: 4px;
					padding: 6px 20px;
					text-align: center;
					background: rgba(136, 136, 136, .1);
					--main-color: var(--muted-color);
					display: inline-block;
					margin: 4px 4px 0;
					min-width: calc(20% - 8px);
					color: var(--main-color);
				}

				.featured-video-episode .switch-video:hover {
					color: var(--theme);
				}

				.switch-video.active {
					color: var(--focus-color);
				}

				.featured-video-episode .badg.badg-sm {
					min-width: 19px;
				}

				.switch-video.active .badg {
					background: var(--focus-color);
					color: #fff;
				}

				.switch-video .episode-active-icon {
					display: inline-block;
					width: 0;
					height: 1.2em;
					vertical-align: middle;
					margin-right: 0;
					transition: .6s;
				}

				.switch-video.active .episode-active-icon {
					background-image: url(<?= joe\theme_url('assets/images/playing.svg', false) ?>);
					width: 1em;
					margin-right: 6px;
				}

				@media (max-width: 640px) {
					.featured-video-episode .switch-video {
						width: calc(50% - 4px);
						padding: 6px 10px;
						margin: 2px 2px 0;
					}
				}
			</style>
			<h2 class="title">播放预览</h2>
			<iframe allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen" data-player="<?php $this->options->JCustomPlayer ? $this->options->JCustomPlayer() : Helper::options()->themeUrl('module/player.php?url=') ?>"></iframe>
			<!-- <div class="play">
				<div class="title">播放预览</div>
				<div class="box">

				</div>
			</div> -->
			<!-- <div class="episodes">
				<div class="title">剧集列表</div>
				<?php $video_arr = explode("\r\n", $this->fields->video); ?>
				<div class="box">
					<?php foreach ($video_arr as $item) : ?>
						<div class="item" data-src="<?= urlencode(explode("$", $item)[1]) ?>" alt="<?= explode("$", $item)[2] ?>"><?= explode("$", $item)[0] ?></div>
					<?php endforeach; ?>
				</div>
			</div> -->

			<div class="featured-video-episode mt10 dplayer-featured">
				<?php $video_arr = joe\optionMulti($this->fields->video, "\r\n", '$') ?>
				<?php foreach ($video_arr as $key => $item) : ?>
					<a data-toggle="tooltip" class="switch-video text-ellipsis" data-index="<?= $key + 1 ?>" video-url="<?= $item[1] ?? '' ?>" data-original-title="<?= $item[2] ?? '' ?>" href="javascript:;"><span class="mr6 badg badg-sm"><?= $key + 1 ?></span><i class="episode-active-icon"></i><?= $item[0] ?? '' ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<script>
			window.onload = () => {
				let iframe = document.querySelector('.joe_detail__article-video>iframe').contentWindow;
				let video = iframe.videoPlayer;
				if (video) {
					const next = () => {
						let item = document.querySelector('.featured-video-episode>.switch-video.active');
						if (item.nextSibling) item.nextSibling.click()
					}
					video.addEventListener('ended', next, false);
					video.addEventListener('error', setTimeout(() => {
						next();
					}, 2000), false);
				}
			}

			// window.onload = () => {
			// 	let iframe = document.querySelector('.joe_detail__article>.joe_detail__article-video>.play>.box>iframe').contentWindow
			// 	const next = () => {
			// 		let item = $('.joe_detail__article .episodes .box .item');
			// 		if (item) {
			// 			for (var i in item) {
			// 				if (item[i].className == 'item active') {
			// 					var next_item = item[Number(i) + 1]
			// 					if (next_item) {
			// 						let url = decodeURIComponent(next_item.dataset.src);
			// 						iframe.videoPlayer.switchVideo({
			// 							'url': url
			// 						})
			// 						iframe.videoPlayer.play()
			// 						item[i].classList.remove('active')
			// 						next_item.classList.add('active')
			// 					}
			// 					break;
			// 				}
			// 			}
			// 		}
			// 	}
			// 	iframe.videoPlayer.video.onended = next
			// 	iframe.videoPlayer.video.onerror = () => {
			// 		setTimeout(() => {
			// 			next();
			// 		}, 2000)
			// 	}
			// }
		</script>
	<?php endif ?>

	<?php if ($this->is('post')) : ?>
		<?php if ($this->hidden) : ?>
			<form class="joe_detail__article-protected" action="<?php echo Typecho_Widget::widget('Widget_Security')->getTokenUrl($this->permalink); ?>">
				<div class="contain">
					<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
						<path d="M812.631 664.064H373.54a79.027 79.027 0 0 0-78.956 79.099v196.966h518.047a79.027 79.027 0 0 0 78.95-79.099V743.183a79.027 79.027 0 0 0-78.95-79.119z" fill="#F4CA1C" />
						<path d="M812.974 382.976h-32.369V313.37a272.256 272.256 0 1 0-544.512 0v69.606h-25.062A113.915 113.915 0 0 0 97.28 496.773v367.33A113.915 113.915 0 0 0 211.031 977.92h601.943A113.91 113.91 0 0 0 926.72 864.102V496.773a113.91 113.91 0 0 0-113.746-113.797zM305.715 313.37a202.634 202.634 0 1 1 405.269 0v69.606H305.715V313.37zm551.373 550.732a44.186 44.186 0 0 1-44.124 44.155H211.03a44.196 44.196 0 0 1-44.119-44.155V496.773a44.196 44.196 0 0 1 44.119-44.165h601.943a44.186 44.186 0 0 1 44.124 44.16v367.334zM525.373 554.138a62.7 62.7 0 0 0-34.816 114.82v103.46a34.816 34.816 0 1 0 69.632 0v-103.46a62.7 62.7 0 0 0-34.805-114.82z" fill="#595BB3" />
					</svg>
					<input class="password" type="password" placeholder="请输入访问密码...">
					<button class="submit" type="submit">确定</button>
				</div>
			</form>
		<?php else : ?>
			<?php _parseContent($this, $this->user->hasLogin()) ?>
		<?php endif; ?>
	<?php else : ?>
		<?php _parseContent($this, $this->user->hasLogin()) ?>
	<?php endif; ?>
</article>
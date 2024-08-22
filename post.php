<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta name="referrer" content="no-referrer" />
	<?php $this->need('module/head.php'); ?>
	<?php if (!empty($this->options->JPrismTheme)) : ?>
		<link rel="stylesheet" href="<?= \Joe\theme_url('assets/plugin/prism/themes/' . $this->options->JPrismTheme) ?>">
	<?php else : ?>
		<link rel="stylesheet" href="//cdn.staticfile.org/prism/1.23.0/themes/prism.min.css">
	<?php endif; ?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.post.css'); ?>">
	<script src="//cdn.staticfile.org/clipboard.js/2.0.6/clipboard.min.js"></script>
	<script src="<?= joe\theme_url('assets/plugin/prism/prism.min.js') ?>"></script>
	<script src="<?= joe\theme_url('assets/js/joe.post_page.js'); ?>"></script>
</head>

<body>
	<div id="Joe">
		<?php $this->need('module/header.php'); ?>
		<?php
		if (($this->options->JPost_Header_Img_Switch == 'on') && ($this->options->JPost_Header_Img || joe\getThumbnails($this)[0])) {
		?>
			<div class="HeaderImg" style="background: url(<?php echo ($this->options->JPost_Header_Img ? $this->options->JPost_Header_Img :  joe\getThumbnails($this)[0]) ?>) center; background-size:cover;">
				<div class="infomation">
					<?php
					if ($this->options->JPost_Header_Img) {
					?>
						<div class="title"><?php $this->options->title(); ?></div>
						<div class="desctitle">
							<span class="motto joe_motto"></span>
						</div>
					<?php
					} else {
					?>
						<div class="title"><?php $this->title(); ?></div>
						<div class="desctitle">
							<span class="motto"><?php $this->options->title(); ?></span>
						</div>
					<?php
					}
					?>
				</div>

				<section class="HeaderImg_bottom">
					<svg class="waves-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
						<defs>
							<path id="gentle-wave" d="M -160 44 c 30 0 58 -18 88 -18 s 58 18 88 18 s 58 -18 88 -18 s 58 18 88 18 v 44 h -352 Z"></path>
						</defs>
						<g class="parallax">
							<use xlink:href="#gentle-wave" x="48" y="0"></use>
							<use xlink:href="#gentle-wave" x="48" y="3"></use>
							<use xlink:href="#gentle-wave" x="48" y="5"></use>
							<use xlink:href="#gentle-wave" x="48" y="7"></use>
						</g>
					</svg>
				</section>
			<?php
		}
			?>
			</div>

			<div class="joe_container joe_bread">
				<ul class="joe_bread__bread">
					<li class="item">
						<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
							<path d="M307.867 805.441h408.266V575.792c0-15.31 29.344-22.046 44.654-22.046 15.336 0 27.762 12.426 27.762 27.762v277.544c0 15.335-12.426 27.762-27.762 27.762h-499.59c-15.31 0-27.762-12.427-27.762-27.762V581.507c0-15.31 12.426-27.762 27.762-27.762 15.31 0 46.67 6.71 46.67 22.046v229.65zM205.8 524.758c-10.845 10.845-56.851 3.93-67.696-6.89a27.762 27.762 0 0 1-.025-39.295l353.253-353.227a27.762 27.762 0 0 1 39.296 0L883.93 478.573a27.813 27.813 0 0 1-12.478 46.491c-9.568 2.552-46.236 6.686-53.253-.331L512 218.559 205.8 524.758z" />
						</svg>
						<a href="<?php $this->options->siteUrl(); ?>" class="link" title="首页">首页</a>
					</li>
					<li class="line">/</li>
					<?php if (sizeof($this->categories) > 0) : ?>
						<li class="item">
							<a class="link" href="<?php echo $this->categories[0]['permalink']; ?>" title="<?php echo $this->categories[0]['name']; ?>"><?php echo $this->categories[0]['name']; ?></a>
						</li>
						<li class="line">/</li>
					<?php endif; ?>
					<li class="item">正文</li>
				</ul>
			</div>
			<div class="joe_container">
				<div class="joe_main joe_post">
					<div class="joe_detail" data-cid="<?php echo $this->cid ?>">
						<?php $this->need('module/batten.php'); ?>
						<?php if ($this->options->JOverdue && $this->options->JOverdue !== 'off' && floor((time() - ($this->modified)) / 86400) > $this->options->JOverdue) : ?>
							<div class="joe_detail__overdue">
								<div class="joe_detail__overdue-wrapper">
									<div class="title">
										<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
											<path d="M0 512c0 282.778 229.222 512 512 512s512-229.222 512-512S794.778 0 512 0 0 229.222 0 512z" fill="#FF8C00" fill-opacity=".51" />
											<path d="M462.473 756.326a45.039 45.039 0 0 0 41.762 28.74 45.039 45.039 0 0 0 41.779-28.74h-83.541zm119.09 0c-7.73 35.909-39.372 62.874-77.311 62.874-37.957 0-69.598-26.965-77.33-62.874H292.404a51.2 51.2 0 0 1-42.564-79.65l23.723-35.498V484.88a234.394 234.394 0 0 1 167.492-224.614c3.635-31.95 30.498-56.815 63.18-56.815 31.984 0 58.386 23.808 62.925 54.733A234.394 234.394 0 0 1 742.093 484.88v155.512l24.15 36.454a51.2 51.2 0 0 1-42.668 79.48H581.564zm-47.957-485.922c.069-.904.12-1.809.12-2.73 0-16.657-13.26-30.089-29.491-30.089-16.214 0-29.474 13.432-29.474 30.089 0 1.245.085 2.491.221 3.703l1.81 15.155-14.849 3.499a200.226 200.226 0 0 0-154.265 194.85v166.656l-29.457 44.1a17.067 17.067 0 0 0 14.182 26.556h431.155a17.067 17.067 0 0 0 14.234-26.487l-29.815-45.04V484.882A200.21 200.21 0 0 0 547.26 288.614l-14.985-2.986 1.331-15.224z" fill="#FFF" />
											<path d="M612.864 322.697c0 30.378 24.303 55.022 54.272 55.022 30.003 0 54.323-24.644 54.323-55.022 0-30.38-24.32-55.023-54.306-55.023s-54.306 24.644-54.306 55.023z" fill="#FA5252" />
										</svg>
										<span class="text">温馨提示：</span>
									</div>
									<div class="content">
										本文最后更新于<?php echo date('Y年m月d日', $this->modified); ?>，已超过<?php echo floor((time() - ($this->modified)) / 86400); ?>天没有更新，若内容或图片失效，请留言反馈。
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php
						$post_ad_text = $this->options->JPost_Ad;
						if ($post_ad_text) {
							$post_ad_arr = explode("\r\n", $post_ad_text);
							foreach ($post_ad_arr as $key => $value) {
								$post_ad_arr_arr[] = [
									'url' => trim(explode("||", $post_ad_arr[$key])[1] ?? ''),
									'image' => trim(explode("||", $post_ad_arr[$key])[0] ?? '')
								];
							}
						}
						if (!empty($post_ad_arr_arr[0]['image'])) {
							foreach ($post_ad_arr_arr as $key => $value) {
						?>
								<div class="joe_post__ad">
									<a class="joe_post__ad-link" href="<?php echo $post_ad_arr_arr[$key]['url'] ?>" target="_blank" rel="noopener noreferrer nofollow">
										<img width="100%" style="height:auto;max-height:200px" class="image lazyload" src="<?php joe\getLazyload() ?>" data-src="<?php echo $post_ad_arr_arr[$key]['image'] ?>" alt="<?php echo $post_ad_arr_arr[$key]['url'] ?>" />
										<span class="icon">广告</span>
									</a>
								</div>
						<?php
							}
						}
						$this->need('module/article.php');
						$this->need('module/handle.php');
						$this->need('module/operate.php');
						$this->need('module/copyright.php');
						$this->need('module/related.php');
						?>
					</div>
					<ul class="joe_post__pagination">
						<?php $this->theNext('<li class="joe_post__pagination-item prev">%s</li>', '', ['title' => '上一篇']); ?>
						<?php $this->thePrev('<li class="joe_post__pagination-item next">%s</li>', '', ['title' => '下一篇']); ?>
					</ul>
					<?php $this->need('module/comment.php'); ?>
				</div>
				<?php $this->need('module/aside.php'); ?>
			</div>
			<?php $this->need('module/footer.php'); ?>
	</div>
	<?php if ($this->options->JArticle_Guide == 'on') { ?>
		<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.autoc.min.css'); ?>">
		<script src="<?= joe\theme_url('assets/js/joe.autoc.min.js'); ?>"></script>
		<script type="text/javascript">
			if ($(".joe_detail__article").children().length > 0) {
				(function() {
					// 创建文章导读目录
					new AutocJs({
						article: '.joe_detail__article', // 文章正文 DOM 节点的 ID 选择器
						selector: 'h1,h2,h3,h4,h5,h6', // 要收集的标题选择器
						title: '文章导读', // 侧边栏导航的标题

						// 文章导读导航的位置
						// outside - 以侧边栏菜单形式显示（默认值）
						// inside - 在文章正文一开始的地方显示
						//只有设置了 isGenerateOutline 为 true，position 参数才会起效。
						position: 'outside',

						//用来指定文章标题锚点链接图标的链接地址：'' - 点击链接页面滚动到标题位置，其它 URL 值 - 就直接跳转到指定页面了
						// 标题图标链接的 URL 地址
						// （默认）没有设置定制，点击链接页面滚动到标题位置
						// 设置了链接地址，则不会滚动定位
						anchorURL: '',

						// 链接的显示位置
						// front - 在标题最前面（默认值）
						// back - 在标题后面
						anchorAt: 'back',
						isGenerateOutline: true, // 是否生成文章导读导航
						isGenerateOutlineChapterCode: false, // 是否在文章导读导航中显示段落章节编号
						isGenerateHeadingChapterCode: false, // 是否在正文的文章标题中显示段落章节编号
						isGenerateHeadingAnchor: false // 用来指定是否在文章标题位置生成锚点链接图标：true - 生成锚点链接图标，并给标题添加 ID 属性，false - 不生成锚点链接图标，仅给标题添加 ID 属性。
					});
					// 可以在创建导航后，重置配置信息，重新生成新的导航
					// 	navigation.reload({
					// 		// 调整位直接在文章内生成导航
					// 		position: 'outside',
					// 		// 并且在文章标题前显示段落的章节层次索引值
					// 		isGenerateHeadingChapterCode: true
					// 	})
					if ($('.outline-heading').length > 0) {
						$('.joe_header').css('position', 'static')
					} else {
						$('.outline-outside').remove()
						return
					}
					if (!Joe.IS_MOBILE) return;
					let icon = $('.joe_aside__item.outline-outside .joe_aside__item-title svg').prop("outerHTML")
					$('.joe_action').append(`<div class="joe_action_item outline-outside-switcher">${icon}</div>`)
					$('.joe_action .joe_action_item.outline-outside-switcher').css({
						'margin-top': '15px',
						'position': 'relative'
					})
					let joe_aside = $('.joe_aside .joe_aside__item.outline-outside .joe_aside__item-contain').html()
					$('.joe_aside .joe_aside__item.outline-outside').remove()
					let html = document.createElement('div')
					html.className = 'outline-outside'
					html.innerHTML = joe_aside
					$('.outline-outside-switcher').append(html)
					$('.joe_action_item .outline-outside').css({
						'position': 'absolute',
						'display': 'none',
						'right': '50px',
						'bottom': '0px',
						'padding': '10px',
						'border-radius': 'var(--radius-wrap)',
						'box-shadow': '0 0 10px rgb(116 116 116 / 50%)'
					})
					$('.joe_action_item.outline-outside-switcher').click(() => {
						$('.joe_action_item.outline-outside-switcher .outline-outside').toggle(200);
					})
				}())
			}
		</script>
		<script>

		</script>
	<?php
	}
	?>
</body>

</html>
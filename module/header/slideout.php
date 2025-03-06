<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<div class="joe_header__slideout">
	<?php
	$JAside_Wap_Image_Height = $this->options->JAside_Wap_Image ? $this->options->JAside_Wap_Image : joe\theme_url('assets/images/wap_aside_image.jpg');
	if ($this->options->JAside_Wap_Image_Height == '100vh' || $this->options->JAside_Wap_Image_Height == '100%') {
		echo "<style>.joe_header__slideout {background-image: url('$JAside_Wap_Image_Height');background-size: cover;background-repeat: no-repeat;background-position: center;}</style>";
	} else {
		echo '<img class="joe_header__slideout-image lazyload" style="height: ' . $this->options->JAside_Wap_Image_Height . ';" src="' . $JAside_Wap_Image_Height . '" alt="侧边栏壁纸" />';
	}
	?>
	<div class="joe_header__slideout-author">
		<img onerror="Joe.avatarError(this)" width="50" height="50" class="avatar lazyload" src="<?= joe\getAvatarLazyload(); ?>" data-src="<?php $this->user->hasLogin() ? joe\getAvatarByMail($this->user->mail) : joe\theme_url('assets/images/avatar-default.png') ?>" alt="<?= $this->user->hasLogin() ? $this->user->screenName . '的' : '默认' ?>头像 - <?= $this->options->title ?>" />
		<div class="info">
			<a class="link" href="<?= $this->user->hasLogin() ? joe\root_relative_link($this->user->permalink) : 'javascript:;' ?>" rel="nofollow"><?= $this->user->hasLogin() ? $this->user->screenName : 'HI！请登录' ?></a>
			<p class="motto joe_motto mb0"></p>
		</div>
	</div>
	<?php
	if ($this->user->hasLogin()) {
	?>
		<ul class="joe_header__slideout-count">
			<?php
			Typecho\Widget::widget('Widget_Stat')->to($stat);
			$PostsNum = joe\number_word($stat->myPublishedPostsNum);
			$CommentsNum = joe\number_word($stat->myPublishedCommentsNum);
			?>
			<li class="item">
				<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
					<use xlink:href="#icon-joe-slideout-write"></use>
				</svg>
				<span>累计撰写 <strong><?= $PostsNum ?></strong> 篇文章</span>
			</li>
			<li class="item">
				<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
					<use xlink:href="#icon-joe-slideout-envelope"></use>
				</svg>
				<span>累计收到 <strong><?= $CommentsNum ?></strong> 条评论</span>
			</li>
		</ul>
	<?php
	}
	?>
	<ul class="joe_header__slideout-menu panel-box">
		<?php
		$custom_navs = joe\custom_navs();
		if (empty($custom_navs)) {
		?>
			<li>
				<a class="link" href="/" title="首页">
					<span>首页</span>
				</a>
			</li>
			<!-- 栏目 -->
			<li>
				<a class="link panel" href="javascript:;" rel="nofollow">
					<span>栏目</span>
					<svg class="icon" width="13" height="13">
						<use xlink:href="#icon-joe-slideout-forward"></use>
					</svg>
				</a>
				<ul class="slides panel-body">
					<?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
					<?php while ($category->next()) : ?>
						<?php if ($category->levels === 0) : ?>
							<?php $children = $category->getAllChildren($category->mid); ?>
							<?php if (empty($children)) : ?>
								<li>
									<a class="link <?php echo $this->is('category', $category->slug) ? 'current' : '' ?>" href="<?= joe\root_relative_link($category->permalink); ?>" title="<?= htmlentities($category->name) ?>"><?php $category->name(); ?></a>
								</li>
							<?php else : ?>
								<li>
									<div class="link panel <?php echo $this->is('category', $category->slug) ? 'current' : '' ?>">
										<a href="<?= joe\root_relative_link($category->permalink); ?>" title="<?= htmlentities($category->name)  ?>"><?php $category->name(); ?></a>
										<svg class="icon" width="13" height="13">
											<use xlink:href="#icon-joe-slideout-forward"></use>
										</svg>
									</div>
									<ul class="slides panel-body">
										<?php foreach ($children as $mid) : ?>
											<?php $child = $category->getCategory($mid); ?>
											<li>
												<a class="link <?php echo $this->is('category', $child['slug']) ? 'current' : '' ?>" href="<?= joe\root_relative_link($child['permalink']) ?>" title="<?php echo $child['name']; ?>"><?php echo $child['name']; ?></a>
											</li>
										<?php endforeach; ?>
									</ul>
								</li>
							<?php endif; ?>
						<?php endif; ?>
					<?php endwhile; ?>
				</ul>
			</li>
			<!-- 页面 -->
			<li>
				<a class="link panel" href="javascript:;" rel="nofollow">
					<span>页面</span>
					<svg class="icon" width="13" height="13">
						<use xlink:href="#icon-joe-slideout-forward"></use>
					</svg>
				</a>
				<ul class="slides panel-body">
					<?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
					<?php foreach ($pages->stack as $item) : ?>
						<li>
							<a class="link <?php echo $this->is('page', $item['slug']) ? 'current' : '' ?>" href="<?= joe\root_relative_link($item['permalink']) ?>" title="<?php echo $item['title'] ?>"><?php echo $item['title'] ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
			<!-- 推荐 -->
			<?php $JMoreNavs = joe\optionMulti($this->options->JMoreNavs); ?>
			<?php if (sizeof($JMoreNavs) > 0) : ?>
				<li>
					<a class="link panel" href="javascript:;" rel="nofollow">
						<span>推荐</span>
						<svg class="icon" width="13" height="13">
							<use xlink:href="#icon-joe-slideout-forward"></use>
						</svg>
					</a>
					<ul class="slides panel-body">
						<?php foreach ($JMoreNavs as $item) : ?>
							<li>
								<a class="link" href="<?= $item[1] ?>" target="_blank" rel="nofollow"><?= $item[0] ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endif; ?>
			<?php
		} else {
			foreach ($custom_navs as $key => $nav) {
			?>
				<li>
					<a class="link panel" href="<?= $nav['url'] ?>" target="<?= $nav['target'] ?>" rel="nofollow">
						<span><?= $nav['title'] ?></span><?= empty($nav['list']) ? null : '<svg class="icon" xmlns="http://www.w3.org/2000/svg" width="13" height="13"><use xlink:href="#icon-joe-slideout-forward"></use></svg>' ?>
					</a>
					<?php
					if (!empty($nav['list'])) {
						echo '<ul class="slides panel-body">';
						foreach ($nav['list'] as $key => $value) {
							echo '<li><a target="' . $nav['target'] . '" class="link" href="' . $value['url'] . '"><span>' . $value['title'] . '</span></a></li>';
						}
						echo '</ul>';
					}
					?>
				</li>
		<?php
			}
		}
		?>
	</ul>

	<?php
	if ($this->user->hasLogin()) {
	?>
		<ul class="joe_header__slideout-menu panel-box" style="margin-top: 15px; ">
			<li id="wap-login-main">
				<a class="link panel" href="javascript:;" rel="nofollow">
					<span>
						<svg class="svg">
							<use xlink:href="#icon-joe-slideout-user"></use>
						</svg>
						<?php $this->user->screenName(); ?>
					</span>
					<svg class="icon">
						<use xlink:href="#icon-joe-slideout-forward"></use>
					</svg>
				</a>
				<ul class="slides panel-body">
					<li>
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor' || $this->user->group == 'contributor') { ?>
							<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>manage-posts.php" class="link">管理文章</a>
						<?php } ?>
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor') { ?>
							<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>manage-comments.php" class="link">管理评论</a>
						<?php } ?>
						<?php if ($this->user->group == 'administrator') { ?>
							<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>options-theme.php" class="link">主题设置</a>
						<?php } ?>
						<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>" class="link">进入后台</a>
						<a data-turbolinks="false" href="<?= joe\root_relative_link($this->options->logoutUrl) ?>" class="link">退出登录</a>
					</li>
				</ul>
			</li>
		</ul>
	<?php
	} else {
	?>
		<ul class="joe_header__slideout-menu panel-box" style="margin-top: 15px; ">
			<li id="wap-login-main">
				<a class="link panel" href="javascript:;" rel="nofollow">
					<span>
						<svg class="svg">
							<use xlink:href="#icon-joe-slideout-user"></use>
						</svg>
						用户登录
					</span>
					<svg class="icon">
						<use xlink:href="#icon-joe-slideout-forward"></use>
					</svg>
				</a>
				<ul class="slides panel-body">
					<li>
						<a class="link header-login" href="<?= joe\user_url('login'); ?>" rel="nofollow">登录</a>
						<?php if ($this->options->allowRegister) : ?>
							<a class="link header-register" href="<?= joe\user_url('register'); ?>" rel="nofollow">注册</a>
						<?php endif; ?>
					</li>
				</ul>
			</li>
		</ul>
	<?php
	}
	?>
	<script>
		(function() {
			const pathname = window.location.pathname;
			const search = window.location.search;
			const path = search ? pathname + search : pathname;
			document.querySelectorAll('.joe_header__slideout-menu a').forEach(aElement => {
				const tempPath = aElement.getAttribute('href');
				if (tempPath === path || tempPath === window.location.href) {
					aElement.classList.add('current', 'in');

					// 向上查找父级结构
					const liParent = aElement.parentElement;
					if (!liParent) return;

					const ulParent = liParent.parentElement;
					if (!ulParent) return;

					// 查找紧邻的前一个兄弟元素并判断是否符合条件
					const prevSibling = ulParent.previousElementSibling;
					if (prevSibling && prevSibling.matches('a.link')) {
						prevSibling.classList.add('current');
					}
				}
			});
		})();
		(function() {
			return;
			var pathname = window.location.pathname;
			var search = window.location.search;
			var path = search ? pathname + search : pathname;
			$('.joe_header__slideout-menu').find('a').each(function() {
				temp_path = $(this).attr('href');
				if (temp_path == path || temp_path == window.location.href) {
					$(this).addClass('current in');
					$(this).parent('li').parent('ul').prev('a.link').addClass('current');
				}
			});
		}());
	</script>
</div>
<div class="joe_header__mask"></div>
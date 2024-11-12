<div class="joe_header__slideout">
	<?php
	$JAside_Wap_Image_Height = $this->options->JAside_Wap_Image ? $this->options->JAside_Wap_Image : joe\theme_url('assets/images/wap_aside_image.jpg');
	if ($this->options->JAside_Wap_Image_Height == '100vh' || $this->options->JAside_Wap_Image_Height == '100%') {
		echo "<style>.joe_header__slideout {background-image: url('$JAside_Wap_Image_Height');background-size: cover;}</style>";
	} else {
		echo '<img class="joe_header__slideout-image lazyload" style="height: ' . $this->options->JAside_Wap_Image_Height . ';" src="' . $JAside_Wap_Image_Height . '" alt="侧边栏壁纸" />';
	}
	?>
	<div class="joe_header__slideout-author">
		<img width="50" height="50" class="avatar lazyload" src="<?php joe\getAvatarLazyload(); ?>" data-src="<?php $this->options->JAside_Author_Avatar ? $this->options->JAside_Author_Avatar() : joe\getAvatarByMail($this->authorId ? $this->author->mail : $this->user->mail) ?>" alt="博主昵称" />
		<div class="info">
			<a class="link" href="<?php $this->options->JAside_Author_Link() ?>" target="_blank" rel="noopener noreferrer nofollow"><?php $this->options->JAside_Author_Nick ? $this->options->JAside_Author_Nick() : ($this->authorId ? $this->author->screenName() : $this->user->screenName()); ?></a>
			<p class="motto joe_motto mb0"></p>
		</div>
	</div>
	<ul class="joe_header__slideout-count">
		<?php Typecho_Widget::widget('Widget_Stat')->to($count); ?>
		<li class="item">
			<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
				<path d="M606.227 985.923H164.75c-69.715 0-126.404-56.722-126.404-126.442V126.477C38.346 56.755 95.04 0 164.75 0h619.275c69.715 0 126.549 56.755 126.549 126.477v503.925c0 18.216-14.814 32.997-33.07 32.997-18.183 0-32.925-14.78-32.925-32.997V126.477c0-33.355-27.2-60.488-60.554-60.488H164.75c-33.353 0-60.41 27.133-60.41 60.488v733.004c0 33.353 27.057 60.441 60.41 60.441h441.477c18.183 0 32.925 14.787 32.925 33.004 0 18.211-14.742 32.997-32.925 32.997zm0 0" />
				<path d="M657.62 322.056H291.154c-18.183 0-32.924-14.786-32.924-33.003 0-18.21 14.74-32.998 32.924-32.998H657.62c18.256 0 33.07 14.787 33.07 32.998 0 18.217-14.814 33.003-33.07 33.003zm0 0M657.62 504.749H291.154c-18.183 0-32.924-14.78-32.924-32.993 0-18.222 14.74-32.997 32.924-32.997H657.62c18.256 0 33.07 14.775 33.07 32.997 0 18.218-14.814 32.993-33.07 32.993zm0 0M445.611 687.486H291.154c-18.183 0-32.924-14.78-32.924-33.004 0-18.21 14.74-32.991 32.924-32.991h154.457c18.184 0 32.998 14.78 32.998 32.991 0 18.224-14.814 33.004-32.998 33.004zm0 0M866.482 1024c-8.447 0-16.896-3.225-23.34-9.662L577.595 748.786c-7.156-7.123-10.592-17.07-9.446-27.056l8.733-77.728c1.788-15.321 13.885-27.378 29.2-29.06l77.45-8.52c10.443-.965 19.9 2.433 26.905 9.449l265.558 265.551c12.875 12.877 12.875 33.784 0 46.666l-86.184 86.25c-6.438 6.437-14.887 9.662-23.33 9.662zm-231.05-310.646l231.05 231.018 39.575-39.62-231.043-231.05-35.505 3.938-4.076 35.714zm0 0" />
			</svg>
			<span>累计撰写 <strong><?php echo number_format($count->publishedPostsNum); ?></strong> 篇文章</span>
		</li>
		<li class="item">
			<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
				<path d="M921.6 153.6H102.4A102.4 102.4 0 0 0 0 256v512a102.4 102.4 0 0 0 102.4 102.4h819.2A102.4 102.4 0 0 0 1024 768V256a102.4 102.4 0 0 0-102.4-102.4zM687.616 473.088L972.8 258.304V791.04zM960 204.8L527.104 527.36 73.216 204.8zM371.2 483.584l-320 287.232V256zM73.984 819.2l339.2-307.2 83.456 59.392a51.2 51.2 0 0 0 60.416 0l89.6-67.328L931.072 819.2z" />
			</svg>
			<span>累计收到 <strong><?php echo number_format($count->publishedCommentsNum); ?></strong> 条评论</span>
		</li>
	</ul>
	<ul class="joe_header__slideout-menu panel-box">
		<?php
		$custom_navs = joe\custom_navs();
		if (empty($custom_navs)) {
		?>
			<li>
				<a class="link" href="<?php $this->options->siteUrl(); ?>" title="首页">
					<span>首页</span>
				</a>
			</li>
			<!-- 栏目 -->
			<li>
				<a class="link panel" href="javascript:;" rel="nofollow">
					<span>栏目</span>
					<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="13" height="13">
						<path d="M624.865 512.247L332.71 220.088c-12.28-12.27-12.28-32.186 0-44.457 12.27-12.28 32.186-12.28 44.457 0l314.388 314.388c12.28 12.27 12.28 32.186 0 44.457L377.167 848.863c-6.136 6.14-14.183 9.211-22.228 9.211s-16.092-3.071-22.228-9.211c-12.28-12.27-12.28-32.186 0-44.457l292.155-292.16z" />
					</svg>
				</a>
				<ul class="slides panel-body">
					<?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
					<?php while ($category->next()) : ?>
						<?php if ($category->levels === 0) : ?>
							<?php $children = $category->getAllChildren($category->mid); ?>
							<?php if (empty($children)) : ?>
								<li>
									<a class="link <?php echo $this->is('category', $category->slug) ? 'current' : '' ?>" href="<?php $category->permalink(); ?>" title="<?= htmlentities($category->name) ?>"><?php $category->name(); ?></a>
								</li>
							<?php else : ?>
								<li>
									<div class="link panel <?php echo $this->is('category', $category->slug) ? 'current' : '' ?>">
										<a href="<?php $category->permalink(); ?>" title="<?= htmlentities($category->name)  ?>"><?php $category->name(); ?></a>
										<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="13" height="13">
											<path d="M624.865 512.247L332.71 220.088c-12.28-12.27-12.28-32.186 0-44.457 12.27-12.28 32.186-12.28 44.457 0l314.388 314.388c12.28 12.27 12.28 32.186 0 44.457L377.167 848.863c-6.136 6.14-14.183 9.211-22.228 9.211s-16.092-3.071-22.228-9.211c-12.28-12.27-12.28-32.186 0-44.457l292.155-292.16z" />
										</svg>
									</div>
									<ul class="slides panel-body">
										<?php foreach ($children as $mid) : ?>
											<?php $child = $category->getCategory($mid); ?>
											<li>
												<a class="link <?php echo $this->is('category', $child['slug']) ? 'current' : '' ?>" href="<?php echo $child['permalink'] ?>" title="<?php echo $child['name']; ?>"><?php echo $child['name']; ?></a>
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
					<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="13" height="13">
						<path d="M624.865 512.247L332.71 220.088c-12.28-12.27-12.28-32.186 0-44.457 12.27-12.28 32.186-12.28 44.457 0l314.388 314.388c12.28 12.27 12.28 32.186 0 44.457L377.167 848.863c-6.136 6.14-14.183 9.211-22.228 9.211s-16.092-3.071-22.228-9.211c-12.28-12.27-12.28-32.186 0-44.457l292.155-292.16z" />
					</svg>
				</a>
				<ul class="slides panel-body">
					<?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
					<?php foreach ($pages->stack as $item) : ?>
						<li>
							<a class="link <?php echo $this->is('page', $item['slug']) ? 'current' : '' ?>" href="<?php echo $item['permalink'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['title'] ?></a>
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
						<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="13" height="13">
							<path d="M624.865 512.247L332.71 220.088c-12.28-12.27-12.28-32.186 0-44.457 12.27-12.28 32.186-12.28 44.457 0l314.388 314.388c12.28 12.27 12.28 32.186 0 44.457L377.167 848.863c-6.136 6.14-14.183 9.211-22.228 9.211s-16.092-3.071-22.228-9.211c-12.28-12.27-12.28-32.186 0-44.457l292.155-292.16z" />
						</svg>
					</a>
					<ul class="slides panel-body">
						<?php foreach ($JMoreNavs as $item) : ?>
							<li>
								<a class="link" href="<?= $item[0] ?>" target="_blank" rel="noopener noreferrer nofollow"><?= $item[1] ?></a>
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
						<span><?= $nav['title'] ?></span><?= empty($nav['list']) ? null : '<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="13" height="13">
						<path d="M624.865 512.247L332.71 220.088c-12.28-12.27-12.28-32.186 0-44.457 12.27-12.28 32.186-12.28 44.457 0l314.388 314.388c12.28 12.27 12.28 32.186 0 44.457L377.167 848.863c-6.136 6.14-14.183 9.211-22.228 9.211s-16.092-3.071-22.228-9.211c-12.28-12.27-12.28-32.186 0-44.457l292.155-292.16z" />
					</svg>' ?>
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
					<span><?php $this->user->screenName(); ?></span>
					<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
						<path d="M231.594 610.125C135.087 687.619 71.378 804.28 64.59 935.994c-.373 7.25 3.89 23.307 30.113 23.307s33.512-16.06 33.948-23.301c6.861-114.025 63.513-214.622 148.5-280.346 3.626-2.804 16.543-17.618 3.24-39.449-13.702-22.483-40.863-12.453-48.798-6.08zm280.112-98.44v63.96c204.109 0 370.994 159.345 383.06 360.421.432 7.219 8.649 23.347 32.44 23.347s31.991-16.117 31.62-23.342c-12.14-236.422-207.676-424.386-447.12-424.386z"></path>
						<path d="M319.824 319.804c0-105.974 85.909-191.883 191.882-191.883s191.883 85.91 191.883 191.883c0 26.57-5.405 51.88-15.171 74.887-5.526 14.809-2.082 31.921 20.398 38.345 23.876 6.822 36.732-8.472 41.44-20.583 11.167-28.729 17.294-59.973 17.294-92.65 0-141.297-114.545-255.842-255.843-255.842S255.863 178.506 255.863 319.804s114.545 255.843 255.843 255.843v-63.961c-105.973-.001-191.882-85.909-191.882-191.882z"></path>
						<path d="M512 255.843s21.49-5.723 21.49-31.306S512 191.882 512 191.882c-70.65 0-127.921 57.273-127.921 127.922 0 3.322.126 6.615.375 9.875.264 3.454 14.94 18.116 37.044 14.425 22.025-3.679 26.6-21.93 26.6-21.93-.028-.788-.06-1.575-.06-2.37.001-35.325 28.637-63.961 63.962-63.961z"></path>
					</svg>
				</a>
				<ul class="slides panel-body">
					<li>
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor' || $this->user->group == 'contributor') { ?>
							<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl("manage-posts.php"); ?>" class="link">管理文章</a>
						<?php } ?>
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor') { ?>
							<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl("manage-comments.php"); ?>" class="link">管理评论</a>
						<?php } ?>
						<?php if ($this->user->group == 'administrator') { ?>
							<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl("options-theme.php"); ?>" class="link">主题设置</a>
						<?php } ?>
						<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl(); ?>" class="link">进入后台</a>
						<a href="<?php $this->options->logoutUrl(); ?>" class="link">退出登录</a>
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
					<span>用户登录</span>
					<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
						<path d="M231.594 610.125C135.087 687.619 71.378 804.28 64.59 935.994c-.373 7.25 3.89 23.307 30.113 23.307s33.512-16.06 33.948-23.301c6.861-114.025 63.513-214.622 148.5-280.346 3.626-2.804 16.543-17.618 3.24-39.449-13.702-22.483-40.863-12.453-48.798-6.08zm280.112-98.44v63.96c204.109 0 370.994 159.345 383.06 360.421.432 7.219 8.649 23.347 32.44 23.347s31.991-16.117 31.62-23.342c-12.14-236.422-207.676-424.386-447.12-424.386z"></path>
						<path d="M319.824 319.804c0-105.974 85.909-191.883 191.882-191.883s191.883 85.91 191.883 191.883c0 26.57-5.405 51.88-15.171 74.887-5.526 14.809-2.082 31.921 20.398 38.345 23.876 6.822 36.732-8.472 41.44-20.583 11.167-28.729 17.294-59.973 17.294-92.65 0-141.297-114.545-255.842-255.843-255.842S255.863 178.506 255.863 319.804s114.545 255.843 255.843 255.843v-63.961c-105.973-.001-191.882-85.909-191.882-191.882z"></path>
						<path d="M512 255.843s21.49-5.723 21.49-31.306S512 191.882 512 191.882c-70.65 0-127.921 57.273-127.921 127.922 0 3.322.126 6.615.375 9.875.264 3.454 14.94 18.116 37.044 14.425 22.025-3.679 26.6-21.93 26.6-21.93-.028-.788-.06-1.575-.06-2.37.001-35.325 28.637-63.961 63.962-63.961z"></path>
					</svg>
				</a>
				<ul class="slides panel-body">
					<li>
						<a class="link header-login" href="<?= joe\user_url('login'); ?>" rel="noopener noreferrer nofollow">登录</a>
						<?php if ($this->options->allowRegister) : ?>
							<a class="link header-register" href="<?= joe\user_url('register'); ?>" rel="noopener noreferrer nofollow">注册</a>
						<?php endif; ?>
					</li>
				</ul>
			</li>
		</ul>
	<?php
	}
	?>
</div>
<div class="joe_header__mask"></div>
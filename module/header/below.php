<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<div class="joe_header__below">
	<div class="joe_container">
		<?php if ($this->is('post')) :  ?>
			<div class="joe_header__below-title"><?php $this->title() ?></div>
		<?php endif; ?>
		<nav class="joe_header__below-class">
			<?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
			<?php while ($category->next()) : ?>
				<?php if ($category->levels === 0) : ?>
					<?php $children = $category->getAllChildren($category->mid); ?>
					<?php if (empty($children)) : ?>
						<a class="item <?php echo $this->is('category', $category->slug) ? 'active' : '' ?>" href="<?= joe\permalink($category->permalink); ?>" title="<?= htmlentities($category->name) ?>"><?php $category->name(); ?></a>
					<?php else : ?>
						<div class="joe_dropdown" trigger="hover">
							<div class="joe_dropdown__link">
								<a class="item <?php echo $this->is('category', $category->slug) ? 'active' : '' ?>" href="<?= joe\permalink($category->permalink); ?>" title="<?= htmlentities($category->name) ?>"><?php $category->name(); ?></a>
								<svg class="joe_dropdown__link-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="13" height="13">
									<path d="M561.873 725.165c-11.262 11.262-26.545 21.72-41.025 18.502-14.479 2.413-28.154-8.849-39.415-18.502L133.129 375.252c-17.697-17.696-17.697-46.655 0-64.352s46.655-17.696 64.351 0l324.173 333.021 324.977-333.02c17.696-17.697 46.655-17.697 64.351 0s17.697 46.655 0 64.351L561.873 725.165z" fill="var(--minor)" />
								</svg>
							</div>
							<nav class="joe_dropdown__menu">
								<?php foreach ($children as $mid) : ?>
									<?php $child = $category->getCategory($mid); ?>
									<a class="<?php echo $this->is('category', $child['slug']) ? 'active' : '' ?>" href="<?= joe\permalink($child['permalink']) ?>" title="<?php echo $child['name']; ?>"><?php echo $child['name']; ?></a>
								<?php endforeach; ?>
							</nav>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endwhile; ?>
		</nav>
		<div class="joe_header__below-sign">
			<?php if ($this->user->hasLogin()) : ?>
				<div class="joe_dropdown" trigger="click">
					<div class="joe_dropdown__link">
						<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
							<path d="M231.594 610.125C135.087 687.619 71.378 804.28 64.59 935.994c-.373 7.25 3.89 23.307 30.113 23.307s33.512-16.06 33.948-23.301c6.861-114.025 63.513-214.622 148.5-280.346 3.626-2.804 16.543-17.618 3.24-39.449-13.702-22.483-40.863-12.453-48.798-6.08zm280.112-98.44v63.96c204.109 0 370.994 159.345 383.06 360.421.432 7.219 8.649 23.347 32.44 23.347s31.991-16.117 31.62-23.342c-12.14-236.422-207.676-424.386-447.12-424.386z" />
							<path d="M319.824 319.804c0-105.974 85.909-191.883 191.882-191.883s191.883 85.91 191.883 191.883c0 26.57-5.405 51.88-15.171 74.887-5.526 14.809-2.082 31.921 20.398 38.345 23.876 6.822 36.732-8.472 41.44-20.583 11.167-28.729 17.294-59.973 17.294-92.65 0-141.297-114.545-255.842-255.843-255.842S255.863 178.506 255.863 319.804s114.545 255.843 255.843 255.843v-63.961c-105.973-.001-191.882-85.909-191.882-191.882z" />
							<path d="M512 255.843s21.49-5.723 21.49-31.306S512 191.882 512 191.882c-70.65 0-127.921 57.273-127.921 127.922 0 3.322.126 6.615.375 9.875.264 3.454 14.94 18.116 37.044 14.425 22.025-3.679 26.6-21.93 26.6-21.93-.028-.788-.06-1.575-.06-2.37.001-35.325 28.637-63.961 63.962-63.961z" />
						</svg>
						<span><?php $this->user->screenName(); ?></span>
					</div>
					<nav class="joe_dropdown__menu">
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor' || $this->user->group == 'contributor') : ?>
							<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl("manage-posts.php"); ?>">管理文章</a>
						<?php endif; ?>
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor') : ?>
							<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl("manage-comments.php"); ?>">管理评论</a>
						<?php endif; ?>
						<?php if ($this->user->group == 'administrator') : ?>
							<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl("options-theme.php"); ?>">修改外观</a>
						<?php endif; ?>
						<a rel="noopener noreferrer nofollow" target="_blank" href="<?php $this->options->adminUrl(); ?>">进入后台</a>
						<a data-turbolinks="false" href="<?= joe\permalink($this->options->logoutUrl) ?>">退出登录</a>
					</nav>
				</div>
			<?php else : ?>
				<div class="item">
					<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
						<path d="M710.698 299a213.572 213.572 0 1 0-213.572 213.954A213.572 213.572 0 0 0 710.698 299zm85.429 0a299.382 299.382 0 1 1-299-299 299 299 0 0 1 299 299z" />
						<path d="M114.223 1024a46.91 46.91 0 0 1-46.91-46.91 465.281 465.281 0 0 1 468.332-460.704 475.197 475.197 0 0 1 228.827 58.35 46.91 46.91 0 1 1-45.384 82.378 381.378 381.378 0 0 0-183.443-46.909 371.08 371.08 0 0 0-374.131 366.886A47.29 47.29 0 0 1 114.223 1024zM944.483 755.129a38.138 38.138 0 0 0-58.733 0l-146.449 152.55-92.675-91.53a38.138 38.138 0 0 0-58.732 0 43.858 43.858 0 0 0 0 61.402l117.083 122.422a14.492 14.492 0 0 0 8.39 4.577c4.196 0 4.196 4.195 8.39 4.195h32.037c4.195 0 4.195-4.195 8.39-4.195s4.195-4.577 8.39-4.577L946.39 816.15a48.054 48.054 0 0 0-1.906-61.02z" />
						<path d="M763.328 776.104L730.53 744.45a79.708 79.708 0 0 0 32.798 31.654" />
					</svg>
					<a href="<?= joe\user_url('login'); ?>" rel="noopener noreferrer nofollow">登录</a>
					<?php if ($this->options->allowRegister) : ?>
						<span class="split">/</span>
						<a href="<?= joe\user_url('register'); ?>" rel="noopener noreferrer nofollow">注册</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
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
						<a class="item <?php echo $this->is('category', $category->slug) ? 'active' : '' ?>" href="<?= joe\root_relative_link($category->permalink); ?>" title="<?= htmlentities($category->name) ?>"><?php $category->name(); ?></a>
					<?php else : ?>
						<div class="joe_dropdown" trigger="hover">
							<div class="joe_dropdown__link">
								<a class="item <?php echo $this->is('category', $category->slug) ? 'active' : '' ?>" href="<?= joe\root_relative_link($category->permalink); ?>" title="<?= htmlentities($category->name) ?>"><?php $category->name(); ?></a>
								<svg class="joe_dropdown__link-icon" width="13" height="13" style="color: var(--minor);">
									<use xlink:href="#icon-joe-header-dropdown"></use>
								</svg>
							</div>
							<nav class="joe_dropdown__menu">
								<?php foreach ($children as $mid) : ?>
									<?php $child = $category->getCategory($mid); ?>
									<a class="<?php echo $this->is('category', $child['slug']) ? 'active' : '' ?>" href="<?= joe\root_relative_link($child['permalink']) ?>" title="<?php echo $child['name']; ?>"><?php echo $child['name']; ?></a>
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
						<svg class="icon" width="15" height="15">
							<use xlink:href="#icon-joe-header-user-ok"></use>
						</svg>
						<span><?php $this->user->screenName(); ?></span>
					</div>
					<nav class="joe_dropdown__menu">
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor' || $this->user->group == 'contributor') : ?>
							<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl)?>manage-posts.php">管理文章</a>
						<?php endif; ?>
						<?php if ($this->user->group == 'administrator' || $this->user->group == 'editor') : ?>
							<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>manage-comments.php">管理评论</a>
						<?php endif; ?>
						<?php if ($this->user->group == 'administrator') : ?>
							<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>options-theme.php">修改外观</a>
						<?php endif; ?>
						<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>">进入后台</a>
						<a data-turbolinks="false" href="<?= joe\root_relative_link($this->options->logoutUrl) ?>">退出登录</a>
					</nav>
				</div>
			<?php else : ?>
				<div class="item">
					<svg class="icon" width="15" height="15">
						<use xlink:href="#icon-joe-header-user"></use>
					</svg>
					<a href="<?= joe\user_url('login'); ?>" rel="nofollow">登录</a>
					<?php if ($this->options->allowRegister) : ?>
						<span class="split">/</span>
						<a href="<?= joe\user_url('register'); ?>" rel="nofollow">注册</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
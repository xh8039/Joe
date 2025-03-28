<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$custom_navs = joe\custom_navs();
?>
<link rel="stylesheet" href="<?= joe\theme_url('assets/css/options/custom-navs.css') ?>">
<div class="header header-layout-2">
	<nav class="navbar navbar-top center">
		<div class="container-fluid container-header">
			<div class="navbar-header">
				<div class="navbar-brand">
					<a class="navbar-logo <?= $this->options->JLogo_Light_Effect == 'on' ? 'joe_scan_light' : null ?>" href="/"><img referrerpolicy="no-referrer" rel="noreferrer" src="<?php empty($this->options->JLogo) ? $this->options->themeUrl('assets/images/logo.png') : $this->options->JLogo(); ?>" switch-src="<?php $this->options->JDarkLogo(); ?>" alt="<?= $this->options->title ?>"></a>
				</div>
				<button type="button" data-toggle-class="mobile-navbar-show" data-target="body" class="navbar-toggle joe_header__above-slideicon"><i class="em12 css-icon i-menu"><i></i></i></button>
				<a class="main-search-btn navbar-toggle joe_header__above-searchicon" href="javascript:;"><svg class="icon svg" aria-hidden="true">
						<use xlink:href="#icon-search"></use>
					</svg></a>
			</div>
			<?php
			if (joe\isPc()) {
			?>
				<nav class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<?php
						'current-menu-item';
						foreach ($custom_navs as $nav) {
							$title = empty($nav['list']) ? $nav['title'] : $nav['title'] . '<i class="fa fa-angle-down ml6"></i>';
							$href = empty($nav['url']) ? '' : 'href="' . $nav['url'] . '"';
							echo '<li class="menu-item">';
							echo '<a ' . $href . ' target="' . $nav['target'] . '">' . $title . '</a>';
							if (!empty($nav['list'])) {
								echo '<ul class="sub-menu">';
								foreach ($nav['list'] as $value) {
						?>
									<li class="menu-item">
										<a href="<?= $value['url'] ?>" target="<?= $value['target'] ?>"><?= $value['title'] ?></a>
									</li>
						<?php
								}
								echo '</ul>';
							}
							echo '</li>';
						}
						?>
					</ul>
					<form method="get" class="navbar-form navbar-left hover-show" action="/">
						<div class="form-group relative dropdown">
							<input type="text" class="form-control search-input focus-show" name="s" placeholder="搜索内容" value="<?php echo $this->is('search') ? $this->archiveTitle(' &raquo; ', '', '') : '' ?>">
							<div class="abs-right muted-3-color">
								<button style="font-family: inherit;font-size: inherit;line-height: inherit;" type="submit" tabindex="3" class="null"><svg class="icon svg" aria-hidden="true">
										<use xlink:href="#icon-search"></use>
									</svg></button>
							</div>
							<div class="lazyloaded lazyloadafter" lazyload-action="ias">
								<div class="dropdown-menu hover-show-con">
									<div class="search-input">
										<?php
										$this->widget('Widget_Contents_Hot@Search', 'action=search&pageSize=5')->to($hots);
										if ($hots->have()) {
										?>
											<div class="search-keywords hot-search">
												<p class="muted-color">热门文章</p>
												<div>
													<?php
													while ($hots->next()) {
														echo '<a class="search_keywords text-ellipsis muted-2-color but em09 mr6 mb6" href="' . joe\root_relative_link($hots->permalink) . '">' . $hots->title . '</a>';
													}
													?>
												</div>
											</div>
										<?php
										}
										$this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 20))->to($tags);
										if ($tags->have()) {
										?>
											<div class="search-keywords tag-search">
												<p class="muted-color">标签搜索</p>
												<div>
													<?php
													while ($tags->next()) {
														echo '<a class="search_keywords text-ellipsis muted-2-color but em09 mr6 mb6" href="' . joe\root_relative_link($tags->permalink) . '">' . $tags->name . '</a>';
													}
													?>
												</div>
											</div>
										<?php
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="navbar-form navbar-right hide show-nav-but" style="margin-right:-10px;"><a data-toggle-class="" data-target=".nav.navbar-nav" href="javascript:;" class="but" style="overflow: hidden; position: relative;"><svg class="svg" aria-hidden="true" data-viewbox="0 0 1024 1024" viewBox="0 0 1024 1024">
								<use xlink:href="#icon-menu_2"></use>
							</svg></a></div>
					<div class="navbar-form navbar-right navbar-but">
						<a rel="nofollow" class="newadd-btns but nowave jb-blue radius btn-newadd" href="<?= $this->user->hasLogin() ? (joe\root_relative_link($this->options->adminUrl) . 'write-post.php') : joe\user_url('login') ?>"><i class="fa fa-fw fa-pencil"></i>发布</a>
					</div>
					<div class="navbar-form navbar-right">
						<a href="javascript:;" class="toggle-theme toggle-radius"><i class="fa fa-toggle-theme"></i></a><?= $this->user->hasLogin() ? ('<a rel="nofollow" href="' . joe\root_relative_link($this->options->adminUrl) . 'manage-comments.php" class="msg-news-icon ml10"><span class="toggle-radius msg-icon"><i class="fa fa-bell-o" aria-hidden="true"></i></span></a>') : null ?>
					</div>
					<?php
					if ($this->user->hasLogin()) {
					?>
						<div class="navbar-form navbar-right">
							<ul class="list-inline splitters relative">
								<li>
									<a href="javascript:;" class="navbar-avatar">
										<img onerror="Joe.avatarError(this)" alt="<?= $this->user->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->user->mail) ?>" class="lazyload avatar avatar-id-<?= $this->user->uid ?>">
									</a>
									<ul class="sub-menu">
										<div class="padding-10">
											<div class="sub-user-box">
												<div class="user-info flex ac relative">
													<a href="<?= joe\root_relative_link($this->user->permalink) ?>">
														<span class="avatar-img">
															<img onerror="Joe.avatarError(this)" alt="<?= $this->user->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->user->mail) ?>" class="lazyload avatar avatar-id-<?= $this->user->uid ?>">
														</span>
													</a>
													<div class="user-right flex flex1 ac jsb ml10">
														<div class="flex1" style="max-width: calc(100% - 40px);">
															<b>
																<name class="flex ac flex1">
																	<a class="display-name text-ellipsis" href="<?= joe\root_relative_link($this->user->permalink) ?>"><?= $this->user->screenName ?></a>
																</name>
															</b>
															<div class="px12 muted-2-color text-ellipsis">这家伙很懒，什么都没有写...</div>
														</div>
													</div>
													<a href="<?= joe\root_relative_link($this->options->adminUrl) ?>manage-comments.php" class="msg-news-icon abs-right">
														<span class="toggle-radius msg-icon">
															<i class="fa fa-bell-o" aria-hidden="true"></i>
														</span>
													</a>
												</div>
												<?php
												Typecho\Widget::widget('Widget_Stat')->to($stat);
												$PostsNum = joe\number_word($stat->myPublishedPostsNum);
												$CommentsNum = joe\number_word($stat->myPublishedCommentsNum);
												?>
												<div class="em09 author-tag mb10 mt6 flex jc">
													<a class="but c-blue tag-posts" data-toggle="tooltip" title="共<?= $PostsNum ?>篇文章" href="<?= joe\root_relative_link($this->user->permalink) ?>">
														<svg class="icon svg" aria-hidden="true">
															<use xlink:href="#icon-post"></use>
														</svg><?= $PostsNum ?>
													</a>
													<a class="but c-green tag-comment" data-toggle="tooltip" title="共<?= $CommentsNum ?>条评论" href="<?= joe\root_relative_link($this->options->adminUrl) ?>manage-comments.php">
														<svg class="icon svg" aria-hidden="true">
															<use xlink:href="#icon-comment"></use>
														</svg><?= $CommentsNum ?>
													</a>
													<?php $agree = joe\number_word(joe\author_content_field_sum($this->user->uid, 'agree')) ?>
													<span class="but c-yellow tag-follow" data-toggle="tooltip" title="共<?= $agree ?>个点赞"><i class="fa fa-heart em09"></i><?= $agree ?>
													</span>
													<?php $views = joe\number_word(joe\author_content_field_sum($this->user->uid, 'views')) ?>
													<span class="badg c-red tag-view" data-toggle="tooltip" title="人气值 <?= $views ?>">
														<svg class="icon svg" aria-hidden="true">
															<use xlink:href="#icon-hot"></use>
														</svg><?= $views ?>
													</span>
												</div>
												<div class="relative opacity5">
													<i class="line-form-line"></i>
												</div>
												<div class="mt10 text-center">
													<div class="flex jsa header-user-href">
														<a rel="nofollow" href="<?= joe\root_relative_link($this->options->adminUrl) ?>profile.php">
															<div class="badg mb6 toggle-radius c-blue"><svg style="transform: translate(1px, -50%)" class="icon svg" aria-hidden="true" data-viewBox="50 0 924 924" viewBox="50 0 924 924">
																	<use xlink:href="#icon-user"></use>
																</svg></div>
															<div class="c-blue">用户中心</div>
														</a>
														<a rel="nofollow" class="newadd-btns start-new-posts btn-newadd" href="<?= joe\root_relative_link($this->options->adminUrl) ?>write-post.php">
															<div class="badg mb6 toggle-radius c-green"><i style="transform: translate(1px, -50%)" class="fa fa-fw fa-pencil-square-o"></i></div>
															<div class="c-green">发布文章</div>
														</a>
														<a data-turbolinks="false" href="<?= joe\root_relative_link($this->options->logoutUrl) ?>">
															<div class="badg mb6 toggle-radius c-red"><svg class="icon svg" aria-hidden="true">
																	<use xlink:href="#icon-signout"></use>
																</svg></div>
															<div class="c-red">退出登录</div>
														</a>
													</div>
													<?php
													if ($this->user->group == 'administrator') {
													?>
														<div class="flex jsa header-user-href">
															<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>options-theme.php">
																<div class="badg mb6 toggle-radius c-yellow"><svg class="icon svg" aria-hidden="true">
																		<use xlink:href="#icon-theme"></use>
																	</svg></div>
																<div class="c-yellow">主题设置</div>
															</a>
															<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>manage-comments.php">
																<div class="badg mb6 toggle-radius c-yellow"><i class="fa fa-comments"></i></div>
																<div class="c-yellow">管理评论</div>
															</a>
															<a rel="nofollow" target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl) ?>">
																<div class="badg mb6 toggle-radius c-yellow"><svg class="icon svg" aria-hidden="true">
																		<use xlink:href="#icon-set"></use>
																	</svg>
																</div>
																<div class="c-yellow">后台管理</div>
															</a>
														</div>
													<?php
													}
													?>

												</div>
											</div>
										</div>
									</ul>
								</li>
							</ul>
						</div>
					<?php
					} else {
					?>
						<div class="navbar-form navbar-right navbar-text">
							<ul class="list-inline splitters relative">
								<li><a href="<?= joe\user_url('login'); ?>" class="signin-loader header-login">登录</a></li><?= $this->options->allowRegister ? '<li><a href="' . joe\user_url('register') . '" class="signup-loader">注册</a></li>' : null ?>
							</ul>
						</div>
					<?php
					}
					?>
				</nav>
			<?php
			}
			?>
		</div>
	</nav>
</div>
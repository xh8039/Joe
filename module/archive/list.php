<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->is('index')) $index_hide_categorize_list = joe\optionMulti(Helper::options()->JIndex_Hide_Categorize, '||', false);
while ($article = $this->next()) :
	if ($this->is('index')) {
		$categorize_slug_list = [];
		foreach ($this->categories as $key => $value) {
			$categorize_slug_list[] = $value['slug'] ? $value['slug'] : $value['name'];
		}
		if (array_intersect($categorize_slug_list, $index_hide_categorize_list)) continue;
	}
	if ($this->fields->mode == "default" || !$this->fields->mode) : ?>
		<?php if ($this->options->JArticleListAtropos) echo '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' ?>
		<li class="joe_list__item wow default">
			<div class="line"></div>
			<a href="<?= joe\root_relative_link($this->permalink) ?>" class="thumbnail" title="<?php $this->title() ?>">
				<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="<?= joe\getLazyload() ?>" data-src="<?= joe\getThumbnails($this)[0] ?>" alt="<?php $this->title() ?>" />
				<time datetime="<?php $this->date('Y-m-d'); ?>"><?php $this->date('Y-m-d'); ?></time>
				<svg width="20" height="20">
					<use xlink:href="#icon-joe-article-thumbnail"></use>
				</svg>
			</a>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>">
					<?php $this->title() ?>
				</a>
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要"><?php joe\getAbstract($this) ?></a>
				<div class="meta">
					<div class="item-tags scroll-x no-scrollbar mb6"><?= joe\get_archive_tags($this) ?></div>
					<div class="item-meta muted-2-color flex jsb ac">
						<item class="meta-author flex ac">
							<a href="<?= joe\root_relative_link($this->author->permalink) ?>">
								<span class="avatar-mini">
									<img onerror="Joe.avatarError(this)" alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
								</span>
							</a>
							<span class="hide-sm ml6"><?= $this->author->screenName ?></span>
							<span title="<?= date('Y-m-d H:i:s', $this->created) ?>" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;"><?= joe\dateWord($this->dateWord) ?></span>
						</item>
						<div class="meta-right">
							<item class="meta-comm">
								<a rel="nofollow" data-toggle="tooltip" title="去评论" href="<?= joe\root_relative_link($this->permalink) ?>?scroll=comment_module">
									<svg class="icon svg" aria-hidden="true">
										<use xlink:href="#icon-comment"></use>
									</svg><?= number_format($this->commentsNum) ?>
								</a>
							</item>
							<item class="meta-view">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-view"></use>
								</svg><?= isset($article['views']) ? number_format($article['views']) : joe\getViews($this) ?>
							</item>
							<item class="meta-like">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-like"></use>
								</svg><?= isset($article['agree']) ? number_format($article['agree']) : joe\getAgree($this) ?>
							</item>
						</div>
					</div>
				</div>
			</div>
		</li>
		<?php if ($this->options->JArticleListAtropos) echo '</div></div></div></div>' ?>

	<?php elseif ($this->fields->mode == "single") : ?>
		<?php if ($this->options->JArticleListAtropos) echo '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' ?>
		<li class="joe_list__item wow single">
			<div class="line"></div>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>">
					<?php $this->title() ?>
				</a>
				<div class="meta">
					<div class="item-tags scroll-x no-scrollbar mb6"><?= joe\get_archive_tags($this) ?></div>
					<div class="item-meta muted-2-color flex jsb ac">
						<item class="meta-author flex ac">
							<a href="<?= joe\root_relative_link($this->author->permalink) ?>">
								<span class="avatar-mini">
									<img onerror="Joe.avatarError(this)" alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
								</span>
							</a>
							<span class="hide-sm ml6"><?= $this->author->screenName ?></span>
							<span title="<?= date('Y-m-d H:i:s', $this->created) ?>" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;"><?= joe\dateWord($this->dateWord) ?></span>
						</item>
						<div class="meta-right">
							<item class="meta-comm">
								<a rel="nofollow" data-toggle="tooltip" title="去评论" href="<?= joe\root_relative_link($this->permalink) ?>?scroll=comment_module">
									<svg class="icon svg" aria-hidden="true">
										<use xlink:href="#icon-comment"></use>
									</svg><?= number_format($this->commentsNum) ?>
								</a>
							</item>
							<item class="meta-view">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-view"></use>
								</svg><?= isset($article['views']) ? number_format($article['views']) : joe\getViews($this) ?>
							</item>
							<item class="meta-like">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-like"></use>
								</svg><?= isset($article['agree']) ? number_format($article['agree']) : joe\getAgree($this) ?>
							</item>
						</div>
					</div>

				</div>
			</div>
			<a href="<?= joe\root_relative_link($this->permalink) ?>" class="thumbnail" title="<?php $this->title() ?>">
				<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="<?= joe\getLazyload() ?>" data-src="<?= joe\getThumbnails($this)[0] ?>" alt="<?php $this->title() ?>" />
				<time datetime="<?php $this->date('Y-m-d'); ?>"><?php $this->date('Y-m-d'); ?></time>
				<svg width="20" height="20">
					<use xlink:href="#icon-joe-article-thumbnail"></use>
				</svg>
			</a>
			<div class="information" style="margin-bottom: 0;">
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要"><?php joe\getAbstract($this) ?></a>
			</div>
		</li>
		<?php if ($this->options->JArticleListAtropos) echo '</div></div></div></div>' ?>
	<?php elseif ($this->fields->mode == "multiple") : ?>
		<?php if ($this->options->JArticleListAtropos) echo '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' ?>
		<li class="joe_list__item wow multiple">
			<div class="line"></div>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>">
					<?php $this->title() ?>
				</a>
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要"><?php joe\getAbstract($this) ?></a>
			</div>
			<a href="<?= joe\root_relative_link($this->permalink) ?>" class="thumbnail" title="<?php $this->title() ?>">
				<?php $image = joe\getThumbnails($this) ?>
				<?php for ($x = 0; $x < 3; $x++) : ?>
					<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="<?= joe\getLazyload() ?>" data-src="<?php echo $image[$x]; ?>" alt="<?php $this->title() ?>" />
				<?php endfor; ?>
			</a>
			<div class="meta">
				<div class="item-tags scroll-x no-scrollbar mb6"><?= joe\get_archive_tags($this) ?></div>
				<div class="item-meta muted-2-color flex jsb ac">
					<item class="meta-author flex ac">
						<a href="<?= joe\root_relative_link($this->author->permalink) ?>">
							<span class="avatar-mini">
								<img onerror="Joe.avatarError(this)" alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
							</span>
						</a>
						<span class="hide-sm ml6"><?= $this->author->screenName ?></span>
						<span title="<?= date('Y-m-d H:i:s', $this->created) ?>" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;"><?= joe\dateWord($this->dateWord) ?></span>
					</item>
					<div class="meta-right">
						<item class="meta-comm">
							<a rel="nofollow" data-toggle="tooltip" title="去评论" href="<?= joe\root_relative_link($this->permalink) ?>?scroll=comment_module">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-comment"></use>
								</svg><?= number_format($this->commentsNum) ?>
							</a>
						</item>
						<item class="meta-view">
							<svg class="icon svg" aria-hidden="true">
								<use xlink:href="#icon-view"></use>
							</svg><?= isset($article['views']) ? number_format($article['views']) : joe\getViews($this) ?>
						</item>
						<item class="meta-like">
							<svg class="icon svg" aria-hidden="true">
								<use xlink:href="#icon-like"></use>
							</svg><?= isset($article['agree']) ? number_format($article['agree']) : joe\getAgree($this) ?>
						</item>
					</div>
				</div>

			</div>
		</li>
		<?php if ($this->options->JArticleListAtropos) echo '</div></div></div></div>' ?>
	<?php else : ?>
		<?php if ($this->options->JArticleListAtropos) echo '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' ?>
		<li class="joe_list__item wow none">
			<div class="line"></div>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>">
					<?php $this->title() ?>
				</a>
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要"><?php joe\getAbstract($this) ?></a>
				<div class="meta">
					<div class="item-tags scroll-x no-scrollbar mb6"><?= joe\get_archive_tags($this) ?></div>
					<div class="item-meta muted-2-color flex jsb ac">
						<item class="meta-author flex ac">
							<a href="<?= joe\root_relative_link($this->author->permalink) ?>">
								<span class="avatar-mini">
									<img onerror="Joe.avatarError(this)" alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
								</span>
							</a>
							<span class="hide-sm ml6"><?= $this->author->screenName ?></span>
							<span title="<?= date('Y-m-d H:i:s', $this->created) ?>" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;"><?= joe\dateWord($this->dateWord) ?></span>
						</item>
						<div class="meta-right">
							<item class="meta-comm">
								<a rel="nofollow" data-toggle="tooltip" title="去评论" href="<?= joe\root_relative_link($this->permalink) ?>?scroll=comment_module">
									<svg class="icon svg" aria-hidden="true">
										<use xlink:href="#icon-comment"></use>
									</svg><?= number_format($this->commentsNum) ?>
								</a>
							</item>
							<item class="meta-view">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-view"></use>
								</svg><?= isset($article['views']) ? number_format($article['views']) : joe\getViews($this) ?>
							</item>
							<item class="meta-like">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-like"></use>
								</svg><?= isset($article['agree']) ? number_format($article['agree']) : joe\getAgree($this) ?>
							</item>
						</div>
					</div>
				</div>
			</div>
		</li>
		<?php if ($this->options->JArticleListAtropos) echo '</div></div></div></div>' ?>
<?php endif;
endwhile; ?>
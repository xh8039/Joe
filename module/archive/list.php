<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->is('index')) $index_hide_categorize_list = joe\optionMulti(Helper::options()->JIndex_Hide_Categorize, '||', false);
while ($this->next()) :
	if ($this->is('index')) {
		$categorize_slug_list = [];
		foreach ($this->categories as $key => $value) {
			$categorize_slug_list[] = $value['slug'] ? $value['slug'] : $value['name'];
		}
		if (array_intersect($categorize_slug_list, $index_hide_categorize_list)) continue;
	}
	if ($this->fields->mode == "default" || !$this->fields->mode) : ?>
		<li class="joe_list__item wow default">
			<div class="line"></div>
			<a href="<?= joe\root_relative_link($this->permalink) ?>" class="thumbnail" title="<?php $this->title() ?>" target="<?php $this->options->Jsearch_target() ?>" rel="noopener noreferrer">
				<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="<?= joe\getLazyload() ?>" data-src="<?= joe\getThumbnails($this)[0] ?>" alt="<?php $this->title() ?>" />
				<time datetime="<?php $this->date('Y-m-d'); ?>"><?php $this->date('Y-m-d'); ?></time>
				<svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
					<path d="M903.93 107.306H115.787c-51.213 0-93.204 42.505-93.204 93.72V825.29c0 51.724 41.99 93.717 93.717 93.717h788.144c51.72 0 93.717-41.993 93.717-93.717V201.025c-.512-51.214-43.017-93.719-94.23-93.719zm-788.144 64.527h788.657c16.385 0 29.704 13.316 29.704 29.704v390.229L760.54 402.285c-12.805-13.828-30.217-21.508-48.14-19.971-17.924 1.02-34.821 10.754-46.602 26.114l-172.582 239.16-87.06-85.52c-12.29-11.783-27.654-17.924-44.039-17.924-16.39.508-31.755 7.676-43.53 20.48L86.595 821.705V202.05c-1.025-17.416 12.804-30.73 29.191-30.217zm788.145 683.674H141.906l222.255-245.82 87.06 86.037c12.8 12.807 30.212 18.95 47.115 17.417 17.41-1.538 33.797-11.266 45.063-26.118l172.584-238.647 216.111 236.088 2.051-1.54V825.8c.509 16.39-13.315 29.706-30.214 29.706zm0 0" />
					<path d="M318.072 509.827c79.89 0 144.417-65.037 144.417-144.416 0-79.378-64.527-144.925-144.417-144.925-79.891 0-144.416 64.527-144.416 144.412 0 79.892 64.525 144.93 144.416 144.93zm0-225.327c44.553 0 80.912 36.362 80.912 80.91 0 44.557-35.847 81.43-80.912 81.43-45.068 0-80.916-36.36-80.916-80.915 0-44.556 36.872-81.425 80.916-81.425zm0 0" />
				</svg>
			</a>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>" target="<?php $this->options->Jsearch_target() ?>" rel="noopener noreferrer">
					<?php $this->title() ?>
				</a>
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要" target="<?php $this->options->Jsearch_target() ?>" rel="noopener noreferrer"><?php joe\getAbstract($this) ?></a>
				<div class="meta">
					<div class="item-tags scroll-x no-scrollbar mb6"><?= joe\get_archive_tags($this) ?></div>
					<div class="item-meta muted-2-color flex jsb ac">
						<item class="meta-author flex ac">
							<a href="<?= joe\root_relative_link($this->author->permalink) ?>">
								<span class="avatar-mini">
									<img alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
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
								</svg><?= joe\getViews($this) ?>
							</item>
							<item class="meta-like">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-like"></use>
								</svg><?= joe\getAgree($this) ?>
							</item>
						</div>
					</div>
				</div>
			</div>
		</li>
	<?php elseif ($this->fields->mode == "single") : ?>
		<li class="joe_list__item wow single">
			<div class="line"></div>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>" target="_blank" rel="noopener noreferrer">
					<?php $this->title() ?>
				</a>
				<div class="meta">
					<div class="item-tags scroll-x no-scrollbar mb6"><?= joe\get_archive_tags($this) ?></div>
					<div class="item-meta muted-2-color flex jsb ac">
						<item class="meta-author flex ac">
							<a href="<?= joe\root_relative_link($this->author->permalink) ?>">
								<span class="avatar-mini">
									<img alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
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
								</svg><?= joe\getViews($this) ?>
							</item>
							<item class="meta-like">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-like"></use>
								</svg><?= joe\getAgree($this) ?>
							</item>
						</div>
					</div>

				</div>
			</div>
			<a href="<?= joe\root_relative_link($this->permalink) ?>" class="thumbnail" title="<?php $this->title() ?>" target="_blank" rel="noopener noreferrer">
				<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="<?= joe\getLazyload() ?>" data-src="<?= joe\getThumbnails($this)[0] ?>" alt="<?php $this->title() ?>" />
				<time datetime="<?php $this->date('Y-m-d'); ?>"><?php $this->date('Y-m-d'); ?></time>
				<svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
					<path d="M903.93 107.306H115.787c-51.213 0-93.204 42.505-93.204 93.72V825.29c0 51.724 41.99 93.717 93.717 93.717h788.144c51.72 0 93.717-41.993 93.717-93.717V201.025c-.512-51.214-43.017-93.719-94.23-93.719zm-788.144 64.527h788.657c16.385 0 29.704 13.316 29.704 29.704v390.229L760.54 402.285c-12.805-13.828-30.217-21.508-48.14-19.971-17.924 1.02-34.821 10.754-46.602 26.114l-172.582 239.16-87.06-85.52c-12.29-11.783-27.654-17.924-44.039-17.924-16.39.508-31.755 7.676-43.53 20.48L86.595 821.705V202.05c-1.025-17.416 12.804-30.73 29.191-30.217zm788.145 683.674H141.906l222.255-245.82 87.06 86.037c12.8 12.807 30.212 18.95 47.115 17.417 17.41-1.538 33.797-11.266 45.063-26.118l172.584-238.647 216.111 236.088 2.051-1.54V825.8c.509 16.39-13.315 29.706-30.214 29.706zm0 0" />
					<path d="M318.072 509.827c79.89 0 144.417-65.037 144.417-144.416 0-79.378-64.527-144.925-144.417-144.925-79.891 0-144.416 64.527-144.416 144.412 0 79.892 64.525 144.93 144.416 144.93zm0-225.327c44.553 0 80.912 36.362 80.912 80.91 0 44.557-35.847 81.43-80.912 81.43-45.068 0-80.916-36.36-80.916-80.915 0-44.556 36.872-81.425 80.916-81.425zm0 0" />
				</svg>
			</a>
			<div class="information" style="margin-bottom: 0;">
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要" target="_blank" rel="noopener noreferrer"><?php joe\getAbstract($this) ?></a>
			</div>
		</li>
	<?php elseif ($this->fields->mode == "multiple") : ?>
		<li class="joe_list__item wow multiple">
			<div class="line"></div>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>" target="_blank" rel="noopener noreferrer">
					<?php $this->title() ?>
				</a>
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要" target="_blank" rel="noopener noreferrer"><?php joe\getAbstract($this) ?></a>
			</div>
			<a href="<?= joe\root_relative_link($this->permalink) ?>" class="thumbnail" title="<?php $this->title() ?>" target="_blank" rel="noopener noreferrer">
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
								<img alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
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
							</svg><?= joe\getViews($this) ?>
						</item>
						<item class="meta-like">
							<svg class="icon svg" aria-hidden="true">
								<use xlink:href="#icon-like"></use>
							</svg><?= joe\getAgree($this) ?>
						</item>
					</div>
				</div>

			</div>
		</li>
	<?php else : ?>
		<li class="joe_list__item wow none">
			<div class="line"></div>
			<div class="information">
				<a href="<?= joe\root_relative_link($this->permalink) ?>" class="title" title="<?php $this->title() ?>" target="_blank" rel="noopener noreferrer">
					<?php $this->title() ?>
				</a>
				<a class="abstract" href="<?= joe\root_relative_link($this->permalink) ?>" title="文章摘要" target="_blank" rel="noopener noreferrer"><?php joe\getAbstract($this) ?></a>
				<div class="meta">
					<div class="item-tags scroll-x no-scrollbar mb6"><?= joe\get_archive_tags($this) ?></div>
					<div class="item-meta muted-2-color flex jsb ac">
						<item class="meta-author flex ac">
							<a href="<?= joe\root_relative_link($this->author->permalink) ?>">
								<span class="avatar-mini">
									<img alt="<?= $this->author->screenName ?>的头像 - <?= $this->options->title ?>" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($this->author->mail) ?>" class="lazyload avatar avatar-id-<?= $this->author->uid ?>">
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
								</svg><?= joe\getViews($this) ?>
							</item>
							<item class="meta-like">
								<svg class="icon svg" aria-hidden="true">
									<use xlink:href="#icon-like"></use>
								</svg><?= joe\getAgree($this) ?>
							</item>
						</div>
					</div>
				</div>
			</div>
		</li>
<?php endif;
endwhile; ?>
<div class="joe_detail__leaving">
	<?php $this->comments()->to($comments); ?>
	<?php if ($comments->have()) : ?>
		<ul class="joe_detail__leaving-list">
			<?php while ($comments->next()) : ?>
				<li class="item">
					<div class="user">
						<img onerror="Joe.avatarError(this)" class="avatar lazyload" src="<?= joe\getAvatarLazyload(); ?>" data-src="<?php joe\getAvatarByMail($comments->mail) ?>" alt="用户头像" />
						<div class="nickname"><?php $comments->author(); ?></div>
						<div class="date"><?php $comments->date('Y/m/d'); ?></div>
					</div>
					<div class="wrapper">
						<div class="content"><?php _parseLeavingReply($comments->content); ?></div>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php else : ?>
		<div class="joe_detail__leaving-none">暂无留言，期待第一个脚印。</div>
	<?php endif; ?>
</div>
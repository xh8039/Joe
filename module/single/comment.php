<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$this->comments()->to($comments);
$is_comment = ($this->allow('comment') && $this->options->JCommentStatus != "off") ? true : false;
$login_comment = $this->options->JcommentLogin == 'on' && !is_numeric(USER_ID) ? true : false;
?>
<div class="box-body notop">
	<div class="joe_comment__title title-theme">评论 <small><?= $is_comment ? (empty($this->commentsNum) ? '抢沙发' : '共' . $this->commentsNum . '条') : null ?></small></div>
</div>
<div class="joe_comment" id="comment_module">
	<?php
	if ($this->hidden) {
	?>
		<div class="joe_comment__close">
			<svg class="joe_comment__close-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
				<use xlink:href="#icon-joe-comment-close"></use>
			</svg>
			<span>当前文章受密码保护，无法评论</span>
		</div>
	<?php
	} else if ($this->options->JCommentStatus == "off") {
	?>
		<div class="joe_comment__close">
			<svg class="joe_comment__close-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
				<use xlink:href="#icon-joe-comment-close"></use>
			</svg>
			<span>所有页面的评论已关闭</span>
		</div>
	<?php
	} else if (!$this->allow('comment')) {
	?>
		<div class="joe_comment__close">
			<svg class="joe_comment__close-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
				<use xlink:href="#icon-joe-comment-close"></use>
			</svg>
			<span>当前页面的评论已关闭</span>
		</div>
	<?php
	} else {
	?>
		<div id="<?php $this->respondId(); ?>" class="joe_comment__respond">
			<div class="joe_comment__respond-type">
				<?php
				if ($this->options->JcommentDraw == 'on' && !$login_comment) {
				?>
					<button class="item" data-type="draw">画图模式</button>
					<button class="item active" data-type="text">文本模式</button>
				<?php
				}
				?>
			</div>
			<form method="post" class="joe_comment__respond-form" action="<?= joe\root_relative_link($this->commentUrl) ?>" data-type="text">
				<div class="head">
					<?php
					if ($this->user->hasLogin() || $login_comment) {
					?>
						<style>
							.joe_comment__respond-form .head {
								border-bottom: none;
							}
						</style>
						<input type="hidden" name="author" value="<?= $this->user->screenName() ?>">
						<input type="hidden" name="mail" value="<?= $this->user->mail() ?>">
						<input type="hidden" name="url" value="<?= $this->user->url() ?>">
					<?php
					} else {
					?>
						<div class="list">
							<input type="text" value="<?php $this->remember('author') ?>" autocomplete="off" name="author" maxlength="16" placeholder="请输入昵称..." />
						</div>
						<div class="list">
							<input type="text" value="<?php $this->remember('mail') ?>" autocomplete="off" name="mail" placeholder="请输入真实邮箱，用于接收回信..." />
						</div>
						<div class="list">
							<input type="text" value="<?php $this->remember('url') ?>" autocomplete="off" name="url" placeholder="请输入网址（非必填）..." />
						</div>
					<?php
					}
					?>
				</div>
				<div class="body">
					<?php
					if ($this->options->JcommentDraw == 'on' && !$login_comment) {
					?>
						<textarea class="text joe_owo__target" name="text" value="" autocomplete="new-password" placeholder="聊点什么吧，回车可快速发送，点击左上方切换成画图试试？"></textarea>
						<div class="draw" style="display: none;">
							<ul class="line">
								<li data-line="3">细</li>
								<li data-line="5" class="active">中</li>
								<li data-line="8">粗</li>
							</ul>
							<ul class="color">
								<li data-color="#303133" class="active"></li>
								<li data-color="#67c23a"></li>
								<li data-color="#e6a23c"></li>
								<li data-color="#f56c6c"></li>
							</ul>
							<svg class="icon icon-undo" viewBox="0 0 1365 1024" xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true">
								<use xlink:href="#icon-joe-undo"></use>
							</svg>
							<svg class="icon icon-animate" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="24" height="24">
								<use xlink:href="#icon-joe-sketchpad-animate"></use>
							</svg>
							<canvas id="joe_comment_draw" height="300"></canvas>
						</div>
					<?php
					} else {
					?>
						<textarea class="text joe_owo__target" name="text" value="<?php $this->remember('text') ?>" autocomplete="new-password" placeholder="<?= $login_comment ? '请登录后再进行评论' : '来都来啦，聊点什么吧，回车可快速发送' ?>" <?= $login_comment ? 'disabled="true"' : null ?>><?php $this->remember('text') ?></textarea>
					<?php
					}
					?>
				</div>
				<div class="foot">
					<div class="owo joe_owo__contain">
						<div class="seat">OωO</div>
						<div class="box"></div>
					</div>
					<div class="submit">
						<span class="cancle joe_comment__cancle">取消</span>
						<?php
						if ($login_comment) {
							echo '<a href="' . joe\user_url('login') . '" rel="nofollow">登录评论</a>';
						} else {
							echo '<button type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> 发送评论</button>';
						}
						?>

					</div>
				</div>
			</form>
		</div>
	<?php
		if ($comments->have()) {
			$comments->listComments();
		} else {
			echo '<ol class="comment-list" style="display: none;"></ol>';
		}
		// 开始输出缓冲
		ob_start();
		$comments->pageNav(
			'<i class="fa fa-angle-left em12"></i><span class="hide-sm ml6">上一页</span>',
			'<span class="hide-sm mr6">下一页</span><i class="fa fa-angle-right em12"></i>',
			1,
			'...',
			array(
				'wrapTag' => 'ul',
				'wrapClass' => 'joe_pagination',
				'itemTag' => 'li',
				'textTag' => 'a',
				'currentClass' => 'active',
				'prevClass' => 'prev',
				'nextClass' => 'next'
			)
		);
		// 获取缓冲区的内容并存储到变量中
		$comments_page = ob_get_contents();
		// 清空缓冲区并关闭输出缓冲
		ob_end_clean();
		// 评论分页标记ajax加载
		echo str_replace('<a', '<a ajax-replace="true"', $comments_page);
	}
	?>
</div>

<?php
function threadedComments($comments, $options)
{
	$login_comment = Helper::options()->JcommentLogin == 'on' && !is_numeric(USER_ID) ? true : false;
?>
	<li data-coid="<?= $comments->coid ?>" class="comment-list__item" <?= $comments->status == 'waiting' ? 'style="opacity: 0.8;"' : null ?>>
		<div class="comment-list__item-contain" id="<?php $comments->theId(); ?>">
			<div class="term">
				<?php
				$mobile_handle = joe\isMobile() ? 'tabindex="0" data-placement="right" data-trigger="focus" data-content="' . ($comments->authorId == $comments->ownerId ? '作者&nbsp;·&nbsp;' : '') . htmlentities(joe\comment_author($comments)) . '&nbsp;·&nbsp;' . joe\getAgentOS($comments->agent) . '&nbsp;·&nbsp;' . joe\getAgentBrowser($comments->agent) . '&nbsp;·&nbsp;' . joe\dateWord($comments->dateWord) . '" data-toggle="popover"' : '';
				if ($comments->authorId == $comments->ownerId && joe\isMobile()) $mobile_handle .= ' style="border-color: var(--theme);"';
				if ($comments->request->getHeader('x-requested-with')) {
				?>
					<img onerror="Joe.avatarError(this)" <?= $mobile_handle ?> width="48" height="48" class="avatar" src="<?php joe\getAvatarByMail($comments->mail); ?>" alt="头像" />
				<?php
				} else {
				?>
					<img onerror="Joe.avatarError(this)" <?= $mobile_handle ?> width="48" height="48" class="avatar lazyload" src="<?= joe\getAvatarLazyload() ?>" data-src="<?php joe\getAvatarByMail($comments->mail); ?>" alt="头像" />
				<?php
				}
				if (joe\isPc()) {
				?>
					<div class="content">
						<div class="user">
							<div class="nickname">
								<span class="author"><?= joe\comment_author($comments); ?></span>
								<?= $comments->authorId == $comments->ownerId ? '<i class="owner">作者</i>' : null ?>
							</div>
							<span>&nbsp;·&nbsp;</span>
							<div class="agent">
								<?php
								$os_svg = joe\getAgentOSIcon($comments->agent) . '.svg';
								$os_svg_url =  joe\theme_url('assets/images/agent/' . $os_svg, false);
								$AgentBrowser = joe\getAgentBrowser($comments->agent);
								$browser_url = joe\getAgentBrowserIcon($AgentBrowser);
								?>
								<img src="<?= $os_svg_url ?>" title="<?= joe\getAgentOS($comments->agent) ?>" data-toggle="tooltip">
								<img src="<?= $browser_url ?>" title="<?= $AgentBrowser ?>" data-toggle="tooltip">
							</div>
							<?= $comments->status == "waiting" ? '<em class="waiting">（评论审核中...）</em>' : null ?>
							<div class="handle">
								<time class="date" data-toggle="tooltip" title="<?php $comments->date('Y-m-d H:i:s'); ?>" datetime="<?php $comments->date('Y-m-d H:i:s'); ?>"><?= joe\dateWord($comments->dateWord); ?></time>
								<?= ($GLOBALS['JOE_USER']->hasLogin() && $GLOBALS['JOE_USER']->group == 'administrator') ? '
								<span class="reply joe_comment__operate ml10" status="delete" data-coid="' . $comments->coid . '"><i class="icon fa fa-remove" aria-hidden="true"></i>删除</span>
								<span class="reply joe_comment__operate ml10" status="spam" data-coid="' . $comments->coid . '"><i class="icon fa fa-trash" aria-hidden="true"></i>垃圾</span>
								<span class="reply joe_comment__operate ml10" status="waiting" data-coid="' . $comments->coid . '"><i class="icon fa fa-eye-slash" aria-hidden="true"></i>待审</span>
								' : null ?>
								<?= !$login_comment ? '<span class="reply joe_comment__reply ml10" data-id="' . $comments->theId . '" data-coid="' . $comments->coid . '"><i class="icon fa fa-pencil" aria-hidden="true"></i>回复</span>' : null ?>
							</div>
						</div>
						<div class="substance">
							<?php joe\getParentReply($comments->parent) ?><?= _parseCommentReply($comments->content); ?>
						</div>
					</div>
				<?php
				} else {
				?>
					<div class="content" data-id="<?= $comments->theId ?>" data-coid="<?= $comments->coid ?>">
						<div class="substance" tabindex="0">
							<?= $comments->status == "waiting" ? '<em class="waiting">（评论审核中...）</em>' : null ?>
							<?php joe\getParentReply($comments->parent) ?><?= _parseCommentReply($comments->content); ?>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
		<?php if ($comments->children) : ?>
			<div class="comment-list__item-children">
				<?php $comments->threadedComments($options); ?>
			</div>
		<?php endif; ?>
	</li>
<?php
}
?>
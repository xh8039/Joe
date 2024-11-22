<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

require_once JOE_ROOT . 'public/phpmailer.php';
require_once JOE_ROOT . 'public/smtp.php';

/* 加强评论拦截功能 */
Typecho_Plugin::factory('Widget_Feedback')->comment = array('Intercept', 'message');
class Intercept
{
	public static function message($comment)
	{
		if (Helper::options()->JCommentStatus == 'off') {
			throw new Typecho_Exception(_t('叼毛 不要想着强制评论！'));
			return false;
		}
		if (Helper::options()->JcommentLogin == 'on' && !is_numeric(USER_ID)) {
			throw new Typecho_Exception(_t('叼毛 老老实实登录评论！'));
			return false;
		}

		// 用户输入内容画图模式
		if (preg_match('/\{!\{(.*)\}!\}/', $comment['text'], $matches)) {
			// 如果判断是否有双引号，如果有双引号，则禁止评论
			if (strpos($matches[1], '"') !== false || _checkXSS($matches[1])) {
				$comment['status'] = 'waiting';
			} else {
				$comment_md5 = md5($matches[1]);
				$save_comment_path = '/usr/uploads/draw-comment/' . $comment_md5 . '.webp';
				$save_comment = joe\draw_save($matches[1], __TYPECHO_ROOT_DIR__ . $save_comment_path);
				if ($save_comment) {
					$comment['text'] = '{!{' . $save_comment_path . '}!}';
				} else {
					throw new Typecho_Exception(_t('画图图片保存失败！'));
					return false;
				}
			}
		} else if (preg_match('/[a-zA-z]+:\/\/[^\s]*/i', $comment['text'])) {
			// 判断用户输入是否包含网址URL
			$comment['status'] = 'waiting';
		} else if (Helper::options()->JTextLimit && strlen($comment['text']) > Helper::options()->JTextLimit) {
			// 判断用户输入是否大于字符
			$comment['status'] = 'waiting';
		} else if (Helper::options()->JSensitiveWords && joe\checkSensitiveWords(Helper::options()->JSensitiveWords, $comment['text'])) {
			// 判断评论内容是否包含敏感词
			$comment['status'] = 'waiting';
		} else if (Helper::options()->JLimitOneChinese === "on" && preg_match("/[\x{4e00}-\x{9fa5}]/u", $comment['text']) == 0) {
			// 判断评论是否至少包含一个中文
			$comment['status'] = 'waiting';
		}

		Typecho_Cookie::delete('__typecho_remember_text');
		return $comment;
	}
}

/* 邮件通知 */
if (
	Helper::options()->JCommentMail === 'on' &&
	Helper::options()->JCommentMailHost &&
	Helper::options()->JCommentMailPort &&
	Helper::options()->JCommentMailFromName &&
	Helper::options()->JCommentMailAccount &&
	Helper::options()->JCommentMailPassword &&
	Helper::options()->JCommentSMTPSecure
) {
	if (isset($_SESSION['JOE_SEND_MAIL_TIME'])) {
		if (time() - $_SESSION['JOE_SEND_MAIL_TIME'] >= 180) {
			Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('Email', 'send');
		}
	} else {
		Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('Email', 'send');
	}
}

class Email
{
	public static function send($comment)
	{
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->CharSet = 'UTF-8';
		$mail->SMTPSecure = Helper::options()->JCommentSMTPSecure;
		$mail->Host = Helper::options()->JCommentMailHost;
		$mail->Port = Helper::options()->JCommentMailPort;
		$mail->FromName = Helper::options()->JCommentMailFromName;
		$mail->Username = Helper::options()->JCommentMailAccount;
		$mail->From = Helper::options()->JCommentMailAccount;
		$mail->Password = Helper::options()->JCommentMailPassword;
		$mail->isHTML(true);
		$text = $comment->text;
		$text = _parseReply($text);
		$text = preg_replace('/\{!\{([^\"]*)\}!\}/', '<img referrerpolicy="no-referrer" rel="noreferrer" style="max-width: 100%;vertical-align: middle;" src="' . trim(Helper::options()->siteUrl, '/') . '$1"/>', $text);
		$html = '<!DOCTYPE html><html lang="zh-cn"><head><meta charset="UTF-8"><meta name="viewport"content="width=device-width, initial-scale=1.0"><title>{title}</title></head><body><style>.Joe{width:95%;margin:0 auto;border-radius:8px;overflow:hidden;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.Joe_title{color:#fff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400%400%;background-position:50%100%;padding:15px;font-size:15px;line-height:1.5}</style><div class="Joe"><div class="Joe_title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;"><div style="margin-bottom: 20px;line-height: 1.5;">{subtitle}</div><div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div></body></html>';
		/* 如果是博主发的评论 */
		if ($comment->authorId == $comment->ownerId) {
			/* 发表的评论是回复别人 */
			if ($comment->parent != 0) {
				$db = Typecho_Db::get();
				$parentInfo = $db->fetchRow($db->select('mail')->from('table.comments')->where('coid = ?', $comment->parent));
				$parentMail = $parentInfo['mail'];
				/* 被回复的人不是自己时，发送邮件 */
				if ($parentMail != $comment->mail) {
					$text = CommentLink($text, $comment->permalink, '回复');
					$mail->Body = strtr(
						$html,
						array(
							"{title}" => '您在 [' . $comment->title . '] 的评论有了新的回复！',
							"{subtitle}" => '博主：[ ' . $comment->author . ' ] 在《 <a style="color: #12addb;text-decoration: none;" href="' . substr($comment->permalink, 0, strrpos($comment->permalink, "#")) . '" target="_blank">' . $comment->title . '</a> 》上回复了您:',
							"{content}" => $text,
						)
					);
					$mail->addAddress($parentMail);
					$mail->Subject = '您在 [' . $comment->title . '] 的评论有了新的回复！';
					$mail->send();
					$_SESSION['JOE_SEND_MAIL_TIME'] = time();
				}
			}
			/* 如果是游客发的评论 */
		} else {
			/* 如果是直接发表的评论，不是回复别人，那么发送邮件给博主 */
			if ($comment->parent == 0) {
				$db = Typecho_Db::get();
				$authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', $comment->ownerId));
				$authorMail = $authoInfo['mail'];
				if ($authorMail) {
					$text = CommentLink($text, $comment->permalink, '评论');
					$mail->Body = strtr(
						$html,
						array(
							"{title}" => '您的文章 [' . $comment->title . '] 收到一条新的评论！',
							"{subtitle}" => $comment->author . ' [' . $comment->ip . '] 在您的《 <a style="color: #12addb;text-decoration: none;" href="' . substr($comment->permalink, 0, strrpos($comment->permalink, "#")) . '" target="_blank">' . $comment->title . '</a> 》上发表评论:',
							"{content}" => $text,
						)
					);
					$mail->addAddress($authorMail);
					$mail->Subject = '您的文章 [' . $comment->title . '] 收到一条新的评论！';
					$mail->send();
					$_SESSION['JOE_SEND_MAIL_TIME'] = time();
				}
				/* 如果发表的评论是回复别人 */
			} else {
				$db = Typecho_Db::get();
				$parentInfo = $db->fetchRow($db->select('mail')->from('table.comments')->where('coid = ?', $comment->parent));
				$parentMail = $parentInfo['mail'];
				/* 被回复的人不是自己时，发送邮件 */
				if ($parentMail != $comment->mail) {
					$text = CommentLink($text, $comment->permalink, '回复');
					$mail->Body = strtr(
						$html,
						array(
							"{title}" => '您在 [' . $comment->title . '] 的评论有了新的回复！',
							"{subtitle}" => $comment->author . ' 在《 <a style="color: #12addb;text-decoration: none;" href="' . substr($comment->permalink, 0, strrpos($comment->permalink, "#")) . '" target="_blank">' . $comment->title . '</a> 》上回复了您:',
							"{content}" => $text,
						)
					);
					$mail->addAddress($parentMail);
					$mail->Subject = '您在 [' . $comment->title . '] 的评论有了新的回复！';
					$mail->send();
					$_SESSION['JOE_SEND_MAIL_TIME'] = time();
				}
			}
		}
	}
}

function CommentLink($text, $link, $type)
{
	$text = $text . '<br><a style="display:block;color: #12addb;text-decoration: none;text-align:right;" href="' . str_replace('#', '?scroll=', $link) . '" target="_blank">查看' . $type . '</a>';
	return $text;
}


/* 加强后台编辑器功能 */
if (Helper::options()->JEditor !== 'off') {
	Typecho_Plugin::factory('admin/write-post.php')->richEditor  = array('Editor', 'Edit');
	Typecho_Plugin::factory('admin/write-post.php')->option  = array('Editor', 'labelSelection');
	Typecho_Plugin::factory('admin/write-page.php')->richEditor  = array('Editor', 'Edit');
}

class Editor
{
	public static function Edit()
	{
?>
		<!-- Bootstrap: tooltip.css v3.4.1 -->
		<link rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/tooltip.css', false); ?>">

		<link rel="stylesheet" href="<?= joe\cdn('aplayer/1.10.1/APlayer.min.css') ?>">

		<!-- Prism.css -->
		<link rel="stylesheet" href="<?= joe\cdn('prism-themes/1.9.0/'  . Helper::options()->JPrismTheme) ?>">
		<link href="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.css') ?>" rel="stylesheet">

		<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.mode.css') ?>">
		<link rel="stylesheet" href="<?= joe\theme_url('assets/typecho/write/css/joe.write.css', false) ?>">

		<!-- 自定义CSS样式 -->
		<style>
			<?php Helper::options()->JCustomCSS(); ?>
		</style>
		<!-- 自定义CSS样式 -->

		<script>
			window.JoeConfig = {
				uploadAPI: `<?php Helper::security()->index('/action/upload'); ?>`,
				emojiAPI: `<?= joe\theme_url('assets/typecho/write/json/emoji.json', false) ?>`,
				expressionAPI: `<?= Helper::options()->themeUrl('assets/json/joe.owo.json') ?>`,
				characterAPI: `<?= joe\theme_url('assets/typecho/write/json/character.json', false) ?>`,
				playerAPI: `<?php Helper::options()->JCustomPlayer ? Helper::options()->JCustomPlayer() : Helper::options()->themeUrl('module/player.php?url=') ?>`,
				autoSave: <?php Helper::options()->autoSave(); ?>,
				themeURL: `<?php Helper::options()->themeUrl(); ?>`,
				JOwOAssetsUrl: `<?php empty(Helper::options()->JOwOAssetsUrl) ? '' : rtrim(Helper::options()->JOwOAssetsUrl, ' /') . '/' ?>`,
				canPreview: false
			}
		</script>
		<script src="<?= joe\cdn('aplayer/1.10.1/APlayer.min.js') ?>"></script>

		<!-- Prism.js -->
		<script src="<?= joe\cdn('prism/1.9.0/prism.min.js') ?>"></script>
		<script src="<?= joe\cdn('prism/1.9.0/plugins/autoloader/prism-autoloader.min.js') ?>"></script>
		<script>
			Prism.plugins.autoloader.languages_path = '<?php Helper::options()->themeUrl('assets/plugin/prism/1.9.0/components/') ?>';
		</script>
		<script src="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.js') ?>"></script>

		<!-- Bootstrap: tooltip.js v3.4.1 -->
		<script src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/tooltip.js', false); ?>"></script>

		<script src="<?= joe\theme_url('assets/typecho/write/parse/parse.min.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/dist/CodeMirror.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/tools.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/actions.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/create.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/index.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/js/joe.function.js'); ?>"></script>
		<script src="<?= joe\theme_url('assets/js/joe.short.js') ?>"></script>
	<?php
	}

	public static function labelSelection()
	{
	?>
		<section class="typecho-post-option">
			<style>
				.tagshelper {
					list-style: none;
					border: 1px solid #D9D9D6;
					padding: 6px;
					max-height: 240px;
					overflow: auto;
					background-color: #FFF;
					border-radius: 2px;
				}

				.tagshelper a {
					cursor: pointer;
					padding: 0px 6px;
					margin: 2px 0;
					display: inline-block;
					border-radius: 2px;
					text-decoration: none;
					transition: 0.1s;
				}

				.tagshelper a:hover {
					background: #ccc;
					color: #fff;
				}
			</style>
			<label for="token-input-tags" class="typecho-label"><?php _e('标签选择'); ?></label>
			<ul class="tagshelper">
				<?php
				Typecho_Widget::widget('Widget_Metas_Tag_Cloud')->to($tags);
				if ($tags->have()) {
					$i = 0;
					while ($tags->next()) {
						echo "<a onclick=\"$('#tags').tokenInput('add', {id: '" . $tags->name . "', tags: '" . $tags->name . "'});\">", $tags->name, "</a>";
						$i++;
					}
				}
				?>
			</ul>
		</section>
<?php
	}
}

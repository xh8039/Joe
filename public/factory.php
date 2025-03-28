<?php

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

/* 加强评论拦截功能 */
Typecho\Plugin::factory('Widget_Feedback')->comment = array('Intercept', 'message');
class Intercept
{
	public static function waiting($text)
	{
		// 判断用户输入是否大于字符
		if (Helper::options()->JTextLimit && mb_strlen($text) > Helper::options()->JTextLimit) {
			Typecho\Cookie::set('__typecho_remember_text', $text);
			throw new Typecho\Widget\Exception(_t('评论的内容超出 ' . Helper::options()->JTextLimit . ' 字符限制！'));
		}

		// 判断评论是否至少包含一个中文
		if ($GLOBALS['JOE_USER']->group != 'administrator' && Helper::options()->JLimitOneChinese == "on" && preg_match("/[\x{4e00}-\x{9fa5}]/u", $text) == 0) {
			Typecho\Cookie::set('__typecho_remember_text', $text);
			throw new Typecho\Widget\Exception(_t('评论至少包含一个中文！'));
		}

		// 判断评论内容是否包含敏感词
		if (Helper::options()->JSensitiveWords && joe\checkSensitiveWords(Helper::options()->JSensitiveWords, $text)) return true;

		// 评论敏感词API检测
		if (Helper::options()->JSensitiveWordApi) {
			$sensitive_word_api = joe\optionMulti(Helper::options()->JSensitiveWordApi);
			$sensitive_word_api_info = joe\optionMulti($sensitive_word_api[0], ['api', 'content', 'is', 'message']);
			$sensitive_word_api_header = empty($sensitive_word_api[1]) ? null : $sensitive_word_api[1];

			if (empty($sensitive_word_api_info['api'])) throw new Typecho\Widget\Exception(_t('评论敏感词检测API地址设置错误'));
			if (empty($sensitive_word_api_info['content'])) throw new Typecho\Widget\Exception(_t('评论敏感词检测API请求内容字段设置错误'));
			if (empty($sensitive_word_api_info['is'])) throw new Typecho\Widget\Exception(_t('评论敏感词检测API响应违规字段设置错误'));

			$client = new network\http\Client(['timeout' => 5]);
			$IP = joe\request()->getIp();
			if (!empty($IP)) $client->header([
				'client-ip' => $IP,
				'x-real-ip' => $IP,
				'x-forwarded-for' => $IP,
			]);
			if (is_array($sensitive_word_api_header)) $client->header($sensitive_word_api_header);
			$response = $client->post($sensitive_word_api_info['api'], [
				$sensitive_word_api_info['content'] => $text,
				'userIp' => $IP,
			]);
			$data = $response->toArray();

			if (is_array($data) && !empty($data)) {
				$error_message = $sensitive_word_api_info['message'];
				if (!isset($data[$sensitive_word_api_info['is']]) && $error_message && isset($data[$error_message])) {
					throw new Typecho\Widget\Exception(_t('评论敏感词检测接口响应失败：' . $data[$error_message]));
				} else if ($data[$sensitive_word_api_info['is']]) {
					return true;
				}
			} else {
				throw new Typecho\Widget\Exception(_t('评论敏感词检测接口响应失败：' . $response->error()));
			}
		}

		return false;
	}
	public static function message($comment)
	{
		if (Helper::options()->JCommentStatus == 'off') {
			throw new Typecho\Widget\Exception(_t('叼毛 不要想着强制评论！'));
			return false;
		}
		if (Helper::options()->JcommentLogin == 'on' && !is_numeric(USER_ID)) {
			throw new Typecho\Widget\Exception(_t('叼毛 老老实实登录评论！'));
			return false;
		}

		// 用户输入内容画图模式
		if (preg_match('/\{!\{(.*)\}!\}/i', $comment['text'], $matches) && Helper::options()->JcommentDraw == 'on') {
			// 如果判断是否有双引号，如果有双引号，则禁止评论
			if (strpos($matches[1], '"') !== false || _checkXSS($matches[1])) {
				$comment['status'] = 'waiting';
			} else {
				$comment_md5 = md5($matches[1]);
				$save_comment_path = '/usr/uploads/joe-draw/' . $comment_md5 . '.webp';
				$save_comment = joe\draw_save($matches[1], __TYPECHO_ROOT_DIR__ . $save_comment_path);
				if ($save_comment) {
					$comment['text'] = '{!{' . $save_comment_path . '}!}';
				} else {
					throw new Typecho\Exception(_t('画图图片保存失败！'));
					return false;
				}
			}
		} else if ($GLOBALS['JOE_USER']->group !== 'administrator' && self::waiting($comment['text'])) {
			$comment['status'] = 'waiting';
		}

		// Typecho\Cookie::delete('__typecho_remember_text');
		return $comment;
	}
}

/* 邮件通知 */
if (Helper::options()->JCommentMail === 'on' && joe\email_config()) {
	if (isset($_SESSION['joe_send_mail_time'])) {
		if (time() - $_SESSION['joe_send_mail_time'] >= 180) {
			Typecho\Plugin::factory('Widget_Feedback')->finishComment = array('Email', 'send');
		}
	} else {
		Typecho\Plugin::factory('Widget_Feedback')->finishComment = array('Email', 'send');
	}
}

class Email
{
	public static function send($comment)
	{
		$text = $comment->text;
		$text = _parseReply($text);
		$text = preg_replace('/\{!\{([^\"]*)\}!\}/', '<img referrerpolicy="no-referrer" rel="noreferrer" style="max-width: 100%;vertical-align: middle;" src="' . trim(Helper::options()->siteUrl, '/') . '$1"/>', $text);
		/* 如果是博主发的评论 */
		if ($comment->authorId == $comment->ownerId) {
			/* 发表的评论是回复别人 */
			if ($comment->parent != 0) {
				$parent_comment = Db::name('comments')->where('coid', $comment->parent)->find();
				/* 被回复的人不是自己时，发送邮件 */
				if ($parent_comment['mail'] != $comment->mail) {
					$text = CommentLink($text, $comment->permalink, '回复');
					$parent_text = _parseReply($parent_comment['text']);
					$parent_text = preg_replace('/\{!\{([^\"]*)\}!\}/', '<img referrerpolicy="no-referrer" rel="noreferrer" style="max-width: 100%;vertical-align: middle;" src="' . trim(Helper::options()->siteUrl, '/') . '$1"/>', $parent_text);
					joe\send_mail(
						'您在 [' . $comment->title . '] 的评论有了新的回复',
						'博主 [ ' . $comment->author . ' ] 在《 <a style="color: #12addb;text-decoration: none;" href="' . substr($comment->permalink, 0, strrpos($comment->permalink, "#")) . '" target="_blank">' . $comment->title . '</a> 》上回复了您:',
						['评论' => $parent_text, '回复' => $text],
						$parent_comment['mail']
					);
					$_SESSION['joe_send_mail_time'] = time();
				}
			}
			/* 如果是游客发的评论 */
		} else {
			/* 如果是直接发表的评论，不是回复别人，那么发送邮件给博主 */
			if ($comment->parent == 0) {
				$authorMail = Db::name('users')->where('uid', $comment->ownerId)->value('mail');
				if ($authorMail) {
					$text = CommentLink($text, $comment->permalink, '评论');
					joe\send_mail(
						'您的文章 [' . $comment->title . '] 收到一条新的评论',
						$comment->author . ' [' . $comment->ip . '] 在您的《 <a style="color: #12addb;text-decoration: none;" href="' . substr($comment->permalink, 0, strrpos($comment->permalink, "#")) . '" target="_blank">' . $comment->title . '</a> 》上发表评论:',
						$text,
						$authorMail
					);
					$_SESSION['joe_send_mail_time'] = time();
				}
				/* 如果发表的评论是回复别人 */
			} else {
				$parent_comment = Db::name('comments')->where('coid', $comment->parent)->find();
				/* 被回复的人不是自己时，发送邮件 */
				if ($parent_comment['mail'] != $comment->mail) {
					$text = CommentLink($text, $comment->permalink, '回复');
					$parent_text = _parseReply($parent_comment['text']);
					$parent_text = preg_replace('/\{!\{([^\"]*)\}!\}/', '<img referrerpolicy="no-referrer" rel="noreferrer" style="max-width: 100%;vertical-align: middle;" src="' . trim(Helper::options()->siteUrl, '/') . '$1"/>', $parent_text);
					joe\send_mail(
						'您在 [' . $comment->title . '] 的评论有了新的回复',
						$comment->author . ' 在《 <a style="color: #12addb;text-decoration: none;" href="' . substr($comment->permalink, 0, strrpos($comment->permalink, "#")) . '" target="_blank">' . $comment->title . '</a> 》上回复了您:',
						['评论' => $parent_text, '回复' => $text],
						$parent_comment['mail']
					);
					$_SESSION['joe_send_mail_time'] = time();
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
	Typecho\Plugin::factory('admin/write-post.php')->richEditor  = array('Editor', 'Edit');
	Typecho\Plugin::factory('admin/write-post.php')->option  = array('Editor', 'labelSelection');
	Typecho\Plugin::factory('admin/write-page.php')->richEditor  = array('Editor', 'Edit');
	Typecho\Plugin::factory('admin/write-page.php')->option  = array('Editor', 'visibility');
}

class Editor
{
	public static function Edit()
	{
?>
		<link async rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/tooltip.css', false); ?>">
		<link rel="stylesheet" href="<?= joe\theme_url('assets/typecho/write/css/joe.write.css') ?>">

		<!-- 自定义CSS样式 -->
		<style>
			<?php Helper::options()->JCustomCSS(); ?>
		</style>
		<!-- 自定义CSS样式 -->

		<script>
			window.JoeConfig = {
				uploadAPI: `<?php Helper::security()->index('/action/upload'); ?>`,
				emojiAPI: `<?php Helper::options()->themeUrl('assets/typecho/write/json/emoji.json') ?>`,
				expressionAPI: `<?php Helper::options()->themeUrl('assets/json/joe.owo.json') ?>`,
				characterAPI: `<?php Helper::options()->themeUrl('assets/typecho/write/json/character.json') ?>`,
				playerAPI: `<?php empty(Helper::options()->JCustomPlayer) ? 'false' : Helper::options()->JCustomPlayer; ?>`,
				autoSave: <?php Helper::options()->autoSave(); ?>,
				themeURL: `<?php Helper::options()->themeUrl(); ?>`,
				JPrismTheme: `<?= Helper::options()->JPrismTheme ?>`,
				canPreview: false
			}
			window.Joe = window.Joe || {};
			window.Joe.BASE_API = `<?= joe\root_relative_link(joe\index('joe/api')) ?>`;
			window.Joe.CDN_URL = `<?= joe\cdn() ?>`;
			window.Joe.THEME_URL = `<?= joe\theme_url('', false) ?>`;
		</script>

		<script src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/tooltip.js', false); ?>"></script>
		<script src="<?= joe\theme_url('assets/plugin/layer/3.7.0/layer.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/parse/parse.min.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/dist/CodeMirror.js', false) ?>"></script>
		<script src="<?= joe\theme_url('assets/js/joe.function.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/tools.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/actions.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/create.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/js/index.js') ?>"></script>
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
				Typecho\Widget::widget('Widget_Metas_Tag_Cloud')->to($tags);
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

	public static function visibility()
	{
	?>
		<script>
			document.addEventListener('DOMContentLoaded', () => {
				$('select[name=visibility]').append(`<option value="private">私密</option>`);
			})
		</script>
<?php
	}
}

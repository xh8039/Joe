<?php

require_once("phpmailer.php");
require_once("smtp.php");

/* 加强评论拦截功能 */
Typecho_Plugin::factory('Widget_Feedback')->comment = array('Intercept', 'message');
class Intercept
{
	public static function message($comment)
	{
		/* 用户输入内容画图模式 */
		if (preg_match('/\{!\{(.*)\}!\}/', $comment['text'], $matches)) {
			/* 如果判断是否有双引号，如果有双引号，则禁止评论 */
			if (strpos($matches[1], '"') !== false || _checkXSS($matches[1])) {
				$comment['status'] = 'waiting';
			}
			/* 普通评论 */
		} else {
			/* 判断用户输入是否大于字符 */
			if (Helper::options()->JTextLimit && strlen($comment['text']) > Helper::options()->JTextLimit) {
				$comment['status'] = 'waiting';
			} else {
				/* 判断评论内容是否包含敏感词 */
				if (Helper::options()->JSensitiveWords) {
					if (joe\checkSensitiveWords(Helper::options()->JSensitiveWords, $comment['text'])) {
						$comment['status'] = 'waiting';
					}
				}
				/* 判断评论是否至少包含一个中文 */
				if (Helper::options()->JLimitOneChinese === "on") {
					if (preg_match("/[\x{4e00}-\x{9fa5}]/u", $comment['text']) == 0) {
						$comment['status'] = 'waiting';
					}
				}
			}
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
	Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('Email', 'send');
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
		$text = preg_replace_callback(
			'/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血|狗头)\s*\)/is',
			function ($match) {
				return '<img style="max-height: 22px;" src="' . Helper::options()->themeUrl . '/assets/owo/paopao/' . str_replace('%', '', urlencode($match[1])) . '_2x.png"/>';
			},
			$text
		);
		$text = preg_replace_callback(
			'/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
			function ($match) {
				return '<img style="max-height: 22px;" src="' . Helper::options()->themeUrl . '/assets/owo/aru/' . str_replace('%', '', urlencode($match[1])) . '_2x.png"/>';
			},
			$text
		);
		$text = preg_replace('/\{!\{([^\"]*)\}!\}/', '<img style="max-width: 100%;vertical-align: middle;" src="$1"/>', $text);
		$html = '
			<style>.Joe{width:550px;margin:0 auto;border-radius:8px;overflow:hidden;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.Joe_title{color:#fff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400% 400%;background-position:50% 100%;padding:15px;font-size:15px;line-height:1.5}</style>
			<div class="Joe"><div class="Joe_title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;"><div style="margin-bottom: 20px;line-height: 1.5;">{subtitle}</div><div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div>
		';
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
		<link rel="stylesheet" href="<?= joe\cdn('aplayer/1.10.1/APlayer.min.css') ?>">
		<link rel="stylesheet" href="<?= joe\theme_url('assets/plugin/prism/prism-onedark.min.css') ?>">
		<link rel="stylesheet" href="<?= joe\theme_url('assets/typecho/write/css/joe.write.min.css') ?>">
		<script>
			window.JoeConfig = {
				uploadAPI: '<?php Helper::security()->index('/action/upload'); ?>',
				emojiAPI: '<?= joe\theme_url('assets/typecho/write/json/emoji.json') ?>',
				expressionAPI: '<?= joe\theme_url('assets/typecho/write/json/expression.json') ?>',
				characterAPI: '<?= joe\theme_url('assets/typecho/write/json/character.json') ?>',
				playerAPI: '<?php Helper::options()->JCustomPlayer ? Helper::options()->JCustomPlayer() : Helper::options()->themeUrl('module/player.php?url=') ?>',
				autoSave: <?php Helper::options()->autoSave(); ?>,
				themeURL: '<?php Helper::options()->themeUrl(); ?>',
				canPreview: false
			}
		</script>
		<script src="<?= joe\cdn('aplayer/1.10.1/APlayer.min.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/plugin/prism/prism.min.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/parse/parse.min.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/typecho/write/dist/index.bundle.min.js') ?>"></script>
		<script src="<?= joe\theme_url('assets/js/joe.short.js') ?>"></script>
		<script>
			function EditorAutoStorage(form) {
				if (!form) return;

				function getCurrentTime() {
					const now = new Date();
					const year = now.getFullYear();
					const month = ("0" + (now.getMonth() + 1)).slice(-2); // 月份从 0 开始，需要加 1
					const day = ("0" + now.getDate()).slice(-2);
					const hours = ("0" + now.getHours()).slice(-2);
					const minutes = ("0" + now.getMinutes()).slice(-2);
					const seconds = ("0" + now.getSeconds()).slice(-2);
					return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
				}

				// 从本地存储加载数据并填充到表单的函数
				function loadFormData() {
					const formData = localStorage.getItem('form-data');
					if (formData) {
						const data = JSON.parse(formData);

						formSlug = document.getElementById('slug').value;

						console.log(formSlug);
						console.log(data.slug != formSlug);

						if (data.slug != formSlug) {
							return;
						}

						alert('检测到您于 ' + data.time + ' 有自动存储的未发布文章 [' + data.title + '] 已自动为您恢复');

						// 遍历 data 对象并填充表单元素
						for (const key in data) {
							if (data.hasOwnProperty(key)) {
								const escapedName = key.replace(/\[/g, "\[");
								console.log(escapedName);
								const element = document.querySelector(`[name="${escapedName}"]`);
								if (element) element.value = data[key];
							}
						}

						// 特别处理 CodeMirror 内容
						window.CodeMirrorEditor.dispatch({
							changes: {
								from: 0,
								to: window.CodeMirrorEditor.state.doc.length,
								insert: data.text
							}
						});
					}
				}

				// 保存表单数据的函数
				function saveFormData() {
					var formData = new FormData(form);
					var data = {
						title: formData.get('title'), // 使用 formData.get 来获取字段值
						slug: formData.get('slug'),
						text: window.CodeMirrorEditor.state.doc.toString(),
						'fields[mode]': formData.get('fields[mode]'),
						'fields[keywords]': formData.get('fields[keywords]'),
						'fields[description]': formData.get('fields[description]'),
						'fields[abstract]': formData.get('fields[abstract]'),
						'fields[thumb]': formData.get('fields[thumb]'),
						'fields[video]': formData.get('fields[video]'),
						'time': getCurrentTime()
					};
					localStorage.setItem('form-data', JSON.stringify(data));
				}

				// 获取文章内容的元素
				const contentElement = form; // 假设文章内容的元素是 `textarea`，并且有 `name="text"` 属性

				// 监听内容改变事件
				contentElement.addEventListener('input', saveFormData);

				// 监听剪切事件
				contentElement.addEventListener('cut', saveFormData);

				// 监听键盘事件
				contentElement.addEventListener('keydown', (event) => {
					saveFormData();
				});

				form.onsubmit = function(event) {
					// 阻止表单提交
					event.preventDefault();
					document.getElementById('text').value = window.CodeMirrorEditor.state.doc.toString();
					// 删除自动保存的本地存储数据
					localStorage.removeItem('form-data');
					// 手动触发表单提交
					form.submit();
				};

				// 页面加载时加载本地存储数据
				window.addEventListener('load', loadFormData);
			}
			EditorAutoStorage(document.querySelector('[name="write_post"]'));
			EditorAutoStorage(document.querySelector('[name="write_page"]'));
		</script>
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

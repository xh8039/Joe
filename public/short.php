<?php

function _parseContent($post, $login)
{
	$content = $post->content;
	$content = _parseReply($content);

	// 跑马灯
	if (strpos($content, '{lamp/}') !== false) {
		$content = strtr($content, array(
			"{lamp/}" => '<span class="joe_lamp"></span>',
		));
	}

	// 任务
	if (strpos($content, '{x}') !== false || strpos($content, '{ }') !== false) {
		$content = strtr($content, array(
			// 任务已完成
			"{x}" => '<input type="checkbox" class="joe_checkbox" checked disabled></input>',
			// 任务未完成
			"{ }" => '<input type="checkbox" class="joe_checkbox" disabled></input>'
		));
	}

	// 网易云音乐
	if (strpos($content, '{music') !== false) {
		// 网易云歌单
		$content = preg_replace('/{music-list([^}]*)\/}/SU', '<joe-mlist $1></joe-mlist>', $content);
		// 网易云单首
		$content = preg_replace('/{music([^}]*)\/}/SU', '<joe-music $1></joe-music>', $content);
	}

	// 音乐标签
	if (strpos($content, '{mp3') !== false) {
		$content = preg_replace('/{mp3([^}]*)\/}/SU', '<joe-mp3 $1></joe-mp3>', $content);
	}

	// 哔哩哔哩视频
	if (strpos($content, '{bilibili') !== false) {
		$content = preg_replace('/{bilibili([^}]*)\/}/SU', '<joe-bilibili $1></joe-bilibili>', $content);
	}

	// 视频
	if (strpos($content, '{dplayer') !== false) {
		$player = empty(Helper::options()->JCustomPlayer) ? 'false' : Helper::options()->JCustomPlayer;
		$content = preg_replace('/{dplayer([^}]*)\/}/SU', '<joe-dplayer player="' . $player . '" $1></joe-dplayer>', $content);
	}

	// 居中标题标签
	if (strpos($content, '{mtitle') !== false) {
		$content = preg_replace('/{mtitle([^}]*)\/}/SU', '<joe-mtitle $1></joe-mtitle>', $content);
	}

	// 多彩按钮
	if (strpos($content, '{abtn') !== false) {
		$content = preg_replace('/{abtn([^}]*)\/}/SU', '<joe-abtn $1></joe-abtn>', $content);
	}

	// 云盘下载
	if (strpos($content, '{cloud') !== false) {
		$content = preg_replace('/{cloud([^}]*)\/}/SU', '<joe-cloud $1></joe-cloud>', $content);
	}

	// 便条按钮
	if (strpos($content, '{anote') !== false) {
		$content = preg_replace('/{anote([^}]*)\/}/SU', '<joe-anote $1></joe-anote>', $content);
	}

	// 彩色虚线
	if (strpos($content, '{dotted') !== false) {
		$content = preg_replace('/{dotted([^}]*)\/}/SU', '<joe-dotted $1></joe-dotted>', $content);
	}

	if (strpos($content, '{message') !== false) {
		$content = preg_replace('/{message([^}]*)\/}/SU', '<joe-message $1></joe-message>', $content);
	}

	if (strpos($content, '{progress') !== false) {
		$content = preg_replace('/{progress([^}]*)\/}/SU', '<joe-progress $1></joe-progress>', $content);
	}

	if (strpos($content, '{hide') !== false) {
		if ($post->fields->hide == 'pay') {
			$db = Typecho_Db::get();
			$pay = $db->fetchRow($db->select()->from('table.joe_pay')->where('user_id = ?', USER_ID)->where('status = ?', '1')->where('content_cid = ?', $post->cid)->limit(1));
			// '<a rel="nofollow" target="_blank" href="https://bri6.cn/user/order" class="">' . $pay['trade_no'] . '</a>';
			if (!empty($pay)) {
				$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
				$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
				$content = _payPurchased($post, $pay) . $content;
			} else {
				if ($post->fields->price > 0) {
					$pay_box_position = _payBox($post);
				} else {
					if ($login) {
						$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $GLOBALS['JOE_USER']->mail)->limit(1);
					} else {
						$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $post->remember('mail', true))->limit(1);
					}
					$hasComment = $db->fetchRow($comment_sql);
					if (!empty($hasComment)) {
						$pay_box_position = '
						<div class="pay-box zib-widget" id="posts-pay">
							<div class="box-body relative">
								<div>
									<span class="badg c-red hollow badg-sm mr6"><i class="fa fa-download mr3"></i>免费资源</span>
									<b>' . $post->title . '</b>
								</div>
								<div class="mt10">
									<a href="window.Joe.scrollTo(\'joe-cloud\')" class="but jb-blue padding-lg btn-block"><i class="fa fa-download fa-fw" aria-hidden="true"></i>资源下载</a>
								</div>
							</div>
						</div>';
						$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
						$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
					} else {
						$pay_box_position = _payFreeResources($post);
					}
				}
				if ($post->fields->pay_box_position == 'top' && !joe\detectSpider()) {
					$content = $pay_box_position . $content;
				}
				if ($post->fields->pay_box_position == 'bottom' && !joe\detectSpider()) {
					$content = $content . $pay_box_position;
				}
			}
		} else if ($post->fields->hide == 'login' && $login) {
			$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
			$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
		} else {
			$db = Typecho_Db::get();
			if ($login) {
				$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $GLOBALS['JOE_USER']->mail)->limit(1);
			} else {
				$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $post->remember('mail', true))->limit(1);
			}
			$hasComment = $db->fetchRow($comment_sql);
			if (!empty($hasComment)) {
				$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
				$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
			}
		}
		$content = preg_replace('/{hide[^}]*}([\s\S]*?){\/hide}/', '<joe-hide></joe-hide>', $content);
	}
	if (strpos($content, '{card-default') !== false) {
		$content = preg_replace('/{card-default([^}]*)}([\s\S]*?){\/card-default}/', '<section style="margin-bottom: 15px"><joe-card-default $1><span class="_temp" style="display: none">$2</span></joe-card-default></section>', $content);
	}
	if (strpos($content, '{callout') !== false) {
		$content = preg_replace('/{callout([^}]*)}([\s\S]*?){\/callout}/', '<section style="margin-bottom: 15px"><joe-callout $1><span class="_temp" style="display: none">$2</span></joe-callout></section>', $content);
	}
	if (strpos($content, '{alert') !== false) {
		$content = preg_replace('/{alert([^}]*)}([\s\S]*?){\/alert}/', '<section style="margin-bottom: 15px"><joe-alert $1><span class="_temp" style="display: none">$2</span></joe-alert></section>', $content);
	}
	if (strpos($content, '{card-describe') !== false) {
		$content = preg_replace('/{card-describe([^}]*)}([\s\S]*?){\/card-describe}/', '<section style="margin-bottom: 15px"><joe-card-describe $1><span class="_temp" style="display: none">$2</span></joe-card-describe></section>', $content);
	}
	if (strpos($content, '{tabs') !== false) {
		$content = preg_replace('/{tabs}([\s\S]*?){\/tabs}/', '<section style="margin-bottom: 15px"><joe-tabs><span class="_temp" style="display: none">$1</span></joe-tabs></section>', $content);
	}
	if (strpos($content, '{card-list') !== false) {
		$content = preg_replace('/{card-list}([\s\S]*?){\/card-list}/', '<section style="margin-bottom: 15px"><joe-card-list><span class="_temp" style="display: none">$1</span></joe-card-list></section>', $content);
	}
	if (strpos($content, '{timeline') !== false) {
		$content = preg_replace('/{timeline}([\s\S]*?){\/timeline}/', '<section style="margin-bottom: 15px"><joe-timeline><span class="_temp" style="display: none">$1</span></joe-timeline></section>', $content);
	}
	if (strpos($content, '{collapse') !== false) {
		$content = preg_replace('/{collapse}([\s\S]*?){\/collapse}/', '<section style="margin-bottom: 15px"><joe-collapse><span class="_temp" style="display: none">$1</span></joe-collapse></section>', $content);
	}
	if (strpos($content, '{gird') !== false) {
		$content = preg_replace('/{gird([^}]*)}([\s\S]*?){\/gird}/', '<section style="margin-bottom: 15px"><joe-gird $1><span class="_temp" style="display: none">$2</span></joe-gird></section>', $content);
	}
	if (strpos($content, '{copy') !== false) {
		$content = preg_replace('/{copy([^}]*)\/}/SU', '<joe-copy $1></joe-copy>', $content);
	}

	// img图片引入时不携带referrer信息
	if (strpos($content, '<img src="') !== false) {
		$content = str_replace('<img src="', '<img referrerPolicy="no-referrer" rel="noreferrer" src="', $content);
	}

	// 告诉搜索引擎不将这个链接的权重传递给目标页面
	if (strpos($content, '<a href="') !== false && strpos($content, '<a href="javascript:') === false) {
		$content = str_replace('<a href="', '<a target="_blank" rel="noopener nofollow" href="', $content);
	}

	// 代码显示行号
	if (strpos($content, '<pre>') !== false) {
		$content = str_replace('<pre>', '<pre class="line-numbers">', $content);
	}
	// shell 已经更名为 powershell
	if (strpos($content, '<code class="lang-shell">') !== false) {
		$content = str_replace('<code class="lang-shell">', '<code class="lang-powershell">', $content);
	}

	echo $content;
}

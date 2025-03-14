<?php

use think\facade\Db;

require_once JOE_ROOT . 'public/short.php';

/* 过滤短代码 */
function _checkXSS($text)
{
	$isXss = false;
	$list = array(
		'/onabort/is',
		'/onblur/is',
		'/onchange/is',
		'/onclick/is',
		'/ondblclick/is',
		'/onerror/is',
		'/onfocus/is',
		'/onkeydown/is',
		'/onkeypress/is',
		'/onkeyup/is',
		'/onload/is',
		'/onmousedown/is',
		'/onmousemove/is',
		'/onmouseout/is',
		'/onmouseover/is',
		'/onmouseup/is',
		'/onreset/is',
		'/onresize/is',
		'/onselect/is',
		'/onsubmit/is',
		'/onunload/is',
		'/eval\(/is',
		'/ascript:/is',
		'/style=/is',
		'/width=/is',
		'/width:/is',
		'/height=/is',
		'/height:/is',
		'/src=/is',
	);
	if (strip_tags($text)) {
		for ($i = 0; $i < count($list); $i++) {
			if (preg_match($list[$i], $text) > 0) {
				$isXss = true;
				break;
			}
		}
	} else {
		$isXss = true;
	};
	return $isXss;
}

/* 过滤评论回复 */
function _parseCommentReply($text)
{
	if (_checkXSS($text)) {
		echo "该回复疑似异常，已被系统拦截！";
	} else {
		$text = _parseReply($text);
		if (\Typecho\Request::getInstance()->getHeader('x-requested-with')) {
			echo preg_replace('/\{!\{([^\"]*)\}!\}/', '<img referrerpolicy="no-referrer" rel="noreferrer" class="draw_image" src="$1" alt="画图"/>', $text);
		} else {
			echo preg_replace('/\{!\{([^\"]*)\}!\}/', '<img referrerpolicy="no-referrer" rel="noreferrer" class="lazyload draw_image" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="$1" alt="画图"/>', $text);
		}
	}
}

/* 过滤表情 */
function _parseReply($text)
{
	if (strpos($text, '表情]::(') === false) return $text;

	static $emoticon = null;

	if (is_null($emoticon)) {
		$emoticon = json_decode(file_get_contents(JOE_ROOT . 'assets/json/joe.owo.json'), true);
		unset($emoticon['颜文字']);
		unset($emoticon['emoji表情']);
	}

	$emoticon_text_list = [];
	$emoticon_icon_list = [];
	foreach ($emoticon as $emoticon_list) {
		foreach ($emoticon_list as $value) {
			if (strpos($text, $value['text']) !== false) {
				$emoticon_text_list[] = $value['text'];
				if (\Typecho\Request::getInstance()->getHeader('x-requested-with')) {
					$emoticon_icon_list[] = '<img height="22px" referrerpolicy="no-referrer" rel="noreferrer" class="owo_image" src="' . Helper::options()->themeUrl . '/' . $value['icon'] . '" alt="' . $value['text'] . '"/>';
				} else {
					$emoticon_icon_list[] = '<img referrerpolicy="no-referrer" rel="noreferrer" class="owo_image lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="' . joe\root_relative_link(Helper::options()->themeUrl) . '/' . $value['icon'] . '" alt="' . $value['text'] . '"/>';
				}
			}
		}
	}

	$text = str_replace($emoticon_text_list, $emoticon_icon_list, $text);
	return $text;
}

/* 格式化留言回复 */
function _parseLeavingReply($text)
{
	if (_checkXSS($text)) {
		echo "该回复疑似异常，已被系统拦截！";
	} else {
		$text = strip_tags($text);
		$text = _parseReply($text);
		echo preg_replace('/\{!\{([^\"]*)\}!\}/', '<img class="draw_image" src="$1" alt="画图"/>', $text);
	}
}

/* 格式化侧边栏回复 */
function _parseAsideReply($text, $type = true)
{
	if (_checkXSS($text)) {
		echo "该回复疑似异常，已被系统拦截！";
	} else {
		$text = strip_tags($text);
		$text = preg_replace('/\{!\{([^\"]*)\}!\}/', '# 图片回复', $text);
		if ($type) echo _parseReply($text);
		else echo $text;
	}
}

/* 过滤侧边栏最新回复的跳转链接 */
function _parseAsideLink($link)
{
	return str_replace(["#", '/comment-page-1'], ['?scroll=', ''], $link);
}

function _payPurchased($post, $pay)
{
	$count = Db::name('orders')->where(['content_cid' => $post->cid, 'status' => 1])->count();
	return '
	<div class="zib-widget pay-box paid-box" id="posts-pay">
		<div class="flex ac jb-green padding-10 em09">
			<div class="text-center flex-auto">
				<div class="mb6">
					<i class="fa fa-shopping-bag fa-2x" aria-hidden="true"></i>
				</div>
				<b class="em12">已购买</b>
			</div>
			<div class="em09 paid-info flex-auto">
				<div class="flex jsb">
					<span>订单号</span>
					<span>' . $pay['trade_no'] . '</span>
				</div>
				<div class="flex jsb">
					<span>支付时间</span>
					<span>' . $pay['update_time'] . '</span>
				</div>
				<div class="flex jsb">
					<span>支付金额</span>
					<span>
						<span class="pay-mark">￥</span>' . $pay['pay_price'] . '
					</span>
				</div>
			</div>
		</div>
		<div class="box-body relative">
			<badge class="img-badge hot jb-blue px12">已售 ' . $count . '</badge>
			<div style="padding-right: 48px;">
				<span class="badg c-red hollow badg-sm mr6">
					<i class="fa fa-book mr3"></i>
					付费阅读
				</span>
				<b>' . $post->title . '</b>
			</div>
		</div>
	</div>
	';
}

function _payFreeResources($post, $comment = false)
{
	if (!empty($comment)) {
		return '
		<div class="pay-box zib-widget paid-box" id="posts-pay">
			<div class="box-body relative">
				<div>
					<span class="badg c-red hollow badg-sm mr6"><i class="fa fa-download mr3"></i>免费资源</span>
					<b>' . $post->title . '</b>
				</div>
				<div class="mt10">
					<a href="javascript:window.Joe.scrollTo(\'joe-cloud\');" class="but jb-blue padding-lg btn-block"><i class="fa fa-download fa-fw" aria-hidden="true"></i>资源下载</a>
				</div>
			</div>
		</div>';
	}
	$login_comment = (!is_numeric(USER_ID) && Helper::options()->JcommentLogin == 'on') ? true : false;
	return '
	<div class="zib-widget pay-box" id="posts-pay">
		<div class="flex pay-flexbox">
			<div class="flex0 relative mr20 hide-sm pay-thumb">
				<div class="graphic">
					<img src="' . joe\getLazyload() . '" data-src="' . joe\getThumbnails($post)[0] . '" alt="' . $post->title . ' - ' . Helper::options()->title . '" onerror="Joe.thumbnailError(this)" class="lazyload fit-cover" fancybox="false">
					<div class="abs-center text-center left-bottom"></div>
				</div>
			</div>
			<div class="flex-auto-h flex xx jsb">
				<dt class="text-ellipsis pay-title" style="padding-right: 48px;">' . $post->title . '</dt>
				<div class="mt6 em09 muted-2-color">此内容为免费资源，请评论后查看</div>
				<div class="price-box">
					<div class="price-box">
						<div class="c-red">
							<b class="em3x">
								<span class="pay-mark">￥</span>' . round(($post->fields->price ? $post->fields->price : 0), 2) . '
							</b>
						</div>
					</div>
				</div>
				<div class="text-right mt10">
					<div class="">
						' . ($login_comment ? '<a href="javascript:document.querySelector(\'.header-login\').click();" class="but padding-lg btn-block jb-blue joe_scan_light"><i class="fa fa-sign-in"></i> 登录评论</a>' : '<a href="javascript:window.Joe.scrollTo(\'.joe_comment\');" class="but padding-lg btn-block jb-blue joe_scan_light"><i class="fa fa-comment"></i> 评论查看</a>') . '
					</div>
					' . ($login_comment ? '<div class="pay-extra-hide px12 mt6" style="font-size:12px;">您当前未登录！请登陆后再进行评论</div>' : '') . '
				</div>
			</div>
		</div>
		<div class="pay-tag abs-center"><i class="fa fa-download mr3"></i>免费资源</div>
		<badge class="img-badge hot jb-blue px12">已评论 ' . $post->commentsNum . '</badge>
	</div>
	';
}

function _payBox($post)
{
	$count = Db::name('orders')->where(['content_cid' => $post->cid, 'status' => 1])->count();
	return '
	<div class="zib-widget pay-box" id="posts-pay">
		<div class="flex pay-flexbox">
			<div class="flex0 relative mr20 hide-sm pay-thumb">
				<div class="graphic">
					<img src="' . joe\getLazyload() . '" data-src="' . joe\getThumbnails($post)[0] . '" alt="' . $post->title . ' - ' . Helper::options()->title . '" onerror="Joe.thumbnailError(this)" class="lazyload fit-cover" fancybox="false">
					<div class="abs-center text-center left-bottom"></div>
				</div>
			</div>
			<div class="flex-auto-h flex xx jsb">
				<dt class="text-ellipsis pay-title" style="padding-right: 48px;">' . $post->title . '</dt>
				<div class="mt6 em09 muted-2-color">此内容为付费阅读，请付费后查看</div>
				<div class="price-box">
					<div class="price-box">
						<div class="c-red">
							<b class="em3x">
								<span class="pay-mark">￥</span>' . round(($post->fields->price ? $post->fields->price : 0), 2) . '
							</b>
						</div>
					</div>
				</div>
				<div class="text-right mt10">
					<a data-class="modal-mini" mobile-bottom="true" data-height="300" data-remote="' . joe\root_relative_link(Helper::options()->index . '/') . 'joe/api/pay_cashier_modal?cid=' . $post->cid . '" class="cashier-link but jb-red joe_scan_light" href="javascript:;" data-toggle="RefreshModal">立即购买</a>
					' . (is_numeric(USER_ID) ? '' : '<div class="pay-extra-hide px12 mt6" style="font-size:12px;">您当前未登录！建议登陆后购买，可保存购买订单</div>') . '
				</div>
			</div>
		</div>
		<div class="pay-tag abs-center"><i class="fa fa-book mr3"></i>付费阅读</div>
		<badge class="img-badge hot jb-blue px12">已售 ' . $count . '</badge>
	</div>
	';
}

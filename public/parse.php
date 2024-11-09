<?php

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
		'/eval/is',
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
		echo preg_replace('/\{!\{([^\"]*)\}!\}/', '<img class="lazyload draw_image" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="$1" alt="画图"/>', $text);
	}
}

/* 过滤表情 */
function _parseReply($text)
{

	$text = preg_replace_callback(
		'/\:\#\(\s*(doge|亲亲|偷笑|再见|发怒|发财|可爱|吐血|呆|呕吐|困|坏笑|大佬|大哭|委屈|害羞|尴尬|微笑|思考|惊吓|打脸|抓狂|抠鼻子|斜眼笑|无奈|晕|流汗|流鼻血|点赞|生气|生病|疑问|白眼|睡着|笑哭|腼腆|色|调皮|鄙视|闭嘴|难过|馋|黑人问号|鼓掌)\s*\)/is',
		function ($match) {
			return '<img class="owo_image lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="' . Helper::options()->themeUrl . '/assets/images/owo/bilibili/' . str_replace('%', '', urlencode($match[1])) . '.gif" alt="小电视"/>';
		},
		$text
	);
	$text = preg_replace_callback(
		'/\:\$\(\s*(爱你|爱心|傲慢|白眼|棒棒糖|爆筋|抱拳|鄙视|闭嘴|擦汗|菜刀|吃|呲牙|大兵|大哭|蛋|得意|doge|发呆|发怒|奋斗|尴尬|勾引|鼓掌|害羞|憨笑|好棒|哈欠|喝彩|河蟹|坏笑|饥饿|惊恐|惊喜|惊讶|菊花|可爱|可怜|抠鼻|酷|快哭了|骷髅|困|篮球|泪奔|冷汗|流汗|流泪|难过|OK|喷血|撇嘴|啤酒|强|敲打|亲亲|糗大了|拳头|骚扰|色|胜利|手枪|衰|睡|调皮|偷笑|吐|托腮|委屈|微笑|握手|我最美|无奈|吓|小纠结|笑哭|小样儿|斜眼笑|西瓜|嘘|羊驼|阴险|疑问|右哼哼|幽灵|晕|再见|眨眼睛|折磨|咒骂|抓狂|左哼哼)\s*\)/is',
		function ($match) {
			return '<img class="owo_image lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="' . Helper::options()->themeUrl . '/assets/images/owo/QQ/' . str_replace('%', '', urlencode($match[1])) . '.gif" alt="QQ"/>';
		},
		$text
	);
	$text = preg_replace_callback(
		'/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血|狗头)\s*\)/is',
		function ($match) {
			return '<img class="owo_image lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="' . Helper::options()->themeUrl . '/assets/images/owo/paopao/' . str_replace('%', '', urlencode($match[1])) . '_2x.png" alt="表情"/>';
		},
		$text
	);
	$text = preg_replace_callback(
		'/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
		function ($match) {
			return '<img class="owo_image lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="' . Helper::options()->themeUrl . '/assets/images/owo/aru/' . str_replace('%', '', urlencode($match[1])) . '_2x.png" alt="表情"/>';
		},
		$text
	);
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
	echo str_replace("#", "?scroll=", $link);
}

function _payPurchased($post, $pay)
{
	$db = Typecho_Db::get();
	$count = $db->fetchRow($db->select('COUNT(*) AS count')->from('table.joe_pay')->where('status = ?', '1')->where('content_cid = ?', $post->cid))['count'];
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

function _payFreeResources($post)
{
	$login_comment = (!is_numeric(USER_ID) && Helper::options()->JcommentLogin == 'on') ? true : false;
	return '
	<div class="zib-widget pay-box" id="posts-pay">
		<div class="flex pay-flexbox">
			<div class="flex0 relative mr20 hide-sm pay-thumb">
				<div class="graphic">
					<img src="' . joe\getLazyload(false) . '" data-src="' . joe\getThumbnails($post)[0] . '" alt="' . $post->title . ' - ' . Helper::options()->title . '" class="lazyload fit-cover error-thumbnail" fancybox="false">
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
								<span class="pay-mark">￥</span>' . round($post->fields->price, 2) . '
							</b>
						</div>
					</div>
				</div>
				<div class="text-right mt10">
					<div class="">
						' . ($login_comment ? '<a href="javascript:document.querySelector(\'.header-login\').click();" class="but padding-lg btn-block jb-blue"><i class="fa fa-sign-in"></i> 登录评论</a>' : '<a href="javascript:window.Joe.scrollTo(\'.joe_comment\');" class="but padding-lg btn-block jb-blue"><i class="fa fa-comment"></i> 评论查看</a>') . '
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
	$db = Typecho_Db::get();
	$count = $db->fetchRow($db->select('COUNT(*) AS count')->from('table.joe_pay')->where('status = ?', '1')->where('content_cid = ?', $post->cid))['count'];
	return '
	<div class="zib-widget pay-box" id="posts-pay">
		<div class="flex pay-flexbox">
			<div class="flex0 relative mr20 hide-sm pay-thumb">
				<div class="graphic">
					<img src="' . joe\getLazyload(false) . '" data-src="' . joe\getThumbnails($post)[0] . '" alt="' . $post->title . ' - ' . Helper::options()->title . '" class="lazyload fit-cover error-thumbnail" fancybox="false">
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
								<span class="pay-mark">￥</span>' . round($post->fields->price, 2) . '
							</b>
						</div>
					</div>
				</div>
				<div class="text-right mt10">
					<a data-class="modal-mini" mobile-bottom="true" data-height="300" data-remote="' . Helper::options()->index . '/joe/api' . '?routeType=pay_cashier_modal&cid=' . $post->cid . '" class="cashier-link but jb-red joe_scan_light" href="javascript:;" data-toggle="RefreshModal">立即购买</a>
					' . (is_numeric(USER_ID) ? '' : '<div class="pay-extra-hide px12 mt6" style="font-size:12px;">您当前未登录！建议登陆后购买，可保存购买订单</div>') . '
				</div>
			</div>
		</div>
		<div class="pay-tag abs-center"><i class="fa fa-book mr3"></i>付费阅读</div>
		<badge class="img-badge hot jb-blue px12">已售 ' . $count . '</badge>
	</div>
	';
}

<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
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
		$player = Helper::options()->JCustomPlayer ? Helper::options()->JCustomPlayer : Helper::options()->themeUrl . '/module/player.php?url=';
		$content = preg_replace('/{dplayer([^}]*)\/}/SU', '<joe-dplayer cid="' . $post->cid . '" player="' . $player . '" $1></joe-dplayer>', $content);
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
		if ($post->fields->hide_type == 'pay') {
			$db = Typecho_Db::get();
			$pay = $db->fetchRow($db->select()->from('table.joe_pay')->where('user_id = ?', USER_ID)->where('status = ?', '1')->where('content_cid = ?', $post->cid)->limit(1));
			$count = $db->fetchRow($db->select('COUNT(*) AS count')->from('table.joe_pay')->where('status = ?', '1')->where('content_cid = ?', $post->cid))['count'];
			// '<a rel="nofollow" target="_blank" href="https://bri6.cn/user/order" class="">' . $pay['trade_no'] . '</a>';
			if (!empty($pay)) {
				$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
				$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));

				$content = '
				   <div class="pay-box zib-widget paid-box order-type-1" id="posts-pay">
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
				' . $content;
			} else {
				$content = '
				  <div class="zib-widget pay-box  order-type-1" id="posts-pay">
							<div class="flex pay-flexbox">
								<div class="flex0 relative mr20 hide-sm pay-thumb">
									<div class="graphic">
										<img src="' . joe\getLazyload(false) . '" data-src="' . joe\getThumbnails($post)[0] . '" alt="' . $post->title . ' - ' . Helper::options()->title . '" class="lazyload fit-cover" fancybox="false">
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
													<span class="pay-mark">￥</span>
													0.01
												</b>
											</div>
										</div>
									</div>
									<div class="text-right mt10">
										<a data-class="modal-mini" mobile-bottom="true" data-height="300" data-remote="' . JOE_BASE_API . '?routeType=pay_cashier_modal&cid=' . $post->cid . '" class="cashier-link but jb-red" href="javascript:;" data-toggle="RefreshModal">立即购买</a>
										<div class="pay-extra-hide px12 mt6" style="font-size:12px;">您当前未登录！建议登陆后购买，可保存购买订单</div>
									</div>
								</div>
							</div>
							<div class="pay-tag abs-center">
								<i class="fa fa-book mr3"></i>
								付费阅读
							</div>
							<badge class="img-badge hot jb-blue px12">已售 ' . $count . '</badge>
						</div>
				' . $content;
			}
		}
		if ($post->fields->hide_type == 'login') {
			if ($login) {
				$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
				$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
			}
		}
		// if ($post->fields->hide_type == 'comment') {
		// }
		$db = Typecho_Db::get();
		$hasComment = $db->fetchAll($db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $post->remember('mail', true))->limit(1));
		if ($hasComment) {
			$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
			$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
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
	if (strpos($content, '<a href="') !== false) {
		$content = str_replace('<a href="', '<a target="_blank" rel="noopener nofollow" href="', $content);
	}

	echo $content;
}

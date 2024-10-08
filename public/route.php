<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

use Metowolf\Meting;

/* 获取文章列表 已测试 √  */

function _getPost($self)
{
	$self->response->setStatus(200);
	$page = $self->request->page;
	$pageSize = $self->request->pageSize;
	$type = $self->request->type;

	/* sql注入校验 */
	if (!preg_match('/^\d+$/', $page)) {
		return $self->response->throwJson(array("data" => "非法请求！已屏蔽！"));
	}
	if (!preg_match('/^\d+$/', $pageSize)) {
		return $self->response->throwJson(array("data" => "非法请求！已屏蔽！"));
	}
	if (!preg_match('/^[created|views|commentsNum|agree]+$/', $type)) {
		return $self->response->throwJson(array("data" => "非法请求！已屏蔽！"));
	}

	/* 如果传入0，强制赋值1 */
	if ($page == 0) $page = 1;
	$result = [];
	/* 增加置顶文章功能，通过JS判断（如果你想添加其他标签的话，请先看置顶如何实现的） */
	$sticky_text = Helper::options()->JIndexSticky;
	if ($sticky_text && $page == 1) {
		$sticky_arr = explode("||", $sticky_text);
		foreach ($sticky_arr as $cid) {
			$self->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid)->to($item);
			if ($item->next()) {
				$result[] = array(
					"mode" => $item->fields->mode ? $item->fields->mode : 'default',
					"image" => joe\getThumbnails($item),
					"time" => date('Y-m-d', $item->created),
					"created" => date('Y年m月d日', $item->created),
					"title" => $item->title,
					"abstract" => joe\getAbstract($item, false),
					"category" => $item->categories,
					"views" => joe\getViews($item, false),
					"commentsNum" => number_format($item->commentsNum),
					"agree" => joe\getAgree($item, false),
					"permalink" => $item->permalink,
					"lazyload" => joe\getLazyload(false),
					"type" => "sticky",
					'target' => Helper::options()->Jessay_target,
				);
			}
		}
	}
	$self->widget('Widget_Contents_Sort', 'page=' . $page . '&pageSize=' . $pageSize . '&type=' . $type)->to($item);
	while ($item->next()) {
		$result[] = array(
			"mode" => $item->fields->mode ? $item->fields->mode : 'default',
			"image" => joe\getThumbnails($item),
			"time" => date('Y-m-d', $item->created),
			"created" => date('Y年m月d日', $item->created),
			"title" => $item->title,
			"abstract" => joe\getAbstract($item, false),
			"category" => $item->categories,
			"views" => number_format($item->views),
			"commentsNum" => number_format($item->commentsNum),
			"agree" => number_format($item->agree),
			"permalink" => $item->permalink,
			"lazyload" => joe\getLazyload(false),
			"type" => "normal",
			'target' => Helper::options()->Jessay_target,
		);
	};

	$self->response->throwJson(array("data" => $result));
}

// 百度统计展示
function _getstatistics($self)
{
	$statistics_config = joe\baidu_statistic_config();
	if (is_array($statistics_config)) {
	} else {
		$self->response->setStatus(200);
		$self->response->throwJson(array('access_token' => 'off'));
	}
	if (empty($statistics_config['access_token'])) {
		$self->response->setStatus(200);
		$self->response->throwJson(array('access_token' => 'off'));
	}
	// 获取站点列表
	$baidu_list = function () use ($statistics_config, $self) {
		$url = 'https://openapi.baidu.com/rest/2.0/tongji/config/getSiteList?access_token=' . trim($statistics_config['access_token']);
		$data = json_decode(file_get_contents($url), true);
		if (isset($data['error_code'])) {
			$self->response->setStatus(404);
			if ($data['error_code'] == 111) {
				$self->response->throwJson(['msg' => '请更新您的access_token']);
			}
			$self->response->throwJson($data);
		}
		return $data['list'];
	};
	// 获取站点详情
	$web_metrics = function ($list, $start_date, $end_date) use ($statistics_config) {
		$access_token = trim($statistics_config['access_token']);
		$site_id = $list['site_id'];
		$url = "https://openapi.baidu.com/rest/2.0/tongji/report/getData?access_token=$access_token&site_id=$site_id&method=trend/time/a&start_date=$start_date&end_date=$end_date&metrics=pv_count,ip_count&gran=day";
		$data = \network\http\post($url)->toArray();
		if (is_array($data)) {
			$data = $data['result']['sum'][0];
		} else {
			$data = 0;
		}
		return $data;
	};
	$domain = $_SERVER['HTTP_HOST'];
	$list = $baidu_list();
	for ($i = 0; $i < count($list); $i++) {
		if ($list[$i]['domain'] == $domain) {
			$list = $list[$i];
			break;
		}
	}
	if (!isset($list['domain']) || $list['domain'] != $domain) {
		$data = array(
			'msg' => '没有当前站点'
		);
		$self->response->setStatus(404);
		$self->response->throwJson($data);
	}
	$today = $web_metrics($list, date('Ymd'), date('Ymd'));
	$yesterday = $web_metrics($list, date('Ymd', strtotime("-1 days")), date('Ymd', strtotime("-1 days")));
	$moon = $web_metrics($list, date('Ym') . '01', date('Ymd'));
	$data = array(
		'today' => $today,
		'yesterday' => $yesterday,
		'month' => $moon
	);
	$self->response->setStatus(200);
	$self->response->throwJson($data);
}

/* 增加浏览量 已测试 √ */
function _handleViews($self)
{
	$self->response->setStatus(200);
	$cid = $self->request->cid;
	/* sql注入校验 */
	if (!preg_match('/^\d+$/',  $cid)) {
		return $self->response->throwJson(array("code" => 0, "data" => "非法请求！已屏蔽！"));
	}
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
	if (sizeof($row) > 0) {
		$db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
		$self->response->throwJson(array(
			"code" => 1,
			"data" => array('views' => number_format($db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid))['views']))
		));
	} else {
		$self->response->throwJson(array("code" => 0, "data" => null));
	}
}

/* 点赞和取消点赞 已测试 √ */
function _handleAgree($self)
{
	$self->response->setStatus(200);
	$cid = $self->request->cid;
	$type = $self->request->type;
	/* sql注入校验 */
	if (!preg_match('/^\d+$/',  $cid)) {
		return $self->response->throwJson(array("code" => 0, "data" => "非法请求！已屏蔽！"));
	}
	/* sql注入校验 */
	if (!preg_match('/^[agree|disagree]+$/', $type)) {
		return $self->response->throwJson(array("code" => 0, "data" => "非法请求！已屏蔽！"));
	}
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select('agree')->from('table.contents')->where('cid = ?', $cid));
	if (sizeof($row) > 0) {
		if ($type === "agree") {
			$db->query($db->update('table.contents')->rows(array('agree' => (int)$row['agree'] + 1))->where('cid = ?', $cid));
		} else {
			if (intval($row['agree']) - 1 >= 0) {
				$db->query($db->update('table.contents')->rows(array('agree' => (int)$row['agree'] - 1))->where('cid = ?', $cid));
			}
		}
		$self->response->throwJson(array(
			"code" => 1,
			"data" => array('agree' => number_format($db->fetchRow($db->select('agree')->from('table.contents')->where('cid = ?', $cid))['agree']))
		));
	} else {
		$self->response->throwJson(array("code" => 0, "data" => null));
	}
}

/* 查询是否收录 已测试 √ */
function _getRecord($self)
{
	$self->response->setStatus(200);
	$client = new \network\http\Client;
	$client->param([
		'url' => $self->request->site
	]);
	$output = $client->get('https://api.fish9.cn/api/baidu/')->toArray();
	if (is_array($output)) {
		if (isset($output['baidu']) && $output['baidu']) {
			$self->response->throwJson(array("data" => "已收录"));
		} else {
			$cid = $self->request->cid;
			/* sql注入校验 */
			if (!preg_match('/^\d+$/',  $cid)) {
				return $self->response->throwJson(array("code" => 0, "data" => "非法请求！已屏蔽！"));
			}
			$db = Typecho_Db::get();
			$sql = $db->select('str_value')->from('table.fields')->where('cid = ?', $cid)->where('name = ?', 'baidu_push');
			$row = $db->fetchRow($sql);
			if ($row && $row['str_value'] == 'yes') {
				$self->response->throwJson(["data" => "未收录，已推送"]);
			} else {
				$self->response->throwJson(array("data" => "未收录"));
			}
		}
	} else {
		$self->response->throwJson(array("data" => "检测失败"));
	}
}

/* 主动推送到百度收录 已测试 √ */
function _pushRecord($self)
{
	$self->response->setStatus(200);

	$cid = $self->request->cid;

	/* sql注入校验 */
	if (!preg_match('/^\d+$/',  $cid)) {
		return $self->response->throwJson(array("code" => 0, "data" => "非法请求！已屏蔽！"));
	}

	$db = Typecho_Db::get();
	$sql = $db->select('str_value')->from('table.fields')->where('cid = ?', $cid)->where('name = ?', 'baidu_push');
	$row = $db->fetchRow($sql);
	if ($row && $row['str_value'] == 'yes') {
		$self->response->throwJson(['already' => true]);
		return;
	}

	$token = Helper::options()->JBaiduToken;
	$domain = $self->request->domain;
	$url = $self->request->url;
	$urls = explode(",", $url);
	$api = "http://data.zz.baidu.com/urls?site={$domain}&token={$token}";
	$ch = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => implode("\n", $urls),
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($result, true);
	if (empty($result['error'])) {
		// 存储推送记录到文章或者页面的自定义字段里面
		$db = Typecho_Db::get();
		if (isset($row['str_value']) && $row['str_value'] != 'yes') {
			$db->query(
				$db->update('table.fields')
					->rows(['str_value' => 'yes'])
					->where('cid = ?', $cid)
					->where('name = ?', 'baidu_push')
			);
		} else {
			$db->query(
				$db
					->insert('table.fields')
					->rows(array(
						'cid' => $cid,
						'name' => 'baidu_push',
						'type' => 'str',
						'str_value' => 'yes',
						'int_value' => '0',
						'float_value' => '0',
					))
			);
		}
	}
	$self->response->throwJson(array(
		'domain' => $domain,
		'url' => $url,
		'data' => $result
	));
}

// 主动推送到必应收录
function _pushBing($self)
{
	$self->response->setStatus(200);
	$token = Helper::options()->JBingToken;
	if (empty($token)) {
		exit;
	}
	$domain = $self->request->domain;  //网站域名
	$url = $self->request->url;
	$urls = explode(",", $url);  //要推送的url
	$api = "https://www.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=$token";
	$data = array(
		'siteUrl' => $domain,
		'urlList' => $urls
	);
	$ch = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => array('Content-Type: application/json; charset=utf-8', 'Host: ssl.bing.com'),
	);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	curl_close($ch);
	$self->response->throwJson(array(
		'domain' => $domain,
		'url' => $url,
		'data' => json_decode($result, TRUE)
	));
}

/* 获取壁纸分类 已测试 √ */
function _getWallpaperType($self)
{
	$self->response->setStatus(200);
	$json = \network\http\get("http://cdn.apc.360.cn/index.php?c=WallPaper&a=getAllCategoriesV2&from=360chrome");
	$res = json_decode($json, TRUE);
	if ($res['errno'] == 0) {
		$self->response->throwJson([
			"code" => 1,
			"data" => $res['data']
		]);
	} else {
		$self->response->throwJson([
			"code" => 0,
			"data" => null
		]);
	}
}

/* 获取壁纸列表 已测试 √ */
function _getWallpaperList($self)
{
	$self->response->setStatus(200);

	$cid = $self->request->cid;
	$start = $self->request->start;
	$count = $self->request->count;
	$json = \network\http\get("http://wallpaper.apc.360.cn/index.php?c=WallPaper&a=getAppsByCategory&cid={$cid}&start={$start}&count={$count}&from=360chrome");
	$res = json_decode($json, TRUE);
	if ($res['errno'] == 0) {
		$self->response->throwJson([
			"code" => 1,
			"data" => $res['data'],
			"total" => $res['total']
		]);
	} else {
		$self->response->throwJson([
			"code" => 0,
			"data" => null
		]);
	}
}

/* 抓取苹果CMS视频分类 已测试 √ */
function _getMaccmsList($self)
{
	$self->response->setStatus(200);

	$cms_api = Helper::options()->JMaccmsAPI;
	$ac = $self->request->ac ? $self->request->ac : '';
	$ids = $self->request->ids ? $self->request->ids : '';
	$t = $self->request->t ? $self->request->t : '';
	$pg = $self->request->pg ? $self->request->pg : '';
	$wd = $self->request->wd ? $self->request->wd : '';
	if ($cms_api) {
		$json = \network\http\get("{$cms_api}?ac={$ac}&ids={$ids}&t={$t}&pg={$pg}&wd={$wd}");
		$res = json_decode($json, TRUE);
		if ($res['code'] === 1) {
			$self->response->throwJson([
				"code" => 1,
				"data" => $res,
			]);
		} else {
			$self->response->throwJson([
				"code" => 0,
				"data" => "抓取失败！请联系作者！"
			]);
		}
	} else {
		$self->response->throwJson([
			"code" => 0,
			"data" => "后台苹果CMS API未填写！"
		]);
	}
}

/* 获取虎牙视频列表 已测试 √ */
function _getHuyaList($self)
{
	$self->response->setStatus(200);

	$gameId = $self->request->gameId;
	$page = $self->request->page;
	$json = \network\http\get("https://www.huya.com/cache.php?m=LiveList&do=getLiveListByPage&gameId={$gameId}&tagAll=0&page={$page}");
	$res = json_decode($json, TRUE);
	if ($res['status'] === 200) {
		$self->response->throwJson([
			"code" => 1,
			"data" => $res['data'],
		]);
	} else {
		$self->response->throwJson([
			"code" => 0,
			"data" => "抓取失败！请联系作者！"
		]);
	}
}

/* 获取服务器状态 */
function _getServerStatus($self)
{
	$self->response->setStatus(200);

	$api_panel = Helper::options()->JBTPanel;
	$api_sk = Helper::options()->JBTKey;
	if (!$api_panel) return $self->response->throwJson([
		"code" => 0,
		"data" => "宝塔面板地址未填写！"
	]);
	if (!$api_sk) return $self->response->throwJson([
		"code" => 0,
		"data" => "宝塔接口密钥未填写！"
	]);
	$request_time = time();
	$request_token = md5($request_time . '' . md5($api_sk));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_panel . '/system?action=GetNetWork');
	curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3000);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,  array("request_time" => $request_time, "request_token" => $request_token));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response  = json_decode(curl_exec($ch), true);
	curl_close($ch);
	$self->response->throwJson(array(
		/* 状态 */
		"status" => $response ? true : false,
		/* 信息提示 */
		"message" => $response['msg'] ?? '',
		/* 上行流量KB */
		"up" => $response["up"] ? $response["up"] : 0,
		/* 下行流量KB */
		"down" => $response["down"] ? $response["down"] : 0,
		/* 总发送（字节数） */
		"upTotal" => $response["upTotal"] ? $response["upTotal"] : 0,
		/* 总接收（字节数） */
		"downTotal" => $response["downTotal"] ? $response["downTotal"] : 0,
		/* 内存占用 */
		"memory" => $response["mem"] ? $response["mem"] : ["memBuffers" => 0, "memCached" => 0, "memFree" => 0, "memRealUsed" => 0, "memTotal" => 0],
		/* CPU */
		"cpu" => $response["cpu"] ? $response["cpu"] : [0, 0, [0], 0, 0, 0],
		/* 系统负载 */
		"load" => $response["load"] ? $response["load"] : ["fifteen" => 0, "five" => 0, "limit" => 0, "max" => 0, "one" => 0, "safe" => 0],
	));
}

/* 获取最近评论 */
function _getCommentLately($self)
{
	$self->response->setStatus(200);

	$time = time();
	$num = 7;
	$categories = [];
	$series = [];
	$db = Typecho_Db::get();
	$prefix = $db->getPrefix();
	for ($i = ($num - 1); $i >= 0; $i--) {
		$date = date("Y/m/d", $time - ($i * 24 * 60 * 60));
		$sql = "SELECT coid FROM `{$prefix}comments` WHERE FROM_UNIXTIME(created, '%Y/%m/%d') = '{$date}' limit 100";
		$count = count($db->fetchAll($sql));
		$categories[] = $date;
		$series[] = $count;
	}
	$self->response->throwJson([
		"categories" => $categories,
		"series" => $series,
	]);
}

/* 获取文章归档 */
function _getArticleFiling($self)
{
	$self->response->setStatus(200);

	$page = $self->request->page;
	$pageSize = 8;
	if (!preg_match('/^\d+$/', $page)) return $self->response->throwJson(array("data" => "非法请求！已屏蔽！"));
	if ($page == 0) $page = 1;
	$offset = $pageSize * ($page - 1);
	$time = time();
	$db = Typecho_Db::get();
	$prefix = $db->getPrefix();
	$result = [];
	$sql_version = $db->fetchAll('select VERSION()')[0]['VERSION()'];
	if ($sql_version >= 8) {
		$sql = "SELECT FROM_UNIXTIME(created, '%Y 年 %m 月') as date FROM `{$prefix}contents` WHERE created < {$time} AND (password is NULL or password = '') AND status = 'publish' AND type = 'post' GROUP BY FROM_UNIXTIME(created, '%Y 年 %m 月') LIMIT {$pageSize} OFFSET {$offset}";
	} else {
		$sql = "SELECT FROM_UNIXTIME(created, '%Y 年 %m 月') as date FROM `{$prefix}contents` WHERE created < {$time} AND (password is NULL or password = '') AND status = 'publish' AND type = 'post' GROUP BY FROM_UNIXTIME(created, '%Y 年 %m 月') DESC LIMIT {$pageSize} OFFSET {$offset}";
	}
	$temp = $db->fetchAll($sql);
	$options = Typecho_Widget::widget('Widget_Options');
	foreach ($temp as $item) {
		$date = $item['date'];
		$list = [];
		$sql = "SELECT * FROM `{$prefix}contents` WHERE created < {$time} AND (password is NULL or password = '') AND status = 'publish' AND type = 'post' AND FROM_UNIXTIME(created, '%Y 年 %m 月') = '{$date}' ORDER BY created DESC LIMIT 100";
		$_list = $db->fetchAll($sql);
		foreach ($_list as $_item) {
			$type = $_item['type'];
			$_item['categories'] = $db->fetchAll($db->select()->from('table.metas')
				->join('table.relationships', 'table.relationships.mid = table.metas.mid')
				->where('table.relationships.cid = ?', $_item['cid'])
				->where('table.metas.type = ?', 'category')
				->order('table.metas.order', Typecho_Db::SORT_ASC));
			$_item['category'] = urlencode(current(Typecho_Common::arrayFlatten($_item['categories'], 'slug')));
			$_item['slug'] = urlencode($_item['slug']);
			$_item['date'] = new Typecho_Date($_item['created']);
			$_item['year'] = $_item['date']->year;
			$_item['month'] = $_item['date']->month;
			$_item['day'] = $_item['date']->day;
			$routeExists = (NULL != Typecho_Router::get($type));
			$_item['pathinfo'] = $routeExists ? Typecho_Router::url($type, $_item) : '#';
			$_item['permalink'] = Typecho_Common::url($_item['pathinfo'], $options->index);
			$list[] = array(
				"title" => date('m/d', $_item['created']) . '：' . $_item['title'],
				"permalink" => $_item['permalink'],
			);
		}
		$result[] = array("date" => $date, "list" => $list);
	}
	$self->response->throwJson($result);
}

// 提交友情链接
function _friendSubmit($self)
{
	$self->response->setStatus(200);

	$captcha = $self->request->captcha;
	if (empty($captcha)) {
		$self->response->throwJson([
			'code' => 0,
			'msg' => '请输入验证码！'
		]);
	}
	if (empty($_SESSION['joe_captcha'])) {
		$self->response->throwJson([
			'code' => 0,
			'msg' => '验证码过期，请重新获取验证码'
		]);
	}
	if ($_SESSION['joe_captcha'] != $captcha) {
		unset($_SESSION['joe_captcha']);
		$self->response->throwJson([
			'code' => 0,
			'msg' => '验证码错误'
		]);
	}
	unset($_SESSION['joe_captcha']);

	$title = $self->request->title;
	$description = $self->request->description;
	$link = $self->request->link;
	$logo = $self->request->logo;
	$qq = $self->request->qq;
	if (empty($title) || empty($link) || empty($qq)) {
		$self->response->throwJson([
			'code' => 0,
			'msg' => '必填项不能为空'
		]);
	}
	if (empty($logo)) {
		$logo = 'http://q4.qlogo.cn/headimg_dl?dst_uin=' . $qq . '&spec=640';
	}
	$EmailTitle = '友链申请';
	$subtitle = $title . '向您提交了友链申请：';
	$content = "$title || $link || $logo || $description<br><br>对方QQ号：$qq";
	$SendEmail = joe\send_email($EmailTitle, $subtitle, $content);
	if ($SendEmail == 'success') {
		$self->response->throwJson([
			'code' => 200,
			'msg' => '提交成功，管理员会在24小时内进行审核，请耐心等待'
		]);
	}
	if (!empty($SendEmail)) {
		$self->response->throwJson([
			'code' => 0,
			'msg' => '提交失败，错误原因：' . $SendEmail
		]);
	}
	if ($SendEmail == false) {
		$self->response->throwJson([
			'code' => 0,
			'msg' => '提交失败，请联系本站点管理员进行处理'
		]);
	}
}

function _Meting($self)
{
	$extension = ['bcmath', 'curl', 'openssl'];
	foreach ($extension as  $value) {
		if (!extension_loaded($value)) {
			$self->response->setStatus(404);
			$self->response->throwJson([
				'code' => 0,
				'msg' => '请开启PHP的' . $value . '扩展！'
			]);
		}
	}
	if (empty($_REQUEST['server']) || empty($_REQUEST['type']) || empty($_REQUEST['id'])) {
		$self->response->setStatus(404);
	}
	$api = new Meting($_REQUEST['server']);
	$type = $_REQUEST['type'];
	if ($type == 'playlist') {
		$data = $api->format(true)->cookie(Helper::options()->JMusicCookie)->playlist($_REQUEST['id']);
		$data = json_decode($data, true);
		foreach ($data as $key => $value) {
			unset($data[$key]);
			$data[$key]['author'] = is_array($value['artist']) ? implode(' / ', $value['artist']) : $value['artist'];
			$data[$key]['title'] = $value['name'];
			$base_url = (Helper::options()->rewrite == 0 ? Helper::options()->rootUrl . '/index.php/joe/api/' : Helper::options()->rootUrl . '/joe/api') . '/meting';
			$data[$key]['url'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=url&id=' . $value['url_id'];
			$data[$key]['pic'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=pic&size=1000&id=' . $value['pic_id'];
			$data[$key]['lrc'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=lrc&id=' . $value['lyric_id'];
		}
		$self->response->setStatus(200);
		$self->response->throwJson($data);
	}
	if ($type == 'url') {
		$data = json_decode($api->format(true)->cookie(Helper::options()->JMusicCookie)->url($_REQUEST['id']), true);
		$url = $data['url'];
		$self->response->setStatus(302);
		header("Location: $url");
		exit;
	}
	if ($type == 'pic') {
		$data = json_decode($api->format(true)->cookie(Helper::options()->JMusicCookie)->pic($_REQUEST['id'], ($_REQUEST['size'] ?? 300)), true);
		$url = $data['url'];
		$self->response->setStatus(302);
		header("Location: $url");
		exit;
	}
	if ($type == 'lrc') {
		$data = json_decode($api->format(true)->cookie(Helper::options()->JMusicCookie)->lyric($_REQUEST['id']), true);
		// 计算180天后的日期
		$expireTime = gmdate('D, d M Y H:i:s', time() + (180 * 24 * 60 * 60)) . ' GMT';
		// 设置缓存控制头部
		$self->response->setStatus(200);
		header("Cache-Control: max-age=" . (180 * 24 * 60 * 60) . ", public");
		header("Expires: $expireTime");
		header("Content-Type: text/plain; charset=utf-8");
		if (empty($data['tlyric'])) {
			echo $data['lyric'];
		} else {
			echo $data['tlyric'];
		}
		exit;
	}
	if ($type == 'song') {
		$data = $api->format(true)->cookie(Helper::options()->JMusicCookie)->song($_REQUEST['id']);
		$data = array_shift(json_decode($data, true));
		$data['author'] = is_array($data['artist']) ? implode(' / ', $data['artist']) : $data['artist'];
		$data['title'] = $data['name'];
		$base_url = (Helper::options()->rewrite == 0 ? Helper::options()->rootUrl . '/index.php/joe/api/' : Helper::options()->rootUrl . '/joe/api') . '/meting';
		$data['url'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=url&id=' . $data['url_id'];
		$data['pic'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=pic&id=' . $data['pic_id'];
		$data['lrc'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=lrc&id=' . $data['lyric_id'];
		$self->response->setStatus(200);
		$self->response->throwJson([$data]);
	}
}

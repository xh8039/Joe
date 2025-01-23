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
			$cid = trim($cid);
			$self->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid)->to($item);
			if ($item->next()) {
				$result[] = array(
					"cid" => $item->cid,
					"mode" => $item->fields->mode ? $item->fields->mode : 'default',
					"image" => joe\getThumbnails($item),
					"time" => date('Y-m-d', $item->created),
					'date_time' => date('Y-m-d H:i:s', $item->created),
					"created" => date('Y年m月d日', $item->created),
					'dateWord' => joe\dateWord($item->dateWord),
					"title" => $item->title,
					"abstract" => joe\getAbstract($item, false),
					"category" => $item->categories,
					"views" => joe\getViews($item, false),
					"commentsNum" => number_format($item->commentsNum),
					"agree" => joe\getAgree($item, false),
					"permalink" => $item->permalink,
					"lazyload" => joe\getLazyload(false),
					"type" => '置顶',
					'target' => Helper::options()->Jessay_target,
					'author_screenName' => $item->author->screenName,
					'author_permalink' => $item->author->permalink,
					'author_avatar' => joe\getAvatarByMail($item->author->mail, false),
					'tags' => $item->tags,
					'fields' => $item->fields->toArray()
				);
			}
		}
	} else {
		$sticky_arr = [];
	}
	$JIndex_Hide_Post = array_map('trim', explode("||", Helper::options()->JIndex_Hide_Post ?? ''));
	$hide_post_list = array_merge($sticky_arr, $JIndex_Hide_Post);
	$self->widget('Widget_Contents_Sort', 'page=' . $page . '&pageSize=' . $pageSize . '&type=' . $type)->to($item);
	while ($item->next()) {
		if (in_array($item->cid, $hide_post_list)) continue;
		$result[] = [
			"cid" => $item->cid,
			"mode" => $item->fields->mode ? $item->fields->mode : 'default',
			"image" => joe\getThumbnails($item),
			"time" => date('Y-m-d', $item->created),
			'date_time' => date('Y-m-d H:i:s', $item->created),
			"created" => date('Y年m月d日', $item->created),
			'dateWord' => joe\dateWord($item->dateWord),
			"title" => $item->title,
			"abstract" => joe\getAbstract($item, false),
			"category" => $item->categories,
			"views" => number_format($item->views),
			"commentsNum" => number_format($item->commentsNum),
			"agree" => number_format($item->agree),
			"permalink" => $item->permalink,
			"lazyload" => joe\getLazyload(false),
			"type" => 'normal',
			'target' => Helper::options()->Jessay_target,
			'author_screenName' => $item->author->screenName,
			'author_permalink' => $item->author->permalink,
			'author_avatar' => joe\getAvatarByMail($item->author->mail, false),
			'tags' => $item->tags,
			'fields' => $item->fields->toArray()
		];
	};
	$self->response->throwJson(array("data" => $result));
}

// 百度统计展示
function _getstatistics($self)
{
	$self->response->setStatus(200);
	$statistics_config = joe\baidu_statistic_config();
	if (!is_array($statistics_config)) {
		$self->response->throwJson(array('access_token' => 'off'));
	}
	if (empty($statistics_config['access_token'])) {
		$self->response->throwJson(array('access_token' => 'off'));
	}
	// 获取站点列表
	$baidu_list = function () use ($statistics_config, $self) {
		$url = 'https://openapi.baidu.com/rest/2.0/tongji/config/getSiteList?access_token=' . $statistics_config['access_token'];
		$data = json_decode(file_get_contents($url), true);
		if (isset($data['error_code'])) {
			if ($data['error_code'] == 111) {
				$refresh_token = \network\http\get('http://openapi.baidu.com/oauth/2.0/token', [
					'grant_type' => 'refresh_token',
					'refresh_token' => $statistics_config['refresh_token'],
					'client_id' => $statistics_config['client_id'],
					'client_secret' => $statistics_config['client_secret']
				])->toArray();
				if (is_array($refresh_token)) {
					$theme_options = Helper::options()->__get('theme:' . THEME_NAME);
					if (empty($theme_options)) {
						$self->response->throwJson(['msg' => '请更新您的 access_token']);
						return;
					}
					$db = Typecho_Db::get();
					if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . THEME_NAME . '_backup'))) {
						$db->query($db->update('table.options')->rows(array('value' => $theme_options))->where('name = ?', 'theme:' . THEME_NAME . '_backup'));
					} else {
						$db->query($db->insert('table.options')->rows(array('name' => 'theme:' . THEME_NAME . '_backup', 'user' => '0', 'value' => $theme_options)));
					}

					$theme_options = unserialize($theme_options);
					$theme_options['baidu_statistics'] =
						trim($refresh_token['access_token']) . "\r\n" .
						trim($refresh_token['refresh_token']) . "\r\n" .
						$statistics_config['client_id'] . "\r\n" . // API Key
						$statistics_config['client_secret']; // Secret Key

					$options_update = $db->update('table.options')->rows(['value' => serialize($theme_options)])->where('name = ?', 'theme:' . THEME_NAME);
					if ($db->query($options_update)) {
						$self->response->throwJson(['code' => 200, 'msg' => 'access_token 已更新']);
					} else {
						$self->response->throwJson(['msg' => 'access_token 更新失败！']);
					}
				} else {
					$self->response->throwJson(['msg' => '请更新您的 access_token']);
				}
			}
			$self->response->throwJson($data);
		}
		return $data['list'];
	};
	// 获取站点详情
	$web_metrics = function ($list, $start_date, $end_date) use ($statistics_config) {
		$access_token = $statistics_config['access_token'];
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
	$list = $baidu_list();
	for ($i = 0; $i < count($list); $i++) {
		if ($list[$i]['domain'] == JOE_DOMAIN) {
			$list = $list[$i];
			break;
		}
	}
	if (!isset($list['domain']) || $list['domain'] != JOE_DOMAIN) {
		$data = array(
			'msg' => '没有当前站点'
		);
		$self->response->throwJson($data);
	}
	$today = $web_metrics($list, date('Ymd'), date('Ymd'));
	$yesterday = $web_metrics($list, date('Ymd', strtotime("-1 days")), date('Ymd', strtotime("-1 days")));
	$moon = $web_metrics($list, date('Ym') . '01', date('Ymd'));
	$data = array(
		'code' => 200,
		'today' => $today,
		'yesterday' => $yesterday,
		'month' => $moon
	);
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
	$cid = $self->request->cid;
	/* sql注入校验 */
	if (!preg_match('/^\d+$/',  $cid)) {
		return $self->response->throwJson(array("code" => 0, "data" => "非法请求！已屏蔽！"));
	}
	$baidu_index = baidu_index($self->request->site);
	if (is_bool($baidu_index['index'])) {
		if ($baidu_index['index']) {
			$self->response->throwJson(["data" => "已收录", 'response' => $baidu_index['response']]);
		} else {
			$db = Typecho_Db::get();
			$sql = $db->select('str_value')->from('table.fields')->where('cid = ?', $cid)->where('name = ?', 'baidu_push');
			$row = $db->fetchRow($sql);
			if ($row && $row['str_value'] == 'yes') {
				$self->response->throwJson(["data" => "未收录，已推送", 'response' => $baidu_index['response']]);
			} else {
				$self->response->throwJson(["data" => "未收录", 'response' => $baidu_index['response']]);
			}
		}
	} else {
		$self->response->throwJson(["data" => "检测失败", 'index' => $baidu_index['index'], 'response' => $baidu_index['response']]);
	}
}

function baidu_index($url)
{
	$index = false;
	$url = preg_replace('/^https?:\/\//', '', $url);
	$client = new \network\http\Client;
	$client->param([
		'wd' => $url,
		'rn' => 1,
		'tn' => 'json',
		'ie' => 'utf-8',
		'cl' => 3,
		'f' => 9
	]);
	$cookie = empty(Helper::options()->Baidu_Index_Cookie) ? '' : trim(Helper::options()->Baidu_Index_Cookie);
	$user_agent = empty(Helper::options()->Baidu_Index_User_Agent) ? '' : trim(Helper::options()->Baidu_Index_User_Agent);
	$client->header([
		'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
		// 'accept-encoding' => 'gzip, deflate, br, zstd',
		'Accept-Language' => 'zh-CN,zh;q=0.9',
		'cache-control' => 'max-age=0',
		'Connection' => 'keep-alive',
		'cookie' => $cookie,
		'Host' => 'www.baidu.com',
		// 'sec-ch-ua' => '"Chromium";v="130", "Microsoft Edge";v="130", "Not?A_Brand";v="99"',
		// 'sec-ch-ua-mobile' => '?0',
		// 'sec-ch-ua-platform' => '"Windows"',
		// 'sec-fetch-dest' => 'document',
		// 'sec-fetch-mode' => 'navigate',
		// 'sec-fetch-site' => 'none',
		// 'sec-fetch-user' => '?1',
		// 'upgrade-insecure-requests' => '1',
		'User-Agent' => $user_agent,
	]);
	$response = $client->get('http://www.baidu.com/s')->toArray();
	if (is_array($response)) {
		if (!empty($response['feed']['entry'][0]['url'])) {
			$baidu_url = preg_replace('/^https?:\/\//', '', $response['feed']['entry'][0]['url']);
			if ($baidu_url == $url) $index = true;
		}
	} else {
		$index = null;
	}
	return [
		'index' => $index,
		'response' => $response
	];
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

	$token = trim(Helper::options()->BaiduPushToken);
	$domain = $self->request->domain;
	$url = explode('?', $self->request->url, 2)[0];
	$urls = explode(",", $url);
	$api = "http://data.zz.baidu.com/urls?site={$domain}&token={$token}";
	$ch = curl_init();
	$options =  [
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => implode("\n", $urls),
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain')
	];
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($result, true);
	if (empty($result['error'])) {
		// 存储推送记录到文章或者页面的自定义字段里面
		$db = Typecho_Db::get();
		if (isset($row['str_value']) && $row['str_value'] != 'yes') {
			$db->query($db->update('table.fields')
				->rows(['str_value' => 'yes'])
				->where('cid = ?', $cid)
				->where('name = ?', 'baidu_push'));
		} else {
			$db->query($db->insert('table.fields')->rows([
				'cid' => $cid,
				'name' => 'baidu_push',
				'type' => 'str',
				'str_value' => 'yes',
				'int_value' => '0',
				'float_value' => '0',
			]));
		}
	}
	if (!empty($result['message'])) {
		$messages = [
			'site error' => '站点未在站长平台验证',
			'empty content' => 'post内容为空',
			'only 2000 urls are allowed once' => '每次最多只能提交2000条链接',
			'over quota' => '已超过每日配额',
			'token is not valid' => 'token错误',
			'not found' => '接口地址填写错误',
			'internal error, please try later' => '服务器偶然异常，通常重试就会成功'
		];
		foreach ($messages as $key => $value) {
			if ($result['message'] == $key) $result['message'] = $value;
		}
	}
	$self->response->throwJson([
		'domain' => $domain,
		'url' => $url,
		'data' => $result
	]);
}

// 主动推送到必应收录
function _pushBing($self)
{
	$self->response->setStatus(200);
	$token = Helper::options()->BingPushToken;
	if (empty($token)) exit;
	$domain = $self->request->domain;  //网站域名
	$url = explode('?', $self->request->url, 2)[0];
	$urls = explode(",", $url);  //要推送的url
	$api = "https://www.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=$token";
	$data = ['siteUrl' => $domain, 'urlList' => $urls];
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
	$WallpaperAPI = joe\optionMulti(Helper::options()->WallpaperAPI, '||', null, ['type', 'list']);
	$api = $WallpaperAPI['type'] ?? 'http://cdn.apc.360.cn/index.php';
	$res = \network\http\get($api . "?c=WallPaper&a=getAllCategoriesV2&from=360chrome")->toArray();
	if (is_array($res) && $res['errno'] == 0) {
		$self->response->throwJson(["code" => 1, "data" => $res['data']]);
	}
	$self->response->throwJson(["code" => 0, "data" => null, 'res' => $res]);
}

/* 获取壁纸列表 已测试 √ */
function _getWallpaperList($self)
{
	$self->response->setStatus(200);
	$cid = $self->request->cid;
	$start = $self->request->start;
	$count = $self->request->count;
	$WallpaperAPI = joe\optionMulti(Helper::options()->WallpaperAPI, '||', null, ['type', 'list']);
	$api = $WallpaperAPI['list'] ?? 'http://wallpaper.apc.360.cn/index.php';
	$res = \network\http\get($api . "?c=WallPaper&a=getAppsByCategory&cid={$cid}&start={$start}&count={$count}&from=360chrome")->toArray();
	if (is_array($res) && $res['errno'] == 0) {
		$self->response->throwJson(["code" => 1, "data" => $res['data'], "total" => $res['total']]);
	}
	$self->response->throwJson(["code" => 0, "data" => null, 'res' => $res]);
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
			$self->response->throwJson(["code" => 1, "data" => $res,]);
		} else {
			$self->response->throwJson(["code" => 0, "data" => "抓取失败！请联系作者！"]);
		}
	} else {
		$self->response->throwJson(["code" => 0, "data" => "后台苹果CMS API未填写！"]);
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
		$self->response->throwJson(["code" => 1, "data" => $res['data'],]);
	} else {
		$self->response->throwJson(["code" => 0, "data" => "抓取失败！请联系作者！"]);
	}
}

/* 获取服务器状态 */
function _getServerStatus($self)
{
	$self->response->setStatus(200);

	$api_panel = Helper::options()->JBTPanel;
	$api_sk = Helper::options()->JBTKey;
	if (!$api_panel) return $self->response->throwJson(["code" => 0, "data" => "宝塔面板地址未填写！"]);
	if (!$api_sk) return $self->response->throwJson(["code" => 0, "data" => "宝塔接口密钥未填写！"]);
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
		"up" => isset($response["up"]) ? $response["up"] : 0,
		/* 下行流量KB */
		"down" => isset($response["down"]) ? $response["down"] : 0,
		/* 总发送（字节数） */
		"upTotal" => isset($response["upTotal"]) ? $response["upTotal"] : 0,
		/* 总接收（字节数） */
		"downTotal" => isset($response["downTotal"]) ? $response["downTotal"] : 0,
		/* 内存占用 */
		"memory" => isset($response["mem"]) ? $response["mem"] : ["memBuffers" => 0, "memCached" => 0, "memFree" => 0, "memRealUsed" => 0, "memTotal" => 0],
		/* CPU */
		"cpu" => isset($response["cpu"]) ? $response["cpu"] : [0, 0, [0], 0, 0, 0],
		/* 系统负载 */
		"load" => isset($response["load"]) ? $response["load"] : ["fifteen" => 0, "five" => 0, "limit" => 0, "max" => 0, "one" => 0, "safe" => 0],
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
	$self->response->throwJson(["categories" => $categories, "series" => $series]);
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
	if (empty($captcha)) $self->response->throwJson(['code' => 0, 'msg' => '请输入验证码！']);
	if (empty($_SESSION['joe_captcha'])) $self->response->throwJson(['code' => 0, 'msg' => '验证码过期，请重新获取验证码']);
	if ($_SESSION['joe_captcha'] != $captcha) {
		unset($_SESSION['joe_captcha']);
		$self->response->throwJson(['code' => 0, 'msg' => '验证码错误']);
	}
	unset($_SESSION['joe_captcha']);

	$title = $self->request->title;
	$description = $self->request->description;
	$url = $self->request->url;
	$logo = $self->request->logo;
	$email = $self->request->email;

	if (empty($title) || empty($url) || empty($email)) $self->response->throwJson(['code' => 0, 'msg' => '必填项不能为空']);
	if (!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)) $self->response->throwJson(['code' => 0, 'msg' => '联系邮箱错误！']);
	if (!preg_match('/^http[s]?:\/\/[^\s]*/', $url)) $self->response->throwJson(['code' => 0, 'msg' => '网站地址错误！']);
	if (empty($logo)) $logo = Helper::options()->themeUrl . '/assets/images/avatar-default.png';
	if (!preg_match('/^http[s]?:\/\/[^\s]*/', $logo)) $self->response->throwJson(['code' => 0, 'msg' => '网站LOGO地址错误！']);

	$db = Typecho_Db::get();
	$value = $db->fetchRow($db->select('status')->from('table.friends')->where('url = ?', $url));
	if (is_array($value) && isset($value['status'])) $self->response->throwJson(['code' => 0, 'msg' => ($value['status'] ? '本站已有您的友情链接！' : '您已提交过友链，请耐心等待审核')]);

	$sql = $db->insert('table.friends')->rows([
		'title' => $title,
		'url' =>  $url,
		'logo' =>  $logo,
		'description' => $description,
		'email' => $email,
		'position' => 'single'
	]);
	if (!$db->query($sql)) $self->response->throwJson(['code' => 0, 'msg' => '提交失败，请联系本站点管理员进行处理']);
	if (Helper::options()->JFriendEmail == 'on') {
		$EmailTitle = '友链申请';
		$subtitle = $title . ' 向您提交了友链申请';
		$content = "<p>站点标题：$title</p><p>站点链接：$url</p><p>站点图标：$logo</p><p>站点描述：$description</p><p>对方邮箱：$email</p>";
		$SendEmail = joe\send_email($EmailTitle, $subtitle, $content);
		if ($SendEmail !== true) $self->response->throwJson(['code' => 0, 'msg' => '提交失败，' . $SendEmail]);
	}
	$self->response->throwJson(['code' => 200, 'msg' => '提交成功，管理员会在24小时内进行审核，请耐心等待']);
}

function _Meting($self)
{
	if (empty($_REQUEST['server']) || empty($_REQUEST['type']) || empty($_REQUEST['id'])) $self->response->setStatus(404);
	$self->response->setStatus(200);
	$extension = ['bcmath', 'curl', 'openssl'];
	foreach ($extension as  $value) {
		if (!extension_loaded($value)) $self->response->throwJson(['code' => 0, 'msg' => '请开启PHP的' . $value . '扩展！']);
	}
	$api = new Meting($_REQUEST['server']);
	$type = $_REQUEST['type'];
	if ($type == 'playlist') {
		if ($_REQUEST['server'] == 'kugou' && str_starts_with($_REQUEST['id'], 'http')) {
			$headers = [
				'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
				'accept-encoding' => 'gzip, deflate, br, zstd',
				'accept-language' => 'zh-CN,zh;q=0.9',
				'cache-control' => 'no-cache',
				'cookie' => Helper::options()->JMusicCookie,
				'pragma' => 'no-cache',
				'priority' => 'u=0, i',
				'sec-ch-ua' => '"Not A(Brand";v="8", "Chromium";v="132", "Microsoft Edge";v="132"',
				'sec-ch-ua-mobile' => '?0',
				'sec-ch-ua-platform' => '"Windows"',
				'sec-fetch-dest' => 'document',
				'sec-fetch-mode' => 'navigate',
				'sec-fetch-site' => 'none',
				'sec-fetch-user' => '?1',
				'upgrade-insecure-requests' => '1',
				'user-agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Mobile Safari/537.36 EdgA/129.0.0.0 Edg/130.0.0.0',
			];
			$response = (new \network\http\Client())->header($headers)->get($_REQUEST['id'])->body();
			if (strpos($response, 'dataFromSmarty')) {
				$data = preg_match('/dataFromSmarty \= \[\{(.*)\}\]/', $response, $response_match);
				$data = json_decode('[{' . $response_match[1] . '}]', true);
				foreach ($data as $key => $value) {
					unset($data[$key]);
					$data[$key]['author'] = is_array($value['author_name']) ? implode(' / ', $value['author_name']) : $value['author_name'];
					$data[$key]['title'] = $value['song_name'];
					$base_url = Helper::options()->index . '/joe/api?routeType=meting';
					$data[$key]['url'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=url&id=' . $value['hash'] . '&time=' . time();
					$data[$key]['pic'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=pic&size=1000&id=' . $value['hash'];
					$data[$key]['lrc'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=lrc&id=' . $value['hash'];
				}
				$self->response->throwJson($data);
			}
			if (strpos($response, 'window.$output')) {
				$data = preg_match('/window\.\$output \= \{(.*)\}; \<\/script\>/', $response, $response_match);
				$data = json_decode('{' . $response_match[1] . '}', true)['info']['songs'];
				foreach ($data as $key => $value) {
					unset($data[$key]);
					$name = explode('-', $value['name']);
					$data[$key]['author'] = trim($name[0]);
					$data[$key]['title'] = trim($name[1]);
					$base_url = Helper::options()->index . '/joe/api?routeType=meting';
					$data[$key]['url'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=url&id=' . $value['hash'] . '&time=' . time();
					$data[$key]['pic'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=pic&size=1000&id=' . $value['hash'];
					$data[$key]['lrc'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=lrc&id=' . $value['hash'];
				}
				$self->response->throwJson($data);
			}
			$self->response->throwJson([]);
		}
		$data = $api->format(true)->cookie(Helper::options()->JMusicCookie)->playlist($_REQUEST['id']);
		$data = json_decode($data, true);
		if (!empty($data['error'])) $self->response->throwJson($data);
		foreach ($data as $key => $value) {
			unset($data[$key]);
			$data[$key]['author'] = is_array($value['artist']) ? implode(' / ', $value['artist']) : $value['artist'];
			$data[$key]['title'] = $value['name'];
			$base_url = Helper::options()->index . '/joe/api?routeType=meting';
			$data[$key]['url'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=url&id=' . $value['url_id'] . '&time=' . time();
			$data[$key]['pic'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=pic&size=1000&id=' . $value['pic_id'];
			$data[$key]['lrc'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=lrc&id=' . $value['lyric_id'];
		}
		$self->response->throwJson($data);
	}
	if ($type == 'url') {
		$data = json_decode($api->format(true)->cookie(Helper::options()->JMusicCookie)->url($_REQUEST['id']), true);
		if (empty($data['url'])) $self->response->throwJson(['code' => 0, 'msg' => '音频URL获取失败！']);
		$url = $data['url'];
		$self->response->setStatus(302);
		header("Location: $url");
		exit;
	}
	if ($type == 'pic') {
		$data = json_decode($api->format(true)->cookie(Helper::options()->JMusicCookie)->pic($_REQUEST['id'], ($_REQUEST['size'] ?? 300)), true);
		$url = $data['url'];
		if (empty($data['url'])) $self->response->throwJson(['code' => 0, 'msg' => '封面URL获取失败！']);
		$self->response->setStatus(302);
		header("Location: $url");
		exit;
	}
	if ($type == 'lrc') {
		$data = json_decode($api->format(true)->cookie(Helper::options()->JMusicCookie)->lyric($_REQUEST['id']), true);
		// 计算180天后的日期
		$expireTime = gmdate('D, d M Y H:i:s', time() + (180 * 24 * 60 * 60)) . ' GMT';
		// 设置缓存控制头部
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
		$base_url = Helper::options()->index . '/joe/api?routeType=meting';
		$data['url'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=url&id=' . $data['url_id'] . '&time=' . time();
		$data['pic'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=pic&id=' . $data['pic_id'];
		$data['lrc'] = $base_url . '&server=' . $_REQUEST['server'] . '&type=lrc&id=' . $data['lyric_id'];
		$self->response->throwJson([$data]);
	}
}

function _payCashierModal($self)
{
	if (!is_numeric($self->request->cid)) $self->response->setStatus(404);
	$self->response->setStatus(200);

	if (empty(Helper::options()->JYiPayApi)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付接口！']);
	if (empty(Helper::options()->JYiPayID)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付商户号！']);
	if (empty(Helper::options()->JYiPayKey)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付商户密钥！']);

	if (Helper::options()->JWeChatPay != 'on' && Helper::options()->JAlipayPay != 'on' && Helper::options()->JQQPay != 'on') {
		$self->response->throwJson(['code' => 503, 'message' => '暂无可用的支付方式!']);
	}

	$cid = trim($self->request->cid);

	$self->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid)->to($item);
	$item->next();
	$price = $item->fields->price ? $item->fields->price : 0;

	if (!is_numeric($price) || round($price, 2) <= 0) $self->response->throwJson(['code' => 503, 'message' => '金额设置错误！']);

	$price = round($price, 2);
?>
	<div class="modal-colorful-header colorful-bg jb-blue">
		<button class="close" data-dismiss="modal">
			<svg class="ic-close svg" aria-hidden="true">
				<use xlink:href="#icon-close"></use>
			</svg>
		</button>
		<div class="colorful-make"></div>
		<div class="text-center">
			<div class="em2x">
				<i class="fa fa-cart-plus" style="margin-left: -6px;"></i>
			</div>
			<?= is_numeric(USER_ID) ? '<div class="mt10 em12 padding-w10">确认购买</div>' : '<div class="mt10 padding-w10">您当前未登录！建议登陆后购买，可保存购买订单</div>' ?>
		</div>
	</div>
	<div class="mb10 order-type-1">
		<span class="pay-tag badg badg-sm mr6">
			<i class="fa fa-book mr3"></i>
			付费阅读
		</span>
		<span><?= $item->title ?></span>
	</div>
	<div class="mb10 muted-box padding-h6 line-16">
		<div class="flex jsb ab">
			<span class="muted-2-color">价格</span>
			<div>
				<span>
					<span class="pay-mark px12">￥</span>
					<span><?= $price ?></span>
					<!-- <span class="em14">0.01</span> -->
				</span>
			</div>
		</div>
	</div>
	<form>
		<input type="hidden" name="cid" value="<?= $item->cid ?>">
		<input type="hidden" name="order_type" value="1">
		<input type="hidden" name="order_name" value="<?= Helper::options()->title ?> - 付费阅读">
		<div class="dependency-box">
			<div class="muted-2-color em09 mb6">请选择支付方式</div>
			<div class="flex mb10">
				<?php
				if (Helper::options()->JWeChatPay == 'on') {
				?>
					<div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="wxpay">
						<img src="<?= joe\theme_url('assets/images/pay/pay-wechat-logo.svg', false) ?>" alt="wechat-logo">
						<div>微信</div>
					</div>
				<?php
				}
				if (Helper::options()->JAlipayPay == 'on') {
				?>
					<div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="alipay">
						<img src="<?= joe\theme_url('assets/images/pay/pay-alipay-logo.svg', false) ?>" alt="alipay-logo">
						<div>支付宝</div>
					</div>
				<?php
				}
				if (Helper::options()->JQQPay == 'on') {
				?>
					<div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="qqpay">
						<img src="<?= joe\theme_url('assets/images/pay/pay-qq-logo.svg', false) ?>" alt="wechat-logo">
						<div>QQ</div>
					</div>
				<?php
				}
				?>
				<!-- <div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="balance">
					<img src="<?= joe\theme_url('assets/images/pay/pay-balance-logo.svg', false) ?>" alt="balance-logo">
					<div>余额</div>
				</div> -->
			</div>
			<input type="hidden" name="payment_method" value="">
			<script>
				document.querySelector('.payment-method-radio').click()
			</script>
			<button class="mt6 but jb-red initiate-pay btn-block radius">
				立即支付
				<span class="pay-price-text">
					<span class="px12 ml10">￥</span>
					<span class="actual-price-number" data-price="<?= $price ?>"><?= $price ?></span>
				</span>
			</button>
		</div>
	</form>
	<script src="<?= joe\theme_url('assets/js/joe.pay.js'); ?>"></script>
<?php
	$self->response->throwContent('');
}

function _initiatePay($self)
{
	$cid = trim($self->request->cid);

	if (!is_numeric($cid)) $self->response->setStatus(404);

	$epay_config = [];

	if (empty(Helper::options()->JYiPayApi)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付接口！']);
	$epay_config['apiurl'] = trim(Helper::options()->JYiPayApi);

	if (empty(Helper::options()->JYiPayID)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付商户号！']);
	$epay_config['partner'] = trim(Helper::options()->JYiPayID);

	if (empty(Helper::options()->JYiPayKey)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付商户密钥！']);
	$epay_config['key'] = trim(Helper::options()->JYiPayKey);

	if (!empty(Helper::options()->JYiPayMapiUrl)) $epay_config['mapi_url'] = trim(Helper::options()->JYiPayMapiUrl);

	$self->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid)->to($item);
	$item->next();
	$price = $item->fields->price ? $item->fields->price : 0;
	if (!is_numeric($price) || round($price, 2) <= 0) $self->response->throwJson(['code' => 503, 'message' => '金额设置错误！']);
	$price = round($price, 2);
	$out_trade_no = date("YmdHis") . mt_rand(100, 999);
	//构造要请求的参数数组，无需改动
	$parameter = array(
		'pid' => $epay_config['partner'],
		"type" => $self->request->payment_method,
		"notify_url" => Helper::options()->themeUrl . '/library/pay/callback.php',
		"return_url" => Helper::options()->themeUrl . '/library/pay/callback.php?redirect_url=' . urlencode($self->request->return_url),
		"out_trade_no" => $out_trade_no,
		"name" =>  Helper::options()->title . ' - 付费阅读',
		"money"	=> $price,
		'sitename' => Helper::options()->title,
	);

	//建立请求
	require_once JOE_ROOT . 'library/pay/EpayCore.php';
	$epay = new \Joe\library\pay\EpayCore($epay_config);
	$clientip = $self->request->getIp();

	$self->response->setStatus(200);

	$db = Typecho_Db::get();
	$sql = $db->insert('table.orders')->rows([
		'trade_no' => $out_trade_no,
		"name" =>  Helper::options()->title . ' - 付费阅读',
		'content_title' => $item->title,
		'content_cid' => $cid,
		'type' => $self->request->payment_method,
		'money' => $price,
		'ip' => $clientip,
		'user_id' => USER_ID
	]);

	if (!$db->query($sql)) $self->response->throwJson(['code' => 500, 'msg' => '订单创建失败！']);
	if (Helper::options()->JYiPayMapi == 'on') {
		$parameter['clientip'] = $clientip;
		$data = $epay->apiPay($parameter);
		if ($data['code'] != 1) $self->response->throwJson(['code' => 500, 'msg' => $data['msg']]);
		$data['trade_no'] = isset($data['trade_no']) ? $data['trade_no'] : $data['orderid'];
		if (empty($data['trade_no'])) $self->response->throwJson(['code' => 500, 'msg' => '获取支付接口订单号失败！']);
		// 更新订单状态
		$order_update_sql = $db->update('table.orders')->rows(['api_trade_no' =>  $data['trade_no']])->where('trade_no = ?', $out_trade_no);
		if (!$db->query($order_update_sql)) $self->response->throwJson(['code' => 500, 'msg' => '更新支付接口订单号失败！']);
		$result = [
			'check_sdk' => 'epay',
			'code' => 1,
			'ip_address' => $clientip,
			'msg' => '创建订单成功',
			'order_name' => Helper::options()->title . ' - 付费阅读',
			'trade_no' => $out_trade_no,
			'order_price' => isset($data['price']) ? $data['price'] : (isset($data['money']) ? $data['money'] : $price),
			'payment_method' => $self->request->payment_method,
			'price' => $price,
			'return_url' => Helper::options()->themeUrl . '/library/pay/callback.php',
			'api_trade_no' => $data['trade_no'],
			'user_id' => USER_ID,
		];
		if (!empty($data['qrcode'])) {
			$result['qrcode'] = $data['qrcode'];
			$result['url_qrcode'] = Helper::options()->themeUrl . '/module/qrcode.php?text=' . urlencode($data['qrcode']);
		}
		if (!empty($data['payurl'])) {
			$result['open_url'] = true;
			$result['url'] = $data['payurl'];
		}
		$self->response->throwJson($result);
	} else {
		$html_text = $epay->pagePay($parameter);
		$self->response->throwJson(['code' => 200, 'form_html' => $html_text]);
	}
}

function _checkPay($self)
{
	if (!is_numeric($self->request->trade_no)) $self->response->setStatus(404);

	$self->response->setStatus(200);

	$trade_no = trim($self->request->trade_no);

	$epay_config = [];

	if (empty(Helper::options()->JYiPayApi)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付接口！']);
	$epay_config['apiurl'] = trim(Helper::options()->JYiPayApi);

	if (empty(Helper::options()->JYiPayID)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付商户号！']);
	$epay_config['partner'] = trim(Helper::options()->JYiPayID);

	if (empty(Helper::options()->JYiPayKey)) $self->response->throwJson(['code' => 503, 'message' => '未配置易支付商户密钥！']);
	$epay_config['key'] = trim(Helper::options()->JYiPayKey);

	if (!empty(Helper::options()->JYiPayMapiUrl)) $epay_config['mapi_url'] = trim(Helper::options()->JYiPayMapiUrl);

	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select()->from('table.orders')->where('trade_no = ?', $trade_no)->limit(1));
	if (sizeof($row) > 0) {
		//建立请求
		require_once JOE_ROOT . 'library/pay/EpayCore.php';
		$epay = new \Joe\library\pay\EpayCore($epay_config);
		$data = $epay->queryOrder($trade_no, $row['api_trade_no']);
		$status = isset($data['status']) ? $data['status'] : 0;
		$msg = empty($data['msg']) ? '支付失败，订单失效！' : $data['msg'];
		$self->response->throwJson(['status' => $status, 'msg' => $msg]);
	} else {
		$self->response->throwJson(['code' => 500, 'msg' => '订单不存在！']);
	}
}

function _userRewardsModal($self)
{
	$self->response->setStatus(200);
?>
	<style>
		.rewards-img {
			height: 140px;
			width: 140px;
			border-radius: var(--main-radius);
			overflow: hidden;
			margin: auto;
		}
	</style>
	<div class="modal-colorful-header colorful-bg jb-blue">
		<button class="close" data-dismiss="modal">
			<svg class="ic-close svg" aria-hidden="true">
				<use xlink:href="#icon-close"></use>
			</svg>
		</button>
		<div class="colorful-make"></div>
		<div class="text-center">
			<div class="em2x">
				<svg class="em12 svg" aria-hidden="true">
					<use xlink:href="#icon-money"></use>
				</svg>
			</div>
			<div class="mt10 em12 padding-w10"><?= Helper::options()->JRewardTitle ?></div>
		</div>
	</div>
	<ul class="flex jse mb10 text-center rewards-box">
		<?php
		if (!empty(Helper::options()->JWeChatRewardImg)) {
		?>
			<li>
				<p class="muted-2-color" style="margin-bottom: 10px;">微信扫一扫</p>
				<div class="rewards-img">
					<img class="fit-cover" src="<?= Helper::options()->JWeChatRewardImg ?>">
				</div>
			</li>
		<?php
		}
		if (!empty(Helper::options()->JAlipayRewardImg)) {
		?>
			<li>
				<p class="muted-2-color" style="margin-bottom: 10px;">支付宝扫一扫</p>
				<div class="rewards-img">
					<img class="fit-cover" src="<?= Helper::options()->JAlipayRewardImg ?>">
				</div>
			</li>
		<?php
		}
		if (!empty(Helper::options()->JQQRewardImg)) {
		?>
			<li>
				<p class="muted-2-color" style="margin-bottom: 10px;">QQ扫一扫</p>
				<div class="rewards-img">
					<img class="fit-cover" src="<?= Helper::options()->JQQRewardImg ?>">
				</div>
			</li>
		<?php
		}
		?>
	</ul>
<?php
	$self->response->throwContent('');
}

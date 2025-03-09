<?php

namespace joe;

use Metowolf\Meting;
use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

class Api
{
	public static $self;
	public static $options;
	public static $user;

	public static function manifest()
	{
		return [
			'name' => self::$options->title,
			'short_name' => self::$options->title,
			'start_url' => '/',
			'display' => 'standalone',
			'icons' => [
				[
					'src' => str_starts_with(self::$options->JFavicon, '/') ? (self::$options->index . self::$options->JFavicon) : self::$options->JFavicon,
				]
			]
		];
	}

	public static function online()
	{
		// 设置在线时间阈值
		$inactiveThreshold = self::$options->JOnLineCountThreshold;

		if (!is_numeric($inactiveThreshold)) return ['message' => '未填写在线人数统计时间阈值'];

		$_SESSION['time'] = time();

		// 获取当前会话保存路径
		$sessionPath = empty($GLOBALS['session_save_path']) ? sys_get_temp_dir() : $GLOBALS['session_save_path'];

		// 初始化在线人数计数器
		$onlineCount = 0;

		// 遍历会话目录
		$dirHandle = opendir($sessionPath);
		if ($dirHandle) {
			while (($file = readdir($dirHandle)) !== false) {
				// 仅处理以"sess_"开头的会话文件
				if (strpos($file, 'sess_') === 0) {
					$filePath = $sessionPath . DIRECTORY_SEPARATOR . $file;
					// 检查文件最后修改时间
					if (filemtime($filePath) + $inactiveThreshold >= time()) $onlineCount++;
				}
			}
			closedir($dirHandle);
		}

		return ['count' => $onlineCount];
	}

	public static function optionsBackup(\Widget\Archive $self)
	{
		if (self::$user->group != 'administrator') return ['message' => '权限不足！'];
		$action = $self->request->action;
		$theme_field = 'theme:' . THEME_NAME;
		$backup_field = $theme_field . '_backup';
		if ($action == 'backup') {
			$theme_options = Db::name('options')->where('name', $theme_field)->value('value');
			if (empty($theme_options)) return ['message' => '备份失败！无法获取主题设置！'];
			if (Db::name('options')->where('name', $backup_field)->find()) {
				Db::name('options')->where('name', $backup_field)->update(['value' => $theme_options]);
				return ['code' => 200, 'message' => '主题备份已更新！'];
			} else {
				Db::name('options')->insert(['name' => $backup_field, 'user' => '0', 'value' => $theme_options]);
				return ['code' => 200, 'message' => '主题设置已备份！'];
			}
		}
		if ($action == 'revert') {
			$backup_value = Db::name('options')->where('name', $backup_field)->value('value');
			if (empty($backup_value)) return ['message' => '未备份过数据，无法恢复！'];
			Db::name('options')->where('name', $theme_field)->update(['value' => $backup_value]);
			return ['code' => 200, 'message' => '主题设置已还原！'];
		}
		if ($action == 'delete') {
			$backup_delete = Db::name('options')->where('name', $backup_field)->delete();
			if (!$backup_delete) return ['message' => '没有备份内容，无法删除！'];
			return ['code' => 200, 'message' => '主题备份已删除！'];
		}
	}

	public static function mailTest()
	{
		if (self::$user->group != 'administrator') return ['message' => '权限不足！'];
		$mail = Db::name('users')->where('uid', 1)->value('mail');
		$email = $mail ? $mail : self::$options->JCommentMailAccount;
		$send_email = \joe\send_mail('邮件发送测试', null, '这是一封测试邮件！', $email);
		if ($send_email === true) {
			return ['code' => 200, 'message' => '邮件发送成功！'];
		} else {
			return ['message' => $send_email];
		}
	}

	/** 用户登录 */
	public static function userLogin()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		if (empty($username)) return ['message' => '请输入账号/邮箱'];
		if (empty($password)) return ['message' => '请输入密码'];
		$login = self::$user->login($username, $password);
		return $login ? ['code' => 200, 'message' => '登录成功', 'location' => true] : ['message' => '账号或密码错误'];
	}

	/** 用户注册邮箱验证码 */
	public static function userRegisterCaptcha(\Widget\Archive $self)
	{
		if (extension_loaded('gd')) {
			$captcha = $self->request->captcha;
			if (empty($captcha) || !is_string($captcha)) return ['message' => '请先输入图像验证码！'];
			if (empty($_SESSION['joe_image_captcha']) || !is_string($_SESSION['joe_image_captcha'])) return ['message' => '验证码过期，请点击验证码图片刷新'];
			if (strtolower($_SESSION['joe_image_captcha']) !== strtolower($captcha)) return ['message' => '验证码错误'];
			unset($_SESSION['joe_image_captcha']);
		}
		$email = trim($self->request->email);
		if (empty($email)) return ['message' => '请输入邮箱后发送验证码'];
		if (Db::name('users')->where('mail', $email)->find()) return ['message' => '该邮箱已经注册'];

		$_SESSION['joe_user_register_captcha'] = rand(100000, 999999);
		$_SESSION['joe_user_register_email'] = $email;
		$send_email = \joe\send_mail('注册验证', '您正在本站进行注册验证操作，如非您本人操作，请忽略此邮件。', [
			'验证码30分钟内有效，如果超时请重新获取',
			'您的邮箱为：' . $email,
			'您的验证码为：',
			'<span style="border-bottom: 1px dashed #ccc; z-index: 1; position: static;">' . $_SESSION['joe_user_register_captcha'] . '</span>',
		], $email, 60);
		return $send_email === true ? ['code' => 200, 'message' => '验证码已发送到您的邮箱'] : ['message' => $send_email];
	}
	/** 用户注册 */
	public static function userRegister(\Widget\Archive $self)
	{
		if (!self::$options->allowRegister) return ['message' => '禁止用户注册！'];

		/** 初始化验证类 */
		$validator = new \Typecho\Validate();
		$validator->addRule('nickname', 'required', _t('请输入用户昵称'));
		$validator->addRule('nickname', 'maxLength', _t('昵称最多包含10个字符'), 10);

		$validator->addRule('username', 'required', _t('请输入账号'));
		$validator->addRule('username', 'minLength', _t('账号至少包含2个字符'), 3);
		$validator->addRule('username', 'maxLength', _t('账号最多包含32个字符'), 30);
		$validator->addRule('username', 'xssCheck', _t('请不要在账号中使用特殊字符'));
		$validator->addRule('username', function (string $username) {
			return preg_match('/^[A-Za-z0-9]{3,30}$/i', $username);
		}, _t('账号必须由字母或数字组成'));
		$validator->addRule('username', [self::$user, 'nameExists'], _t('账号已经存在'));

		$validator->addRule('email', 'required', _t('请输入邮箱'));
		$validator->addRule('email', [self::$user, 'mailExists'], _t('邮箱已经存在'));
		$validator->addRule('email', 'email', _t('邮箱格式错误'));
		$validator->addRule('email', 'maxLength', _t('邮箱最多包含64个字符'), 64);

		$validator->addRule('password', 'required', _t('请输入密码'));
		$validator->addRule('password', 'minLength', _t('为了保证账户安全, 请输入至少六位的密码'), 6);
		$validator->addRule('password', 'maxLength', _t('为了便于记忆, 密码长度请不要超过十八位'), 18);

		/** 截获验证异常 */
		$error = $validator->run($self->request->from('nickname', 'username', 'email', 'password'));
		if ($error) return ['message' => implode('，', $error)];

		if (Db::name('users')->where('screenName', $self->request->nickname)->find()) {
			return ['message' => '昵称已被其它小伙伴使用了'];
		}

		$email = trim($self->request->email);

		if (\joe\email_config()) {
			$captcha = $self->request->captcha;
			if (empty($captcha) || !is_string($captcha)) return ['message' => '请先输入邮箱验证码！'];
			if (empty($_SESSION['joe_user_register_captcha'])) return ['message' => '请先发送邮箱验证码'];
			if ($_SESSION['joe_user_register_email'] !== $email) return ['message' => '接收验证码邮箱与当前邮箱不符'];
			if ($_SESSION['joe_user_register_captcha'] != trim($captcha)) return ['message' => '验证码错误'];
		}

		$hasher = new \Utils\PasswordHash(8, true);
		$group = empty(self::$options->JUserRegisterGroup) ? 'subscriber' : self::$options->JUserRegisterGroup;

		$dataStruct = \Widget\Register::pluginHandle()->register([
			'name' => $self->request->username,
			'mail' => $email,
			'screenName' => $self->request->nickname,
			'password' => $hasher->hashPassword($self->request->password),
			'created' => self::$options->time,
			'group' => $group
		]);

		$insertId = self::$user->insert($dataStruct);
		if (!$insertId) return ['message' => '服务器异常，请稍后重试'];

		$user = Db::name('users')->where('uid', $insertId)->find();
		if (!$user) return ['message' => '服务器异常，请稍后重试'];

		self::$user->push($user);

		\Widget\Register::pluginHandle()->finishRegister(self::$user);

		$login = self::$user->login($self->request->username, $self->request->password);

		// \Typecho\Cookie::delete('__typecho_first_run');
		\Typecho\Cookie::set('__typecho_first_run', 1);
		\Typecho\Cookie::delete('__typecho_remember_name');
		\Typecho\Cookie::delete('__typecho_remember_mail');
		$_SESSION['joe_user_register_captcha'] = null;
		$_SESSION['joe_user_register_email'] = null;

		\joe\send_mail('注册成功', '您已成功注册账号，您的账号信息如下：', [
			'昵称' => $self->request->nickname,
			'账号' => $self->request->username,
			'邮箱' => $self->request->email,
			'密码' => $self->request->password,
		], $self->request->email, 0);

		$message = '注册成功，' . ($login ? '已自动为您登录' : '请前往登录');

		return (['code' => 200, 'message' => $message, 'location' => $login ? true : false]);
	}

	/** 用户重置密码邮箱验证码 */
	public static function userRetrieveCaptcha(\Widget\Archive $self)
	{
		if (extension_loaded('gd')) {
			$captcha = $self->request->captcha;
			if (empty($captcha) || !is_string($captcha)) return ['message' => '请先输入图像验证码！'];
			if (empty($_SESSION['joe_image_captcha']) || !is_string($_SESSION['joe_image_captcha'])) return ['message' => '验证码过期，请点击验证码图片刷新'];
			if (strtolower($_SESSION['joe_image_captcha']) !== strtolower($captcha)) return ['message' => '验证码错误'];
			unset($_SESSION['joe_image_captcha']);
		}
		/** 初始化验证类 */
		$validator = new \Typecho\Validate();
		$validator->addRule('email', 'required', _t('请输入邮箱后发送验证码'));
		$validator->addRule('email', 'email', _t('邮箱格式错误'));
		$validator->addRule('email', 'maxLength', _t('邮箱最多包含64个字符'), 64);
		$error = $validator->run($self->request->from('email'));
		if ($error) return ['message' => implode('，', $error)];

		$email = $self->request->email;
		if (!Db::name('users')->where('mail', $email)->find()) return (['message' => '邮箱未注册']);

		$_SESSION['joe_user_retrieve_captcha'] = rand(100000, 999999);
		$_SESSION['joe_user_retrieve_email'] = $email;
		$send_email = \joe\send_mail('密码重置', '您正在本站进行重置密码验证操作，如非您本人操作，请忽略此邮件。', [
			'验证码30分钟内有效，如果超时请重新获取',
			'您的邮箱为：' . $email,
			'您的验证码为：',
			'<span style="border-bottom: 1px dashed #ccc; z-index: 1; position: static;">' . $_SESSION['joe_user_retrieve_captcha'] . '</span>'
		], $email, 60);
		if ($send_email === true) {
			return (['code' => 200, 'message' => '验证码已发送到您的邮箱']);
		} else {
			return (['message' => $send_email]);
		}
	}
	/** 用户重置密码 */
	public static function userRetrieve(\Widget\Archive $self)
	{
		/** 初始化验证类 */
		$validator = new \Typecho\Validate();

		// 检测验证码
		$validator->addRule('captcha', 'required', _t('请输入验证码'));

		// 检测邮箱
		$validator->addRule('email', 'required', _t('请输入邮箱'));
		$validator->addRule('email', 'email', _t('邮箱格式错误'));
		$validator->addRule('email', 'maxLength', _t('邮箱最多包含64个字符'), 64);

		// 检测密码
		$validator->addRule('password', 'required', _t('请输入密码'));
		$validator->addRule('password', 'minLength', _t('为了保证账户安全, 请输入至少六位的密码'), 6);
		$validator->addRule('password', 'maxLength', _t('为了便于记忆, 密码长度请不要超过十八位'), 18);
		$validator->addRule('confirm_password', 'confirm', _t('两次输入的密码不一致'), 'password');

		// 截获验证异常
		$error = $validator->run($self->request->from('captcha', 'email', 'password', 'confirm_password'));
		if ($error) return ['message' => implode('，', $error)];

		$captcha = $self->request->captcha;
		$email = $self->request->email;

		// 查询用户
		$user = Db::name('users')->where('mail', $email)->find();
		if (!$user) return (['message' => '您输入的邮箱未注册账号']);

		// 检测验证码
		if (empty($captcha) || !is_string($captcha)) return ['message' => '请先输入邮箱验证码！'];
		if (empty($_SESSION['joe_user_retrieve_captcha'])) return ['message' => '请先发送邮箱验证码'];
		if ($_SESSION['joe_user_retrieve_email'] !== $email) return ['message' => '接收验证码邮箱与当前邮箱不符'];
		if ($_SESSION['joe_user_retrieve_captcha'] != trim($captcha)) return ['message' => '验证码错误'];

		// 生成新的用户密码
		$hasher = new \Utils\PasswordHash(8, true);
		$password = $hasher->hashPassword($self->request->password);

		if ($user['password'] === $password) return (['message' => '新密码不能与旧密码相同']);

		// 更新用户密码
		$user_update = Db::name('users')->where('uid', $user['uid'])->update(['password' => $password]);
		if (!$user_update) return ['message' => '服务器异常，请稍后再试'];

		// 清理验证码会话
		$_SESSION['joe_user_retrieve_captcha'] = null;
		$_SESSION['joe_user_retrieve_email'] = null;

		// 自动帮助用户登录
		$user['password'] = $self->request->password;
		$login = self::$user->simpleLogin($user, false);
		$message = '新密码设置成功，' . ($login ? '已自动为您登录' : '请重新登录');

		return (['code' => 200, 'message' => $message, 'location' => $login ? true : false]);
	}

	public static function commentOperate($self)
	{
		try {
			if ($GLOBALS['JOE_USER']->group != 'administrator') return ['message' => '权限不足'];
			$coid = $self->request->coid;
			$status = $self->request->status;
			$comment = Db::name('comments')->where('coid', $coid)->find();
			if ($status == 'delete') {
				if (preg_match('/\{!\{(.*)\}!\}/', $comment['text'], $matches)) {
					$draw_file = __TYPECHO_ROOT_DIR__ . $matches[1];
					if (@file_exists($draw_file)) {
						$delete_comment = unlink($draw_file);
						if ($delete_comment !== true) return ['message' => '删除画图文件失败，请检查文件权限！'];
					} else {
						return ['message' => '画图文件 [' . $matches[1] . '] 不存在！'];
					}
				}
				Db::name('comments')->where('coid', $coid)->delete();
			}
			if (in_array($status, ['waiting', 'spam'])) {
				Db::name('comments')->where('coid', $coid)->update(['status' => $status]);
			}
			$commentsNum = Db::name('comments')->where(['cid' => $comment['cid'], 'status' => 'approved'])->count();
			Db::name('contents')->where('cid', $comment['cid'])->update(['commentsNum' => $commentsNum]);
			return ['code' => 200, 'commentsNum' => $commentsNum];
		} catch (\Throwable $th) {
			return ['message' => '删除失败：' . $th];
		}
	}

	/* 获取文章列表 已测试 √  */
	public static function publishList($self)
	{
		$page = $self->request->page;
		$pageSize = $self->request->pageSize;
		$type = $self->request->type;

		/* sql注入校验 */
		if (!preg_match('/^\d+$/', $page)) return ['data' => '非法请求！已屏蔽！'];
		if (!preg_match('/^\d+$/', $pageSize)) return ['data' => '非法请求！已屏蔽！'];
		if (!preg_match('/^[created|views|commentsNum|agree]+$/', $type)) return ['data' => '非法请求！已屏蔽！'];

		/* 如果传入0，强制赋值1 */
		if ($page == 0) $page = 1;
		$result = [];

		/* 增加置顶文章功能，通过JS判断（如果你想添加其他标签的话，请先看置顶如何实现的） */
		$sticky_text = self::$options->JIndexSticky;
		if ($sticky_text && $page == 1) {
			$sticky_arr = explode("||", $sticky_text);
			foreach ($sticky_arr as $cid) {
				$cid = trim($cid);
				$item = $self->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid);
				if ($item->next()) {
					$result[] = array(
						"cid" => $item->cid,
						"mode" => $item->fields->mode ? $item->fields->mode : 'default',
						"image" => \joe\getThumbnails($item),
						"time" => date('Y-m-d', $item->created),
						'date_time' => date('Y-m-d H:i:s', $item->created),
						"created" => date('Y年m月d日', $item->created),
						'dateWord' => \joe\dateWord($item->dateWord),
						"title" => $item->title,
						"abstract" => \joe\getAbstract($item, false),
						"category" => $item->categories,
						"views" => number_format($item->views),
						"commentsNum" => number_format($item->commentsNum),
						"agree" => number_format($item->agree),
						"permalink" => \joe\root_relative_link($item->permalink),
						"lazyload" => \joe\getLazyload(),
						"type" => '置顶',
						'author_screenName' => $item->author->screenName,
						'author_permalink' => \joe\root_relative_link($item->author->permalink),
						'author_avatar' => \joe\getAvatarByMail($item->author->mail, false),
						'tags' => $item->tags,
						'fields' => $item->fields->toArray()
					);
				}
			}
		} else {
			$sticky_arr = [];
		}
		$JIndex_Hide_Post = array_map('trim', explode("||", self::$options->JIndex_Hide_Post ?? ''));
		$hide_post_list = array_merge($sticky_arr, $JIndex_Hide_Post);
		$self->widget('Widget_Contents_Sort', 'page=' . $page . '&pageSize=' . $pageSize . '&type=' . $type)->to($item);
		while ($item->next()) {
			if (in_array($item->cid, $hide_post_list)) continue;
			$result[] = [
				"cid" => $item->cid,
				"mode" => $item->fields->mode ? $item->fields->mode : 'default',
				"image" => \joe\getThumbnails($item),
				"time" => date('Y-m-d', $item->created),
				'date_time' => date('Y-m-d H:i:s', $item->created),
				"created" => date('Y年m月d日', $item->created),
				'dateWord' => \joe\dateWord($item->dateWord),
				"title" => $item->title,
				"abstract" => \joe\getAbstract($item, false),
				"category" => $item->categories,
				"views" => number_format($item->views),
				"commentsNum" => number_format($item->commentsNum),
				"agree" => number_format($item->agree),
				"permalink" => \joe\root_relative_link($item->permalink),
				"lazyload" => \joe\getLazyload(),
				"type" => 'normal',
				'author_screenName' => $item->author->screenName,
				'author_permalink' => \joe\root_relative_link($item->author->permalink),
				'author_avatar' => \joe\getAvatarByMail($item->author->mail, false),
				'tags' => $item->tags,
				'fields' => $item->fields->toArray()
			];
		};
		return (array('data' => $result));
	}

	// 百度统计展示
	public static function baiduStatistic($self)
	{

		$statistics_config = \joe\baidu_statistic_config();
		if (!is_array($statistics_config)) {
			return (array('access_token' => 'off'));
		}
		if (empty($statistics_config['access_token'])) {
			return (array('access_token' => 'off'));
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
						$theme_options = self::$options->__get('theme:' . THEME_NAME);
						if (empty($theme_options)) return (['message' => '请更新您的 access_token']);
						$backup_field = 'theme:' . THEME_NAME . '_backup';
						$backup = Db::name('options')->where('name', $backup_field)->find();
						if ($backup) {
							Db::name('options')->where('name', $backup_field)->update(['value' => $theme_options]);
						} else {
							Db::name('options')->where('name', $backup_field)->insert(['user' => '0', 'name' => $backup_field, 'value' => $theme_options]);
						}
						$theme_options = unserialize($theme_options);
						$theme_options['baidu_statistics'] =
							trim($refresh_token['access_token']) . "\r\n" .
							trim($refresh_token['refresh_token']) . "\r\n" .
							$statistics_config['client_id'] . "\r\n" . // API Key
							$statistics_config['client_secret']; // Secret Key

						$options_update = Db::name('options')->where('name', 'theme:' . THEME_NAME)->update(['value' => serialize($theme_options)]);
						if ($options_update) {
							return ['code' => 200, 'message' => 'access_token 已更新'];
						} else {
							return ['message' => 'access_token 更新失败！'];
						}
					} else {
						return ['message' => '请更新您的 access_token'];
					}
				}
				return $data;
			}
			return $data['list'];
		};
		// 获取站点详情
		$web_metrics = function ($site_id, $start_date, $end_date) use ($statistics_config) {
			$access_token = $statistics_config['access_token'];
			$url = "https://openapi.baidu.com/rest/2.0/tongji/report/getData?access_token=$access_token&site_id=$site_id&method=trend/time/a&start_date=$start_date&end_date=$end_date&metrics=avg_visit_time,ip_count,pv_count,&gran=day";
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
		if (!isset($list['domain']) || $list['domain'] != JOE_DOMAIN) return ['message' => '没有当前站点'];

		$today = $web_metrics($list['site_id'], date('Ymd'), date('Ymd'));
		$yesterday = $web_metrics($list['site_id'], date('Ymd', strtotime("-1 days")), date('Ymd', strtotime("-1 days")));
		$moon = $web_metrics($list['site_id'], date('Ym') . '01', date('Ymd'));
		$data = [
			'code' => 200,
			'today' => $today,
			'yesterday' => $yesterday,
			'month' => $moon
		];
		return ($data);
	}

	/* 增加浏览量 已测试 √ */
	public static function handleViews($self)
	{
		$cid = $self->request->cid;
		if (!preg_match('/^\d+$/',  $cid)) return ['code' => 0, 'data' => '非法请求！已屏蔽！'];
		$update = Db::name('contents')->where('cid', $cid)->inc('views')->update();
		return ['code' => $update ? 200 : 0];
	}

	/* 点赞和取消点赞 已测试 √ */
	public static function handleAgree($self)
	{
		$cid = $self->request->cid;
		$type = $self->request->type;
		if (!preg_match('/^\d+$/',  $cid)) return ['code' => 0, 'data' => '非法请求！已屏蔽！'];

		$update = Db::name('contents')->where('cid', $cid);
		if ($type == 'agree') $update->inc('agree');
		if ($type == 'disagree') $update->dec('agree');
		return ['code' => $update->update()];
	}

	/* 查询是否收录 已测试 √ */
	public static function baiduRecord($self)
	{
		$cid = $self->request->cid;
		/* sql注入校验 */
		if (!preg_match('/^\d+$/',  $cid)) return ['code' => 0, 'data' => '非法请求！已屏蔽！'];
		$baidu_record = self::baidu_record($self->request->site);
		if (is_bool($baidu_record['index'])) {
			if ($baidu_record['index']) {
				return ['data' => "已收录", 'response' => $baidu_record['response']];
			} else {
				$baidu_push = Db::name('fields')->where(['cid' => $cid, 'name' => 'baidu_push'])->value('str_value');
				return ['data' => $baidu_push ? '未收录，已推送' : '未收录', 'response' => $baidu_record['response']];
			}
		} else {
			return ['data' => "检测失败", 'index' => $baidu_record['index'], 'response' => $baidu_record['response']];
		}
	}

	public static function baidu_record($url)
	{
		$index = false;
		$url = preg_replace('/^https?:\/\//', '', $url);
		$client = new \network\http\Client;
		$client->param(['wd' => $url, 'rn' => 1, 'tn' => 'json', 'ie' => 'utf-8', 'cl' => 3, 'f' => 9]);
		$cookie = empty(self::$options->BaiduRecordCookie) ? '' : trim(self::$options->BaiduRecordCookie);
		$user_agent = empty(self::$options->BaiduRecordUserAgent) ? '' : trim(self::$options->BaiduRecordUserAgent);
		$client->header([
			'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
			'Accept-Language' => 'zh-CN,zh;q=0.9',
			'cache-control' => 'max-age=0',
			'Connection' => 'keep-alive',
			'cookie' => $cookie,
			'Host' => 'www.baidu.com',
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
		return ['index' => $index, 'response' => $response];
	}

	/* 主动推送到百度收录 已测试 √ */
	public static function baiduPush($self)
	{
		$cid = $self->request->cid;

		/* sql注入校验 */
		if (!preg_match('/^\d+$/',  $cid)) return ['code' => 0, 'data' => '非法请求！已屏蔽！'];

		$baidu_push = Db::name('fields')->where(['cid' => $cid, 'name' => 'baidu_push'])->value('str_value');
		if ($baidu_push) return ['already' => true];

		$token = trim(self::$options->BaiduPushToken);
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
			if (isset($baidu_push) && $baidu_push != '1') {
				Db::name('fields')->where(['cid' => $cid, 'name' => 'baidu_push'])->update(['str_value' => '1']);
			} else {
				Db::name('fields')->insert(['cid' => $cid, 'name' => 'baidu_push', 'str_value' => '1']);
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
		return ['domain' => $domain, 'url' => $url, 'data' => $result];
	}

	// 主动推送到必应收录
	public static function bingPush($self)
	{

		$token = self::$options->BingPushToken;
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
		return ['domain' => $domain, 'url' => $url, 'data' => json_decode($result, true)];
	}

	/* 获取壁纸分类 已测试 √ */
	public static function wallpaperType($self)
	{
		$WallpaperAPI = \joe\optionMulti(self::$options->WallpaperAPI, '||', null, ['type', 'list']);
		$api = $WallpaperAPI['type'] ?? 'http://cdn.apc.360.cn/index.php';
		$res = \network\http\get($api . "?c=WallPaper&a=getAllCategoriesV2&from=360chrome")->toArray();
		if (is_array($res) && $res['errno'] == 0) {
			return (['code' => 1, 'data' => $res['data']]);
		}
		return ['code' => 0, 'data' => null, 'res' => $res];
	}

	/* 获取壁纸列表 已测试 √ */
	public static function wallpaperList($self)
	{
		$cid = $self->request->cid;
		$start = $self->request->start;
		$count = $self->request->count;
		$WallpaperAPI = \joe\optionMulti(self::$options->WallpaperAPI, '||', null, ['type', 'list']);
		$api = $WallpaperAPI['list'] ?? 'http://wallpaper.apc.360.cn/index.php';
		$res = \network\http\get($api . "?c=WallPaper&a=getAppsByCategory&cid={$cid}&start={$start}&count={$count}&from=360chrome")->toArray();
		if (is_array($res) && $res['errno'] == 0) {
			return array('code' => 1, 'data' => $res['data'], "total" => $res['total']);
		}
		return ['code' => 0, 'data' => null, 'res' => $res];
	}

	/* 抓取苹果CMS视频分类 已测试 √ */
	public static function maccmsList($self)
	{
		$cms_api = self::$options->JMaccmsAPI;
		$ac = $self->request->ac ? $self->request->ac : '';
		$ids = $self->request->ids ? $self->request->ids : '';
		$t = $self->request->t ? $self->request->t : '';
		$pg = $self->request->pg ? $self->request->pg : '';
		$wd = $self->request->wd ? $self->request->wd : '';
		if ($cms_api) {
			$json = \network\http\get("{$cms_api}?ac={$ac}&ids={$ids}&t={$t}&pg={$pg}&wd={$wd}");
			$res = json_decode($json, TRUE);
			if ($res['code'] === 1) {
				return ['code' => 1, 'data' => $res];
			} else {
				return ['code' => 0, 'data' => '抓取失败！请联系作者！'];
			}
		} else {
			return ['code' => 0, 'data' => '后台苹果CMS API未填写！'];
		}
	}

	/* 获取虎牙视频列表 已测试 √ */
	public static function huyaList($self)
	{
		$gameId = $self->request->gameId;
		$page = $self->request->page;
		$json = \network\http\get("https://www.huya.com/cache.php?m=LiveList&do=getLiveListByPage&gameId={$gameId}&tagAll=0&page={$page}");
		$res = json_decode($json, TRUE);
		if ($res['status'] === 200) {
			return ['code' => 1, 'data' => $res['data']];
		} else {
			return ['code' => 0, 'data' => '抓取失败！请联系作者！'];
		}
	}

	/* 获取服务器状态 */
	public static function serverStatus($self)
	{
		$api_panel = self::$options->JBTPanel;
		$api_sk = self::$options->JBTKey;
		if (!$api_panel) return (['code' => 0, 'data' => '宝塔面板地址未填写！']);
		if (!$api_sk) return (['code' => 0, 'data' => '宝塔接口密钥未填写！']);
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
		return [
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
			"load" => isset($response["load"]) ? $response["load"] : ["fifteen" => 0, "five" => 0, "limit" => 0, "max" => 0, "one" => 0, "safe" => 0]
		];
	}

	/* 获取最近评论 */
	public static function commentLately($self)
	{
		$time = time();
		$num = 7;
		$categories = [];
		$series = [];
		for ($i = ($num - 1); $i >= 0; $i--) {
			$date = date("Y/m/d", $time - ($i * 24 * 60 * 60));
			$count = Db::name('comments')->whereRaw("FROM_UNIXTIME(created, '%Y/%m/%d') = '{$date}'")->limit(100)->count();
			$categories[] = $date;
			$series[] = $count;
		}
		return ["categories" => $categories, "series" => $series];
	}

	/* 获取文章归档 */
	public static function articleFiling($self)
	{
		$page = $self->request->page;
		if (!$page) $page = 1;
		if (!preg_match('/^\d+$/', $page)) return ['data' => '非法请求！已屏蔽！'];
		$result = [];

		$select = Db::name('contents')
			->fieldRaw("FROM_UNIXTIME(created, '%Y 年 %m 月') as date")
			->where(['status' => 'publish', 'type' => 'post'])
			->group("FROM_UNIXTIME(`created`, '%Y 年 %m 月')")
			->order('created', 'desc')
			->page($page, 8)
			->select();

		foreach ($select as $item) {
			$date = $item['date'];
			$list = [];
			$contents_select = Db::name('contents')
				->where(['status' => 'publish', 'type' => 'post'])
				->whereRaw("FROM_UNIXTIME(created, '%Y 年 %m 月') = '{$date}'")
				->order('created', 'desc')
				->limit(100)
				->select();
			foreach ($contents_select as $content) {
				$list[] = [
					"title" => date('Y/m/d', $content['created']) . '：' . $content['title'],
					"permalink" => \joe\permalink($content),
				];
			}
			$result[] = array('date' => $date, 'list' => $list);
		}
		return $result;
	}

	// 提交友情链接
	public static function friendSubmit($self)
	{
		if (extension_loaded('gd')) {
			$captcha = $self->request->captcha;
			if (empty($captcha) || !is_string($captcha)) return ['message' => '请输入验证码！'];
			if (empty($_SESSION['joe_image_captcha']) || !is_string($_SESSION['joe_image_captcha'])) return ['message' => '验证码过期，请点击验证码图片刷新'];
			if (strtolower($_SESSION['joe_image_captcha']) !== strtolower($captcha)) return ['message' => '验证码错误'];
			unset($_SESSION['joe_image_captcha']);
		}

		$title = $self->request->title;
		$description = $self->request->description;
		$url = $self->request->url;
		$logo = $self->request->logo;
		$email = $self->request->email;

		if (empty($title) || empty($url) || empty($email)) return (['code' => 0, 'message' => '必填项不能为空']);
		if (!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)) return (['code' => 0, 'message' => '联系邮箱错误！']);
		if (!preg_match('/^http[s]?:\/\/[^\s]*/', $url)) return (['code' => 0, 'message' => '网站地址错误！']);
		if (empty($logo)) $logo = self::$options->themeUrl . '/assets/images/avatar-default.png';
		if (!preg_match('/^http[s]?:\/\/[^\s]*/', $logo)) return (['code' => 0, 'message' => '网站LOGO地址错误！']);

		$friend = Db::name('friends')->where('url')->find();
		if ($friend) return ['code' => 0, 'message' => ($friend['status'] ? '本站已有您的友情链接！' : '您已提交过友链，请耐心等待审核')];

		$insert = Db::name('friends')->insert([
			'title' => $title,
			'url' =>  $url,
			'logo' =>  $logo,
			'description' => $description,
			'email' => $email,
			'position' => 'single'
		]);

		if (!$insert) return ['code' => 0, 'message' => '提交失败，请联系本站点管理员进行处理'];
		if (self::$options->JFriendEmail == 'on') {
			$EmailTitle = '友链申请';
			$subtitle = $title . ' 向您提交了友链申请';
			$content = ['站点标题' => $title, '站点链接' => $url, '站点图标' => $logo, '站点描述' => $description, '对方邮箱' => $email];
			$SendEmail = \joe\send_mail($EmailTitle, $subtitle, $content);
			if ($SendEmail !== true) return (['code' => 0, 'message' => '提交失败，' . $SendEmail]);
		}
		return ['code' => 200, 'message' => '提交成功，管理员会在24小时内进行审核，请耐心等待'];
	}

	public static function meting($self)
	{
		if (empty($_REQUEST['server']) || empty($_REQUEST['type']) || empty($_REQUEST['id'])) $self->response->setStatus(404);

		$extension = ['bcmath', 'curl', 'openssl'];
		foreach ($extension as  $value) {
			if (!extension_loaded($value)) return (['code' => 0, 'msg' => '请开启PHP的' . $value . '扩展！']);
		}
		$api = new Meting($_REQUEST['server']);
		$type = $_REQUEST['type'];
		if ($type == 'playlist') {
			$data = $api->format(true)->cookie(self::$options->JMusicCookie)->playlist($_REQUEST['id']);
			$data = json_decode($data, true);
			if (!empty($data['error'])) return ($data);
			foreach ($data as $key => $value) {
				unset($data[$key]);
				$data[$key]['author'] = is_array($value['artist']) ? implode(' / ', $value['artist']) : $value['artist'];
				$data[$key]['title'] = $value['name'];
				$base_url = \joe\root_relative_link(self::$options->index . '/joe/api/meting');
				$data[$key]['url'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=url&id=' . $value['url_id'] . '&time=' . time();
				$data[$key]['pic'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=pic&size=1000&id=' . $value['pic_id'];
				$data[$key]['lrc'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=lrc&id=' . $value['lyric_id'];
			}
			\joe\header_cache(24 * 60 * 60);
			return $data;
		}
		if ($type == 'url') {
			$data = json_decode($api->format(true)->cookie(self::$options->JMusicCookie)->url($_REQUEST['id']), true);
			if (empty($data['url'])) return (['code' => 0, 'msg' => '音频URL获取失败！']);
			$url = $data['url'];
			$self->response->setStatus(302);
			header("Location: $url");
			exit;
		}
		if ($type == 'pic') {
			$data = json_decode($api->format(true)->cookie(self::$options->JMusicCookie)->pic($_REQUEST['id'], ($_REQUEST['size'] ?? 300)), true);
			$url = $data['url'];
			if (empty($data['url'])) return (['code' => 0, 'msg' => '封面URL获取失败！']);
			$self->response->setStatus(302);
			header("Location: $url");
			exit;
		}
		if ($type == 'lrc') {
			$data = json_decode($api->format(true)->cookie(self::$options->JMusicCookie)->lyric($_REQUEST['id']), true);
			\joe\header_cache(180 * 24 * 60 * 60); // 缓存180天
			header("Content-Type: text/plain; charset=utf-8");
			return empty($data['tlyric']) ? $data['lyric'] : $data['tlyric'];
		}
		if ($type == 'song') {
			$data = $api->format(true)->cookie(self::$options->JMusicCookie)->song($_REQUEST['id']);
			$data = array_shift(json_decode($data, true));
			$data['author'] = is_array($data['artist']) ? implode(' / ', $data['artist']) : $data['artist'];
			$data['title'] = $data['name'];
			$base_url = \joe\root_relative_link(self::$options->index . '/joe/api/meting');
			$data['url'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=url&id=' . $data['url_id'] . '&time=' . time();
			$data['pic'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=pic&id=' . $data['pic_id'];
			$data['lrc'] = $base_url . '?server=' . $_REQUEST['server'] . '&type=lrc&id=' . $data['lyric_id'];
			return $data;
		}
	}

	public static function payCashierModal($self)
	{
		if (!is_numeric($self->request->cid)) $self->response->setStatus(404);

		if (empty(self::$options->JYiPayApi)) return (['code' => 503, 'message' => '未配置易支付接口！']);
		if (empty(self::$options->JYiPayID)) return (['code' => 503, 'message' => '未配置易支付商户号！']);
		if (empty(self::$options->JYiPayKey)) return (['code' => 503, 'message' => '未配置易支付商户密钥！']);

		if (self::$options->JWeChatPay != 'on' && self::$options->JAlipayPay != 'on' && self::$options->JQQPay != 'on') {
			return (['code' => 503, 'message' => '暂无可用的支付方式!']);
		}

		$cid = trim($self->request->cid);

		$item = $self->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid);
		$item->next();
		$price = $item->fields->price ? $item->fields->price : 0;

		if (!is_numeric($price) || round($price, 2) <= 0) return (['code' => 503, 'message' => '金额设置错误！']);

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
			<input type="hidden" name="order_name" value="<?= self::$options->title ?> - 付费阅读">
			<div class="dependency-box">
				<div class="muted-2-color em09 mb6">请选择支付方式</div>
				<div class="flex mb10">
					<?php
					if (self::$options->JWeChatPay == 'on') {
					?>
						<div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="wxpay">
							<img src="<?= \joe\theme_url('assets/images/pay/pay-wechat-logo.svg', false) ?>" alt="wechat-logo">
							<div>微信</div>
						</div>
					<?php
					}
					if (self::$options->JAlipayPay == 'on') {
					?>
						<div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="alipay">
							<img src="<?= \joe\theme_url('assets/images/pay/pay-alipay-logo.svg', false) ?>" alt="alipay-logo">
							<div>支付宝</div>
						</div>
					<?php
					}
					if (self::$options->JQQPay == 'on') {
					?>
						<div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="qqpay">
							<img src="<?= \joe\theme_url('assets/images/pay/pay-qq-logo.svg', false) ?>" alt="wechat-logo">
							<div>QQ</div>
						</div>
					<?php
					}
					?>
					<!-- <div class="flex jc hh payment-method-radio hollow-radio flex-auto pointer" data-for="payment_method" data-value="balance">
					<img src="<?= \joe\theme_url('assets/images/pay/pay-balance-logo.svg', false) ?>" alt="balance-logo">
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
		<script src="<?= \joe\theme_url('assets/js/joe.pay.js'); ?>"></script>
	<?php
		return true;
	}

	public static function initiatePay($self)
	{
		$cid = trim($self->request->cid);

		if (!is_numeric($cid)) $self->response->setStatus(404);

		$epay_config = [];

		if (empty(self::$options->JYiPayApi)) return (['code' => 503, 'message' => '未配置易支付接口！']);
		$epay_config['apiurl'] = trim(self::$options->JYiPayApi);

		if (empty(self::$options->JYiPayID)) return (['code' => 503, 'message' => '未配置易支付商户号！']);
		$epay_config['partner'] = trim(self::$options->JYiPayID);

		if (empty(self::$options->JYiPayKey)) return (['code' => 503, 'message' => '未配置易支付商户密钥！']);
		$epay_config['key'] = trim(self::$options->JYiPayKey);

		if (!empty(self::$options->JYiPayMapiUrl)) $epay_config['mapi_url'] = trim(self::$options->JYiPayMapiUrl);

		$item = $self->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid);
		$item->next();
		$price = $item->fields->price ? $item->fields->price : 0;
		if (!is_numeric($price) || round($price, 2) <= 0) return (['code' => 503, 'message' => '金额设置错误！']);
		$price = round($price, 2);
		$out_trade_no = date("YmdHis") . rand(100, 999);
		//构造要请求的参数数组，无需改动
		$parameter = array(
			'pid' => $epay_config['partner'],
			"type" => $self->request->payment_method,
			"notify_url" => self::$options->themeUrl . '/system/pay/callback.php',
			"return_url" => self::$options->themeUrl . '/system/pay/callback.php?redirect_url=' . urlencode($self->request->return_url),
			"out_trade_no" => $out_trade_no,
			"name" =>  self::$options->title . ' - 付费阅读',
			"money"	=> $price,
			'sitename' => self::$options->title,
		);

		//建立请求
		require_once JOE_ROOT . 'system/pay/EpayCore.php';
		$epay = new \joe\pay\EpayCore($epay_config);
		$clientip = $self->request->getIp();

		$insert = Db::name('orders')->insert([
			'trade_no' => $out_trade_no,
			"name" =>  self::$options->title . ' - 付费阅读',
			'content_title' => $item->title,
			'content_cid' => $cid,
			'type' => $self->request->payment_method,
			'money' => $price,
			'ip' => $clientip,
			'user_id' => USER_ID
		]);
		if (!$insert) return ['code' => 500, 'msg' => '订单创建失败！'];
		if (self::$options->JYiPayMapi == 'on') {
			$parameter['clientip'] = $clientip;
			$data = $epay->apiPay($parameter);
			if ($data['code'] != 1) return (['code' => 500, 'msg' => $data['msg']]);
			$data['trade_no'] = isset($data['trade_no']) ? $data['trade_no'] : $data['orderid'];
			if (empty($data['trade_no'])) return (['code' => 500, 'msg' => '获取支付接口订单号失败！']);
			// 更新订单状态
			$order_update = Db::name('orders')->where('trade_no', $out_trade_no)->update([
				'api_trade_no' => $data['trade_no']
			]);
			if (!$order_update) return ['code' => 500, 'msg' => '更新支付接口订单号失败！'];
			$result = [
				'check_sdk' => 'epay',
				'code' => 1,
				'ip_address' => $clientip,
				'msg' => '创建订单成功',
				'order_name' => self::$options->title . ' - 付费阅读',
				'trade_no' => $out_trade_no,
				'order_price' => isset($data['price']) ? $data['price'] : (isset($data['money']) ? $data['money'] : $price),
				'payment_method' => $self->request->payment_method,
				'price' => $price,
				'return_url' => self::$options->themeUrl . '/system/pay/callback.php',
				'api_trade_no' => $data['trade_no'],
				'user_id' => USER_ID,
			];
			if (!empty($data['qrcode'])) {
				$result['qrcode'] = $data['qrcode'];
				$result['url_qrcode'] = self::$options->themeUrl . '/module/qrcode.php?text=' . urlencode($data['qrcode']);
			}
			if (!empty($data['payurl'])) {
				$result['open_url'] = true;
				$result['url'] = $data['payurl'];
			}
			return ($result);
		} else {
			$html_text = $epay->pagePay($parameter);
			return (['code' => 200, 'form_html' => $html_text]);
		}
	}

	public static function checkPay($self)
	{
		if (!is_numeric($self->request->trade_no)) $self->response->setStatus(404);

		$trade_no = trim($self->request->trade_no);

		$epay_config = [];

		if (empty(self::$options->JYiPayApi)) return (['code' => 503, 'message' => '未配置易支付接口！']);
		$epay_config['apiurl'] = trim(self::$options->JYiPayApi);

		if (empty(self::$options->JYiPayID)) return (['code' => 503, 'message' => '未配置易支付商户号！']);
		$epay_config['partner'] = trim(self::$options->JYiPayID);

		if (empty(self::$options->JYiPayKey)) return (['code' => 503, 'message' => '未配置易支付商户密钥！']);
		$epay_config['key'] = trim(self::$options->JYiPayKey);

		if (!empty(self::$options->JYiPayMapiUrl)) $epay_config['mapi_url'] = trim(self::$options->JYiPayMapiUrl);

		$row = Db::name('orders')->where('trade_no', $trade_no)->find();
		if ($row) {
			//建立请求
			require_once JOE_ROOT . 'system/pay/EpayCore.php';
			$epay = new \joe\pay\EpayCore($epay_config);
			$data = $epay->queryOrder($trade_no, $row['api_trade_no']);
			$status = isset($data['status']) ? $data['status'] : 0;
			$msg = empty($data['msg']) ? '支付失败，订单失效！' : $data['msg'];
			return (['status' => $status, 'msg' => $msg]);
		} else {
			return (['code' => 500, 'msg' => '订单不存在！']);
		}
	}

	public static function userRewardsModal($self)
	{
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
				<div class="mt10 em12 padding-w10"><?= self::$options->JRewardTitle ?></div>
			</div>
		</div>
		<ul class="flex jse mb10 text-center rewards-box">
			<?php
			if (!empty(self::$options->JWeChatRewardImg)) {
			?>
				<li>
					<p class="muted-2-color" style="margin-bottom: 10px;">微信扫一扫</p>
					<div class="rewards-img">
						<img class="fit-cover" referrerpolicy="no-referrer" rel="noreferrer" src="<?= self::$options->JWeChatRewardImg ?>">
					</div>
				</li>
			<?php
			}
			if (!empty(self::$options->JAlipayRewardImg)) {
			?>
				<li>
					<p class="muted-2-color" style="margin-bottom: 10px;">支付宝扫一扫</p>
					<div class="rewards-img">
						<img class="fit-cover" referrerpolicy="no-referrer" rel="noreferrer" src="<?= self::$options->JAlipayRewardImg ?>">
					</div>
				</li>
			<?php
			}
			if (!empty(self::$options->JQQRewardImg)) {
			?>
				<li>
					<p class="muted-2-color" style="margin-bottom: 10px;">QQ扫一扫</p>
					<div class="rewards-img">
						<img class="fit-cover" referrerpolicy="no-referrer" rel="noreferrer" src="<?= self::$options->JQQRewardImg ?>">
					</div>
				</li>
			<?php
			}
			?>
		</ul>
<?php
		return true;
	}
}

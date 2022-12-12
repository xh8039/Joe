<?php

class Joe
{

	static public function jsdelivrUrl($path)
	{
		$path = self::urlQueryBuilder($path, ['version' => JOE_VERSION]);
		return 'https://cdn.jsdelivr.net/gh/xh8039/static-assets/' . $path;
	}

	static public function themeUrl($path)
	{
		if (empty(Helper::options()->JStaticAssetsUrl)) {
			$path = self::urlQueryBuilder($path, ['version' => JOE_VERSION]);
			return Helper::options()->themeUrl . '/' . $path;
		}
		$url = Helper::options()->JStaticAssetsUrl . '/' . $path;
		$url = self::urlQueryBuilder($url, ['version' => JOE_VERSION]);
		return $url;
	}

	static public function userLogin($uid, $expire = 30243600)
	{
		$db = Typecho_Db::get();
		Typecho_Widget::widget('Widget_User')->simpleLogin($uid);
		$authCode = function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : sha1(Typecho_Common::randString(20));
		Typecho_Cookie::set('__typecho_uid', $uid, time() + $expire);
		Typecho_Cookie::set('__typecho_authCode', Typecho_Common::hash($authCode), time() + $expire);
		//更新最后登录时间以及验证码
		$db->query($db->update('table.users')->expression('logged', 'activated')->rows(array('authCode' => $authCode))->where('uid = ?', $uid));
	}

	static public function userUrl($action)
	{
		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
		$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
		$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
		$url = urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
		switch ($action) {
			case 'register':
				$url = Typecho_Common::url('user/register', Helper::options()->index) . '?from=' . $url;
				break;
			case 'login':
				$url = Typecho_Common::url('user/login', Helper::options()->index) . '?from=' . $url;
				break;
			case 'forget':
				$url = Typecho_Common::url('user/forget', Helper::options()->index) . '?from=' . $url;
				break;
		}
		return $url;
	}

	/** 获取百度统计配置 */
	static public function baiduStatisticConfig()
	{
		$statistics_config = Helper::options()->baidu_statistics ? explode(PHP_EOL, Helper::options()->baidu_statistics) : null;
		if (is_array($statistics_config) && count($statistics_config) == 4) {
			return [
				'access_token' => trim($statistics_config[0]),
				'refresh_token' => trim($statistics_config[1]),
				'client_id' => trim($statistics_config[2]),
				'client_secret' => trim($statistics_config[3])
			];
		}
		return null;
	}

	/** 检测主题设置是否配置邮箱 */
	static public function EmailConfig()
	{
		if (
			empty(Helper::options()->JCommentMailHost) ||
			empty(Helper::options()->JCommentMailPort) ||
			empty(Helper::options()->JCommentMailAccount) ||
			empty(Helper::options()->JCommentMailFromName) ||
			empty(Helper::options()->JCommentSMTPSecure) ||
			empty(Helper::options()->JCommentMailPassword)
		) {
			return false;
		} else {
			return true;
		}
	}

	/** 发送电子邮件 */
	static public function sendEmail($title, $subtitle, $content, $email = '')
	{
		if (!self::EmailConfig()) {
			return false;
		}
		if (empty($email)) {
			$db = Typecho_Db::get();
			$authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', 1));
			if (empty($authoInfo['mail'])) {
				$email = Helper::options()->JCommentMailAccount;
			} else {
				$email = $authoInfo['mail'];
			}
		}
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
		$html = '<style>.Joe{width:550px;margin:0 auto;border-radius:8px;overflow:hidden;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.Joe_title{color:#fff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400% 400%;background-position:50% 100%;padding:15px;font-size:15px;line-height:1.5}</style><div class="Joe"><div class="Joe_title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;"><div style="margin-bottom: 20px;line-height: 1.5;">{subtitle}</div><div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div>';
		$mail->Body = strtr(
			$html,
			array(
				"{title}" => $title . ' - ' . Helper::options()->title,
				"{subtitle}" => $subtitle,
				"{content}" => $content,
			)
		);
		$mail->addAddress($email);
		$mail->Subject = $title . ' - ' . Helper::options()->title;
		if ($mail->send()) {
			return 'success';
		} else {
			return $mail->ErrorInfo;
		}
	}

	static public function urlQueryBuilder($url, array $param)
	{
		$param = http_build_query($param);
		$url = strstr($url, '?') ? trim($url, '&') . '&' . $param : $url . '?' . $param;
		return $url;
	}

	/** 过滤Markdown语法代码 */
	static public function markdownFilter($text)
	{
		$text = preg_replace('/{.*?}/', '', $text);
		return $text;
	}

	/** 对部分背景壁纸效果的适配CSS代码 */
	static public function backgroundAdaptive()
	{
		return '
		html .joe_footer .joe_container>.item,
	html .joe_footer .joe_container a,
	html .joe_bread__bread .item,
	html .joe_bread__bread .item .link,
	html .text-muted,
	html .joe_index__title-title>.item,
	html .joe_index__title-notice>a {
	color: var(--classC);
	}

	html .joe_bread__bread>.item>.icon {
	fill: var(--classC);
	}

	html .text-muted>a {
	color: var(--classD);
	}

	html .joe_action_item {
	background: var(--back-trn-85);
	}
	';
	}
}

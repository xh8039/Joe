<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
session_start();
$action = $_POST['action'];
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
$html = '<!DOCTYPE html><html lang="zh-cn"><head><meta charset="UTF-8"><meta name="viewport"content="width=device-width, initial-scale=1.0"><title>{title}</title></head><body><style>.Joe{width:550px;margin:0 auto;border-radius:8px;overflow:hidden;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.Joe_title{color:#fff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400%400%;background-position:50%100%;padding:15px;font-size:15px;line-height:1.5}</style><div class="Joe"><div class="Joe_title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;"><div style="margin-bottom: 20px;line-height: 1.5;">{subtitle}</div><div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div></body></html>';
switch ($action) {
	case 'code':
		$this->geetest($_POST['info']);
		break;
	case 'login':
		$username = $_POST['username'];
		$password = $_POST['password'];
		if (!isset($username)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入用户名/邮箱'
		]);
		if (!isset($password)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入密码'
		]);
		sleep(1);
		$db = Typecho_Db::get();
		$user = $db->select('uid', 'name', 'password')->from('table.users')->where('name = ?', $username)->limit(1);
		$mail = $db->select('uid', 'mail', 'password')->from('table.users')->where('mail = ?', $username)->limit(1);
		if (!$result = $db->fetchAll($user)) {
			if (!$result = $db->fetchAll($mail)) {
				$this->response->throwJson([
					'code' => 0,
					'msg' => '账户或密码错误'
				]);
			}
		}
		if ('$P$' == substr($result[0]['password'], 0, 3)) {
			$hasher = new PasswordHash(8, true);
			$hashValidate = $hasher->CheckPassword($password, $result[0]['password']);
		} else {
			$hashValidate = Typecho_Common::hashValidate($password, $result[0]['password']);
		}

		if ($hashValidate) {
			joe\user_login($result[0]['uid']);
			$this->response->throwJson([
				'code' => 1,
				'msg' => '登录成功'
			]);
		} else {
			$this->response->throwJson([
				'code' => 0,
				'msg' => '账户或密码错误'
			]);
		}
		break;

	case 'register':
		$nickname = $_POST['nickname'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$code = isset($_POST['code']) ? $_POST['code'] : null;
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];
		if (!isset($nickname)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入昵称'
		]);
		if (!isset($username)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入用户名'
		]);
		if (!isset($email)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入邮箱'
		]);
		if (joe\email_config()) {
			if (!isset($code)) $this->response->throwJson([
				'code' => 0,
				'msg' => '请输入验证码'
			]);
		}
		if (!isset($password)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入密码'
		]);
		if (!isset($cpassword)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入确认密码'
		]);
		if ($cpassword != $password) $this->response->throwJson([
			'code' => 0,
			'msg' => '两次密码不一致'
		]);
		if (mb_strlen($nickname, 'UTF-8') > 10) $this->response->throwJson([
			'code' => 0,
			'msg' => '昵称不能超过10个字符'
		]);
		if (!preg_match('/^[A-Za-z0-9]{4,30}$/i', $username)) $this->response->throwJson([
			'code' => 0,
			'msg' => '账号必须由4-30位字母或数字组成'
		]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入正确的邮箱地址'
		]);
		if (mb_strlen($password, 'UTF-8') < 6) $this->response->throwJson([
			'code' => 0,
			'msg' => '密码不能少于6位'
		]);
		sleep(1);
		$db = Typecho_Db::get();
		if ($db->fetchAll($db->select('uid')->from('table.users')->where('screenName = ?', $nickname)->limit(1))) {
			$this->response->throwJson([
				'code' => 0,
				'msg' => '昵称已被其它小伙伴使用'
			]);
		}
		if ($db->fetchAll($db->select('uid')->from('table.users')->where('name = ?', $username)->limit(1))) {
			$this->response->throwJson([
				'code' => 0,
				'msg' => '你输入的用户名已经被注册'
			]);
		}
		if ($db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))) {
			$this->response->throwJson([
				'code' => 0,
				'msg' => '你输入的邮箱已经注册账号'
			]);
		}
		if (joe\email_config()) {
			if ($_SESSION["Gm_Reg_Code"] != $code || $_SESSION["Gm_Reg_Email"] != trim($email)) {
				$this->response->throwJson([
					'code' => 0,
					'msg' => '验证码错误或已过期'
				]);
			}
		}
		$hasher = new PasswordHash(8, true);
		$data = array(
			'name' => $username,
			'screenName' => $nickname,
			'mail' => $email,
			'password' => $hasher->HashPassword($password),
			'created' => time(),
			'group' => empty(Helper::options()->JUser_Register_Group) ? 'contributor' : Helper::options()->JUser_Register_Group
		);
		$result = Typecho_Widget::widget('Widget_Abstract_Users')->insert($data);
		if ($result) {
			$_SESSION['Gm_Reg_Code'] = null;
			$_SESSION['Gm_Reg_Email'] = null;
			joe\user_login($result);
			$this->response->throwJson([
				'code' => 1,
				'msg' => '注册成功'
			]);
		} else {
			$this->response->throwJson([
				'code' => 0,
				'msg' => '服务器异常，请稍后重试'
			]);
		}
		break;

	case 'forget':
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];
		$state = $_POST['state'];
		if (!isset($state)) $this->response->throwJson([
			'code' => 0,
			'msg' => '非法访问'
		]);
		if (!isset($password)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入密码'
		]);
		if (!isset($cpassword)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入确认密码'
		]);
		if ($cpassword != $password) $this->response->throwJson([
			'code' => 0,
			'msg' => '两次密码不一致'
		]);
		if (mb_strlen($password, 'UTF-8') < 6) $this->response->throwJson([
			'code' => 0,
			'msg' => '密码不能少于6位'
		]);
		sleep(1);
		$db = Typecho_Db::get();
		if (!$_SESSION["Gm_Forget_state"] || $_SESSION["Gm_Forget_state"] != $state) {
			$this->response->throwJson([
				'code' => 0,
				'msg' => '验证码错误或已过期'
			]);
		} else if (!$uid = $_SESSION[$state]) {
			$_SESSION['Gm_Forget_state'] = null;
			$this->response->throwJson([
				'code' => 0,
				'msg' => '验证已失效'
			]);
		} else if (!$db->fetchAll($db->select('uid')->from('table.users')->where('uid = ?', $uid)->limit(1))) {
			$_SESSION['Gm_Forget_state'] = null;
			$this->response->throwJson([
				'code' => 0,
				'msg' => '用户不存在' . $uid
			]);
		} else {
			$hasher = new PasswordHash(8, true);
			$update = $db->update('table.users')->rows([
				'password' => $hasher->HashPassword($password)
			])->where('uid=?', $uid);
			$updateRows = $db->query($update);
			if ($updateRows) {
				$_SESSION[$state] = null;
				joe\user_login($uid);
				$this->response->throwJson([
					'code' => 1,
					'msg' => '设置新密码成功'
				]);
			} else {
				$this->response->throwJson([
					'code' => 0,
					'msg' => '服务器异常，请稍后重试'
				]);
			}
		}
		break;

	case 'forget_check':
		$code = $_POST['code'];
		$email = $_POST['email'];
		if (!isset($code)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入验证码'
		]);
		if (!isset($email)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入邮箱'
		]);
		sleep(1);
		$db = Typecho_Db::get();
		if (!$user = $db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))) {
			$this->response->throwJson([
				'code' => 0,
				'msg' => '你输入的邮箱未注册账号'
			]);
		}
		if ($_SESSION["Gm_Forget_Code"] != $code || $_SESSION["Gm_Forget_email"] != $email) {
			$_SESSION['Gm_Forget_Code'] = null;
			$_SESSION['Gm_Forget_email'] = null;
			$this->response->throwJson([
				'code' => 0,
				'msg' => '验证码错误或已过期'
			]);
		} else {
			$_SESSION['Gm_Forget_Code'] = null;
			$_SESSION['Gm_Forget_email'] = null;
			$state = md5(rand(100000, 999999) . time() . rand(100000, 999999));
			$_SESSION["Gm_Forget_state"] = $state;
			$_SESSION[$state] = $user[0]['uid'];
			$this->response->throwJson([
				'code' => 1,
				'msg' => '验证码正确',
				'state' => $_SESSION["Gm_Forget_state"]
			]);
		}
		break;

	case 'reg_code':
		$email = $_POST['email'];
		if (!isset($email)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入邮箱后发送验证码'
		]);
		$db = Typecho_Db::get();
		if ($db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))) $this->response->throwJson([
			'code' => 0,
			'msg' => '你输入的邮箱已经注册账号'
		]);
		$send_time = time() - isset($_SESSION['JOE_SEND_MAIL_TIME']) ? $_SESSION['JOE_SEND_MAIL_TIME'] : 0;
		if (isset($_SESSION['JOE_SEND_MAIL_TIME']) && $send_time <= 60) $this->response->throwJson([
			'code' => 0,
			'msg' => (60 - $send_time) . '秒后重可发验证码'
		]);
		$code = rand(100000, 999999);
		$_SESSION["Gm_Reg_Code"] = $code;
		$_SESSION["Gm_Reg_Email"] = $email;
		$mail->Body = strtr(
			$html,
			array(
				"{title}" => '注册验证 - ' . $this->options->title,
				"{subtitle}" => '您正在进行注册操作，验证码是:',
				"{content}" => $code,
			)
		);
		$mail->addAddress($email);
		$mail->Subject = '注册验证 - ' . $this->options->title;
		if ($mail->send()) {
			$_SESSION['JOE_SEND_MAIL_TIME'] = time();
			$this->response->throwJson([
				'code' => 1,
				'msg' => '验证码已发送到您的邮箱'
			]);
		} else {
			$this->response->throwJson([
				'code' => 0,
				'msg' => $mail->ErrorInfo
			]);
		}

		break;

	case 'forget_code':
		$email = $_POST['email'];
		if (!isset($email)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入邮箱后发送验证码'
		]);
		$db = Typecho_Db::get();
		if (!$db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))) $this->response->throwJson([
			'code' => 0,
			'msg' => '你输入的邮箱未注册账号'
		]);
		$send_time = time() - isset($_SESSION['JOE_SEND_MAIL_TIME']) ? $_SESSION['JOE_SEND_MAIL_TIME'] : 0;
		if (isset($_SESSION['JOE_SEND_MAIL_TIME']) && $send_time <= 60) $this->response->throwJson([
			'code' => 0,
			'msg' => (60 - $send_time) . '秒后重可发验证码'
		]);
		$code = rand(100000, 999999);
		$_SESSION["Gm_Forget_Code"] = $code;
		$_SESSION["Gm_Forget_email"] = $email;
		$mail->Body = strtr(
			$html,
			array(
				"{title}" => '重置密码 - ' . $this->options->title,
				"{subtitle}" => '您正在进行重置密码操作，验证码是:',
				"{content}" => $code,
			)
		);
		$mail->addAddress($email);
		$mail->Subject = '重置密码 - ' . $this->options->title;
		if ($mail->send()) {
			$_SESSION['JOE_SEND_MAIL_TIME'] = time();
			$this->response->throwJson([
				'code' => 1,
				'msg' => '验证码已发送到您的邮箱'
			]);
		} else {
			$this->response->throwJson([
				'code' => 0,
				'msg' => $mail->ErrorInfo
			]);
		}
		break;
	default:
		$this->response->throwJson([
			'code' => 0,
			'msg' => 'api error'
		]);
		break;
}

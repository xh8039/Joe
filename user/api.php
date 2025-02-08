<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
session_start();
$action = $_POST['action'];
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
		$db = Typecho\Db::get();
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
		$db = Typecho\Db::get();
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
		$result = Typecho\Widget::widget('Widget_Abstract_Users')->insert($data);
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
		$db = Typecho\Db::get();
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
		$db = Typecho\Db::get();
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
		$db = Typecho\Db::get();
		if ($db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))) $this->response->throwJson([
			'code' => 0,
			'msg' => '你输入的邮箱已经注册账号'
		]);
		$send_time = time() - (isset($_SESSION['JOE_SEND_MAIL_TIME']) ? $_SESSION['JOE_SEND_MAIL_TIME'] : 0);
		if (isset($_SESSION['JOE_SEND_MAIL_TIME']) && $send_time <= 60) $this->response->throwJson([
			'code' => 0,
			'msg' => (60 - $send_time) . '秒后重可发验证码'
		]);
		$code = rand(100000, 999999);
		$_SESSION["Gm_Reg_Code"] = $code;
		$_SESSION["Gm_Reg_Email"] = $email;
		$send_email = joe\send_email('注册验证', '您正在进行注册操作，验证码是：', $code, $email);
		if ($send_email === true) {
			$_SESSION['JOE_SEND_MAIL_TIME'] = time();
			$this->response->throwJson([
				'code' => 1,
				'msg' => '验证码已发送到您的邮箱'
			]);
		} else {
			$this->response->throwJson([
				'code' => 0,
				'msg' => $send_email
			]);
		}

		break;

	case 'forget_code':
		$email = $_POST['email'];
		if (!isset($email)) $this->response->throwJson([
			'code' => 0,
			'msg' => '请输入邮箱后发送验证码'
		]);
		$db = Typecho\Db::get();
		if (!$db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))) $this->response->throwJson([
			'code' => 0,
			'msg' => '你输入的邮箱未注册账号'
		]);
		$send_time = time() - (isset($_SESSION['JOE_SEND_MAIL_TIME']) ? $_SESSION['JOE_SEND_MAIL_TIME'] : 0);
		if (isset($_SESSION['JOE_SEND_MAIL_TIME']) && $send_time <= 60) $this->response->throwJson([
			'code' => 0,
			'msg' => (60 - $send_time) . '秒后重可发验证码'
		]);
		$code = rand(100000, 999999);
		$_SESSION["Gm_Forget_Code"] = $code;
		$_SESSION["Gm_Forget_email"] = $email;
		$send_email = joe\send_email('重置密码', '您正在进行重置密码操作，验证码是：', $code, $email);
		if ($send_email === true) {
			$_SESSION['JOE_SEND_MAIL_TIME'] = time();
			$this->response->throwJson([
				'code' => 1,
				'msg' => '验证码已发送到您的邮箱'
			]);
		} else {
			$this->response->throwJson([
				'code' => 0,
				'msg' => $send_email
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

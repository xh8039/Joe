<?php

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$path_info_explode = explode('/', $self->request->getPathInfo());
$action = empty($path_info_explode[3]) ? ($_POST['action'] ?? '') : $path_info_explode[3];

\Widget\User::alloc()->to($user_widget);
switch ($action) {
	case 'code':
		$this->geetest($_POST['info']);
		break;

	case 'register':
		/** 如果已经登录 */
		if (!$this->options->allowRegister) $this->response->throwJson(['message' => '禁止用户注册！']);

		\Widget\User::alloc()->to($register_widget);

		/** 初始化验证类 */
		$validator = new Typecho\Validate();
		$validator->addRule('nickname', 'required', _t('必须填写用户昵称'));
		$validator->addRule('nickname', 'maxLength', _t('昵称最多包含10个字符'), 10);

		$validator->addRule('username', 'required', _t('必须填写账号'));
		$validator->addRule('username', 'minLength', _t('账号至少包含2个字符'), 3);
		$validator->addRule('username', 'maxLength', _t('账号最多包含32个字符'), 30);
		$validator->addRule('username', 'xssCheck', _t('请不要在账号中使用特殊字符'));
		$validator->addRule('username', function (string $username) {
			return preg_match('/^[A-Za-z0-9]{3,30}$/i', $username);
		}, _t('账号必须由字母或数字组成'));
		$validator->addRule('username', [$register_widget, 'nameExists'], _t('账号已经存在'));

		$validator->addRule('email', 'required', _t('必须填写邮箱'));
		$validator->addRule('email', [$register_widget, 'mailExists'], _t('邮箱已经存在'));
		$validator->addRule('email', 'email', _t('邮箱格式错误'));
		$validator->addRule('email', 'maxLength', _t('邮箱最多包含64个字符'), 64);

		$validator->addRule('password', 'required', _t('必须填写密码'));
		$validator->addRule('password', 'minLength', _t('为了保证账户安全, 请输入至少六位的密码'), 6);
		$validator->addRule('password', 'maxLength', _t('为了便于记忆, 密码长度请不要超过十八位'), 18);
		$validator->addRule('confirm_password', 'confirm', _t('两次输入的密码不一致'), 'password');

		/** 截获验证异常 */
		$error = $validator->run($this->request->from('nickname', 'username', 'email', 'password', 'confirm_password'));
		if ($error) $this->response->throwJson(['message' => implode('，', $error)]);

		if (Db::name('users')->where('screenName', $_POST['nickname'])->find()) {
			$this->response->throwJson(['message' => '昵称已被其它小伙伴使用了']);
		}

		if (joe\email_config()) {
			if (empty($_SESSION['joe_register_captcha'])) $this->response->throwJson(['message' => '请先发送邮箱验证码']);
			if ($_SESSION['joe_register_captcha'] != $this->request->captcha || $_SESSION['joe_register_email'] != trim($this->request->email)) $this->response->throwJson(['message' => '验证码错误或已过期']);
		}

		$hasher = new Utils\PasswordHash(8, true);
		$group = empty($this->options->JUserRegisterGroup) ? 'subscriber' : $this->options->JUserRegisterGroup;

		$dataStruct = Widget\Register::pluginHandle()->register([
			'name' => $this->request->username,
			'mail' => $this->request->email,
			'screenName' => $this->request->nickname,
			'password' => $hasher->hashPassword($this->request->password),
			'created' => $this->options->time,
			'group' => $group
		]);

		$insertId = $register_widget->insert($dataStruct);
		if (!$insertId) $this->response->throwJson(['message' => '服务器异常，请稍后重试']);

		$register_widget->push(Db::name('users')->where('uid', $insertId)->find());

		Widget\Register::pluginHandle()->finishRegister($register_widget);

		$this->user->login($this->request->username, $this->request->password);

		Typecho\Cookie::delete('__typecho_first_run');
		Typecho\Cookie::delete('__typecho_remember_name');
		Typecho\Cookie::delete('__typecho_remember_mail');
		$_SESSION['joe_register_captcha'] = null;
		$_SESSION['joe_register_email'] = null;

		$this->response->throwJson(['code' => 200]);
		break;

	case 'register_captcha':
		$email = $_POST['email'];
		if (!isset($email)) $this->response->throwJson(['message' => '请输入邮箱后发送验证码']);
		if (Db::name('users')->where('mail', $email)->find()) $this->response->throwJson(['message' => '你输入的邮箱已经注册账号']);

		$_SESSION['joe_register_captcha'] = rand(100000, 999999);
		$_SESSION['joe_register_email'] = $email;
		$send_email = joe\send_email('注册验证', '您正在进行注册操作，验证码是：', $_SESSION['joe_register_captcha'], $email, 60);
		if ($send_email === true) {
			$this->response->throwJson(['code' => 200]);
		} else {
			$this->response->throwJson(['message' => $send_email]);
		}
		break;

	case 'retrieve':
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];
		$state = $_POST['state'];
		if (!isset($state)) $this->response->throwJson(['message' => '非法访问']);
		if (!isset($password)) $this->response->throwJson(['message' => '请输入密码']);
		if (!isset($confirm_password)) $this->response->throwJson(['message' => '请输入确认密码']);
		if ($confirm_password != $password) $this->response->throwJson(['message' => '两次密码不一致']);
		if (mb_strlen($password, 'UTF-8') < 6) $this->response->throwJson(['message' => '密码不能少于6位']);
		if (!$_SESSION['joe_retrieve_state'] || $_SESSION['joe_retrieve_state'] != $state) {
			$this->response->throwJson([
				'message' => '验证码错误或已过期'
			]);
		} else if (!$uid = $_SESSION[$state]) {
			$_SESSION['joe_retrieve_state'] = null;
			$this->response->throwJson(['message' => '验证已失效']);
		} else if (!Db::name('users')->where('uid', $uid)->find()) {
			$_SESSION['joe_retrieve_state'] = null;
			$this->response->throwJson(['message' => '用户不存在' . $uid]);
		} else {
			$hasher = new PasswordHash(8, true);
			$updateRows = Db::name('users')->where('uid', $uid)->update('password', $hasher->HashPassword($password));
			if ($updateRows) {
				$_SESSION[$state] = null;
				joe\user_login($uid);
				$this->response->throwJson(['code' => 200, 'message' => '设置新密码成功']);
			} else {
				$this->response->throwJson(['message' => '服务器异常，请稍后重试']);
			}
		}
		break;

	case 'retrieve_check':
		$code = $_POST['code'];
		$email = $_POST['email'];
		if (!isset($code)) $this->response->throwJson(['message' => '请输入验证码']);
		if (!isset($email)) $this->response->throwJson(['message' => '请输入邮箱']);
		$user = Db::name('users')->where('mail', $email)->find();
		if (!$user) $this->response->throwJson(['message' => '你输入的邮箱未注册账号']);
		if ($_SESSION['joe_retrieve_captcha'] != $code || $_SESSION['joe_retrieve_email'] != $email) {
			$_SESSION['joe_retrieve_captcha'] = null;
			$_SESSION['joe_retrieve_email'] = null;
			$this->response->throwJson(['message' => '验证码错误或已过期']);
		} else {
			$_SESSION['joe_retrieve_captcha'] = null;
			$_SESSION['joe_retrieve_email'] = null;
			$state = md5(rand(100000, 999999) . time() . rand(100000, 999999));
			$_SESSION['joe_retrieve_state'] = $state;
			$_SESSION[$state] = $user['uid'];
			$this->response->throwJson(['code' => 200, 'message' => '验证码正确', 'state' => $_SESSION['joe_retrieve_state']]);
		}
		break;

	case 'retrieve_captcha':
		$email = $_POST['email'];
		if (!isset($email)) $this->response->throwJson(['message' => '请输入邮箱后发送验证码']);
		if (!Db::name('users')->where('mail', $email)->find()) $this->response->throwJson(['message' => '你输入的邮箱未注册账号']);

		$_SESSION['joe_retrieve_captcha'] = rand(100000, 999999);
		$_SESSION['joe_retrieve_email'] = $email;
		$send_email = joe\send_email('重置密码', '您正在进行重置密码操作，验证码是：', $_SESSION["joe_retrieve_captcha"], $email, 60);
		if ($send_email === true) {
			$this->response->throwJson(['code' => 200, 'message' => '验证码已发送到您的邮箱']);
		} else {
			$this->response->throwJson(['message' => $send_email]);
		}
		break;

	default:
		$this->response->throwJson(['message' => 'API错误']);
		break;
}

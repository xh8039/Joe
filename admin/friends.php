<?php

/**
 * Joe再续前缘友链管理
 *
 * @package Joe再续前缘
 * @author  易航
 * @version 1.1
 * @update: 2025.03.14
 * @link http://blog.yihang.info
 */

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
define('JOE_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('THEME_NAME', basename(JOE_ROOT));
define('TYPECHO_ADMIN_ROOT', __TYPECHO_ROOT_DIR__ . __TYPECHO_ADMIN_DIR__);
/* Composer 自动加载 */
require_once JOE_ROOT . 'public/autoload.php';
/* ThinkORM 数据库配置 */
require_once JOE_ROOT . 'public/database.php';
/* 公用函数 */
require_once JOE_ROOT . 'public/function.php';

// $options = Typecho\Widget::widget('Widget\Options');
$notice = \Widget\Notice::alloc();
$action = $request->get('action', 'index');

function getFriends(array $id)
{
	if (empty($id)) return [];
	$friend_list = Db::name('friends')->whereIn('id', $id)->select();
	return $friend_list->toArray();
}

if ($action == 'index') {
	require_once __DIR__ . '/friends/table.php';
}

if ($action == 'create' || $action == 'edit') {
	require_once __DIR__ . '/friends/form.php';
}

if ($action == 'insert') {
	$insert = Db::name('friends')->insert([
		'title' => $_POST['title'],
		'url' =>  $_POST['url'],
		'logo' =>  $_POST['logo'],
		'description' =>  $_POST['description'],
		'rel' =>  $_POST['rel'],
		'email' => $_POST['email'],
		'position' => implode(',', $_POST['position']),
		'order' =>  $_POST['order'],
		'status' =>  $_POST['status']
	]);
	if ($insert) {
		$notice->set('添加友链 [' . $_POST['title'] . '] 成功', 'success');
		$response->goBack();
	} else {
		$notice->set('添加友链 [' . $_POST['title'] . '] 失败！', 'error');
		$response->goBack();
	}
}

if ($action == 'update') {
	$id = $request->get('id', '0');
	$friend = Db::name('friends')->where('id', $id)->find();
	if (!$friend) {
		$notice->set('友链 [' . $_POST['title'] . '] 不存在！', 'error');
		$response->goBack();
	} else {
		$update = Db::name('friends')->where('id', $id)->update([
			'title' => $_POST['title'],
			'url' =>  $_POST['url'],
			'logo' =>  $_POST['logo'],
			'description' =>  $_POST['description'],
			'rel' =>  $_POST['rel'],
			'email' => $_POST['email'],
			'position' => implode(',', $_POST['position']),
			'order' =>  $_POST['order'],
			'status' =>  $_POST['status']
		]);
		if ($update) {
			$notice->set('更新友链 [' . $_POST['title'] . '] 成功', 'success');
			$response->goBack();
		} else {
			$notice->set('更新友链 [' . $_POST['title'] . '] 失败！', 'error');
			$response->goBack();
		}
	}
}

if ($action == 'delete') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) {
		$notice->set('删除友链传入 ID 数据错误！', 'error');
		$response->goBack();
	}
	if (Helper::options()->JFriendsStatusEmail == 'on') $friends = getFriends($id);
	$delete = Db::name('friends')->whereIn('id', $id)->delete();
	if ($delete) {
		if (Helper::options()->JFriendsStatusEmail == 'on') {
			foreach ($friends as $key => $value) {
				if (!empty($value['email']) && preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value['email'])) {
					joe\send_mail('您的友情链接已被删除', '', [
						'友链标题' => $value['title'],
						'友链网址' => $value['url'],
						'友链描述' => $value['description'],
					], $value['email']);
				}
			}
		}
		$notice->set('删除友链 [' . implode(',', $id) . '] 成功', 'success');
		$response->goBack();
	} else {
		$notice->set('删除友链 [' . implode(',', $id) . '] 失败！', 'error');
		$response->goBack();
	}
}

if ($action == 'open') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) $id = [];
	$update = Db::name('friends')->whereIn('id', $id)->update(['status' => 1]);
	if ($update) {
		if (Helper::options()->JFriendsStatusEmail == 'on') {
			$friends = getFriends($id);
			foreach ($friends as $value) {
				if (!empty($value['email']) && preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value['email'])) {
					joe\send_mail('您的友情链接已通过审核', '', [
						'友链标题' => $value['title'],
						'友链网址' => $value['url'],
						'友链描述' => $value['description'],
					], $value['email']);
				}
			}
		}
		$notice->set('启用友链 [' . implode(',', $id) . '] 成功', 'success');
		$response->goBack();
	} else {
		$notice->set('启用友链 [' . implode(',', $id) . '] 失败！', 'error');
		$response->goBack();
	}
}

if ($action == 'disable') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) $id = [];
	$update = Db::name('friends')->where('id', $id)->update(['status' => 0]);
	if ($update) {
		if (Helper::options()->JFriendsStatusEmail == 'on') {
			$friends = getFriends($id);
			foreach ($friends as $value) {
				if (!empty($value['email']) && preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value['email'])) {
					joe\send_mail('您的友情链接已被禁用', '', [
						'友链标题' => $value['title'],
						'友链网址' => $value['url'],
						'友链描述' => $value['description'],
					], $value['email']);
				}
			}
		}
		$notice->set('禁用友链 [' . implode(',', $id) . '] 成功', 'success');
		$response->goBack();
	} else {
		$notice->set('禁用友链 [' . implode(',', $id) . '] 失败！', 'error');
		$response->goBack();
	}
}

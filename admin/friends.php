<?php

/**
 * Joe再续前缘友链管理
 *
 * @package Joe再续前缘
 * @author  易航
 * @version 1.0
 * @update: 2024.10.24
 * @link http://blog.bri6.cn
 */

// include 'common.php';
if (!defined('__TYPECHO_ROOT_DIR__')) {
	exit;
}
define('JOE_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('THEME_NAME', basename(JOE_ROOT));
define('TYPECHO_ADMIN_ROOT', __TYPECHO_ROOT_DIR__ . __TYPECHO_ADMIN_DIR__);
require_once JOE_ROOT . 'public/function.php';

$options = Typecho_Widget::widget('Widget_Options');
$action = empty($_REQUEST['action']) ? 'index' : $_REQUEST['action'];
$db = Typecho_Db::get();

function alert($content)
{
	$referer = empty($_REQUEST['referer']) ? Typecho_Request::getInstance()->getHeader('referer') : $_REQUEST['referer'];
	$url = Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2F' . urlencode(THEME_NAME) . '%2Fadmin%2Ffriends.php&action=index');
	$href = empty($referer) ? $url : $referer;
	echo "<script>alert('$content');window.location.href='$href';</script>";
}
function location()
{
	$referer = empty($_REQUEST['referer']) ? Typecho_Request::getInstance()->getHeader('referer') : $_REQUEST['referer'];
	$url = Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2F' . urlencode(THEME_NAME) . '%2Fadmin%2Ffriends.php&action=index');
	$href = empty($referer) ? $url : $referer;
	echo "<script>window.location.href='$href';</script>";
}
function LinkExists($id)
{
	$db = Typecho_Db::get();
	$link = $db->fetchRow($db->select()->from('table.friends')->where('id = ?', $id)->limit(1));
	return $link ? true : false;
}
function getFriends(array $id)
{
	if (empty($id)) return [];
	$db = Typecho_Db::get();
	$link = $db->fetchAll($db->select()->from('table.friends')->where('id in?', $id));
	if (!is_array($link)) return [];
	return $link;
}

if ($action == 'index') {
	require_once __DIR__ . '/friends/index.php';
}

if ($action == 'create' || $action == 'edit') {
	require_once __DIR__ . '/friends/form.php';
}

if ($action == 'insert') {
	$sql = $db->insert('table.friends')->rows(
		array(
			'title' => $_POST['title'],
			'url' =>  $_POST['url'],
			'logo' =>  $_POST['logo'],
			'description' =>  $_POST['description'],
			'rel' =>  $_POST['rel'],
			'email' => $_POST['email'],
			'order' =>  $_POST['order'],
			'status' =>  $_POST['status']
		)
	);
	if ($db->query($sql)) {
		location();
		// alert('添加友链 [' . $_POST['title'] . '] 成功');
	} else {
		alert('添加友链 [' . $_POST['title'] . '] 失败！');
	}
}

if ($action == 'update') {
	if (LinkExists($_POST['id'])) {
		$sql = $db->update('table.friends')->rows(
			array(
				'title' => $_POST['title'],
				'url' =>  $_POST['url'],
				'logo' =>  $_POST['logo'],
				'description' =>  $_POST['description'],
				'rel' =>  $_POST['rel'],
				'email' => $_POST['email'],
				'order' =>  $_POST['order'],
				'status' =>  $_POST['status']
			)
		)->where('id = ?', $_POST['id']);
		if ($db->query($sql)) {
			location();
		} else {
			alert('更新友链 [' . $_POST['title'] . '] 失败！');
		}
	} else {
		alert('友链不存在！');
	}
}

if ($action == 'delete') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) {
		alert('删除友链 ID 数据错误！');
		exit;
	}
	$sql = $db->delete('table.friends')->where('id in?', $id);
	if (Helper::options()->JFriendsStatusEmail == 'on') $friends = getFriends($id);
	if ($db->query($sql)) {
		if (Helper::options()->JFriendsStatusEmail == 'on') {
			foreach ($friends as $key => $value) {
				if (!empty($value['email']) && preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value['email'])) {
					joe\send_email('您的友情链接已被删除', '', '友情链接地址：' . $value['url'], $value['email']);
				}
			}
		}
		alert('删除友链成功');
	} else {
		alert('删除友链失败！');
	}
}

if ($action == 'open') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) $id = [];
	$sql = $db->update('table.friends')->rows(['status' => 1])->where('id in?', $id);
	if ($db->query($sql)) {
		if (Helper::options()->JFriendsStatusEmail == 'on') {
			$friends = getFriends($id);
			foreach ($friends as $value) {
				if (!empty($value['email']) && preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value['email'])) {
					joe\send_email('您的友情链接已通过审核', '', '<p>友情链接网址：' . $value['url'] . '</p><p>本站网址：' . Helper::options()->siteUrl . '</p>', $value['email']);
				}
			}
		}
		location();
	} else {
		alert('启用友链失败！');
	}
}

if ($action == 'disable') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) $id = [];
	$sql = $db->update('table.friends')->rows(['status' => 0])->where('id in?', $id);
	if ($db->query($sql)) {
		if (Helper::options()->JFriendsStatusEmail == 'on') {
			$friends = getFriends($id);
			foreach ($friends as $value) {
				if (!empty($value['email']) && preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value['email'])) {
					joe\send_email('您的友情链接已被禁用', '', '友情链接地址：' . $value['url'], $value['email']);
				}
			}
		}
		location();
	} else {
		alert('禁用友链失败！');
	}
}

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

$options = Typecho_Widget::widget('Widget_Options');
$action = empty($_REQUEST['action']) ? 'index' : $_REQUEST['action'];
$db = Typecho_Db::get();

function alert($content)
{
	$url = Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2F' . urlencode(THEME_NAME) . '%2Fadmin%2Ffriends.php&action=index');
	echo "<script>alert('$content');window.location.href='$url';</script>";
}
function location()
{
	$url = Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2F' . urlencode(THEME_NAME) . '%2Fadmin%2Ffriends.php&action=index');
	echo "<script>window.location.href='$url';</script>";
}
function LinkExists($id)
{
	$db = Typecho_Db::get();
	$link = $db->fetchRow($db->select()->from('table.friends')->where('id = ?', $id)->limit(1));
	return $link ? true : false;
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
			'qq' => $_POST['qq'],
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
				'qq' => $_POST['qq'],
				'order' =>  $_POST['order'],
				'status' =>  $_POST['status']
			)
		)->where('id = ?', $_POST['id']);
		if ($db->query($sql)) {
			location();
			// alert('更新友链 [' . $_POST['title'] . '] 成功');
		} else {
			alert('更新友链 [' . $_POST['title'] . '] 失败！');
		}
	} else {
		alert('友链不存在！');
	}
}
if ($action == 'delete') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) $id = [];
	$sql = $db->delete('table.friends')->where('id in?', $id);
	if ($db->query($sql)) {
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
		location();
		// alert('启用友链成功');
	} else {
		alert('启用友链失败！');
	}
}
if ($action == 'disable') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) $id = [];
	$sql = $db->update('table.friends')->rows(['status' => 0])->where('id in?', $id);
	if ($db->query($sql)) {
		location();
		// alert('禁用友链成功');
	} else {
		alert('禁用友链失败！');
	}
}

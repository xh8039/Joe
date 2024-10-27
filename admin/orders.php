<?php

/**
 * Joe再续前缘订单管理
 *
 * @package Joe再续前缘
 * @author  易航
 * @version 1.0
 * @update: 2024.10.23
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

function alert($content)
{
	$url = Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2F' . urlencode(THEME_NAME) . '%2Fadmin%2Forders.php&action=index');
	echo "<script>alert('$content');window.location.href='$url';</script>";
}
function location()
{
	$url = Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2F' . urlencode(THEME_NAME) . '%2Fadmin%2Forders.php&action=index');
	echo "<script>window.location.href='$url';</script>";
}

if ($action == 'index') {
	require_once __DIR__ . '/orders/index.php';
}

if ($action == 'delete') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) $id = [];
	$sql = $db->delete('table.joe_pay')->where('id in?', $id);
	if ($db->query($sql)) {
		alert('删除订单成功');
	} else {
		alert('删除订单失败！');
	}
}

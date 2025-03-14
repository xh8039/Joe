<?php

/**
 * Joe再续前缘订单管理
 *
 * @package Joe再续前缘
 * @author  易航
 * @version 1.1
 * @update: 2025.03.14
 * @link http://blog.yihang.info
 */

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	exit;
}
define('JOE_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('THEME_NAME', basename(JOE_ROOT));
define('TYPECHO_ADMIN_ROOT', __TYPECHO_ROOT_DIR__ . __TYPECHO_ADMIN_DIR__);
/* Composer 自动加载 */
require_once JOE_ROOT . 'public/autoload.php';
/* ThinkORM 数据库配置 */
require_once JOE_ROOT . 'public/database.php';

$notice = \Widget\Notice::alloc();
$action = $request->get('action', 'index');

if ($action == 'index') require_once __DIR__ . '/orders/table.php';

if ($action == 'delete') {
	$id = isset($_POST['id']) ? $_POST['id'] : [];
	if (!is_array($id)) {
		$notice->set('删除订单传入 ID 数据错误！', 'error');
		$response->goBack();
	}
	$delete = Db::name('orders')->whereIn('id', $id)->delete();
	if ($delete) {
		$notice->set('删除订单 [' . implode(',', $id) . '] 成功', 'success');
		$response->goBack();
	} else {
		$notice->set('删除订单 [' . implode(',', $id) . '] 失败！', 'error');
		$response->goBack();
	}
}

if ($action == 'clear') {
	$delete = Db::name('orders')->where('status', 0)->delete();
	if ($delete) {
		$notice->set('清理未支付订单成功', 'success');
		$response->goBack();
	} else {
		$notice->set('清理未支付订单失败！', 'error');
		$response->goBack();
	}
}

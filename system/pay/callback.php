<?php
/*
 * @Author: 易航
 * @Url: blog.yihang.info
 * @Date: 2024-10-22 00:00:00
 * @LastEditTime: 2024-10-23 00:00:00
 */

use think\facade\Db;

header('Content-type:text/html; Charset=utf-8');
if (!defined('JOE_ROOT')) define('JOE_ROOT', dirname(dirname(__DIR__)) . '/');

ob_start();
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DIRECTORY_SEPARATOR . 'config.inc.php';
\Widget\Init::alloc(); // 初始化组件
require_once JOE_ROOT . 'system/vendor/autoload.php';
require_once JOE_ROOT . 'public/database.php';
require_once  JOE_ROOT . 'public/function.php';
ob_end_clean();

if (empty($_REQUEST["sign"])) exit('非法请求');

$epay_config = [];
if (empty(Helper::options()->JYiPayApi)) exit('未配置易支付接口！');
$epay_config['apiurl'] = trim(Helper::options()->JYiPayApi);

if (empty(Helper::options()->JYiPayID)) exit('未配置易支付商户号！');
$epay_config['partner'] = trim(Helper::options()->JYiPayID);

if (empty(Helper::options()->JYiPayKey)) exit('未配置易支付商户密钥！');
$epay_config['key'] = trim(Helper::options()->JYiPayKey);

$redirect_url = null;
if (isset($_GET['redirect_url'])) {
	$redirect_url = empty($_GET['redirect_url']) ? null : $_GET['redirect_url'];
	// 移出多余参数，避免验证失败
	unset($_GET['redirect_url']);
}

// 计算得出通知验证结果
require_once __DIR__ . '/EpayCore.php';
$EpayCore      = new joe\pay\EpayCore($epay_config);
$verify_result = $EpayCore->verifyNotify();  //签名验证

if (!$verify_result || $_GET['trade_status'] != 'TRADE_SUCCESS') exit('验证失败！');

$order = Db::name('orders')->where('trade_no', $_GET['out_trade_no'])->find();
if (empty($order)) exit('订单不存在！');

if ($order['status']) {
	if ($redirect_url) {
		echo "<script>window.location.href='$redirect_url'</script>";
	} else {
		echo 'success';
	}
} else {
	// 更新订单状态
	$order_callback = Db::name('orders')->where('trade_no', $_GET['out_trade_no'])->update([
		'pay_type' => $_GET['type'],
		'pay_price' =>  $_GET['money'],
		'api_trade_no' =>  $_GET['trade_no'],
		'update_time' => date('Y-m-d H:i:s'),
		'status' => '1',
	]);
	if ($order_callback) {
		if ($redirect_url) {
			echo "<script>window.location.href='$redirect_url'</script>";
		} else {
			// 返回不在发送异步通知
			echo 'success';
		}
	} else {
		echo '订单数据更新失败！';
	}
}
if (Helper::options()->JPaymentOrderToAdminEmail == 'on' && !$order['admin_email']) {
	$type = ['alipay' => '支付宝', 'wxpay' => '微信', 'qqpay' => 'QQ'];
	$admin_email = joe\send_mail(
		'有新的订单已支付',
		'您的网站 [' . Helper::options()->title . '] 有新的订单已支付！',
		[
			'订单号' => $_GET['out_trade_no'],
			'商品类型' => trim(end(explode('-', $order['name']))),
			'商品' => $order['content_title'],
			'付款明细' => $type[$order['type']] . ' ' . $order['money'],
			'付款时间' => (empty($order['update_time']) ? date('Y-m-d H:i:s') : $order['update_time']),
		]
	);
	if ($admin_email === true) {
		Db::name('orders')->where('trade_no', $_GET['out_trade_no'])->update(['admin_email' => 1]);
	}
}
if (Helper::options()->JPaymentOrderEmail == 'on' && is_numeric($order['user_id']) && !$order['user_email']) {
	$user_info = Db::name('users')->where('uid', $order['user_id'])->find();
	if (!empty($user_info)) {
		$user_email = joe\send_mail(
			'订单支付成功！',
			'您好！' . $user_info['screenName'] . '，您在 [' . Helper::options()->title . '] 购买的商品已支付成功',
			[
				'类型' =>  trim(end(explode('-', $order['name']))),
				'商品' => $order['content_title'],
				'订单号' =>  $_GET['out_trade_no'],
				'付款明细' => $type[$order['type']] . ' ' . $order['money'],
				'付款时间' => (empty($order['update_time']) ? date('Y-m-d H:i:s') : $order['update_time'])
			],
			$user_info['mail']
		);
		if ($user_email === true) {
			Db::name('orders')->where('trade_no', $_GET['out_trade_no'])->update(['user_email' => 1]);
		}
	}
}

exit;

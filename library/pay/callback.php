<?php
/*
 * @Author: 易航
 * @Url: blog.bri6.cn
 * @Date: 2024-10-22 00:00:00
 * @LastEditTime: 2024-10-23 00:00:00
 */

header('Content-type:text/html; Charset=utf-8');

ob_start();
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DIRECTORY_SEPARATOR . 'config.inc.php';
$public_root = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
require_once $public_root . 'function.php';
ob_end_clean();

/** 初始化组件 */
\Widget\Init::alloc();

if (empty($_REQUEST["sign"])) {
	echo '非法请求';
	exit();
}

$epay_config = [];
if (empty(Helper::options()->JYiPayApi)) {
	exit('未配置易支付接口！');
}
$epay_config['apiurl'] = trim(Helper::options()->JYiPayApi);

if (empty(Helper::options()->JYiPayID)) {
	exit('未配置易支付商户号！');
}
$epay_config['partner'] = trim(Helper::options()->JYiPayID);

if (empty(Helper::options()->JYiPayKey)) {
	exit('未配置易支付商户密钥！');
}
$epay_config['key'] = trim(Helper::options()->JYiPayKey);

$redirect_url = null;
if (isset($_GET['redirect_url'])) {
	$redirect_url = empty($_GET['redirect_url']) ? null : $_GET['redirect_url'];
	// 移出多余参数，避免验证失败
	unset($_GET['redirect_url']);
}

// 计算得出通知验证结果
require_once __DIR__ . '/EpayCore.php';
$EpayCore      = new Joe\library\pay\EpayCore($epay_config);
$verify_result = $EpayCore->verifyNotify();  //签名验证

if ($verify_result && $_GET['trade_status'] == 'TRADE_SUCCESS') {
	// 验证成功
	// 本地订单处理
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select()->from('table.joe_pay')->where('trade_no = ?', $_GET['out_trade_no'])->limit(1));
	if (sizeof($row) > 0) {
		require_once $public_root . 'phpmailer.php';
		require_once $public_root . 'smtp.php';
		if (Helper::options()->JPaymentOrderToAdminEmail == 'on' && !$row['admin_email']) {
			$type = ['alipay' => '支付宝', 'wxpay' => '微信', 'qqpay' => 'QQ'];
			$admin_email = joe\send_email('有新的订单已支付', '您的网站 [' . Helper::options()->title . '] 有新的订单已支付！', '
			<p>订单号：' . $_GET['out_trade_no'] . '</p>
			<p>商品类型：' . trim(end(explode('-', $row['name']))) . '</p>
			<p>商品：' . $row['content_title'] . '</p>
			<p>付款明细：' . $type[$row['type']] . ' ' . $row['money'] . '</p>
			<p>付款时间：' . (empty($row['update_time']) ? date('Y-m-d H:i:s') : $row['update_time']) . '</p>
			');
			if ($admin_email == 'success') {
				// 更新订单状态
				$db->query($db->update('table.joe_pay')->rows(['admin_email' => 1,])->where('trade_no = ?', $_GET['out_trade_no']));
			}
		}
		if (Helper::options()->JPaymentOrderEmail == 'on' && is_numeric($row['user_id']) && !$row['user_email']) {
			$authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', $row['user_id']));
			if (sizeof($authoInfo) > 0) {
				$user_email = joe\send_email('订单支付成功！', '您好！' . $authoInfo['screenName'] . '，您在 [' . Helper::options()->title . '] 购买的商品已支付成功', '
				<p>类型：' . trim(end(explode('-', $row['name']))) . '</p>
				<p>商品：' . $row['content_title'] . '</p>
				<p>订单号：' . $_GET['out_trade_no'] . '</p>
				<p>付款明细：' . $type[$row['type']] . ' ' . $row['money'] . '</p>
				<p>付款时间：' . (empty($row['update_time']) ? date('Y-m-d H:i:s') : $row['update_time']) . '</p>
				', $authoInfo['mail']);
				if ($user_email == 'success') {
					$db->query($db->update('table.joe_pay')->rows(['user_email' => 1,])->where('trade_no = ?', $_GET['out_trade_no']));
				}
			}
		}
		if ($row['status']) {
			if ($redirect_url) {
				echo "<script>window.location.href='$redirect_url'</script>";
			} else {
				echo 'success';
			}
		} else {
			// 更新订单状态
			$sql = $db->update('table.joe_pay')->rows([
				'pay_type' => $_GET['type'],
				'pay_price' =>  $_GET['money'],
				'api_trade_no' =>  $_GET['trade_no'],
				'update_time' => date('Y-m-d H:i:s'),
				'status' => '1',
			])->where('trade_no = ?', $_GET['out_trade_no']);
			if ($db->query($sql)) {
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
	} else {
		echo '订单不存在！';
	}
} else {
	echo '验证失败！';
}

exit;

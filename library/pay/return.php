<?php

header('Content-type:text/html; Charset=utf-8');

ob_start();
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DIRECTORY_SEPARATOR . 'config.inc.php';
ob_end_clean();

/** 初始化组件 */
\Widget\Init::alloc();

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

$redirect_url = $_GET['redirect_url'];
unset($_GET['redirect_url']);

//计算得出通知验证结果
require_once __DIR__ . '/EpayCore.php';
$epay = new Joe\library\pay\EpayCore($epay_config);
$verify_result = $epay->verifyReturn($_GET);

if ($verify_result) { //验证成功
	// 验证成功
	// 本地订单处理
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select()->from('table.joe_pay')->where('trade_no = ?', $_GET['out_trade_no'])->limit(1));
	if (sizeof($row) > 0) {
		if ($row['status'] != 0) {
			echo "<script>window.location.href = '$redirect_url'</script>";
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
				echo "<script>window.location.href = '$redirect_url'</script>";
			} else {
				echo '<h3>订单数据更新失败！</h3>';
			}
		}
	} else {
		echo '<h3>订单不存在！</h3>';
	}
} else {
	//验证失败
	echo "<h3>验证失败</h3>";
}

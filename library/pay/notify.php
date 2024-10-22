<?php
/*
 * @Author: Qinver
 * @Url: zibll.com
 * @Date: 2021-04-12 00:20:44
 * @LastEditTime: 2024-04-22 18:10:21
 */

header('Content-type:text/html; Charset=utf-8');

ob_start();
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DIRECTORY_SEPARATOR . 'config.inc.php';
// require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'function.php';
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

require_once __DIR__ . '/EpayCore.php';
$EpayCore      = new Joe\library\pay\EpayCore($epay_config);
$verify_result = $EpayCore->verifyNotify();  //签名验证

if ($verify_result && $_GET['trade_status'] == 'TRADE_SUCCESS') {
	// 验证成功
	// 本地订单处理
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select()->from('table.joe_pay')->where('trade_no = ?', $_GET['out_trade_no'])->limit(1));
	if (sizeof($row) > 0) {
		if ($row['status'] != 0) {
			echo 'success';
		} else {
			// 更新订单状态
			$sql = $db->update('table.contents')->rows([
				'pay_type' => $_GET['type'],
				'pay_price' =>  $_GET['money'],
				'api_trade_no' =>  $_GET['trade_no'],
				'update_time' => date('Y-m-d H:i:s'),
				'status' => '1',
			])->where('trade_no = ?', $_GET['out_trade_no']);
			if ($db->query($sql)) {
				/**返回不在发送异步通知 */
				echo 'success';
			} else {
				echo '订单数据更新失败！';
			}
		}
	} else {
		echo '订单不存在！';
	}
}

exit();

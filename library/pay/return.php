<?php
//计算得出通知验证结果
require_once __DIR__ . '/EpayCore.php';
$epay = new Joe\library\pay\EpayCore($epay_config);
$verify_result = $epay->verifyReturn($_GET);

if ($verify_result) { //验证成功
	// 验证成功
	// 本地订单处理
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select('trade_no')->from('table.joe_pay')->where('trade_no = ?', $_GET['out_trade_no']));
	if (sizeof($row) > 0) {
		// 更新订单状态
		$sql = $db->update('table.contents')->rows([
			'pay_type' => $_GET['type'],
			'pay_price' =>  $_GET['money'],
			'api_trade_no' =>  $_GET['trade_no'],
		])->where('cid = ?', $cid);
		if ($db->query($sql)) {
			?>
			<script>
				window.location.href = '<?= $_GET['redirect_url'] ?>'
			</script>
			<?php
		} else {
			echo '<h3>订单数据更新失败！</h3>';
		}
	} else {
		echo '<h3>订单不存在！</h3>';
	}
} else {
	//验证失败
	echo "<h3>验证失败</h3>";
}

<?php

namespace think\facade;

class DbLog
{
	public static $logs = [];
	public static function list(): array
	{
		return self::$logs;
	}
}

(function () {
	$DB = \Typecho\Db::get();
	$adapter = $DB->getAdapter()->getDriver();
	$config = $DB->getConfig(1);
	// 数据库配置信息设置（全局有效）
	\think\facade\Db::setConfig([
		// 默认数据连接标识
		'default'     => $adapter,
		// 数据库连接信息
		'connections' => [
			$adapter => [
				// 数据库类型
				'type'     => $adapter,
				// 服务器地址
				'hostname' => $config['host'],
				// 数据库名
				'database' => $config['database'],
				// 数据库用户名
				'username' => $config['user'],
				// 数据库密码
				'password'    => $config['password'],
				// 数据库连接端口
				'hostport'    => $config['port'],
				// 数据库编码默认采用utf8
				'charset'  => $config['charset'],
				// 数据库表前缀
				'prefix'   => $DB->getPrefix(),
				// 数据库调试模式
				'debug'    => true,
				// SQL监听（日志）
				'trigger_sql' => \Helper::options()->JoeDeBug == 'on',
			],
		],
	]);
	if (\Helper::options()->JoeDeBug != 'on') return;
	\think\facade\Db::setLog(function ($type, $log) {
		DbLog::$logs[] = $log;
	});
})();

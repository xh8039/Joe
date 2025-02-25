<?php

namespace joe;

// 自动载入类
class AutoLoader
{

	private static $loadedClasses = [];

	// 向PHP注册在自动载入函数
	public static function register()
	{
		spl_autoload_register(array(new self, 'autoload'));
		spl_autoload_extensions('.php');
	}

	// 根据类名载入所在文件
	public static function autoload($class_name)
	{
		if (str_starts_with($class_name, 'joe')) {
			$class_name = str_starts_replace('joe\\', 'system\\', $class_name);
		} else {
			return;
		}
		// 类名重复检测
		if (in_array($class_name, self::$loadedClasses)) return;
		$system = JOE_ROOT . DIRECTORY_SEPARATOR;
		$class_file = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $system . $class_name) . '.php';
		if (file_exists($class_file)) {
			self::$loadedClasses[] = $class_name;
			require_once $class_file;
			if (method_exists($class_name, 'initialize')) {
				$method = new \ReflectionMethod($class_name, 'initialize');
				if ($method->isStatic()) {
					$class_name::initialize();
				}
			}
		}
	}
}

// 系统库自动载入
AutoLoader::register();

/* Composer 自动加载 */
require_once JOE_ROOT . 'system/vendor/autoload.php';

<?php

/**
 * @package Curl
 * @author  易航
 * @version 1.0.0
 * @link    https://gitee.com/yh_IT/php_curl
 *
 **/

class Curl
{
	private static $ch;

	/**
	 * 请求配置
	 * @access public
	 * @return array
	 */
	private static $requset_config = [
		'header' => [
			'Accept: */*',
			'Accept-Encoding: gzip,deflate,sdch',
			'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
			'Connection:close',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.102 Safari/537.36 Edg/104.0.1293.70'
		],
		'param' => [],
		'cookie' => null,
		// 连接时间
		'connect_time' => 10,
		// 读取时间
		'read_time' => null
	];

	/**
	 * 请求成功后响应内容
	 * @access public
	 * @return array
	 */
	public static $response = [];

	private static function init()
	{
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt(self::$ch, CURLOPT_ENCODING, "gzip");
		curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt(self::$ch, CURLOPT_HEADER, 1);
		curl_setopt(self::$ch, CURLOPT_HTTPHEADER, self::$requset_config['header']);  // 请求头
		curl_setopt(self::$ch, CURLOPT_CONNECTTIMEOUT, self::$requset_config['connect_time']);  // 请求时间
		curl_setopt(self::$ch, CURLOPT_TIMEOUT, self::$requset_config['read_time']); // 读取时间
	}

	/**
	 * 配置请求参数
	 * @access public
	 * @param array $config 要配置的CURL请求参数
	 * @return self
	 */
	public static function set($config)
	{
		foreach ($config as $key => $value) {
			if (!empty($value)) {
				self::$requset_config[$key] = $value;
			}
		}
		return self::class;
	}

	/**
	 * 配置请求头部参数
	 * @access public
	 * @param array $config 要配置的请求头部参数
	 * @return self
	 */
	public static function header($config)
	{
		foreach ($config as $key => $value) {
			if (!empty($value)) {
				self::$requset_config['header'][$key] = $value;
			}
		}
		return self::class;
	}

	/**
	 * 配置请求参数
	 * @access public
	 * @param array $config 要配置的请求体参数
	 * @return self
	 */
	public static function param($config)
	{
		foreach ($config as $key => $value) {
			if (!empty($value)) {
				self::$requset_config['param'][$key] = $value;
			}
		}
		return self::class;
	}

	/**
	 * GET请求
	 * @access public
	 * @param string $url 请求URL
	 * @return string
	 */
	public static function get($url)
	{
		self::$ch = curl_init();
		if (self::$requset_config['param']) {
			self::$requset_config['param'] = http_build_query(self::$requset_config['param']);
			$url = strstr($url, '?') ? trim($url, '&') . '&' .  self::$requset_config['param'] : $url . '?' .  self::$requset_config['param'];
		}
		return self::request($url);
	}

	/**
	 * POST请求
	 * @access public
	 * @param string $url 请求URL
	 * @return string
	 */
	public static function post($url)
	{
		self::$ch = curl_init();
		curl_setopt(self::$ch, CURLOPT_POST, 1);
		curl_setopt(self::$ch, CURLOPT_POSTFIELDS, self::$requset_config['param']);
		return self::request($url);
	}

	/**
	 * 处理请求
	 * @access private
	 * @param string $url 请求URL
	 * @return string
	 */
	private static function request($url)
	{
		self::init();
		curl_setopt(self::$ch, CURLOPT_URL, $url);
		$response_body = curl_exec(self::$ch);
		$http_code = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo(self::$ch, CURLINFO_HEADER_SIZE);
		curl_close(self::$ch);
		$header = substr($response_body, 0, $header_size);
		$header_explode = explode(PHP_EOL, $header);
		$header_explodes = [];
		$header_array = [];
		foreach ($header_explode as $value) {
			if ((empty($value) && ($value != 0)) || (empty(strlen($value)))) {
				continue;
			}
			$value = trim($value);
			$header_explodes[] = $value;
			if (strpos($value, ':')) {
				preg_match('/(.*):(.*)/ims', $value, $header_text);
				$header_array[trim($header_text[1])] = trim($header_text[2]);
			} else {
				$header_array[] = $value;
			}
		}
		$headers = [
			$header,
			$header_explodes,
			$header_array
		];
		$body = substr($response_body, $header_size);
		$response = [
			'body' => $body, 'header' => $headers, 'code' => $http_code,
		];
		self::$response = $response;
		self::recovery();
		return $response['body'];
	}

	/**
	 * 将获取到的JSON数据转换为PHP数组
	 * @access public
	 */
	public static function toArray()
	{
		$array = json_decode(self::$response['body'], true);
		if (is_array($array)) {
			return $array;
		}
		return self::$response['body'];
	}

	/**
	 * 恢复Curl静态类
	 * @access private
	 */
	private static function recovery()
	{
		self::$requset_config = [
			'header' => [
				'Accept: */*',
				'Accept-Encoding: gzip,deflate,sdch',
				'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
				'Connection:close',
				'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.102 Safari/537.36 Edg/104.0.1293.70'
			],
			'param' => [],
			'cookie' => null,
			// 连接时间
			'connect_time' => 10,
			// 读取时间
			'read_time' => null
		];
	}
}
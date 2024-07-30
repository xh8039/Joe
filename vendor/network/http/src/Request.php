<?php

namespace network\http;

/**
 * HTTP请求库
 *
 * @package network\http
 * @author  易航
 * @version dev
 * @link    https://gitee.com/yh-it/php-http-request
 */
trait Request
{

	/**
	 * cURL资源
	 * @var \CurlHandle
	 */
	private $ch;

	/**
	 * 配置信息
	 * @var object
	 */
	public $options;

	/**
	 * 响应资源
	 * @var object
	 */
	public $response;

	/**
	 * 构造函数,初始化请求配置
	 *
	 * @param array $options 要设置的配置项
	 */
	public function __construct(array $options = [])
	{
		$this->options = new Options;
		if ((!empty($options)) && is_array($options)) {
			$this->options = (object) array_merge((array) $this->options, (array) $options);
		}
		$this->ch = curl_init();
	}

	/**
	 * 初始化请求头
	 *
	 * @return array
	 */
	private function _initHeaders()
	{
		$headers_array = [];
		foreach ($this->options->headers as $name => $value) {
			if (is_numeric($name)) {
				$content = explode(':', $value, 2);
				$name = $content[0];
				$value = $content[1];
			}
			$name = $this->_headerNameUcfirst(strtolower(trim($name)));
			$value = trim($value);
			$headers_array[$name] = $value;
		}
		$this->options->headers = $headers_array;
		$headers = [];
		foreach ($headers_array as $name => $value) {
			$headers[] = $name . ': ' . $value;
		}
		return $headers;
	}

	private function _headerNameUcfirst($name)
	{
		$name = explode('-', $name);
		foreach ($name as $key => $value) {
			$name[$key] = ucfirst($value);
		}
		$name = implode('-', $name);
		return $name;
	}

	/**
	 * 初始化请求方法
	 *
	 * @return void
	 */
	private function _initMethod()
	{
		$url = $this->options->url;
		$method = $this->options->method = strtoupper($this->options->method);
		$params = $this->options->params;
		if ($method == 'GET' && (!empty($params))) {
			$queryString = http_build_query($params);
			$this->options->url = strstr($url, '?') ? (trim($url, '&') . '&' .  $queryString) : ($url . '?' .  $queryString);
		}
		if ($method == 'POST') {
			curl_setopt($this->ch, CURLOPT_POST, 1);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, empty($params) ? [] : $params);
		}
	}

	/**
	 * 初始化cURL请求
	 */
	private function _initialize()
	{
		$this->_initMethod();
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false); //终止从服务端进行验证
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false); //终止从服务端进行验证
		curl_setopt($this->ch, CURLOPT_ENCODING, ''); //自动发送所有支持的编码类型
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true); //返回原生的（Raw）输出
		curl_setopt($this->ch, CURLOPT_HEADER, 1); //将头文件的信息作为数据流输出
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->_initHeaders());  //设置请求头
		//在发起连接前等待的时间，如果设置为0，则无限等待
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->options->connectTime);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, intval($this->options->timeout)); //设置cURL允许执行的最长秒数
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->options->method);
		curl_setopt($this->ch, CURLOPT_URL, $this->options->url);
	}

	public function method(string $method)
	{
		$this->options->method = strtoupper($method);
		return $this;
	}

	// public function timeout(int|string $second)
	public function timeout($second)
	{
		$this->options->timeout = intval($second);
		return $this;
	}

	/**
	 * 设置全局基础URL，会自动拼接到所有请求URL前面。一旦设置，客户端发出的所有请求URL都会基于这个基础URL
	 * @param string $url URL值
	 */
	public function baseUrl(string $base_url)
	{
		$this->options->baseUrl = $base_url;
		return $this;
	}

	/**
	 * 设置请求URL
	 * @param string $url URL值
	 */
	public function url(string $url)
	{
		$url = empty($url) ? $this->options->url : $url;
		$this->options->url = empty($this->options->baseUrl) ? $url : ($this->options->baseUrl . $url);
		return $this;
	}

	/**
	 * 设置请求头
	 *
	 * @param string|array $name  请求头名称或数组
	 * @param string $value 请求头值
	 * @return $this
	 */
	public function header($name, $value = null)
	{
		if (is_null($value)) {
			if (is_string($name)) $name = explode(PHP_EOL, $name);
			if (is_array($name)) $this->options->headers = array_merge($this->options->headers, $name);
		} else {
			$this->options->headers[$name] = $value;
		}
		return $this;
	}

	/**
	 * 设置请求参数
	 *
	 * @param string|array $name  参数名称或数组
	 * @param string $value 参数值
	 * @return $this
	 */
	public function param($name, $value = null)
	{
		if (is_array($name) && (!empty($name))) {
			$this->options->params = array_merge($this->options->params, $name);
		} else {
			$this->options->params[$name] = $value;
		}
		return $this;
	}

	/**
	 * 设置请求Cookie
	 *
	 * @param string $value Cookie值
	 * @return $this
	 */
	public function cookie(string $value)
	{
		return $this->header('cookie', $value);
	}

	private function _getSendArgType($value)
	{
		if (is_array($value)) return 'params';
		if (!is_string($value)) return false;
		if (preg_match('/^http[s]?:\/\//i', $value)) return 'url';
		if (in_array(strtoupper($value), ['GET', 'POST', 'PUT', 'HEAD', 'DELETE', 'OPTIONS', 'TRACE', 'CONNECT'])) return 'method';
	}

	private function _initSend(array $args)
	{
		$data = [];
		foreach ($args as $value) {
			$type = $this->_getSendArgType($value);
			if (is_string($type)) $data[$type] = $value;
		}
		return (object) $data;
	}

	/**
	 * 发送请求 支持混合传参
	 *
	 * @param $param1 请求方法|请求URL|请求参数
	 * @param $param2 请求方法|请求URL|请求参数
	 * @param $param3 请求方法|请求URL|请求参数
	 * @return Response 响应对象
	 */
	public function send($param1 = null, $param2 = null, $param3 = null)
	{
		$info = $this->_initSend([$param1, $param2, $param3]);

		if (!empty($info->method)) $this->method($info->method);
		if (!empty($info->url)) $this->url($info->url);
		if ((!empty($info->params)) && is_array($info->params)) $this->param($info->params);

		$this->_initialize();

		$response_body = curl_exec($this->ch);
		$error = curl_error($this->ch);
		$http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);

		curl_close($this->ch); // 关闭curl资源

		$response = $this->response = new Response($http_code, $header_size, $response_body, $error);

		return $response;
	}
}

class Options
{

	/**
	 * 请求头参数
	 * @return array
	 */
	public array $headers = [
		'Accept' => '*/*',
		'Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
		'Connection' => 'close',
		'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.102 Safari/537.36 Edg/104.0.1293.70'
	];

	/**
	 * 请求携带参数
	 * @return array
	 */
	public array $params =  [];

	/**
	 * 请求URL
	 * @return string
	 */
	public $url;

	/**
	 * 连接时间 单位:秒
	 * @return integer
	 */
	public $connectTime = 3;

	/**
	 * 执行请求超时时间 单位:秒
	 * @return integer
	 */
	public $timeout = 5;

	/**
	 * 用于全局指定基础URL,会自动拼接到所有请求URL前面。一旦设置,客户端发出的所有请求URL都会基于这个基础URL
	 * @return string
	 */
	public $baseUrl;

	/**
	 * 请求方法
	 * @return string
	 */
	public string $method = 'GET';
}

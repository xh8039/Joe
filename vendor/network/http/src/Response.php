<?php

namespace network\http;

/**
 * @package network\http
 * @author  易航
 * @version dev
 * @link    https://gitee.com/yh-it/php-http-request
 */
class Response
{

	/**
	 * 响应内容
	 * @var array
	 */
	public $response;

	/**
	 * 响应头信息
	 * @var array|null
	 */
	private $headers = null;

	/**
	 * 错误信息
	 */
	private $error;

	/**
	 * 构造函数,初始化响应内容
	 *
	 * @param $response_body 响应内容
	 */
	public function __construct($http_code, $header_size, $response_body, $error)
	{
		$header = substr($response_body, 0, $header_size);
		$headers = explode(PHP_EOL, $header);
		// 过滤数组值两边的空格
		$headers = array_map('trim', $headers);
		// 过滤数组中的空值
		$headers = array_filter($headers);
		$body = substr($response_body, $header_size);
		$this->error = $error;
		$this->response = [
			'body' => $body,
			'header' => $header,
			'headers' => $headers,
			'code' => $http_code
		];
	}

	/**
	 * 直接获取响应体内容
	 *
	 * @return string 响应体字符串
	 */
	public function __toString()
	{
		$body = $this->body();
		if (is_string($body)) {
			return $body;
		}
		return '';
	}

	/**
	 * 获取响应体内容
	 *
	 * @return string 响应体字符串
	 */
	public function body()
	{
		return (string) $this->response['body'];
	}

	/**
	 * 获取响应状态码
	 *
	 * @return integer 响应状态码
	 */
	public function code()
	{
		return (int) trim($this->response['code']);
	}

	/**
	 * 获取响应头信息
	 *
	 * @param string|null $name 响应头名称,为空则获取所有响应头
	 * @return string|array|null 指定响应头值,或所有响应头数组,或空响应头null
	 */
	public function header($name = null)
	{
		if (empty($name)) return $this->headers();
		$headers = $this->headers();
		$name = strtolower(trim($name));
		return isset($headers[$name]) ? $headers[$name] : null;
	}

	/**
	 * 获取所有响应头信息
	 *
	 * @return array 响应头数组
	 */
	public function headers()
	{
		if (is_null($this->headers)) {
			$this->headers = $this->_explodeHeaders($this->response['headers']);
		}
		return $this->headers;
	}

	/**
	 * 将JSON响应体转换为PHP数组
	 * 如果转换失败,返回原响应体字符串
	 *
	 * @return array|string 数组或原始响应体
	 */
	public function toArray(string $name = null)
	{
		$array = json_decode($this->response['body'], true);
		if (is_array($array)) {
			return is_string($name) ? (isset($array[$name]) ? $array[$name] : null) : $array;
		}
		return $this->body();
	}

	/**
	 * 将JSON响应体转换为PHP对象
	 * 如果转换失败,返回原响应体字符串
	 *
	 * @return object|string 对象或原始响应体
	 */
	public function toObject(string $name = null)
	{
		$object = json_decode($this->response['body']);
		if (is_object($object)) {
			return is_string($name) ? (isset($object->$name) ? $object->$name : null) : $object;
		}
		return $this->body();
	}

	/**
	 * 如果请求错误，获取请求错误的信息
	 *
	 * @return string
	 */
	public function error()
	{
		return $this->error;
	}

	/**
	 * 将字符串响应头转换为关联数组
	 *
	 * @param array $headers 字符串响应头
	 * @return array 响应头数组
	 */
	private function _explodeHeaders(array $headers)
	{
		$headers_array = [];
		foreach ($headers as $value) {
			if (strpos($value, ':')) {
				$value = explode(':', $value, 2);
				$headers_array[strtolower(trim($value[0]))] = trim($value[1]);
			} else {
				$headers_array[] = $value;
			}
		}
		return $headers_array;
	}
}

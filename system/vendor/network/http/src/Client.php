<?php

namespace network\http;

/**
 * @package network\http
 * @author  易航
 * @version dev
 * @link    https://gitee.com/yh-it/php-http-request
 */
class Client
{

	use Request;

	/**
	 * 发送GET请求 支持混合传参
	 *
	 * @param $param1 请求URL|请求参数
	 * @param $param2 请求URL|请求参数
	 * @return Response 响应对象
	 */
	public function get($param1 = null, $param2 = null)
	{
		return $this->send(__FUNCTION__, $param1, $param2);
	}

	/**
	 * 发送POST请求 支持混合传参
	 *
	 * @param $param1 请求URL|请求参数
	 * @param $param2 请求URL|请求参数
	 * @return Response 响应对象
	 */
	public function post($param1 = null, $param2 = null)
	{
		return $this->send(__FUNCTION__, $param1, $param2);
	}
}

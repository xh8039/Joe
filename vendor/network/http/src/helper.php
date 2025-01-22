<?php

namespace network\http;

/**
 * POST请求
 * @access public
 * @param string $url 请求URL
 * @param array $params 携带的数组参数
 * @param array $headers 自定义请求头信息
 * @param array $options 配置信息
 * @return Response
 */
function post(string $url, array $params = [], array $headers = [], array $options = [])
{
	$client = new Client($options);
	if (!empty($headers)) $client->header($headers);
	return $client->post($url, $params);
}

/**
 * Get请求
 * @access public
 * @param string $url 请求URL
 * @param array $params 携带的数组参数
 * @param array $headers 自定义请求头信息
 * @param array $options 配置信息
 * @return Response
 */
function get(string $url, array $params = [], array $headers = [], array $options = [])
{
	$client = new Client($options);
	if (!empty($headers)) $client->header($headers);
	return $client->get($url, $params);
}


function halt($arg)
{
	print_r($arg);
	exit;
}

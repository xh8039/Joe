<?php

namespace network\http;

class RequestOptions
{
	/**
	 * 请求URL
	 * @return string
	 */
	public $url;

	/**
	 * 请求方法
	 * @return string
	 */
	public string $method = '';

	/**
	 * 请求头
	 * @return array
	 */
	public array $headers = [
		'Accept' => '*/*',
		'Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
		'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.102 Safari/537.36 Edg/104.0.1293.70'
	];

	/**
	 * 请求携带参数
	 * @return array
	 */
	public array $params =  [];

	/**
	 * 在发起连接前等待的时间，如果设置为0，则无限等待，单位：秒
	 * @return integer
	 */
	public $connectTime = 0;

	/**
	 * 执行请求超时时间 单位:秒
	 * @return integer
	 */
	public $timeout = 10;

	/**
	 * 是否开启自动跟随重定向
	 * @return bool
	 */
	public $followLocation = true;

	/**
	 * 用于全局指定基础URL，会自动拼接到所有请求URL前面。一旦设置，客户端发出的所有请求URL都会基于这个基础URL
	 * @return string
	 */
	public $baseUrl;

	/**
	 * 自定义Curl配置
	 */
	public $setopt = [
		CURLOPT_SSL_VERIFYPEER => false, //终止从服务端进行验证
		CURLOPT_SSL_VERIFYHOST => false, //终止从服务端进行验证
		CURLOPT_ENCODING => '', //自动发送所有支持的编码类型
		CURLOPT_RETURNTRANSFER => true, //返回原生的（Raw）输出
		CURLOPT_HEADER => true //将头文件的信息作为数据流输出
	];
}

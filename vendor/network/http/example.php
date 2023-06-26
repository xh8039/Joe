<?php

require __DIR__ . '/vendor/autoload.php';

$url = 'http://www.bri6.cn';

$response = network\http\get($url, ['name' => '易航'], ['User-Agent' => 'Mozilla/5.0', 'Accept' => '*'], [
	'connect_time' => 30,
	'read_time' => 20
]);

$response = network\http\post($url, ['name' => '易航'], ['User-Agent' => 'Mozilla/5.0', 'Accept' => '*'], [
	'connect_time' => 30,
	'read_time' => 20
]);

$response = (new network\http\Get([
	'connect_time' => 30,
	'read_time' => 20
]))->send($url, ['name' => '易航'], ['User-Agent' => 'Mozilla/5.0', 'Accept' => '*']);

$response = (new network\http\Get([
	'connect_time' => 30,
	'read_time' => 20
]))->header(['User-Agent' => 'Mozilla/5.0', 'Accept' => '*'])->header('Accept-Language', 'zh-CN,zh;q=0.9')->param('name', '易航')->param(['qq' => '2136118039', 'email' => 'xh118039@qq.com'])->send($url);

$response = (new network\http\Post([
	'connect_time' => 30,
	'read_time' => 20
]))->send($url, ['name' => '易航'], ['User-Agent' => 'Mozilla/5.0', 'Accept' => '*']);

$response = (new network\http\Post([
	'connect_time' => 30,
	'read_time' => 20
]))->header(['User-Agent' => 'Mozilla/5.0', 'Accept' => '*'])->header('Accept-Language', 'zh-CN,zh;q=0.9')->param('name', '易航')->param(['qq' => '2136118039', 'email' => 'xh118039@qq.com'])->send($url);

$response = (new network\http\Client([
	'connect_time' => 30,
	'read_time' => 20
]))->header(['User-Agent' => 'Mozilla/5.0', 'Accept' => '*'])->header('Accept-Language', 'zh-CN,zh;q=0.9')->param('name', '易航')->param(['qq' => '2136118039', 'email' => 'xh118039@qq.com'])->get($url);

$response = (new network\http\Client([
	'connect_time' => 30,
	'read_time' => 20
]))->header(['User-Agent' => 'Mozilla/5.0', 'Accept' => '*'])->header('Accept-Language', 'zh-CN,zh;q=0.9')->param('name', '易航')->param(['qq' => '2136118039', 'email' => 'xh118039@qq.com'])->post($url);

// 打印响应状态码
echo $response->code();

// 打印指定响应头  
echo $response->header('Content-Type');

// 以数组形式打印所有响应头
print_r($response->headers());

// 如果响应是JSON,获取JSON对象
$data = $response->toObject();
$data = $response->toArray();

// 直接输出响应体
echo $response;
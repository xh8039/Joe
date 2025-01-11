<?php
$domain = '*'; // 如需限制指定域名 请将*改为你的域名
//判断是http还是https
$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'ON') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'HTTPS')) ? 'https://' : 'http://';
//全路径
$url = $domain == '*' ? $domain : $http_type . trim($domain);
header('Access-Control-Allow-Origin: ' . $url); // 允许你的域名访问
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8'); // 指定字符集为 UTF-8
header('Cache-Control: public, max-age=2592000');
header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('+30 days')) . ' GMT');
echo file_get_contents(__DIR__ . '/joe.owo.json');

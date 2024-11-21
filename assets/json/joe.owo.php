<?php
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DIRECTORY_SEPARATOR . 'config.inc.php';
/** 初始化组件 */
\Widget\Init::alloc();
$host = parse_url(Helper::options()->siteUrl, PHP_URL_HOST);
header('Access-Control-Allow-Origin: ' . $host); // 允许所有域名访问
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8'); // 指定字符集为 UTF-8
header('Cache-Control: public, max-age=2592000');
header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('+30 days')) . ' GMT');
echo file_get_contents(__DIR__ . '/joe.owo.json');

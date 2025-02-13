<?php
if (!extension_loaded('gd')) die('请安装PHP的GD扩展');
session_start();
require_once dirname(__DIR__) . '/system/library/Captcha.php';
$_vc = new joe\library\Captcha();
$_vc->doimg();
$_SESSION['joe_image_captcha'] = $_vc->getCode();

<?php
session_start();
require_once dirname(__DIR__) . '/system/library/Captcha.php';
$_vc = new joe\library\Captcha();
$_vc->doimg();
$_SESSION['joe_captcha'] = $_vc->getCode();

<?php
session_start();
require_once __DIR__ . '/library/Captcha.php';
$_vc = new Joe\library\Captcha();
$_vc->doimg();
$_SESSION['joe_captcha'] = $_vc->getCode();

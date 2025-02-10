<?php
$text = $_GET['text'];
if ($text) {
    require_once dirname(__DIR__) . '/system/library/QRcode.php';
    joe\library\QRcode::png($_GET['text'], false, QR_ECLEVEL_L, 10, 1);
} else {
    echo '{"code":2,"msg":"参数不足!"}';
}

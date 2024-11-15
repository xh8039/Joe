<?php
$owo = json_decode(file_get_contents('C:\BtSoft\wwwroot\blog.cn\usr\themes\Joe\assets\json\joe.owo2.json'), true);

foreach ($owo['阿鲁表情'] as $key => $value) {
    $owo['阿鲁表情'][$key]['data'] = '[阿鲁表情]' . trim($value['data'], ':@');
}

file_put_contents('C:\BtSoft\wwwroot\blog.cn\usr\themes\Joe\assets\json\joe.owo2.json', json_encode($owo, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

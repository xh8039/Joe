<?php

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

function joe_backup_location($message)
{
	$url = Helper::options()->adminUrl('options-theme.php');
	echo '<script>alert("' . $message . '");window.location.href = "' . $url . '"</script>';
}

if (isset($_POST['type'])) {
	$theme_field = 'theme:' . THEME_NAME;
	$backup_field = $theme_field . '_backup';
	if ($_POST["type"] == "备份设置") {
		$theme_options = Db::name('options')->where('name', $theme_field)->value('value');
		if (Db::name('options')->where('name', $backup_field)->find()) {
			Db::name('options')->where('name', $backup_field)->update(['value' => $value]);
			joe_backup_location('备份更新成功！');
		} else if ($theme_options) {
			Db::name('options')->insert(['name' => $backup_field, 'user' => '0', 'value' => $value]);
			joe_backup_location('备份成功！');
		} else {
			joe_backup_location('备份失败！无法获取主题设置！');
		}
	}
	if ($_POST["type"] == "还原备份") {
		$backup_value = Db::name('options')->where('name', $backup_field)->value('value');
		if ($backup_value) {
			Db::name('options')->where('name', $theme_field)->update(['value' => $backup_value]);
			joe_backup_location('还原成功！');
		} else {
			joe_backup_location('未备份过数据，无法恢复！');
		}
	}
	if ($_POST["type"] == "删除备份") {
		$backup_delete = Db::name('options')->where('name', $backup_field)->delete();
		if ($backup_delete) {
			joe_backup_location('删除成功！');
		} else {
			joe_backup_location('没有备份内容，无法删除！');
		}
	}
}

?>
<form class="backup" action="?Joe_backup" method="post">
	<input type="button" id="update" value="检测更新">
	<input type="submit" name="type" value="备份设置" />
	<input type="submit" name="type" value="还原备份" />
	<input type="submit" name="type" value="删除备份" />
</form>
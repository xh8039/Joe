<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$referer = empty($_GET['referer']) ? '/' : addslashes(strip_tags($_GET['referer']));
$referer_parse = parse_url($referer);
$referer_host = $referer_parse['host'] ?? null;
$referer_path = $referer_parse['path'] ?? '';
if ($referer_host == $_SERVER['HTTP_HOST'] || substr($referer_path, 0, 1) == '/') {
	if ($this->user->hasLogin()) exit("<script>window.location.href='{$referer}'</script>");
}
?>
<script>
	window.Joe.referer = '<?= $referer ?>';
</script>
<link href="<?= joe\theme_url('assets/css/joe.user.css') ?>" rel="stylesheet" type="text/css" />
<script defer src="<?= joe\theme_url('assets/js/joe.user.js') ?>"></script>
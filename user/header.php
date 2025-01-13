<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->user->hasLogin()) {
	$from = isset($_GET['from']) ? $_GET['from'] : '';
	$from_parse = parse_url($from);
	$from_host = $from_parse['host'] ?? null;
	$from_path = $from_parse['path'] ?? '';
	if ($from_host == $_SERVER['HTTP_HOST'] || substr($from_path, 0, 1) == '/') {
		?>
		<script>
			let from = '<?= trim(addslashes(strip_tags($from))) ?>';
			window.location.href = from ? from : '/';
		</script>
		<?php
	}
}
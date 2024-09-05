<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->user->hasLogin()) {
	$from = isset($_GET['from']) ? $_GET['from'] : '';
	if (stripos($from, $_SERVER['HTTP_HOST'])) {
		?>
		<script>
			let from = '<?= $from ?>';
			window.location.href = from ? from : "<?= Typecho_Common::url('/', Helper::options()->index) ?>";
		</script>
		<?php
	}
}
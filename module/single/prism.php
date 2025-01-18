<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!-- Prism.css -->
<link rel="stylesheet" href="<?= joe\cdn('prism-themes/1.9.0/'  . $this->options->JPrismTheme) ?>">
<link href="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.css') ?>" rel="stylesheet">
<!-- Prism.js -->
<script src="<?= joe\cdn('prism/1.9.0/prism.min.js') ?>"></script>
<script src="<?= joe\cdn('prism/1.9.0/plugins/autoloader/prism-autoloader.min.js') ?>"></script>
<script>
	// Prism.plugins.autoloader.languages_path = Joe.CDN(`prism/1.9.0/components/`);
</script>
<script src="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.js') ?>"></script>
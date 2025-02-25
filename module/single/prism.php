<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!-- Prism.css -->
<link async rel="stylesheet" href="<?= joe\cdn('prism-themes/1.9.0/'  . $this->options->JPrismTheme) ?>">
<link async href="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.css') ?>" rel="stylesheet">
<!-- Prism.js -->
<script defer src="<?= joe\cdn('prism/1.9.0/prism.min.js') ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\cdn('prism/1.9.0/plugins/autoloader/prism-autoloader.min.js') ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.js') ?>" data-turbolinks-permanent></script>
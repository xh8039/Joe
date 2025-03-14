<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->options->JLoading != 'off') {
	$JLoadingFile = 'module/loading/' . $this->options->JLoading . '.php';
	if (file_exists(JOE_ROOT . $JLoadingFile)) {
		echo '<!-- Loading开始 -->';
		$this->need($JLoadingFile);
		echo '<!-- Loading 结束 -->';
	}
}
$selectors = $this->request->getHeader('x-pjax-selectors');
if (is_string($selectors)) {
	$selectors = json_decode($selectors, true);
	$header = in_array('header', $selectors);
} else {
	$header = true;
}
if (!$header) return;
?>
<header class="joe_header <?php echo $this->is('post') ? 'current' : '' ?>">
	<?php
	if (empty(joe\custom_navs())) {
		$this->need('module/header/above.php');
		if (joe\isPc()) $this->need('module/header/below.php');
	} else {
		$this->need('module/header/custom.php');
	}

	if ($this->options->JHeaderCounter == 'on') $this->need('module/header/counter.php');

	if (joe\isMobile()) {
		$this->need('module/header/searchout.php');
		$this->need('module/header/slideout.php');
	}
	?>
</header>
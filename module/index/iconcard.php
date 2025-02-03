<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$icon_crid_list = joe\optionMulti($this->options->JIndex_Icon_Card);
if (empty($icon_crid_list)) return;

echo '<div>';
if (!empty($this->options->JIndex_Icon_Card_Title)) {
	echo '<div class="box-body notop"><div class="title-theme">' . $this->options->JIndex_Icon_Card_Title . '</div></div>';
}
echo '<div class="mb25"><div class="row gutters-5">';

foreach ($icon_crid_list as $value) {
	$icon_crid = joe\icon_crid_info($value);
?>
	<div class="col-sm-3 col-xs-6">
		<a class="main-color" data-placement="bottom" data-toggle="tooltip" title="<?= $icon_crid['description'] ?? $icon_crid['title'] ?>" href="<?= $icon_crid['url'] ?>" target="<?= $icon_crid['target'] ?>">
			<div class="icon-cover-card flex ac zib-widget mb0">
				<div class="icon-cover-icon badg cir <?= $icon_crid['icon_class'] ?>" style="font-size: 25px;">
					<svg class="icon svg em09" aria-hidden="true"><use xlink:href="#<?= $icon_crid['icon'] ?>"></use></svg>
				</div>
				<div class="icon-cover-desc ml10 flex1 px12-sm">
					<div class="em12 text-ellipsis font-bold"> <?= $icon_crid['title'] ?></div>
					<?= $icon_crid['description'] ? '<div class="muted-color mt6 text-ellipsis">' . $icon_crid['description'] . '</div>' : null ?>
				</div>
			</div>
		</a>
	</div>
<?php
}

echo '</div></div></div>';
?>
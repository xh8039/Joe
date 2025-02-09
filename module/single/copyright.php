<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->options->JArticle_Bottom_Text) {
?>
	<div class="joe_detail__copyright">
		<div class="content">
			<?php
			$JArticle_Bottom_Text_arr = explode("\r\n", $this->options->JArticle_Bottom_Text);
			foreach ($JArticle_Bottom_Text_arr as $value) {
			?>
				<div class="item">
					<svg class="icon" width="20" height="20">
						<use xlink:href="#icon-joe-article-list-disc"></use>
					</svg>
					<span class="text"><?php echo trim($value) ?></span>
				</div>
			<?php
			}
			?>
		</div>
	</div>
<?php
}
?>
<div class="joe_detail__copyright">
	<div class="content">
		<div class="item">
			<svg class="icon" width="20" height="20" viewBox="0 0 1024 1024">
				<use xlink:href="#icon-joe-article-user"></use>
			</svg>
			<span>版权属于：</span><span class="text"><?php $this->author(); ?></span>
		</div>
		<div class="item">
			<svg class="icon" width="20" height="20" viewBox="0 0 1024 1024">
				<use xlink:href="#icon-joe-article-url"></use>
			</svg>
			<span>本文链接：</span><span class="text"><a class="link" href="<?= joe\root_relative_link($this->permalink) ?>" rel="nofollow"><?= $this->permalink ?></a></span>
		</div>
		<div class="item">
			<svg class="icon" width="20" height="20" viewBox="0 0 1024 1024">
				<use xlink:href="#icon-joe-article-share"></use>
			</svg>
			<span>作品采用</span><span class="text">《<a class="link" href="//creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" target="_blank" rel="external nofollow">署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)</a>》许可协议授权
			</span>
		</div>
	</div>
</div>
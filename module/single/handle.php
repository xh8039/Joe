<?php

/**
 * 标签分类
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if ($this->is('post')) : ?>
	<div style="font-size: .9em;color: var(--muted-3-color);line-height: 1.42857143;">
		<div><span>©</span> 版权声明</div>
		<div class="posts-copyright">文章版权归作者所有，未经允许请勿转载。</div>
	</div>
	<div class="separator">THE END</div>
<?php
endif;
?>
<div class="article-tags">
	<?php
	$color_array = ['c-blue', 'c-yellow', 'c-green', 'c-cyan', 'c-blue-2', 'c-purple-2', 'c-yellow-2', 'c-purple', 'c-red-2', 'c-red'];
	foreach ($this->categories as $key => $item) {
		echo '<a href="' . $item['permalink'] . '" class="but ml6 radius ' . ($color_array[$key] ? $color_array[$key] : 'c-blue') . '" title="查看此分类更多文章"><i class="fa fa-folder-open-o" aria-hidden="true"></i>' . $item['name'] . '</a>';
	}
	echo '<br>';
	foreach ($this->tags as $key => $value) {
		echo '<a class="but ml6 radius" href="' . $value['url'] . '" title="查看此标签更多文章"># ' . $value['name'] . '</a>';
	}
	?>
</div>
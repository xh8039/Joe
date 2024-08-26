<div class="article-tags">
    <?php if (sizeof($this->categories) > 0) : ?>
        <?php foreach (array_slice($this->categories, 0, 5) as $key => $item) : ?>
            <a style="color: var(--theme);background :rgba(41, 151, 247, 0.1);" href="<?php echo $item['permalink']; ?>" class="item item-<?php echo $key ?>" title="查看此分类更多文章"><i class="fa fa-folder-open-o" aria-hidden="true"></i> <?php echo $item['name']; ?></a>
        <?php endforeach; ?>
    <?php endif; ?>
    <br>
    <?php
    if (count($this->tags) > 0) {
        foreach ($this->tags as $key => $value) {
    ?>
            <a href="<?= $value['url'] ?>" title="查看此标签更多文章"># <?= $value['name'] ?></a>
    <?php
        }
    } else {
        echo '<a href="javascript:void(0);">暂无标签</a>';
    }
    ?>
</div>
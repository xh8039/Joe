<main class="joe_main">
    <div class="joe_archive">
        <div class="joe_archive__title">
            <svg width="20" height="20" class="joe_archive__title-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5zM16 8L2 22M17.5 15H9" />
            </svg>
            <div class="joe_archive__title-title">
                <span>找到</span>
                <span class="muted"><?php echo $this->getTotal(); ?></span>
                <span>篇与</span>
                <span class="muted ellipsis"><?php echo $this->keywords; ?></span>
                <span>相关的结果</span>
                <?php if ($this->_currentPage > 1) echo '<span>&nbsp;- 第 ' . $this->_currentPage . ' 页</span>'; ?>
            </div>
        </div>

        <?php if ($this->have()) : ?>
            <ul class="joe_archive__list joe_list" data-wow="<?php $this->options->JListAnimate() ?>">
                <?php $this->need('module/archive/list.php') ?>
            </ul>
        <?php else : ?>
            <div class="joe_archive__empty">
                <svg class="joe_archive__empty-icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="120" height="120">
                    <use xlink:href="#icon-joe-archive-empty"></use>
                </svg>
                <span>没有找到相关结果...</span>
            </div>
        <?php endif; ?>
    </div>
    <?php
    $this->pageNav('<i class="fa fa-angle-left em12"></i><span class="hide-sm ml6">上一页</span>', '<span class="hide-sm mr6">下一页</span><i class="fa fa-angle-right em12"></i>', 1, '...', [
        'wrapTag' => 'ul',
        'wrapClass' => 'joe_pagination',
        'itemTag' => 'li',
        'textTag' => 'a',
        'currentClass' => 'active',
        'prevClass' => 'prev',
        'nextClass' => 'next'
    ]);
    ?>
</main>
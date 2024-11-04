<div class="joe_container joe_bread">
    <ul class="joe_bread__bread breadcrumb">
        <li class="item">
            <a href="<?php $this->options->siteUrl(); ?>" class="link" title="首页"><i class="fa fa-map-marker"></i> 首页</a>
        </li>
        <?php if (sizeof($this->categories) > 0) : ?>
            <li class="item">
                <a class="link" href="<?php echo $this->categories[0]['permalink']; ?>" title="<?php echo $this->categories[0]['name']; ?>"><?php echo $this->categories[0]['name']; ?></a>
            </li>
        <?php endif; ?>
        <li class="item"> 正文</li>
    </ul>
</div>
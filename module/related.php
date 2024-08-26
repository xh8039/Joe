<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    http_response_code(404);
    exit;
}
?>
<?php $this->related((Joe\isMobile() ? 4 : 8))->to($relatedPosts); ?>
<?php if ($relatedPosts->have()) : ?>
    <div class="joe_detail__related">
        <div class="joe_detail__related-title">相关推荐</div>
        <div class="joe_detail__related-content">
            <?php while ($relatedPosts->next()) : ?>
                <a class="item" href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>">
                    <img referrerpolicy="no-referrer" rel="noreferrer" class="lazyload" src="<?php joe\getLazyload(); ?>" data-src="<?php echo joe\getThumbnails($relatedPosts)[0]; ?>" alt="<?php $relatedPosts->title(); ?>" />
                    <h6><?php $relatedPosts->title(); ?></h6>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif; ?>
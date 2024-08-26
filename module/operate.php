<div style="padding: 15px;font-size: .9em;color: var(--muted-3-color);text-align: center;">喜欢就支持一下吧</div>
<div class="post-actions">
    <a href="javascript:;" data-action="like" class="action action-like">
        <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-like"></use>
        </svg>
        <text>点赞</text>
        <count><?php joe\getAgree($this) ?></count>
    </a>
    <span class="hover-show dropup action action-share">
        <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-share"></use>
        </svg><text>分享</text>
        <div class="zib-widget hover-show-con share-button dropdown-menu">
            <div>
                <a rel="nofollow" class="share-btn qzone" target="_blank" title="QQ空间" href="https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?= urlencode($this->permalink) ?>&title=<?= urlencode($this->title . ' - ' . $this->options->title) ?>&pics=<?= urlencode(joe\getThumbnails($this)[0]) ?>&summary=<?= urlencode($this->fields->description ? $this->fields->description : joe\markdown_filter($this->description)) ?>">
                    <icon><svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-qzone-color"></use>
                        </svg></icon><text>QQ空间<text></text></text>
                </a>
                <a rel="nofollow" class="share-btn weibo" target="_blank" title="微博" href="https://service.weibo.com/share/share.php?url=<?= urlencode($this->permalink) ?>&title=<?= urlencode($this->title . ' - ' . $this->options->title) ?>&pic=<?= urlencode(joe\getThumbnails($this)[0]) ?>&searchPic=false">
                    <icon><svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-weibo-color"></use>
                        </svg></icon><text>微博<text></text></text>
                </a>
                <a rel="nofollow" class="share-btn qq" target="_blank" title="QQ好友" href="https://connect.qq.com/widget/shareqq/index.html?url=<?= urlencode($this->permalink) ?>&title=<?= urlencode($this->title . ' - ' . $this->options->title) ?>&pics=<?= urlencode(joe\getThumbnails($this)[0]) ?>&desc=<?= urlencode($this->fields->description ? $this->fields->description : joe\markdown_filter($this->description)) ?>">
                    <icon><svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-qq-color"></use>
                        </svg></icon><text>QQ好友<text></text></text>
                </a>
                <a rel="nofollow" class="share-btn poster" poster-share="335" title="海报分享" href="javascript:;">
                    <icon>
                        <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-poster-color"></use>
                        </svg>
                    </icon>
                    <text>海报分享<text></text></text>
                </a>
                <a rel="nofollow" class="share-btn copy" data-clipboard-text="<?php $this->permalink() ?>" data-clipboard-tag="链接" title="复制链接" href="javascript:;">
                    <icon>
                        <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-copy-color"></use>
                        </svg>
                    </icon>
                    <text>复制链接<text></text></text>
                </a>
                <script>
                    var clipboard = new ClipboardJS('.share-btn.copy');
                    clipboard.on('success', function(e) {
                        //注销对象
                        e.clearSelection();
                        Qmsg.success('链接已复制！')
                    });
                    clipboard.on('error', function(e) {
                        //注销对象
                        e.clearSelection();
                        Qmsg.error('链接复制失败！')
                    });
                </script>
            </div>
        </div>
    </span>
    <a href="javascript:;" data-action="favorite" class="action action-favorite">
        <svg class="icon" aria-hidden="true">
            <use xlink:href="#icon-favorite"></use>
        </svg>
        <text>收藏</text>
        <count></count>
    </a>
</div>
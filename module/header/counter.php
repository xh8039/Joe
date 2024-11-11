<!-- 顶部浏览进度条开始 -->
<style>
    #HeaderCounter {
        position: absolute;
        bottom: -3px;
        width: 0;
        height: 3px;
        z-index: 10;
        background-image: var(--back-line-right);
        border-radius: var(--main-radius);
        transition: width 0.45s;
    }
</style>
<div id="HeaderCounter"></div>
<script>
    $(window).scroll(throttle(() => {
        let a = $(window).scrollTop(),
            c = $(document).height(),
            b = $(window).height();
        scrollPercent = a / (c - b) * 100;
        scrollPercent = scrollPercent.toFixed(1);
        document.getElementById('HeaderCounter').style.width = scrollPercent + '%';
    }, 300));
</script>
<!-- 顶部浏览进度条结束 -->
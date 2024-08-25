<script>
    window.loadingStart = () => {
        setTimeout(() => {
            $("#loading-animation").fadeIn(540);
        }, 500);
    }
    window.loadingEnd = () => {
        setTimeout(() => {
            $("#loading-animation").fadeOut(540);
        }, 500);
    }
    if (window.jQuery) {
        $(document).ready(() => {
            loadEnd();
        });
    } else {
        document.addEventListener("DOMContentLoaded", () => {
            loadEnd();
        });
    }
</script>
<!-- Loading 结束 -->
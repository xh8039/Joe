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
            loadingEnd();
        });
    } else {
        document.addEventListener("DOMContentLoaded", () => {
            loadingEnd();
        });
    }
</script>
<!-- Loading 结束 -->
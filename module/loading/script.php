<script>
	window.loadingStart = () => {
		$("#loading-animation").fadeIn(150);
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
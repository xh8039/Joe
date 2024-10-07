<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta name="referrer" content="no-referrer" />
	<meta charset="UTF-8" />
	<meta name="renderer" content="webkit" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, shrink-to-fit=no, viewport-fit=cover" />
	<title>M3U8 - Player</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			-webkit-tap-highlight-color: transparent;
			outline: none;
			text-decoration: none;
		}

		html,
		body,
		#dplayer {
			width: 100%;
			height: 100%;
		}
	</style>
</head>

<body>
	<div id="dplayer"></div>
	<script src="https://cdn.staticfile.net/hls.js/0.14.16/hls.min.js"></script>
	<script src="https://cdn.staticfile.net/dplayer/1.27.0/DPlayer.min.js"></script>
	<script>
		window.videoPlayer = new DPlayer({
			container: document.getElementById('dplayer'), // 播放器容器元素
			autoplay: <?= $_GET['autoplay'] ? 'true' : 'false' ?>, // 视频自动播放
			theme: '<?= $_GET['theme'] ? $_GET['theme'] : '#409eff' ?>', // 主题色
			// lang: '', // 可选值: 'en', 'zh-cn', 'zh-tw'
			preload: '<?= (empty($_GET['pic']) || $_GET['pic'] == 'null') ? 'auto' : 'metadata' ?>', // 视频预加载，可选值: 'none', 'metadata', 'auto'
			loop: <?= (isset($_GET['loop']) && $_GET['loop']) ? 'true' : 'false' ?>, // 视频循环播放
			screenshot: <?= $_GET['screenshot'] ? 'true' : 'false' ?>, // 开启截图，如果开启，视频和视频封面需要允许跨域
			airplay: true, // 在 Safari 中开启 AirPlay
			volume: 1, // 默认音量，请注意播放器会记忆用户设置，用户手动设置音量后默认音量即失效
			playbackSpeed: [2.00, 1.75, 1.50, 1.25, 1.00, 0.75, 0.50, 0.25], // 可选的播放速率，可以设置成自定义的数组
			video: {
				url: `<?= $_GET['url'] ?>`,
				pic: <?= (empty($_GET['pic']) || $_GET['pic'] == 'null') ? 'null' : '`' . $_GET['pic'] . '`' ?>,
			}
		})
	</script>
</body>

</html>
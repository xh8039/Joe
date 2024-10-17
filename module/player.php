<?php
if (empty($_GET['cid'])) exit('未指定文章！');
$cid = $_GET['cid'];
/* sql注入校验 */
if (!preg_match('/^\d+$/',  $cid)) exit('非法请求！已屏蔽！');
require_once dirname(dirname(dirname(dirname(__DIR__)))) . DIRECTORY_SEPARATOR . 'config.inc.php';
/** 初始化组件 */
\Widget\Init::alloc();
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'function.php';
if (!empty(Helper::options()->JCustomPlayer)) exit('已使用第三方视频播放器！');
$db = Typecho_Db::get();
$row = $db->fetchRow($db->select('text,title')->from('table.contents')->where('cid = ?', $cid));
if (sizeof($row) > 0) {
	if (!stripos($row['text'], $_GET['url'])) exit('视频不存在！');
} else {
	exit('视频不存在！');
}

$autoplay = $_GET['autoplay'] ? 'true' : 'false';
$theme = $_GET['theme'] ? $_GET['theme'] : '#409eff';
$preload = (empty($_GET['pic']) || $_GET['pic'] == 'null') ? 'auto' : 'metadata';
$loop = (isset($_GET['loop']) && $_GET['loop']) ? 'true' : 'false';
$screenshot = $_GET['screenshot'] ? 'true' : 'false';
$url = addslashes(strip_tags($_GET['url']));
$pic = (empty($_GET['pic']) || $_GET['pic'] == 'null') ? 'null' : '"' . addslashes(strip_tags($_GET['pic'])) . '"';
if (strpos($url, 'magnet:') === 0) {
	$MSE = joe\cdn('webtorrent/1.9.7/webtorrent.min.js');
	$video_type = 'webtorrent';
} else {
	$parse_url = parse_url($url);
	$url_extension = pathinfo($parse_url['path'], PATHINFO_EXTENSION);
	switch ($url_extension) {
		case 'mp4':
			$MSE = null;
			$video_type = 'mp4';
			break;
		case 'm3u8':
			$MSE = joe\cdn('hls.js/1.5.13/hls.min.js');
			$video_type = 'hls';
			break;
		case 'mpd':
			$MSE = joe\cdn('shaka-player/4.10.7/shaka-player.compiled.min.js');
			$video_type = 'shakaDash';
			$customType = '{
        	shakaDash: function (video, player) {
        		var src = video.src;
        		var playerShaka = new shaka.Player(video); // 将会修改 video.src
        		playerShaka.load(src);
        	}';
			break;
		case 'flv':
			$MSE = joe\cdn('flv.js/1.6.2/flv.min.js');
			$video_type = 'flv';
			break;
		default:
			$MSE = null;
			$video_type = null;
			break;
	}
}
$customType = empty($customType) ? 'null' : $customType . PHP_EOL;
$video_type = empty($video_type) ? 'null' : '"' . $video_type . '"' . PHP_EOL;
$MSE = empty($MSE) ? null : '<script src="' . $MSE . '"></script>' . PHP_EOL;
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta name="referrer" content="no-referrer" />
	<meta charset="UTF-8" />
	<meta name="renderer" content="webkit" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, shrink-to-fit=no, viewport-fit=cover" />
	<title><?= $row['title'] ?></title>
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
	<?= $MSE ?>
	<script src="<?= joe\cdn('dplayer/1.27.0/DPlayer.min.js') ?>"></script>
	<script>
		window.videoPlayer = new DPlayer({
			container: document.getElementById('dplayer'), // 播放器容器元素
			autoplay: <?= $autoplay ?>, // 视频自动播放
			theme: '<?= $theme ?>', // 主题色
			lang: 'zh-cn', // 可选值: 'en', 'zh-cn', 'zh-tw'
			preload: '<?= $preload ?>', // 视频预加载，可选值: 'none', 'metadata', 'auto'
			loop: <?= $loop ?>, // 视频循环播放
			screenshot: <?= $screenshot ?>, // 开启截图，如果开启，视频和视频封面需要允许跨域
			airplay: true, // 在 Safari 中开启 AirPlay
			volume: 1, // 默认音量，请注意播放器会记忆用户设置，用户手动设置音量后默认音量即失效
			playbackSpeed: [2.00, 1.75, 1.50, 1.25, 1.00, 0.75, 0.50, 0.25], // 可选的播放速率，可以设置成自定义的数组
			video: {
				url: "<?= $url ?>",
				pic: <?= $pic ?>,
				type: <?= $video_type ?>
			},
			customType: <?= $customType ?>
		});
	</script>
</body>

</html>
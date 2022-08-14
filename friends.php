<?php

/**
 * 友链
 *
 * @package custom
 *
 **/
$this->need('public/common.php');
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php $this->need('public/include.php'); ?>
	<?php if ($this->options->JPrismTheme) : ?>
		<link rel="stylesheet" href="<?php $this->options->JPrismTheme() ?>">
	<?php else : ?>
		<link rel="stylesheet" href="//cdn.staticfile.org/prism/1.23.0/themes/prism.min.css">
	<?php endif; ?>
	<script src="//cdn.staticfile.org/clipboard.js/2.0.6/clipboard.min.js"></script>
	<script src="<?php _JStorageUrl('assets/js/prism.min.js'); ?>"></script>
	<script src="<?php _JStorageUrl('assets/js/joe.post_page.js'); ?>"></script>
	<script src="//cdn.staticfile.org/vue/3.2.37/vue.global.prod.min.js"></script>
</head>

<body>
	<div id="Joe">
		<?php $this->need('public/header.php'); ?>
		<div class="joe_container">
			<div class="joe_main">
				<div class="joe_detail" data-cid="<?php echo $this->cid ?>">
					<?php $this->need('public/batten.php'); ?>
					<?php $this->need('public/article.php'); ?>
					<ul class="joe_detail__friends" id="friends">
					<li class="joe_detail__friends-item" v-for="friend in friend_data" >
						<a class="contain" :href="friend.url" target="_blank" rel="noopener noreferrer" :style="'background: ' + friend.color">
							<span class="title">{{friend.name}}</span>
							<div class="content">
								<div class="desc">{{friend.desc}}</div>
								<img width="40" height="40" class="avatar lazyload" :src="friend.avatar" v-bind:alt="friend.name" />
							</div>
						</a>
					</li>
					</ul>
					<?php
					$this->need('public/FriendsSubmit.php');
					$this->need('public/handle.php');
					$this->need('public/copyright.php');
					?>
				</div>
				<?php $this->need('public/comment.php'); ?>
			</div>
			<?php $this->need('public/aside.php'); ?>
		</div>
		<?php $this->need('public/footer.php'); ?>
	</div>
	<script type="text/javascript">
		const friends = {
			data() {
				return {
					friend_data: []
				}
			},
			mounted() {
				var self = this;

				$.ajax({
					type: "POST",
					url: Joe.BASE_API,
					data: {
						routeType: 'friend_list'
					},
					dataType: "json",
					beforeSend() {},
					success(response) {
						if (response.code == 200) {
							var data = response.data;
						} else {
							return;
						}
						var color = '#';
						var color_arr = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e",
							"f"
						];
						for (let i in data) {
							for (var c = 0; c < 6; c++) {
								var num = parseInt(Math.random() * 16);
								color += color_arr[num];
							}
							data[i]['color'] = color;
							color = '#';
							if (!data[i]['name']) {
								data[i]['name'] = data[i]['url'];
							}
						}
						self.friend_data = data;
					},
					error(error) {
						console.log(error);
					}
				});
			}
		}
		Vue.createApp(friends).mount('#friends');
	</script>
</body>

</html>
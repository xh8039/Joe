<?php

if ($this->options->JMusic == 'on') {
?>
	<meting-js fixed="true" preload="metadata" mutex="true" volume="0.3" autotheme="true" storage="AllFixed" order="<?= $this->options->JMusicOrder ?>" server="<?= $this->options->JMusicServer ?>" type="<?= $this->options->JMusicType ?>" id="<?= $this->options->JMusicId ?>" <?= $this->options->JMusicPlay == 'on' ? 'autoplay="true"' : null ?>></meting-js>
	<script>
		<?php
		if (empty($this->options->JMusicApi)) {
		?>
			window.meting_api = `${Joe.BASE_API}/meting?server=:server&type=:type&id=:id&r=:r&cookie=<?= $this->options->JMusicCookie ?>`
		<?php
		} else {
		?>
			window.meting_api = '<?= $this->options->JMusicApi ?>'
		<?php
		}
		?>
	</script>
	<script src="<?= joe\theme_url('assets/js/Meting.js'); ?>"></script>
<?php
}

if ($this->options->JPendant_SSL == 'on') {
	// SSL安全认证
?>
	<style>
		#cc-myssl-seal {
			width: 65px;
			height: 65px;
			z-index: 9;
			position: fixed;
			right: 0;
			bottom: 0;
			cursor: pointer;
		}
	</style>
	<div id="cc-myssl-seal">
		<div title="TrustAsia 安全签章" id="myssl_seal" onclick="window.open('https://seal.trustasia.com/seal/detail?domain=<?= $_SERVER['HTTP_HOST'] ?>','TrustAsia 安全签章','height=800,width=470,top=0,right=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no')" style="text-align: center">
			<img src="//static.myssl.com/res/images/myssl-id.png" alt="" style="width: 100%; height: 100%"></a>
		</div>
	</div>
<?php
}

?>
<footer class="joe_footer">
	<div class="joe_container">
		<div class="item">
			<?php $this->options->JFooter_Left() ?>
		</div>
		<?php if ($this->options->JBirthDay) : ?>
			<div class="item">
				<span>已运行 <strong class="joe_run__day">00</strong> 天 <strong class="joe_run__hour">00</strong> 时 <strong class="joe_run__minute">00</strong> 分 <strong class="joe_run__second">00</strong> 秒</span>
			</div>
		<?php endif; ?>
		<div class="item">
			<?php $this->options->JFooter_Right() ?>
		</div>
		<?php
		if (!empty($this->options->baidu_statistics)) {
		?>
			<style>
				#statistics>span>strong {
					color: var(--theme)
				}
			</style>
			<div class="item" id="statistics">
				<span>今日访问人数&nbsp;<strong>...</strong>丨</span><span>昨日访问人数&nbsp;<strong>...</strong>丨</span><span>本月访问量&nbsp;<strong>...</strong></span>
			</div>
		<?php
		}
		?>
	</div>
	<?php
	if ($this->options->JFooter_Fish == 'on') {
		echo '<div id="footer_fish"></div><script src="' . joe\theme_url('assets/js/FooterFish.js') . '"></script>';
	}
	?>
</footer>

<div class="joe_action">
	<div class="joe_action_item scroll">
		<svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
			<path d="M725.902 498.916c18.205-251.45-93.298-410.738-205.369-475.592l-6.257-3.982-6.258 3.414c-111.502 64.853-224.711 224.142-204.8 475.59-55.751 53.476-80.214 116.623-80.214 204.8v15.36l179.2-35.27c11.378 40.39 58.596 69.973 113.21 69.973 54.613 0 101.262-29.582 112.64-68.836l180.337 36.41v-15.36c-.569-89.885-25.031-153.6-82.489-206.507zM571.733 392.533c-33.564 31.29-87.04 28.445-118.329-5.12s-28.444-87.04 5.12-117.76c33.565-31.289 87.04-28.444 118.33 5.12s28.444 86.471-5.12 117.76zm-56.32 368.64c-35.84 0-64.284 29.014-64.284 64.285 0 35.84 54.044 182.613 64.284 182.613s64.285-146.773 64.285-182.613c0-35.271-29.014-64.285-64.285-64.285z" />
		</svg>
	</div>
	<div class="joe_action_item mode">
		<svg class="icon-1" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
			<path d="M587.264 104.96c33.28 57.856 52.224 124.928 52.224 196.608 0 218.112-176.128 394.752-393.728 394.752-29.696 0-58.368-3.584-86.528-9.728C223.744 832.512 369.152 934.4 538.624 934.4c229.376 0 414.72-186.368 414.72-416.256 1.024-212.992-159.744-389.12-366.08-413.184z" />
			<path d="M340.48 567.808l-23.552-70.144-70.144-23.552 70.144-23.552 23.552-70.144 23.552 70.144 70.144 23.552-70.144 23.552-23.552 70.144zM168.96 361.472l-30.208-91.136-91.648-30.208 91.136-30.208 30.72-91.648 30.208 91.136 91.136 30.208-91.136 30.208-30.208 91.648z" />
		</svg>
		<svg class="icon-2" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
			<path d="M234.24 512a277.76 277.76 0 1 0 555.52 0 277.76 277.76 0 1 0-555.52 0zM512 187.733a42.667 42.667 0 0 1-42.667-42.666v-102.4a42.667 42.667 0 0 1 85.334 0v102.826A42.667 42.667 0 0 1 512 187.733zm-258.987 107.52a42.667 42.667 0 0 1-29.866-12.373l-72.96-73.387a42.667 42.667 0 0 1 59.306-59.306l73.387 72.96a42.667 42.667 0 0 1 0 59.733 42.667 42.667 0 0 1-29.867 12.373zm-107.52 259.414H42.667a42.667 42.667 0 0 1 0-85.334h102.826a42.667 42.667 0 0 1 0 85.334zm34.134 331.946a42.667 42.667 0 0 1-29.44-72.106l72.96-73.387a42.667 42.667 0 0 1 59.733 59.733l-73.387 73.387a42.667 42.667 0 0 1-29.866 12.373zM512 1024a42.667 42.667 0 0 1-42.667-42.667V878.507a42.667 42.667 0 0 1 85.334 0v102.826A42.667 42.667 0 0 1 512 1024zm332.373-137.387a42.667 42.667 0 0 1-29.866-12.373l-73.387-73.387a42.667 42.667 0 0 1 0-59.733 42.667 42.667 0 0 1 59.733 0l72.96 73.387a42.667 42.667 0 0 1-29.44 72.106zm136.96-331.946H878.507a42.667 42.667 0 1 1 0-85.334h102.826a42.667 42.667 0 0 1 0 85.334zM770.987 295.253a42.667 42.667 0 0 1-29.867-12.373 42.667 42.667 0 0 1 0-59.733l73.387-72.96a42.667 42.667 0 1 1 59.306 59.306l-72.96 73.387a42.667 42.667 0 0 1-29.866 12.373z" />
		</svg>
	</div>
	<div class="joe_action_item full_screen">
		<svg style="width: 20px;height: 20px;" class="icon-1" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
			<path d="M981.333333 682.666667c-25.6 0-42.666667 17.066667-42.666666 42.666666v192c0 12.8-8.533333 21.333333-21.333334 21.333334H725.333333c-25.6 0-42.666667 17.066667-42.666666 42.666666s17.066667 42.666667 42.666666 42.666667h192c59.733333 0 106.666667-46.933333 106.666667-106.666667V725.333333c0-25.6-17.066667-42.666667-42.666667-42.666666zM298.666667 938.666667H106.666667c-12.8 0-21.333333-8.533333-21.333334-21.333334V725.333333c0-25.6-17.066667-42.666667-42.666666-42.666666s-42.666667 17.066667-42.666667 42.666666v192C0 977.066667 46.933333 1024 106.666667 1024H298.666667c25.6 0 42.666667-17.066667 42.666666-42.666667s-17.066667-42.666667-42.666666-42.666666zM917.333333 0H725.333333c-25.6 0-42.666667 17.066667-42.666666 42.666667s17.066667 42.666667 42.666666 42.666666h192c12.8 0 21.333333 8.533333 21.333334 21.333334V298.666667c0 25.6 17.066667 42.666667 42.666666 42.666666s42.666667-17.066667 42.666667-42.666666V106.666667C1024 46.933333 977.066667 0 917.333333 0zM42.666667 341.333333c25.6 0 42.666667-17.066667 42.666666-42.666666V106.666667c0-12.8 8.533333-21.333333 21.333334-21.333334H298.666667c25.6 0 42.666667-17.066667 42.666666-42.666666s-17.066667-42.666667-42.666666-42.666667H106.666667C46.933333 0 0 46.933333 0 106.666667V298.666667c0 25.6 17.066667 42.666667 42.666667 42.666666zM183.466667 277.333333v469.333334c0 51.2 42.666667 98.133333 98.133333 98.133333h469.333333c51.2 0 98.133333-42.666667 98.133334-98.133333v-469.333334c0-51.2-42.666667-98.133333-98.133334-98.133333h-469.333333c-55.466667 4.266667-98.133333 46.933333-98.133333 98.133333z m597.333333 0v469.333334c0 17.066667-12.8 34.133333-34.133333 34.133333h-469.333334c-17.066667 0-34.133333-12.8-34.133333-34.133333v-469.333334c0-17.066667 12.8-34.133333 34.133333-34.133333h469.333334c17.066667 4.266667 34.133333 17.066667 34.133333 34.133333z"></path>
		</svg>
		<svg class="icon-2" style="display: none;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
			<path d="M432 544H192c-17.67 0-32 14.33-32 32s14.33 32 32 32h178.75L137.38 841.38c-12.5 12.5-12.5 32.75 0 45.25 6.25 6.25 14.44 9.38 22.62 9.38s16.38-3.12 22.62-9.38L416 653.25V832c0 17.67 14.33 32 32 32s32-14.33 32-32V592c0-26.47-21.53-48-48-48zM886.62 137.38c-12.5-12.5-32.75-12.5-45.25 0L608 370.75V192c0-17.67-14.33-32-32-32s-32 14.33-32 32v240c0 26.47 21.53 48 48 48h240c17.67 0 32-14.33 32-32s-14.33-32-32-32H653.25l233.38-233.38c12.49-12.5 12.49-32.74-0.01-45.24z"></path>
		</svg>
	</div>
</div>

<script>
	<?php
	$cookie = Typecho_Cookie::getPrefix();
	$notice = $cookie . '__typecho_notice';
	$type = $cookie . '__typecho_notice_type';
	?>
	<?php if (isset($_COOKIE[$notice]) && isset($_COOKIE[$type]) && ($_COOKIE[$type] == 'success' || $_COOKIE[$type] == 'notice' || $_COOKIE[$type] == 'error')) : ?>
		Qmsg.info("<?php echo preg_replace('#\[\"(.*?)\"\]#', '$1', $_COOKIE[$notice]); ?>！")
	<?php endif; ?>
	<?php
	Typecho_Cookie::delete('__typecho_notice');
	Typecho_Cookie::delete('__typecho_notice_type');
	?>
	console.log("%cTheme By Joe再续前缘版", "color:#fff; background: linear-gradient(270deg, #986fee, #8695e6, #68b7dd, #18d7d3); padding: 8px 15px; border-radius: 0 15px 0 15px");
	<?php $this->options->JCustomScript() ?>
</script>

<?php $this->options->JCustomBodyEnd() ?>

<?php $this->footer(); ?>
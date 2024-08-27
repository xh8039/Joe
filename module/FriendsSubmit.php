<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<style type="text/css">
	.friend_submit {
		margin-top: 30px;
		margin-bottom: 25px;
	}

	.friend_submit>h2 {
		color: var(--main);
		padding: 0 15px;
		font-size: 18px;
		line-height: 24px;
		margin-bottom: 20px;
		position: relative;
	}

	.friend_submit>h2::before {
		content: '';
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		width: 4px;
		background: var(--back-line-bottom);
		border-radius: 0 4px 4px 0;
	}

	.friend_submit>.input {
		width: 100%;
		display: flex;
		align-items: center;
		margin-bottom: 15px;
	}

	.friend_submit>.input>.input-label {
		margin-right: 10px;
		white-space: nowrap;
		color: var(--main);
	}

	.friend_submit>.input>input {
		height: 36px;
		width: 100%;
		display: block;
		padding: 0 10px;
		border-radius: 2px;
		color: var(--routine);
		background: transparent;
		border: 1.5px solid var(--classA);
		transition: 0.3s;
	}

	.friend_submit>.input>input:focus {
		background: var(--background);
		border-color: var(--theme);
	}

	.friend_submit>.button>button {
		padding: 0 15px;
		height: 36px;
		border: none;
		background: var(--back-line-right);
		color: #fff;
		border-radius: 3px;
	}

	.friend_submit>.button>.submit:hover {
		animation: 5s ease-in-out 0s infinite normal none running shaked;
	}

	.friend_submit>.button>.submit {
		margin-left: 36px;
		margin-right: 2vw;
	}

	.friend_submit>.button>.reset {
		color: var(--main);
		background: var(--classC);
		transition: 0.3s;
	}

	.friend_submit>.button>.reset:hover {
		background: var(--classB);
		border-color: var(--classA);
	}
</style>
<form class="friend_submit" id="friend_form">
	<h2 class="outline-heading">在线申请</h2>
	<div class="input">
		<label class="input-label">标题</label>
		<input type="text" placeholder="站点标题" id="title">
	</div>
	<div class="input">
		<label class="input-label">简介</label>
		<input type="text" placeholder="站点简介，NULL = true" id="description">
	</div>
	<div class="input">
		<label class="input-label">链接</label>
		<input type="url" placeholder="链接地址" id="link">
	</div>
	<div class="input">
		<label class="input-label">图标</label>
		<input type="url" placeholder="LOGO地址，不填写则使用您的QQ头像" id="logo">
	</div>
	<div class="input">
		<label class="input-label">Q Q</label>
		<input type="number" placeholder="你的QQ号" id="qq">
	</div>
	<div class="input">
		<label class="input-label">验证</label>
		<input placeholder="请输入图片中的内容" id="captcha">
		<img style="cursor: pointer;height: 36px;" src="<?php $this->options->themeUrl('module/captcha.php') ?>" onclick="this.src=this.src+'?d='+Math.random();" title="点击刷新">
	</div>
	<div class="button">
		<button class="submit" id="friend_submit" type="button">立即提交</button>
		<button type="reset" class="reset">重 置</button>
	</div>
</form>
<script type="text/javascript">
	$('#friend_submit').click(() => {
		var title = $('#title').val();
		var description = $('#description').val();
		var link = $('#link').val();
		var logo = $('#logo').val();
		var qq = $('#qq').val();
		var captcha = $('#captcha').val();
		if (!(title && link && qq)) {
			Qmsg.warning('请填写必填项');
			return
		}
		$.ajax({
			type: "post",
			url: Joe.BASE_API,
			data: {
				routeType: 'friend_submit',
				title: title,
				description: description,
				link: link,
				logo: logo,
				qq: qq,
				captcha: captcha
			},
			dataType: "json",
			beforeSend() {
				$('#friend_submit').html('提交中...');
			},
			success(data) {
				$('#friend_submit').html('立即提交');
				if (data.code == 200) {
					Qmsg.success(data.msg);
				} else {
					Qmsg.warning(data.msg);
				}
			},
			error() {
				$('#friend_submit').html('立即提交');
				Qmsg.error('提交失败');
			}
		});
	})
</script>
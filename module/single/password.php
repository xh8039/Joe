<form class="joe_detail__article-protected" action="<?= $this->security->getTokenUrl($this->permalink) ?>">
	<div class="contain">
		<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
			<use xlink:href="#icon-joe-article-password"></use>
		</svg>
		<input type="hidden" name="protectCID" value="<?= $this->cid ?>" />
		<input class="password" name="protectPassword" type="password" placeholder="请输入访问密码...">
		<button class="submit" type="submit" value="提交">确定</button>
	</div>
</form>
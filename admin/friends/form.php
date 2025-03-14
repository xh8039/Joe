<?php
require_once TYPECHO_ADMIN_ROOT . 'header.php';
require_once TYPECHO_ADMIN_ROOT . 'menu.php';
?>
<div class="main">
	<div class="body container">
		<div class="typecho-page-title">
			<h2>新增友链</h2>
		</div>
		<div class="row typecho-page-main" role="form">
			<div class="col-mb-12 col-tb-6 col-tb-offset-3">
				<?php
				/** 构建表格 */
				$form = new Typecho\Widget\Helper\Form(
					Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2F' . urlencode(THEME_NAME) . '%2Fadmin%2Ffriends.php'),
					Typecho\Widget\Helper\Form::POST_METHOD
				);

				/** 友链名称 */
				$name = new Typecho\Widget\Helper\Form\Element\Text('title', null, null, _t('友链标题*'));
				$form->addInput($name);

				/** 友链地址 */
				$url = new Typecho\Widget\Helper\Form\Element\Text('url', null, "http://", _t('友链地址*'));
				$form->addInput($url);

				/** 友链描述 */
				$description =  new Typecho\Widget\Helper\Form\Element\Textarea('description', null, null, _t('友链描述'));
				$form->addInput($description);

				/** 友链图片 */
				$logo = new Typecho\Widget\Helper\Form\Element\Text('logo', null, null, _t('友链图片'),  _t('需要以 http:// 或 https:// 开头，留空表示没有友链图片'));
				$form->addInput($logo);


				/** 友链rel属性 */
				$rel = new Typecho\Widget\Helper\Form\Element\Text('rel', null, "friend", _t('友链rel属性'));
				$form->addInput($rel);

				/** 友链邮箱 */
				$email = new Typecho\Widget\Helper\Form\Element\Text('email', null, null, _t('友链邮箱'));
				$form->addInput($email);

				/** 友链排序 */
				$order = new Typecho\Widget\Helper\Form\Element\Text('order', null, "0", _t('友链排序'));
				$form->addInput($order);

				/** 友链位置 */
				$list = array('index_bottom' => '首页底部', 'single' => '独立页面');
				$position = new Typecho\Widget\Helper\Form\Element\Checkbox('position', $list, NULL, '友链位置');
				$form->addInput($position);

				/** 友链状态 */
				$status = new Typecho\Widget\Helper\Form\Element\Radio('status', ['1' => '启用', '0' => '禁用'], '1', '友链状态', '注意：此处编辑友链状态不会邮箱通知对方');
				$form->addInput($status);

				/** 友链动作 */
				$do = new Typecho\Widget\Helper\Form\Element\Hidden('action');
				$form->addInput($do);

				/** 友链主键 */
				$id = new Typecho\Widget\Helper\Form\Element\Hidden('id');
				$form->addInput($id);

				$referer = new Typecho\Widget\Helper\Form\Element\Hidden('referer', null, $request->getHeader('referer'));
				$form->addInput($referer);

				/** 提交按钮 */
				$submit = new Typecho\Widget\Helper\Form\Element\Submit();
				$submit->input->setAttribute('class', 'btn primary');
				$form->addItem($submit);

				if (isset($request->id) && 'create' != $action) {
					/** 更新模式 */
					$db = Typecho\Db::get();
					$prefix = $db->getPrefix();
					$link = $db->fetchRow($db->select()->from($prefix . 'friends')->where('id = ?', $request->id));
					if (!$link) {
						throw new Typecho\Widget\Exception(_t('友链不存在'), 404);
					}

					$name->value($link['title']);
					$url->value($link['url']);
					$logo->value($link['logo']);
					$description->value($link['description']);
					$rel->value($link['rel']);
					$email->value($link['email']);
					$order->value($link['order']);
					$position->value(explode(',', $link['position'] ?? ''));
					$status->value($link['status']);
					$do->value('update');
					$id->value($link['id']);
					$submit->value(_t('编辑友链'));
					$_action = 'update';
				} else {
					$do->value('insert');
					$submit->value(_t('增加友链'));
					$_action = 'insert';
				}

				if (empty($action)) $action = $_action;

				/** 给表单增加规则 */
				if ('create' == $action || 'edit' == $action) {
					$name->addRule('required', _t('必须填写友链名称'));
					$url->addRule('required', _t('必须填写友链地址'));
					$url->addRule('url', _t('不是一个合法的链接地址'));
					// $email->addRule('email', _t('不是一个合法的邮箱地址'));
					$logo->addRule('url', _t('不是一个合法的图片地址'));
					$name->addRule('maxLength', _t('友链名称最多包含50个字符'), 50);
					$url->addRule('maxLength', _t('友链地址最多包含200个字符'), 200);
					// $sort->addRule('maxLength', _t('友链分类最多包含50个字符'), 50);
					// $email->addRule('maxLength', _t('友链邮箱最多包含50个字符'), 50);
					$logo->addRule('maxLength', _t('友链图片最多包含200个字符'), 200);
					$description->addRule('maxLength', _t('友链描述最多包含200个字符'), 200);
					// $user->addRule('maxLength', _t('自定义数据最多包含200个字符'), 200);
				}
				if ('create' == $action) {
					$id->addRule('required', _t('友链主键不存在'));
					$id->addRule('LinkExists', _t('友链不存在'));
				}
				$form->render();
				?>
			</div>
		</div>
	</div>
</div>
<?php
include 'copyright.php';
include 'common-js.php';
include 'form-js.php';
include 'footer.php';

<div class="main">
	<div class="body container">
		<div class="typecho-page-title">
			<h2>新增友链</h2>
		</div>
		<div class="row typecho-page-main" role="form">
			<div class="col-mb-12 col-tb-6 col-tb-offset-3">
				<?php
				/** 构建表格 */
				$form = new Typecho_Widget_Helper_Form(
					Helper::security()->getAdminUrl('extending.php?panel=..%2Fthemes%2FJoe%2Fadmin%2Ffriends.php'),
					Typecho_Widget_Helper_Form::POST_METHOD
				);

				/** 友链名称 */
				$name = new Typecho_Widget_Helper_Form_Element_Text('title', null, null, _t('友链标题*'));
				$form->addInput($name);

				/** 友链地址 */
				$url = new Typecho_Widget_Helper_Form_Element_Text('url', null, "http://", _t('友链地址*'));
				$form->addInput($url);

				/** 友链描述 */
				$description =  new Typecho_Widget_Helper_Form_Element_Textarea('description', null, null, _t('友链描述'));
				$form->addInput($description);

				/** 友链图片 */
				$logo = new Typecho_Widget_Helper_Form_Element_Text('logo', null, null, _t('友链图片'),  _t('需要以 http:// 或 https:// 开头，留空表示没有友链图片'));
				$form->addInput($logo);


				/** 友链rel属性 */
				$rel = new Typecho_Widget_Helper_Form_Element_Text('rel', null, "friend", _t('友链rel属性'));
				$form->addInput($rel);

				/** 友链QQ号 */
				$qq = new Typecho_Widget_Helper_Form_Element_Text('qq', null, "friend", _t('友链QQ号'));
				$form->addInput($qq);

				/** 友链排序 */
				$order = new Typecho_Widget_Helper_Form_Element_Text('order', null, "0", _t('友链排序'));
				$form->addInput($order);

				/** 友链状态 */
				$list = array('0' => '禁用', '1' => '启用');
				$status = new Typecho_Widget_Helper_Form_Element_Radio('status', $list, '1', '友链状态');
				$form->addInput($status);

				/** 友链动作 */
				$do = new Typecho_Widget_Helper_Form_Element_Hidden('action');
				$form->addInput($do);

				/** 友链主键 */
				$id = new Typecho_Widget_Helper_Form_Element_Hidden('id');
				$form->addInput($id);

				/** 提交按钮 */
				$submit = new Typecho_Widget_Helper_Form_Element_Submit();
				$submit->input->setAttribute('class', 'btn primary');
				$form->addItem($submit);
				$request = Typecho_Request::getInstance();

				if (isset($request->id) && 'create' != $action) {
					/** 更新模式 */
					$db = Typecho_Db::get();
					$prefix = $db->getPrefix();
					$link = $db->fetchRow($db->select()->from($prefix . 'friends')->where('id = ?', $request->id));
					if (!$link) {
						throw new Typecho_Widget_Exception(_t('友链不存在'), 404);
					}

					$name->value($link['title']);
					$url->value($link['url']);
					// $sort->value($link['sort']);
					// $email->value($link['email']);
					$logo->value($link['logo']);
					$description->value($link['description']);
					$rel->value($link['rel']);
					$qq->value($link['qq']);
					$order->value($link['order']);
					// $user->value($link['user']);
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

				if (empty($action)) {
					$action = $_action;
				}

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

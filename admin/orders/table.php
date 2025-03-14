<?php
require_once TYPECHO_ADMIN_ROOT . 'header.php';
require_once TYPECHO_ADMIN_ROOT . 'menu.php';
require_once __DIR__ . '/widget.php';
$orders = Typecho\Widget::widget('JoeOrders\Widget');
$orders_url = '../themes/' . THEME_NAME . '/admin/orders.php';
$panel_url = $options->adminUrl . 'extending.php?panel=' . urlencode($orders_url);
?>
<style>
	.typecho-list-table td {
		font-size: 12px;
		padding: 5px;
		text-align: center;
	}

	.typecho-list-table th {
		font-size: 12px;
		padding: 0 5px 5px;
		text-align: center;
	}

	.typecho-table-wrap {
		padding: 10px;
	}

	.container {
		max-width: 100% !important;
	}
</style>
<div class="main">
	<div class="body container">
		<div class="typecho-page-title">
			<h2>订单管理</h2>
		</div>
		<div class="row typecho-page-main" role="main">
			<div class="col-mb-12 typecho-list">
				<div class="typecho-list-operate clearfix">
					<form method="get" action="<?php $options->adminUrl('extending.php'); ?>">
						<input type="hidden" name="panel" value="<?= '../themes/' . THEME_NAME . '/admin/orders.php' ?>" />
						<div class="operate">
							<label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox"
									class="typecho-table-select-all" /></label>
							<div class="btn-group btn-drop">
								<button class="btn dropdown-toggle btn-s" type="button"><i
										class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i
										class="i-caret-down"></i></button>
								<ul class="dropdown-menu">
									<li><a lang="<?php _e('你确认要删除这些订单吗?'); ?>" href="<?= $panel_url . '&action=delete' ?>"><?php _e('删除') ?></a>
									</li>
								</ul>
								<button lang="你确认要清理所有未支付订单吗?" class="btn btn-s btn-warn btn-operate" href="<?= $panel_url . '&action=clear' ?>">清理所有未支付订单</button>
							</div>
						</div>
						<div class="search" role="search">
							<?php if ('' != $request->keywords): ?>
								<a href="<?= $panel_url ?>"><?php _e('&laquo; 取消筛选'); ?></a>
							<?php endif; ?>
							<input type="text" class="text-s" placeholder="<?php _e('请输入关键字'); ?>"
								value="<?php echo $request->filter('html')->keywords; ?>" name="keywords" />
							<select class="search-type" name="type">
								<option value=""><?php _e('支付方式'); ?></option>
								<?php foreach (['wxpay' => '微信', 'alipay' => '支付宝', 'qqpay' => 'QQ'] as $id => $name) : ?>
									<option value="<?php echo $id; ?>" <?php if ($request->get('type') == $id) : ?> selected="true" <?php endif; ?>><?php echo $name; ?></option>
								<?php endforeach; ?>
							</select>
							<select class="search-status" name="status">
								<option value=""><?php _e('支付状态'); ?></option>
								<?php foreach (['1' => '已支付', '0' => '未支付'] as $id => $name) : ?>
									<option value="<?php echo $id; ?>" <?= $request->get('status', 2) == $id ? 'selected="true"' : null ?>><?php echo $name; ?></option>
								<?php endforeach; ?>
							</select>
							<button type="submit" class="search-btn btn btn-s"><?php _e('筛选'); ?></button>
						</div>
					</form>
				</div><!-- end .typecho-list-operate -->

				<form method="post" name="manage_users" class="operate-form">
					<div class="typecho-table-wrap">
						<table class="typecho-list-table">
							<colgroup>
								<col width="25px" />
								<col width="130px" />
								<col width="150px" />
								<col width="200px" />
								<col width="60px" />
								<col width="60px" />
								<col width="100px" />
								<col width="50px" />
								<col width="60px" />
								<col width="60px" />
								<col width="60px" />
								<col width="130px" />
							</colgroup>
							<thead>
								<tr>
									<th></th>
									<th>订单号</th>
									<th>接口订单号</th>
									<th>文章标题</th>
									<th>支付方式</th>
									<th>订单金额</th>
									<th>用户IP</th>
									<th>用户ID</th>
									<th>实付金额</th>
									<th>通知管理</th>
									<th>通知用户</th>
									<th>创建时间</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($orders->have()) : ?>
									<?php while ($orders->next()) : ?>
										<tr id="<?php $orders->id(); ?>">
											<td><input type="checkbox" value="<?php $orders->id(); ?>" name="id[]" /></td>
											<td><?php $orders->trade_no(); ?></td>
											<td><?php $orders->api_trade_no(); ?></td>
											<td><?php $orders->content_title(); ?></td>
											<td><?php $orders->typeName(); ?></td>
											<td><?php $orders->money(); ?></td>
											<td><?php $orders->ip(); ?></td>
											<td><?php $orders->user_id(); ?></td>
											<td><?php $orders->pay_price(); ?></td>
											<td><?php $orders->admin_email(); ?></td>
											<td><?php $orders->user_email(); ?></td>
											<td><?php $orders->create_time(); ?></td>
										</tr>
									<?php endwhile; ?>
								<?php else : ?>
									<tr class="even">
										<td colspan="8">
											<h6 class="typecho-list-table-title"><?php _e('当前无订单'); ?></h6>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table><!-- end .typecho-list-table -->
					</div><!-- end .typecho-table-wrap -->
				</form><!-- end .operate-form -->

				<div class="typecho-list-operate clearfix">
					<form method="get">
						<!-- <div class="operate">
							<label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox"
									class="typecho-table-select-all" /></label>
							<div class="btn-group btn-drop">
								<button class="btn dropdown-toggle btn-s" type="button"><i
										class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i
										class="i-caret-down"></i></button>
								<ul class="dropdown-menu">
									<li><a lang="<?php _e('你确认要删除这些用户吗?'); ?>"
											href="<?php $security->index('/action/users-edit?do=delete'); ?>"><?php _e('删除'); ?></a>
									</li>
								</ul>
							</div>
						</div> -->
						<?php if ($orders->have()): ?>
							<ul class="typecho-pager">
								<?php $orders->pageNav(); ?>
							</ul>
						<?php endif; ?>
					</form>
				</div>
				<!-- end .typecho-list-operate -->
			</div><!-- end .typecho-list -->
		</div><!-- end .typecho-page-main -->
	</div>
</div>
<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php';
include 'footer.php';

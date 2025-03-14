<?php
require_once TYPECHO_ADMIN_ROOT . 'header.php';
require_once TYPECHO_ADMIN_ROOT . 'menu.php';
require_once __DIR__ . '/widget.php';
$orders = Typecho\Widget::widget('JoeFriends_Widget');
function waiting_count()
{
	global $db;
	return $db->fetchRow(
		$db->select('COUNT(*) AS count')->from('table.friends')->where('status = ?', 0)
	)['count'];
}
$waiting_count = waiting_count();
$friends_url = '../themes/' . THEME_NAME . '/admin/friends.php';
$panel_url = $options->adminUrl . 'extending.php?panel=' . urlencode($friends_url);
?>
<style>
	.typecho-list-table td {
		text-align: center;
	}

	.typecho-list-table th {
		text-align: center;
	}
</style>
<div class="main">
	<div class="body container">
		<div class="typecho-page-title">
			<h2>友情链接<a href="<?= $panel_url . '&action=create'; ?>">新增</a></h2>
		</div>
		<div class="row typecho-page-main" role="main">
			<div class="col-mb-12 typecho-list">
				<div class="clearfix">
					<ul class="typecho-option-tabs">
						<li class="<?= !isset($_GET['status']) ? 'current' : null ?>"><a href="<?= $panel_url ?>">全部</a></li>
						<li class="<?= (isset($_GET['status']) && $_GET['status'] == 1) ? 'current' : null ?>"><a href="<?= $panel_url . '&status=1' ?>">已通过</a></li>
						<li class="<?= (isset($_GET['status']) && $_GET['status'] == 0) ? 'current' : null ?>"><a href="<?= $panel_url . '&status=0' ?>">待审核<?= $waiting_count ? ' <span class="balloon">' . $waiting_count . '</span>' : null ?></a></li>
					</ul>
				</div>
				<div class="typecho-list-operate clearfix">
					<form method="get" action="<?php $options->adminUrl('extending.php'); ?>">
						<input type="hidden" name="panel" value="<?= $friends_url ?>" />
						<div class="operate">
							<label>
								<i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox" class="typecho-table-select-all" />
							</label>
							<div class="btn-group btn-drop">
								<button class="btn dropdown-toggle btn-s" type="button">
									<i class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i class="i-caret-down"></i>
								</button>
								<ul class="dropdown-menu">
									<li><a href="<?= $panel_url . '&action=open' ?>"><?php _e('通过'); ?></a></li>
									<li><a href="<?= $panel_url . '&action=disable' ?>"><?php _e('待审核'); ?></a></li>
									<li><a href="<?= $panel_url . '&action=delete' ?>" lang="<?php _e('你确认要删除这些友链吗?'); ?>"><?php _e('删除'); ?></a></li>
								</ul>
							</div>
						</div>
						<div class="search" role="search">
							<?php if ('' != $request->keywords): ?>
								<a href="<?= $panel_url ?>"><?php _e('&laquo; 取消筛选'); ?></a>
							<?php endif; ?>
							<input type="text" class="text-s" placeholder="<?php _e('请输入关键字'); ?>" value="<?= $request->filter('html')->keywords; ?>" name="keywords" />
							<button type="submit" class="search-btn btn btn-s"><?php _e('筛选'); ?></button>
						</div>
					</form>
				</div><!-- end .typecho-list-operate -->

				<form method="post" name="manage_users" class="operate-form">
					<div class="typecho-table-wrap">
						<table class="typecho-list-table">
							<colgroup>
								<col width="35px" />
								<col width="120px" />
								<col width="150px" />
								<col width="200px" />
								<col width="80px" />
								<col width="80px" />
								<col width="50px" />
								<col width="70px" />
								<col width="150px" />
							</colgroup>
							<thead>
								<tr>
									<th></th>
									<th>站点标题</th>
									<th>站点链接</th>
									<th>站点简介</th>
									<th>LOGO</th>
									<th>位置</th>
									<th>排序</th>
									<th>状态</th>
									<th>创建时间</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($orders->have()) : ?>
									<?php while ($orders->next()) : ?>
										<tr id="<?php $orders->id() ?>">
											<td><input type="checkbox" value="<?php $orders->id() ?>" name="id[]" /></td>
											<td>
												<a href="<?= $panel_url . '&action=edit&id=' . $orders->id ?>"><?php $orders->title() ?></a>
												<a href="<?= $panel_url . '&action=edit&id=' . $orders->id ?>" title="编辑 <?php $orders->title() ?>"><i class="i-edit"></i></a>
											</td>
											<td><a target="_blank" href="<?php $orders->url() ?>"><?php $orders->url() ?></a></td>
											<td><?php $orders->description() ?></td>
											<td><img onerror="this.src='<?php $options->themeUrl('assets/images/avatar-default.png') ?>'" referrerpolicy="no-referrer" rel="noreferrer" width="50px" src="<?php $orders->logo() ?>"></td>
											<td><?php $orders->position(); ?></td>
											<td><?php $orders->order(); ?></td>
											<td><?php $orders->status(); ?></td>
											<td><?php $orders->create_time(); ?></td>
										</tr>
									<?php endwhile; ?>
								<?php else : ?>
									<tr class="even">
										<td colspan="8">
											<h6 class="typecho-list-table-title"><?php _e('当前无友链'); ?></h6>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</form>
				<div class="typecho-list-operate clearfix">
					<form method="get" <?php $options->adminUrl('extending.php') ?>>
						<input type="hidden" name="panel" value="<?= '../themes/' . THEME_NAME . '/admin/friends.php' ?>" />
						<div class="operate">
							<label>
								<i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox" class="typecho-table-select-all" />
							</label>
							<div class="btn-group btn-drop">
								<button class="btn dropdown-toggle btn-s" type="button">
									<i class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i class="i-caret-down"></i>
								</button>
								<ul class="dropdown-menu">
									<li><a href="<?= $panel_url . '&action=open' ?>"><?php _e('通过'); ?></a></li>
									<li><a href="<?= $panel_url . '&action=disable' ?>"><?php _e('待审核'); ?></a></li>
									<li><a lang="<?php _e('你确认要删除这些友链吗?'); ?>" href="<?= $panel_url . '&action=delete' ?>"><?php _e('删除'); ?></a></li>
								</ul>
							</div>
						</div>
						<?php if ($orders->have()): ?>
							<ul class="typecho-pager">
								<?php $orders->pageNav(); ?>
							</ul>
						<?php endif; ?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php';
include 'footer.php';

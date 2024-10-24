<?php
require_once JOE_ROOT . 'library/widget/Friends.php';
$orders = Typecho_Widget::widget('JoeFriends_Widget');
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
			<h2>友情链接<a href="<?php $options->adminUrl('extending.php?panel=..%2Fthemes%2FJoe%2Fadmin%2Ffriends.php&action=create'); ?>">新增</a></h2>
		</div>
		<div class="row typecho-page-main" role="main">
			<div class="col-mb-12 typecho-list">
				<div class="typecho-list-operate clearfix">
					<form method="get" action="<?php $options->adminUrl('extending.php'); ?>">
						<input type="hidden" name="panel" value="<?= '../themes/' . THEME_NAME . '/admin/friends.php' ?>" />
						<div class="operate">
							<label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox"
									class="typecho-table-select-all" /></label>
							<div class="btn-group btn-drop">
								<button class="btn dropdown-toggle btn-s" type="button"><i
										class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i
										class="i-caret-down"></i></button>
								<ul class="dropdown-menu">
									<li><a lang="<?php _e('你确认要删除这些友链吗?'); ?>" href="<?php $options->adminUrl('extending.php?panel=..%2Fthemes%2FJoe%2Fadmin%2Ffriends.php&action=delete') ?>"><?php _e('删除'); ?></a></li>
									<li><a lang="<?php _e('你确认要启用这些友链吗?'); ?>" href="<?php $options->adminUrl('extending.php?panel=..%2Fthemes%2FJoe%2Fadmin%2Ffriends.php&action=open') ?>"><?php _e('启用'); ?></a></li>
									<li><a lang="<?php _e('你确认要禁用这些友链吗?'); ?>" href="<?php $options->adminUrl('extending.php?panel=..%2Fthemes%2FJoe%2Fadmin%2Ffriends.php&action=disable') ?>"><?php _e('禁用'); ?></a></li>
								</ul>
							</div>
						</div>
						<div class="search" role="search">
							<?php if ('' != $request->keywords): ?>
								<a href="<?php $options->adminUrl('extending.php'); ?>?panel=<?= urlencode('../themes/' . THEME_NAME . '/admin/friends.php') ?>"><?php _e('&laquo; 取消筛选'); ?></a>
							<?php endif; ?>
							<input type="text" class="text-s" placeholder="<?php _e('请输入关键字'); ?>"
								value="<?php echo $request->filter('html')->keywords; ?>" name="keywords" />
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
								<col width="50px" />
								<col width="150px" />
							</colgroup>
							<thead>
								<tr>
									<th></th>
									<th>站点标题</th>
									<th>站点链接</th>
									<th>站点简介</th>
									<th>LOGO</th>
									<th>rel属性</th>
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
											<td><a href="<?php $options->adminUrl('extending.php?panel=..%2Fthemes%2FJoe%2Fadmin%2Ffriends.php&action=edit&id=') . $orders->id() ?>"><?php $orders->title() ?></a></td>
											<td><a target="_blank" href="<?php $orders->url() ?>"><?php $orders->url() ?></a></td>
											<td><?php $orders->description() ?></td>
											<td><img referrerpolicy="no-referrer" rel="noreferrer" width="50px" height="50px" src="<?php $orders->logo() ?>"></td>
											<td><?php $orders->rel(); ?></td>
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
										<li><a lang="<?php _e('你确认要删除这些友链吗?'); ?>"
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

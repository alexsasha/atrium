<div class="container" id="posts">
	<div class="panel panel-default">
	<div class="panel-heading">
		<div class="page-header">
			<h2>Пользователи <small>(<?php echo $users_count; ?>)</small> <a href="<?php echo site_url('admin/register'); ?>" class="btn btn-default btn-sm" role="button">Добавить <span class="glyphicon glyphicon-plus"></span></a></h2>
		</div>
	</div>
	<?php if($users): ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Логин</th>
					<th>E-mail</th>
					<th>Роль</th>
					<th>Дата регистрации</th>
					<th class="function-cell">Записи</th>
					<th class="function-cell">-</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($users as $user): ?>
			
			    <tr>
					<td><?php echo $user->user_login; ?></td>
					<td><?php echo $user->user_email; ?></td>
					<td><?php echo get_user_role($user->ID); ?></td>
					<td><?php echo gmt_to_local_date('Y-m-d H:i:s', $user->user_registered, $timezones); ?></td>
					<td class="function-cell"><?php echo count_user_posts($user->ID); ?></td>
					<td class="function-cell">
				    	<a href="<?php echo site_url('admin/user_delete/' . $user->ID); ?>" class="glyphicon glyphicon-remove" title="Удалить" onclick="if (!confirm('Вы уверены, что хотите удалить пользователя?')) return false;"></a>
				    </td>
				</tr>

			<?php endforeach; ?>
			</tbody>
		</table>

	<?php else: ?>
		<h3 class="alert alert-warning">Пользователей не найдено.</h3>
	<?php endif; ?>
	</div>
</div>
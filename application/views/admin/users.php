<?php echo anchor('admin/register', 'Регистрация нового пользователя'); ?>
<?php if($users): ?>
	<table>
		<tr>
			<td>Логин</td>
			<td>E-mail</td>
			<td>Роль</td>
			<td>Дата регистрации</td>
			<td>Записи</td>
			<td>Операции</td>
		</tr>
	
	<?php foreach ($users as $user): ?>
	    <tr>
			<td><?php echo $user->user_login; ?></td>
			<td><?php echo $user->user_email; ?></td>
			<td><?php echo get_user_role($user->ID); ?></td>
			<td><?php echo gmt_to_local_date('Y-m-d H:i:s', $user->user_registered, $timezones); ?></td>
			<td><?php echo count_user_posts($user->ID); ?></td>
			<td><?php echo anchor('admin/user_delete/' . $user->ID, 'Удалить');?></td>
		</tr>
	<?php endforeach; ?>

	</table>
<?php else: ?>
	<h3>Пользователей не найдено.</h3>
<?php endif; ?>
<div class="container" id="posts">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="page-header">
				<h2>Записи <small>(<?php echo $publish_posts_count; ?>)</small> <a href="<?php echo site_url('admin/create'); ?>" class="btn btn-default btn-sm" role="button">Добавить <span class="glyphicon glyphicon-plus"></span></a></h2>
			</div>

			<ul class="nav nav-pills">
				<li <?php if(!isset($is_trash)) echo 'class="active"'; ?>>
					<a href="<?php echo base_url("admin/posts"); ?>">
						<span class="badge pull-right"><?php echo $publish_posts_count; ?></span>
						Опубликованные
					</a>
				</li>
				<li <?php if(isset($is_trash)) echo 'class="active"'; ?>>
					<a href="<?php echo base_url("admin/posts_trash"); ?>">
						<span class="badge pull-right"><?php echo $trash_posts_count; ?></span>
						Корзина
					</a>
				</li>
			</ul>
		</div>
		<?php if($posts): ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Заголовок</th>
					<th>Автор</th>
					<th>Категории</th>
					<th>Дата</th>
					<th class="function-cell">-</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($posts as $post): ?>
				<tr>
					<td><a href="<?php echo site_url('admin/update/' . $post->ID); ?>"><?php echo $post->post_title; ?></a></td>
					<td><?php $user = get_user($post->post_author); if($user) echo $user->user_login; ?></td>
					<td><?php echo $post->terms; ?></td>
					<td><time><?php echo get_date($post->ID); ?></time></td>
					<td class="function-cell">
						<?php if(isset($is_trash)): ?>
				    	<a href="<?php echo site_url('admin/untrash/' . $post->ID); ?>" class="glyphicon glyphicon-ok-circle" title="Восстановить запись"></a>
				    	<a href="<?php echo site_url('admin/delete_post/' . $post->ID); ?>" class="glyphicon glyphicon-remove-circle" title="Удалить навсегда" onclick="if (!confirm('Вы уверены, что хотите удалить навсегда?')) return false;"></a>
				    	<?php else: ?>
				    	<a href="<?php echo site_url('admin/delete/' . $post->ID); ?>" class="glyphicon glyphicon-trash" title="Удалить запись" onclick="if (!confirm('Вы уверены, что хотите удалить запись?')) return false;"></a>
				    	<?php endif; ?>
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<?php else: ?>
			<h3 class="alert alert-warning">Записей не найдено.</h3>
		<?php endif; ?>
	</div>

	<?php echo $pagi; ?>

</div>
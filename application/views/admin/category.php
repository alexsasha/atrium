<div class="container" id="category">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="page-header">
				<h2>Категории <small>(<?php echo $terms_count; ?>)</small> <a href="<?php echo site_url('admin/term_create'); ?>" class="btn btn-default btn-sm" role="button">Добавить <span class="glyphicon glyphicon-plus"></span></a></h2>
			</div>
		</div>

		<?php if($terms): ?>

			<table class="table table-striped">
				<thead>
					<tr>
						<th>Название</th>
						<th>Описание</th>
						<th>Ярлык</th>
						<th class="function-cell">-</th>
					</tr>
				</thead>
				<tbody>

				<?php foreach ($terms as $term): ?>

				    <tr>
				    	<td><a href=""><?php echo $term->name; ?></a></td>
				    	<td><?php echo $term->description; ?></td>
				    	<td><?php echo $term->slug; ?></td>
						<td class="function-cell">
					    	<a href="<?php echo site_url('admin/term_update/' . $term->term_id); ?>" class="glyphicon glyphicon-pencil"></a>
					    	<a href="<?php echo site_url('admin/term_delete/' . $term->term_id);?>" class="glyphicon glyphicon-remove" title="Удалить" onclick="if (!confirm('Вы уверены, что хотите удалить категорию?')) return false;"></a>
					    </td>
				    </tr>

				<?php endforeach ?>

				</tbody>
			</table>
		<?php else: ?>
			<h3 class="alert alert-warning">Категорий не создано.</h3>
		<?php endif; ?>
	</div>
</div>
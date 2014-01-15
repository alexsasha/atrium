<div class="container" id="panel">

	<div class="jumbotron">
		<h2>Добро пожаловать, <?php echo $user->user_login; ?>.</h2>
		<ul class="nav nav-pills nav-stacked">
			<li>Записей <span class="badge"><?php echo $count_posts; ?></span></li>
			<li>Категорий <span class="badge"><?php echo $count_cats; ?></span></li>
			<li>Пользователей <span class="badge"><?php echo $count_users; ?></span></li>
		</ul>
		<p><a href="<?php echo site_url('admin/create'); ?>" class="btn btn-success btn-lg" role="button">Добавить запись</a></p>
	</div>

</div>
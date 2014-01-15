<div class="container">
	
<?php echo form_open('admin/settings', array('class' => 'form')); ?>
	<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
	<h2 class="form-signin-heading">Настройки</h2>
	<?php
	$sitename = (set_value("sitename"))?set_value("sitename"):$sitename;
	$sitedesc = (set_value("sitedesc"))?set_value("sitedesc"):$sitedesc;
	$date_format = (set_value("date_format"))?set_value("date_format"):$date_format;
	$posts_per_page = (set_value("posts_per_page"))?set_value("posts_per_page"):$posts_per_page;
	$admin_posts_per_page = (set_value("admin_posts_per_page"))?set_value("admin_posts_per_page"):$admin_posts_per_page;
	?>
	<div>
		<label>
			Название сайта
			<input type="text" class="form-control" name="sitename" value="<?php echo $sitename; ?>" />
		</label>
	</div>

	<div>
		<label>
			Краткое описание
			<input type="text" class="form-control" name="sitedesc" value="<?php echo $sitedesc; ?>"/>
		</label>
	</div>
	<div>
		<label>
			Часовой пояс
			<?php echo timezone_menu($timezones); ?>
		</label>
	</div>
	<div>
		<label>
			Формат даты
			<input type="text" class="form-control" name="date_format" value="<?php echo $date_format; ?>"/>
		</label>
	</div>
	<div>
		<label>
			Постов на страницу в админке
			<input type="text" class="form-control" name="admin_posts_per_page" value="<?php echo $admin_posts_per_page; ?>"/>
		</label>
	</div>
	<div>
		<label>
			Постов на страницу на сайте
			<input type="text" class="form-control" name="posts_per_page" value="<?php echo $posts_per_page; ?>"/>
		</label>
	</div>
	<button class="btn btn-md btn-primary btn-block" type="submit">Сохранить</button>
</form>

</div> <!-- /container -->
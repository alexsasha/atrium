<?php echo validation_errors(); ?>

<?php echo form_open('admin/settings'); ?>

	<?php
	$sitename = (set_value("sitename"))?set_value("sitename"):$sitename;
	$sitedesc = (set_value("sitedesc"))?set_value("sitedesc"):$sitedesc;
	$date_format = (set_value("date_format"))?set_value("date_format"):$date_format;
	?>
	<div>
		<label>
			Название сайта
			<input type="text" name="sitename" value="<?php echo $sitename; ?>" />
		</label>
	</div>

	<div>
		<label>
			Краткое описание
			<input type="text" name="sitedesc" value="<?php echo $sitedesc; ?>"/>
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
			<input type="text" name="date_format" value="<?php echo $date_format; ?>"/>
		</label>
	</div>
	<div><input type="submit" value="Сохранить" /></div>

</form>
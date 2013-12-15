<center>
	<h3>Пожалуйста войдите</h3>
	
	<?php echo validation_errors(); ?>

	
	<?php echo anchor('admin', 'Ссылка');?>

	<?php echo form_open('admin/login'); ?>

		<div><input type="text" name="login" value="<?php echo set_value('login'); ?>" size="50" placeholder="Логин"/></div>

		<div><input type="password" name="pass" value="" size="50" placeholder="Пароль"/></div>

		<div><input type="submit" value="Войти" /></div>

	</form>
</center>
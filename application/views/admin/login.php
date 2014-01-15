<div class="container">
	
	<?php echo form_open('admin/login', array('class' => 'form-signin')); ?>
		<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
		<h3 class="form-signin-heading">Пожалуйста, войдите</h3>
		<input type="text" class="form-control" name="login" value="<?php echo set_value('login'); ?>" placeholder="Логин" required autofocus>
		<input type="password" class="form-control" name="pass" placeholder="Пароль" required>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
	</form>

</div> <!-- /container -->
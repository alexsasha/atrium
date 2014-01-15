<div class="container">
	
	<?php echo form_open('admin/register', array('class' => 'form')); ?>
		<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
		<h3 class="form-signin-heading">Регистрация нового пользователя</h3>
		
		<div>
			<label>
				Логин
				<input type="text" class="form-control" name="login" value="<?php echo set_value("login"); ?>" required>
			</label>
		</div>
		<div>
			<label>
				E-mail
				<input type="text" class="form-control" name="email" value="<?php echo set_value("email"); ?>" required>
			</label>
		</div>
		<div>
			<label>
				Пароль
				<input type="password" class="form-control" name="pass" value="" required>
			</label>
		</div>
		<div>
			<label>
				Подтвердите пароль
				<input type="password" class="form-control" name="pass2" value="" required>
			</label>
		</div>

		<button class="btn btn-md btn-primary btn-block" type="submit">Зарегистрировать</button>
	</form>

</div> <!-- /container -->
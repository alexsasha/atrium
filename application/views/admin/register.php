<center>
	<?php echo validation_errors(); ?>

	<?php echo form_open('admin/register'); ?>

		<div>
			<label>
				Логин
				<input type="text" name="login" value="<?php echo set_value("login"); ?>" />
			</label>
		</div>
		<div>
			<label>
				E-mail
				<input type="text" name="email" value="<?php echo set_value("email"); ?>" />
			</label>
		</div>
		<div>
			<label>
				Пароль
				<input type="password" name="pass" value=""/>
			</label>
		</div>
		<div>
			<label>
				Подтвердите пароль
				<input type="password" name="pass2" value=""/>
			</label>
		</div>

		<div><input type="submit" value="Сохранить" /></div>

	</form>
</center>
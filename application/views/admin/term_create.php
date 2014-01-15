<div class="container">
	
	<?php echo form_open('admin/term_create', array('class' => 'form')); ?>
		<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
		<h3 class="form-signin-heading">Создать категорию</h3>
		<input type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" size="50" placeholder="Название" required autofocus>

		<input type="text" class="form-control" name="slug" value="<?php echo set_value('slug'); ?>" size="50" placeholder="Ярлык"/>

		<textarea name="desc" class="form-control" id="desc" cols="30" rows="10" placeholder="Описание"><?php echo set_value('desc'); ?></textarea>

		<div><label><div>Родительская категория: </div>	 <?php echo form_dropdown('term_parent', $terms, 0, 'class="form-control"'); ?></label></div>

		<button class="btn btn-md btn-primary btn-block" type="submit">Добавить</button>
	</form>

</div> <!-- /container -->
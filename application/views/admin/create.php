<div class="container">
	
	<?php echo form_open('admin/create', array('class' => 'form editor')); ?>
		<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

		<div><input type="text" class="form-control" name="title" value="<?php echo set_value('title'); ?>" size="50" placeholder="Заголовок"/></div>

		<div><textarea class="form-control" name="content" id="content" cols="30" rows="10" placeholder="Содержание"><?php echo set_value('content'); ?></textarea></div>
		
		<div>
			<label>Категория: </label>
			<?php 
			foreach ($terms as $id => $name) 
			{
				$checked = FALSE;
				if(isset($terms_checked) && in_array($id, $terms_checked))
					$checked = TRUE;
				echo "<div>" . form_checkbox('terms[]', $id, $checked) . " <span>$name</span></div>";
			}
			?>
		</div>

		<button class="btn btn-md btn-primary" type="submit">Опубликовать</button>
	</form>

</div>
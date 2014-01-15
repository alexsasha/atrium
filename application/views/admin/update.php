<div class="container">
	
	<?php echo form_open('admin/update/' . $ID, array('class' => 'form editor')); ?>
		<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

		<div><input type="text" class="form-control" name="title" value="<?php echo $post_title; ?>" size="50" placeholder="Заголовок"/></div>

		<div><textarea name="content" class="form-control" id="content" cols="30" rows="10" placeholder="Содержание"><?php echo $post_content; ?></textarea></div>
		
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

		<button class="btn btn-md btn-primary" type="submit">Обновить</button>
	</form>

</div>
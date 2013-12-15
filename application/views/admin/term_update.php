<?php echo validation_errors(); ?>

<?php echo form_open('admin/term_update/' . $term->term_id); ?>

	<div><input type="text" name="name" value="<?php echo $term->name; ?>" size="50" placeholder="Название"/></div>

	<div><input type="text" name="slug" value="<?php echo $term->slug; ?>" size="50" placeholder="Ярлык"/></div>

	<div><textarea name="desc" id="desc" cols="30" rows="10" placeholder="Содержание"><?php echo $term->description; ?></textarea></div>

	<div><input type="submit" value="Обновить" /></div>

</form>
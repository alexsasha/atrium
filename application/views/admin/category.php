<?php echo validation_errors(); ?>

<?php echo form_open('admin/category'); ?>

	<div><input type="text" name="name" value="<?php echo set_value('name'); ?>" size="50" placeholder="Название"/></div>

	<div><input type="text" name="slug" value="<?php echo set_value('slug'); ?>" size="50" placeholder="Ярлык"/></div>

	<div><textarea name="desc" id="desc" cols="30" rows="10" placeholder="Содержание"><?php echo set_value('desc'); ?></textarea></div>

	<div><input type="submit" value="Добавить новую категорию" /></div>

</form>

<?php if($terms): ?>

	<?php foreach ($terms as $term): ?>

	    <div class="item">
	    	<div><?php echo $term->name; ?></div>
	    	<a href="<?php echo site_url('admin/term_update/' . $term->term_id);?>">Изменить</a>|
	    	<a href="<?php echo site_url('admin/term_delete/' . $term->term_id);?>">Удалить</a>
	    </div>

	<?php endforeach ?>
<?php else: ?>
	<h3>Категорий не создано.</h3>
<?php endif; ?>
<?php echo validation_errors(); ?>

<?php echo anchor('admin/posts', 'Назад к записям');?>

<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script>
	tinymce.init({
	    selector: "#content",
	    height: 500
	});
</script>

<?php echo form_open('admin/create'); ?>

	<div><input type="text" name="title" value="<?php echo set_value('title'); ?>" size="50" placeholder="Заголовок"/></div>

	<div><textarea name="content" id="content" cols="30" rows="10" placeholder="Содержание"><?php echo set_value('content'); ?></textarea></div>

	<div><input type="submit" value="Опубликовать" /></div>

</form>
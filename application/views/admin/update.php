<?php echo validation_errors(); ?>


<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script>
	tinymce.init({
	    selector: "#content",
	    height: 500
	});
</script>

<?php echo form_open('admin/update/' . $ID); ?>

	<div><input type="text" name="title" value="<?php echo $post_title; ?>" size="50" placeholder="Заголовок"/></div>

	<div><textarea name="content" id="content" cols="30" rows="10" placeholder="Содержание"><?php echo $post_content; ?></textarea></div>

	<div><input type="submit" value="Обновить" /></div>

</form>
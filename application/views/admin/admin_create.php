<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?></title>
</head>
<body>
	<?php echo validation_errors(); ?>

	<?php echo form_open('admin/create'); ?>

	<h5>Название</h5>
	<input type="text" name="title" value="<?php echo set_value('title'); ?>" size="50" />

	<h5>Содержание</h5>
	<textarea name="content" cols="30" rows="10"><?php echo set_value('content'); ?></textarea>

	<div><input type="submit" value="Submit" /></div>

	</form>
</body>
</html>
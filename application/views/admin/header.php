<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?></title>
</head>
<body>
	<ul>
		<li><?php echo anchor('admin', 'Главная');?></li>
		<li><?php echo anchor('admin/posts', 'Записи');?></li>
		<li><?php echo anchor('admin/create', 'Новая запись');?></li>
		<li><?php echo anchor('admin/category', 'Категории');?></li>
		<li><?php echo anchor('admin/users', 'Пользователи');?></li>
		<li><?php echo anchor('admin/settings', 'Настройки');?></li>
		<li><?php echo anchor('admin/logout', 'Выход');?></li>
	</ul>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/bootstrap/css/bootstrap.min.css">
	
	<script src="<?php echo base_url(); ?>js/jquery-1.10.2.min.js"></script>
	<script src="<?php echo base_url(); ?>js/bootstrap/js/bootstrap.min.js"></script>

	<?php 
	if(isset($head) && is_array($head)) {
		foreach ($head as $headObject) {
			echo $headObject; 
		}   
	}
	?>

	<script type='text/javascript'>
		<?php if (isset($js)){ echo $js; }?>          
	</script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php if(is_user_logged_in()): ?>
	<div class="navbar navbar-inverse" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo site_url(); ?>"><?php echo get_siteinfo('sitename'); ?></a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><?php echo anchor('admin', 'Главная');?></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Записи <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo anchor('admin/posts', 'Все записи');?></li>
							<li><?php echo anchor('admin/create', 'Добавить запись');?></li>
						</ul>
		            </li>
		            <li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Категории <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo anchor('admin/category', 'Все категории');?></li>
							<li><?php echo anchor('admin/term_create', 'Создать категорию');?></li>
						</ul>
		            </li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Пользователи <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo anchor('admin/users', 'Все пользователи');?></li>
							<li><?php echo anchor('admin/register', 'Добавить пользователя');?></li>
						</ul>
		            </li>
					<li><?php echo anchor('admin/settings', 'Настройки');?></li>
					<li><?php echo anchor('admin/logout', 'Выход');?></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
<?php endif; ?>
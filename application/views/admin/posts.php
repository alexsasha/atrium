<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?></title>
</head>
<?php foreach ($posts as $post): ?>
	
    <div class="item"><?php echo $post->post_title; ?></div>

<?php endforeach ?>

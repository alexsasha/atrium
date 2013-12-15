<?php get_header(); ?>

<?php 
$posts = get_posts(
	array(
		'offset' => $offset
	)
);
if($posts):?>
<?php $i = 1; foreach ($posts as $post): ?>

	<div>
		<a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
		<span>создано: <time><?php echo get_date($post->ID); ?></time></span>
	</div>

<?php $i++; endforeach; ?>
<?php echo $pagi; ?>
<?php endif; ?>

<?php get_footer(); ?>
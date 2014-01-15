<?php get_header(); ?>

<?php if($posts):?>
<?php $i = 1; foreach ($posts as $post): ?>

	<div>
		<a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
		<span>создано: <time><?php echo get_date($post->ID); ?></time></span>
	</div>

<?php $i++; endforeach; ?>
<?php if(isset($pagi)) echo $pagi; ?>
<?php else: ?>
	не найдено
<?php endif; ?>

<?php get_footer(); ?>
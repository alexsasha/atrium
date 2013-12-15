<?php echo anchor('admin/posts', 'Опубликованные') . ' | ' .anchor('admin/posts_trash', 'Корзина'); ?>
<?php if($posts): ?>

	<?php foreach ($posts as $post): ?>

	    <div class="item">
	    	<a href="<?php echo site_url('admin/update/' . $post->ID);?>"><?php echo $post->post_title; ?></a>
	    	<?php if(isset($is_trash)): ?>
	    	<a href="<?php echo site_url('admin/untrash/' . $post->ID);?>">восстановить</a>
	    	<a href="<?php echo site_url('admin/delete_post/' . $post->ID);?>">удалить навсегда</a>
	    	<?php else: ?>
	    	<a href="<?php echo site_url('admin/delete/' . $post->ID);?>">удалить</a>
	    	<?php endif; ?>
	    </div>

	<?php endforeach ?>
	<?php echo $pagi; ?>
<?php else: ?>
	<h3>Записей не найдено.</h3>
<?php endif; ?>
<?php get_header(); ?>
    
    <!-- Carousel -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active">
                <img src="<?php echo template_url(); ?>/images/s1.jpg">
                <div class="container">
                    <div class="carousel-caption">
                    <h1>Использует CodeIgniter</h1>
                    <p>В основе Compact CMS лежит легкий, но мощный и легко расширяемый фреймворк</p>
                    <p><a class="btn btn-lg btn-primary" href="http://ellislab.com/codeigniter" role="button">Перейти на сайт CodeIgniter</a></p>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="<?php echo template_url(); ?>/images/s2.jpg">
                <div class="container">
                    <div class="carousel-caption carousel-caption-2">
                        <h1>MVC</h1>
                        <p>Используестя Model-View-Controller подход к архитектуре</p>
                    </div>
                </div>
            </div>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->



    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

        <!-- Three columns of text below the carousel -->
        <div class="row">
            <div class="col-lg-4">
                <img src="<?php echo template_url(); ?>/images/c1.png" alt="PHP">
                <h2>PHP</h2>
                <p>Работает практически на любом хостинговом плане, который имеет поддержку PHP версии 5.3 и выше.</p>
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-4">
                <img src="<?php echo template_url(); ?>/images/c3.png" alt="Bootstrap">
                <h2>Bootstrap</h2>
                <p>Построен на Twitter Bootstrap, что гарантирует правильное отображение во всех современных браузерах и на всех платформах, включая мобильные.</p>
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-4">
                <img src="<?php echo template_url(); ?>/images/c2.png" alt="MySQL">
                <h2>MySQL</h2>
                <p>Работает на наиболее распространенной в Интернете СУБД.</p>
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->

        <!-- START THE FEATURETTES -->

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7">
                <h2 class="featurette-heading">CMS абсалютно бесплатна.</h2>
                <p class="lead">Система распространяется без каких-либо ограничений "как есть".</p>
            </div>
            <div class="col-md-5">
                <img class="featurette-image img-responsive img-circle" src="<?php echo template_url(); ?>/images/free.jpg" alt="Free">
            </div>
        </div>

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-5">
          <img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="Generic placeholder image">
        </div>
        <div class="col-md-7">
          <h2 class="featurette-heading">Oh yeah, it's that good. <span class="text-muted">See for yourself.</span></h2>
          <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>
      </div>

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">And lastly, this one. <span class="text-muted">Checkmate.</span></h2>
          <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>
        <div class="col-md-5">
          <img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="Generic placeholder image">
        </div>
      </div>

      <hr class="featurette-divider">

      <!-- /END THE FEATURETTES -->


      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Наверх</a></p>
        <p>&copy; 2014 CompactCMS &middot; <a href="#">О нас</a></p>
      </footer>

    </div><!-- /.container -->


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
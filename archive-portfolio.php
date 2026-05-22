<?php
	
	/**
	 * Template Name: Архив портфолио
	 * Template Post Type: page
	 */
	
	include 'header.php';
	
?>


<!-- Home section -->
<div id="sp-home" class="scroll-points"></div>
<section class="site-wrap" style="height: 400px; z-index: auto;">
	<div class="jobs-home-section" style="min-height: 400px;"></div>

	<?php get_template_part('template-parts/header-section/header-section'); ?>

	<div class="container">
		<div class="row align-items-center home-section-height min-home-section-height">
			<div class="col-xl-10 col-xxl-9">
				<h1 class="home-title" style="color: #fff;">Наши работы</h1>
			</div>
		</div>
	</div>
</section>
<!-- /Home section -->



<!-- Portfolio -->
<section class="archive-portfolio-section-2 pt-4 bg-white" style="padding-bottom: 145px;">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs">
					<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">
						<a href="<?php echo home_url('/'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/breadcrumbs-icon.svg"></a>
						/ Наши работы
					</nav>
				</div>

			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="row">
					<div class="col text-center">
						<h2>Наши работы</h2>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/section-title-dec.svg" class="mb-5">
					</div>
				</div>
				<div class="row">
					<div class="col text-center mb-4 mb-md-5">
						<div class="nav-scroller mb-0" style="text-transform: uppercase; font-family: 'HelveticaNeueCyr-Light'; font-weight: bold;">
							<ul class="nav justify-content-md-center d-flex m-auto">
								<li class="nav-item">
									<a class="nav-link active" href="<?php echo get_post_type_archive_link('portfolio'); ?>">Все</a>
								</li>
								<?php
									$args = [
										'taxonomy' => [ 'portfolio-cat' ],
										'orderby'  		=> 'slug',
										'order'    		=> 'ASC',
									];
									
									$terms = get_terms( $args );
									
									foreach( $terms as $term ) { ?>
								<li class="nav-item d-none d-xl-inline">
									<span class="nav-link px-0">
										<svg style="margin-bottom: 6px;" width="6" height="6" viewBox="0 0 6 6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="svg-icon">
											<rect width="6" height="6" rx="2" />
										</svg>
									</span>
								</li>
								<li class="nav-item"><a class="nav-link" href="<?php echo get_term_link($term->term_id); ?>"><?php echo $term->name; ?></a>
								</li>
								<?php }
								?>
							</ul>
						</div>
						<div class="d-md-none text-center mb-4">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/left-right-finger.svg" style="opacity: 1; max-width: 25px;">
						</div>
					</div>
				</div>
				<div class="row">
					<?php	
						$args = [
							'post_type'	=> 'portfolio',
							'numberposts' 	 => 999,
							'posts_per_page' => 999
						];
						
						$query = new WP_Query( $args );
						$count = 1;
						while( $query->have_posts() ) : $query->the_post(); ?>
					<div class="col-md-6">
						<div id="carouselExampleIndicators<?php echo $post->ID; ?>" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="999999999">
							<div class="carousel-indicators" style="bottom: 5%;">
								<?php
											$count2 = 0;
											for ( $i=1; $i<=9; $i++ ) {
												if ( get_post_meta($post->ID, '_img-'.$i ) ) { ?>
								<button type="button" data-bs-target="#carouselExampleIndicators<?php echo $post->ID; ?>" data-bs-slide-to="<?php echo $i-1; ?>" <?php if ( $i == 1 ) echo ' class="active"'; ?> aria-current="true" aria-label="Slide <?php echo $i; ?>"></button>
								<?php $count2 = $count2 + 1; }
											}
										?>
							</div>
							<div class="carousel-inner rounded">
								<?php
											$count2 = 0;
											for ( $i=1; $i<=9; $i++ ) {
												if ( get_post_meta($post->ID, '_img-'.$i ) ) { ?>
								<div class="carousel-item <?php if ( $i == 1 ) echo ' active'; ?>" data-bs-interval="999999999">
									<a onClick="galleryOn('gallery-<?php echo $post->ID; ?>','img-<?php echo $post->ID; ?>-<?php echo $count2; ?>');">
										<div class="single-product-img approximation">
											<img src="<?php echo get_post_meta($post->ID, '_img-'.$i )[0]; ?>" class="shadow rounded" alt="..." loading="lazy">
											<div class="magnifier"></div>
										</div>
									</a>
								</div>
								<?php $count2 = $count2 + 1; }
											}
										?>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators<?php echo $post->ID; ?>" data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators<?php echo $post->ID; ?>" data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>
					</div>

					<?php $count = $count + 1; endwhile;

						wp_reset_postdata();
						
					?>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container -->
</section>
<!-- /Portfolio -->

<!-- Gallery wrapper-->
<div id="galleryWrapper" style="background: rgba(0,0,0,0.85); display: none; position: fixed; top: 0; bottom: 0; left: 0; right: 0; z-index: 9999;">


	<?php
		// параметры по умолчанию
		$posts = get_posts( array(
			'numberposts' => 999,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'post_type'   => 'portfolio'
		) );
		
		foreach( $posts as $post ) { setup_postdata($post); ?>

	<div id="gallery-<?php echo $post->ID; ?>" class="carousel slide" data-bs-ride="carousel" style="display: none; position: fixed; top: 0; height: 100%; width: 100%;">
		<div class="carousel-indicators">
			<?php
						
						/*
						$images = get_post_gallery_images();
						$count2 = 0;
						foreach ( $images as $image ) {
							
							
							if ( $count2 == 0 ) { ?>

			<button id="ind-<?php echo $post->ID; ?>-<?php echo $count2; ?>" type="button" data-bs-target="#gallery-<?php echo $post->ID; ?>" data-bs-slide-to="<?php echo $count2; ?>" aria-label="Slide 3"></button>

			<?php $count2 = $count2 + 1; } else { ?>

			<button id="ind-<?php echo $post->ID; ?>-<?php echo $count2; ?>" type="button" data-bs-target="#gallery-<?php echo $post->ID; ?>" data-bs-slide-to="<?php echo $count2; ?>" aria-label="Slide 3"></button>

			<?php $count2 = $count2 + 1; }
						}*/
						
						$count3 = 0;
						for ( $i=1; $i<=9; $i++ ) {
							if ( get_post_meta($post->ID, '_img-'.$i ) ) {
								if ( $count3 == 0 ) { ?>

			<button id="ind-<?php echo $post->ID; ?>-<?php echo $count3; ?>" type="button" data-bs-target="#gallery-<?php echo $post->ID; ?>" data-bs-slide-to="<?php echo $count3; ?>" aria-label="Slide 3"></button>

			<?php $count3 = $count3 + 1; } else { ?>

			<button id="ind-<?php echo $post->ID; ?>-<?php echo $count3; ?>" type="button" data-bs-target="#gallery-<?php echo $post->ID; ?>" data-bs-slide-to="<?php echo $count3; ?>" aria-label="Slide 3"></button>

			<?php $count3 = $count3 + 1; }
							}
						}
					?>
		</div>
		<div class="carousel-inner h-100">
			<?php
						
						/*
						$images = get_post_gallery_images();
						$count2 = 0;
						foreach ( $images as $image ) { ?>
			<div id="img-<?php echo $post->ID; ?>-<?php echo $count2; ?>" class="carousel-item h-100">
				<div class="row align-items-center h-100">
					<div class="col text-center">
						<img src="<?php echo $image; ?>" class="img-fluid" style="max-width: 75vw; max-height: 75vh;" alt="...">
					</div>
				</div>
			</div>

			<?php  $count2 = $count2 + 1; } */
						
						
						$count4 = 0;
						for ( $i=1; $i<=9; $i++ ) {
							if ( get_post_meta($post->ID, '_img-'.$i ) ) { ?>
			<div id="img-<?php echo $post->ID; ?>-<?php echo $count4; ?>" class="carousel-item h-100 <?php // if ( $i == 1 ) echo ' active'; ?>" data-bs-interval="999999999">
				<div class="row align-items-center h-100">
					<div class="col text-center">
						<img src="<?php echo get_post_meta($post->ID, '_img-'.$i )[0]; ?>" class="img-fluid" style="max-width: 90vw; max-height: 90vh;" alt="...">
					</div>
				</div>
			</div>
			<?php $count4 = $count4 + 1; }
						}
				
					?>
		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#gallery-<?php echo $post->ID; ?>" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#gallery-<?php echo $post->ID; ?>" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>
	<?php } wp_reset_postdata();
	?>

	<!-- Кнопка закрытия галереи -->
	<button type="button" onClick="closeGallery();" class="btn-close btn-close-white" style="position: fixed; top: 25px; right: 25px; z-index: 99999;" aria-label="Close"></button>
</div>


<script>
	/* Функция открытия галереи */
	function galleryOn(gal, img) {
		var gallery = gal; // Получаем ID галереи
		var image = img; // Получаем ID картинки
		// Открываем обертку галереи
		document.getElementById('galleryWrapper').style.display = 'block';
		
		// Проверяем какие данные передаются для открытия галереи и картинки
		//alert(gallery+' '+image); 
		
		
		<?php // Открываем галерею
			$posts = get_posts( array(
				'numberposts' => 999,
				'orderby'     => 'date',
				'order'       => 'DESC',
				'post_type'   => 'portfolio'
			) );
			
			foreach( $posts as $post ) { setup_postdata($post);
				
				echo 'if ( gallery == "gallery-'.$post->ID.'" ) { document.getElementById("gallery-'.$post->ID.'").style.display = "block"; }';

			} wp_reset_postdata();
		?>
		
		
		<?php // Открываем изображения
			$posts = get_posts( array(
				'numberposts' => 999,
				'orderby'     => 'date',
				'order'       => 'DESC',
				'post_type'   => 'portfolio'
			) );
			
			foreach( $posts as $post ) {
				setup_postdata( $post );
				$count5 = 0;
				for ( $i=1; $i<=9; $i++ ) {
					echo 'if ( image == "img-'.$post->ID.'-'.$count5.'" ) { document.getElementById("img-'.$post->ID.'-'.$count5.'").classList.add("active"); document.getElementById("ind-'.$post->ID.'-'.$count5.'").classList.add("active"); } ';
					$count5 = $count5 + 1;
				}
			} wp_reset_postdata();
		?>
	}
	

	// Кнопка закрытия галереи
	function closeGallery() {
		// Закрываем обертку галереи
		document.getElementById('galleryWrapper').style.display = 'none';
		
		<?php // Открываем галерею
			$posts = get_posts( array(
				'numberposts' => 999,
				'orderby'     => 'date',
				'order'       => 'DESC',
				'post_type'   => 'portfolio'
			) );
			
			foreach( $posts as $post ) { setup_postdata($post);
				
				echo 'document.getElementById("gallery-'.$post->ID.'").style.display = "none";';

			} wp_reset_postdata();
		?>
		
		<?php // Закрываем изображения
		$posts = get_posts( array(
			'numberposts' => 999,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'post_type'   => 'portfolio'
		) );
		
		/*
		foreach( $posts as $post ) { setup_postdata($post);
			$images = get_post_gallery_images();
			$count2 = 0;
			foreach ( $images as $image ) {

				echo 'document.getElementById("img-'.$post->ID.'-'.$count2.'").classList.remove("active"); document.getElementById("ind-'.$post->ID.'-'.$count2.'").classList.remove("active");';
				
				$count2 = $count2 + 1;
			}
		} wp_reset_postdata(); */
		
		
		foreach( $posts as $post ) {
			setup_postdata( $post );
			$count6 = 0;
			for ( $i=1; $i<=9; $i++ ) {
				echo 'document.getElementById("img-'.$post->ID.'-'.$count6.'").classList.remove("active"); document.getElementById("ind-'.$post->ID.'-'.$count6.'").classList.remove("active");';
				
				$count6 = $count6 + 1;
			}
		} wp_reset_postdata(); ?>
		
	}
</script>



<?php include 'footer.php'; ?>
<?php
	
	/**
	 * Template Name: Контакты
	 * Template Post Type: page
	 */
	
	include 'header.php';
	
?>


<div class="site-wrap" style="height: 400px; z-index: auto;">
	<!-- Header -->
	<div class="site-navbar-wrap">
		<div class="jobs-home-section" style="min-height: 400px;"></div>
		<!-- <div class="overlay"></div> -->
		<div id="sp-home" class="scroll-points"></div>

		<?php get_template_part('template-parts/header/header'); ?>

		<div class="container">
			<div class="row align-items-center home-section-height min-home-section-height">
				<div class="col-xl-10 col-xxl-9">
					<h1 class="home-title text-light">Контакты</h1>
				</div>
			</div>
		</div>
	</div>
</div>


<section class="archive-products-section site-section bg-white" style="padding-bottom: 60px; padding-top: 0; position: relative;">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs pt-4">
					<nav class="woocommerce-breadcrumb" itemprop="breadcrumb"><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/img/ico/breadcrumbs-icon.svg"></a> / Контакты</nav>
				</div>
			</div>
		</div>
		<div class="container" style="padding: 0;">
			<div class="row">
				<div class="col text-dark ">
					<h2 class="text-md-center mb-3">Контакты</h2>
					<img src="<?php echo get_template_directory_uri(); ?>/img/ico/section-title-dec.svg" class="mb-5 mx-md-auto d-block">
					<div class="container" style="margin-top: 60px;">
						<div class="row gap-3" style="font-family: 'Gilroy-Regular';">

							<!-- Адрес и время работы -->
							<?php if (mytheme_get_address_full() || mytheme_get_job_time()) : ?>
							<div class="col-md-3 contact-info" style="flex: 0 0 35%;">
								<?php if (mytheme_get_address_full()) : ?>
								<div style="display: flex; padding-bottom: 15px;" class="align-items-center">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/location-ico.svg">
									</div>
									<div class="nav-li-float-right">
										<span style="text-wrap: wrap;"><?php echo wp_kses(mytheme_get_address_full(), ['br' => []]); ?></span>
									</div>
									<div style="clear: both;"></div>
								</div>
								<?php endif; ?>
								<?php if (mytheme_get_job_time()) : ?>
								<div style="display: flex;" class="align-items-center">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/clock-ico.svg">
									</div>
									<div class="nav-li-float-right">
										<span><?php echo wp_kses(mytheme_get_job_time(), ['br' => []]); ?></span>
									</div>
									<div style="clear: both;"></div>
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>

							<!-- Дополнительные телефоны -->
							<?php
							$phones_extra = mytheme_get_phones_extra();
							if (mytheme_get_phone('additional') || !empty($phones_extra)) :
							?>
							<div class="col-md-3 contact-info">
								<?php if (mytheme_get_phone('additional') && mytheme_get_phone_link('additional')) : ?>
								<a href="tel:<?php echo esc_attr(mytheme_get_phone_link('additional')); ?>" style="display: flex; padding-bottom: 15px;" class="align-items-center text-dark">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/telephone-ico-blue.svg">
									</div>
									<div class="nav-li-float-right">
										<span><?php echo esc_html(mytheme_get_phone('additional')); ?></span>
									</div>
									<div style="clear: both;"></div>
								</a>
								<?php endif; ?>
								<?php foreach ($phones_extra as $phone) : ?>
								<a href="tel:<?php echo esc_attr($phone['link']); ?>" style="display: flex;" class="align-items-center text-dark">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/telephone-ico-blue.svg">
									</div>
									<div class="nav-li-float-right">
										<span><?php echo esc_html($phone['display']); ?></span>
									</div>
									<div style="clear: both;"></div>
								</a>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>

							<!-- Основной телефон и обратный звонок -->
							<?php if (mytheme_get_phone('main') && mytheme_get_phone_link('main')) : ?>
							<div class="col-md-3 contact-info">
								<a href="tel:<?php echo esc_attr(mytheme_get_phone_link('main')); ?>" style="display: flex; padding-bottom: 15px;" class="align-items-center text-dark text-decoration-none">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/mobile-phone-ico.svg">
									</div>
									<div class="nav-li-float-right">
										<span><?php echo esc_html(mytheme_get_phone('main')); ?></span>
									</div>
									<div style="clear: both;"></div>
								</a>
								<button data-bs-toggle="modal" data-bs-target="#callbackModal" style="display: flex;" class="btn p-0 align-items-center">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/callback-ico.svg">
									</div>
									<div class="nav-li-float-right">
										<span>Обратный звонок</span>
									</div>
									<div style="clear: both;"></div>
								</button>
							</div>
							<?php endif; ?>

							<!-- Email -->
							<?php if (mytheme_get_email()) : ?>
							<div class="col-md-3 contact-info">
								<a href="mailto:<?php echo esc_attr(mytheme_get_email()); ?>" class="text-dark text-decoration-none" style="display: flex; padding-bottom: 15px;" class="align-items-center">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/email-ico.svg">
									</div>
									<div class="nav-li-float-right">
										<span><?php echo esc_html(mytheme_get_email()); ?></span>
									</div>
									<div style="clear: both;"></div>
								</a>
								<?php foreach (mytheme_get_emails_extra() as $email_item) : ?>
								<a href="mailto:<?php echo esc_attr($email_item['email']); ?>" class="text-dark text-decoration-none" style="display: flex; padding-bottom: 15px;" class="align-items-center">
									<div class="nav-li-float-left">
										<img src="<?php echo get_template_directory_uri(); ?>/img/ico/email-ico.svg">
									</div>
									<div class="nav-li-float-right">
										<span><?php echo esc_html($email_item['email']); ?></span>
									</div>
									<div style="clear: both;"></div>
								</a>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>

						</div>
					</div>

					<!-- Соцсети -->
					<div class="d-flex justify-content-md-center" style="margin-top: 60px;">
						<ul class="nav">
							<?php if (mytheme_get_whatsapp()) : ?>
							<li class="nav-item">
								<a class="nav-link ico-button px-2" href="<?php echo esc_url(mytheme_get_whatsapp()); ?>" target="_blank">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/whatsapp-ico.svg">
								</a>
							</li>
							<?php endif; ?>
							<?php if (mytheme_get_telegram()) : ?>
							<li class="nav-item">
								<a class="nav-link ico-button px-2" href="<?php echo esc_url(mytheme_get_telegram()); ?>" target="_blank">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/telegram-ico.svg">
								</a>
							</li>
							<?php endif; ?>
							<?php if (mytheme_get_max()) : ?>
							<li class="nav-item">
								<a class="nav-link ico-button px-2" href="<?php echo esc_url(mytheme_get_max()); ?>" target="_blank">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/max.svg">
								</a>
							</li>
							<?php endif; ?>
							<?php if (mytheme_get_instagram()) : ?>
							<li class="nav-item">
								<a class="nav-link ico-button px-2" href="<?php echo esc_url(mytheme_get_instagram()); ?>" target="_blank">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/instagram-ico.svg">
								</a>
							</li>
							<?php endif; ?>
							<?php if (mytheme_get_vk()) : ?>
							<li class="nav-item">
								<a class="nav-link ico-button px-2" href="<?php echo esc_url(mytheme_get_vk()); ?>" target="_blank">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/vk-ico.svg">
								</a>
							</li>
							<?php endif; ?>
						</ul>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<!-- Yandex Map -->
<section class="archive-products-section site-section bg-white" style="padding-bottom: 60px; z-index: 10; position: relative;">
	<div class="container">
		<div class="row">
			<section id="map" style="height: 650px; border-radius: 10px;"></section>
		</div>
	</div>
</section>

<!-- API Yandex map -->
<script src="https://api-maps.yandex.ru/2.1/?apikey=67fa0c4f-ad3f-4f9e-bd3f-729ca47910bf&lang=ru_RU" type="text/javascript"></script>

<script type="text/javascript">
	// Функция ymaps.ready() будет вызвана, когда
	// загрузятся все компоненты API, а также когда будет готово DOM-дерево.
	ymaps.ready(init);
	function init(){
		// Создание карты.
		var myMap = new ymaps.Map("map", {
			// Координаты центра карты.
			// Порядок по умолчанию: «широта, долгота».
			// Чтобы не определять координаты центра карты вручную,
			// воспользуйтесь инструментом Определение координат.
			center: [54.624353, 39.734443],
			// Уровень масштабирования. Допустимые значения:
			// от 0 (весь мир) до 19.
			zoom: 17
		});
		
		var glyphIcon1 = new ymaps.Placemark([54.624363, 39.734543], {}, {
			iconLayout: 'default#image',
			iconImageHref: '<?php echo get_template_directory_uri(); ?>/img/ico/placemark0.png',
			iconImageSize: [270, 270],
			iconImageOffset: [-150, -230]
		});
		
		// Размещение геообъекта на карте.
		myMap.geoObjects.add(glyphIcon1);
	}
</script>



<?php include 'footer-1.php'; ?>
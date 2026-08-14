<!-- CONTACTS SECTION 4 -->
<section class="contacts-section-4">

	<!-- Desktop version -->
	<div class="container py-5 d-none d-xl-block">
		<div class="row align-items-center">
			<div class="col-xl-2">
				<a href="/">
					<img id="navbar-brand-img" src="<?php echo get_template_directory_uri(); ?>/img/ico/logo-light.svg" class="img-fluid">
				</a>
			</div>
			<div class="col-xl-8">
				<?php wp_nav_menu([
					'theme_location' => 'contacts-desktop-menu',
					'container'      => false,
					'menu_class'     => '',
					'fallback_cb'    => '__return_false',
					'items_wrap'     => '<ul id="%1$s" class="nav justify-content-center footer-desktop-menu %2$s">%3$s</ul>',
					'depth'          => 2,
					'walker'         => new bootstrap_5_wp_nav_menu_walker(),
				]); ?>
			</div>
			<?php if (mytheme_get_phone('main') && mytheme_get_phone_link('main')): ?>
			<div class="col-xl-2 text-end">
				<a href="tel:<?php echo esc_attr(mytheme_get_phone_link('main')); ?>" class="contacts-phone d-flex align-items-center justify-content-end gap-2">
					<img src="<?php echo get_template_directory_uri(); ?>/img/ico/mobile-phone-ico.svg">
					<?php echo esc_html(mytheme_get_phone('main')); ?>
				</a>
			</div>
			<?php endif; ?>
		</div>

		<div class="row">
			<div class="col py-4">
				<ul class="nav justify-content-center align-items-center">
					<?php if (mytheme_get_address()): ?>
					<li class="nav-item me-1 me-lg-2">
						<a class="nav-link" href="#">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/location-ico.svg">
								<span><?php echo wp_kses(mytheme_get_address(), ['br' => []]); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_job_time()): ?>
					<li class="nav-item me-1 me-lg-2">
						<a class="nav-link" href="#">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/clock-ico.svg">
								<span><?php echo wp_kses(mytheme_get_job_time(), ['br' => []]); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_email()): ?>
					<li class="nav-item me-1 me-lg-2">
						<a href="mailto:<?php echo esc_attr(mytheme_get_email()); ?>" class="nav-link">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/email-ico.svg">
								<span><?php echo esc_html(mytheme_get_email()); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>
					<li class="nav-item me-1 me-lg-2">
						<a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#callbackModal">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/callback-ico.svg">
								<span>Обратный звонок</span>
							</div>
						</a>
					</li>
					<li class="nav-item me-1 me-lg-2">
						<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#calculatePriceWithDownloadModal">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/calculator-ico.svg">
								<span>Рассчитать стоимость</span>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col">
				<ul class="nav justify-content-center footer-socials">
					<?php if (mytheme_get_whatsapp()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_whatsapp()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/whatsapp-ico.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_telegram()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_telegram()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/telegram-ico.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_max()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_max()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/max.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_instagram()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_instagram()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/instagram-ico.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_vk()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_vk()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/vk-ico.svg">
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
	<!-- /Desktop version -->

	<!-- Mobile version -->
	<div class="container d-xl-none">
		<div class="row">
			<div class="col py-5">
				<a href="#">
					<img id="navbar-brand-img" src="<?php echo get_template_directory_uri(); ?>/img/ico/logo-light.svg" class="img-fluid">
				</a>

				<ul class="ps-0 pt-3 pb-2">
					<?php if (mytheme_get_address()): ?>
					<li class="nav-item">
						<a href="#" class="nav-link ps-0 pb-2">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/location-ico.svg">
								<span><?php echo esc_html(mytheme_get_address()); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_job_time()): ?>
					<li class="nav-item">
						<a href="#" class="nav-link ps-0 py-2">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/clock-ico.svg">
								<span><?php echo esc_html(mytheme_get_job_time()); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_email()): ?>
					<li class="nav-item">
						<a href="mailto:<?php echo esc_attr(mytheme_get_email()); ?>" class="nav-link ps-0 py-2">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/email-ico.svg">
								<span><?php echo esc_html(mytheme_get_email()); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>
					<li class="nav-item">
						<a href="#" class="nav-link ps-0 pt-2" data-bs-toggle="modal" data-bs-target="#callbackModal">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo get_template_directory_uri(); ?>/img/ico/callback-ico.svg">
								<span>Обратный звонок</span>
							</div>
						</a>
					</li>
				</ul>

				<?php if (mytheme_get_phone('additional') && mytheme_get_phone_link('additional')): ?>
				<a href="tel:<?php echo esc_attr(mytheme_get_phone_link('additional')); ?>" class="contacts-phone d-flex align-items-center gap-2">
					<img src="<?php echo get_template_directory_uri(); ?>/img/ico/mobile-phone-ico.svg">
					<?php echo esc_html(mytheme_get_phone('additional')); ?>
				</a>
				<div style="height: 15px;"></div>
				<?php endif; ?>

				<?php if (mytheme_get_phone('main') && mytheme_get_phone_link('main')): ?>
				<a href="tel:<?php echo esc_attr(mytheme_get_phone_link('main')); ?>" class="contacts-phone d-flex align-items-center gap-2">
					<img src="<?php echo get_template_directory_uri(); ?>/img/ico/mobile-phone-ico.svg">
					<?php echo esc_html(mytheme_get_phone('main')); ?>
				</a>
				<?php endif; ?>

				<ul class="nav pt-4 pb-3 footer-socials">
					<?php if (mytheme_get_whatsapp()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_whatsapp()); ?>">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/whatsapp-ico.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_telegram()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_telegram()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/telegram-ico.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_max()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_max()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/max.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_instagram()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_instagram()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/instagram-ico.svg">
						</a>
					</li>
					<?php endif; ?>
					<?php if (mytheme_get_vk()): ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url(mytheme_get_vk()); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/vk-ico.svg">
						</a>
					</li>
					<?php endif; ?>
				</ul>

				<div class="row">
					<div class="col-lg-8 pt-4 pt-lg-2">
						<div class="row d-xl-none justify-content-center">
							<div class="col-6 left-col-footer-menu">
								<?php wp_nav_menu([
									'theme_location' => 'footer-left',
									'container'      => false,
									'menu_class'     => '',
									'fallback_cb'    => '__return_false',
									'items_wrap'     => '<ul id="menu-main-menu-5" class="navbar-nav ms-auto mb-lg-0 %2$s">%3$s</ul>',
									'depth'          => 2,
									'walker'         => new bootstrap_5_wp_nav_menu_walker(),
								]); ?>
							</div>
							<div class="col-6 right-col-footer-menu">
								<?php wp_nav_menu([
									'theme_location' => 'footer-right',
									'container'      => false,
									'menu_class'     => '',
									'fallback_cb'    => '__return_false',
									'items_wrap'     => '<ul id="menu-main-menu-6" class="navbar-nav ms-auto mb-lg-0 %2$s">%3$s</ul>',
									'depth'          => 2,
									'walker'         => new bootstrap_5_wp_nav_menu_walker(),
								]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Mobile version -->

	<!-- Footer -->
	<footer style="padding: 29px 0;">
		<div class="container">
			<div class="row">
				<div class="col text-start text-md-center">
					<div id="im-in-footer">Создание, продвижение и поддержка:
						<a href="https://site100.ru" class="text-light">site100.ru</a>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- /Footer -->

</section>
<!-- /CONTACTS SECTION 4 -->
<?php
$main_phone      = mytheme_get_phone('main');
$main_phone_link = mytheme_get_phone_link('main');
$add_phone       = mytheme_get_phone('additional');
$add_phone_link  = mytheme_get_phone_link('additional');
$address         = mytheme_get_address();
$job_time        = mytheme_get_job_time();
$telegram        = mytheme_get_telegram();
$whatsapp        = mytheme_get_whatsapp();
$max_link        = mytheme_get_max();
?>

<header class="d-none d-lg-block">
	<!-- Header nav top -->
	<nav class="header-nav-top navbar navbar-expand-lg navbar-light d-none d-lg-block py-0">
		<div class="container">
			<div class="collapse navbar-collapse">
				<ul class="navbar-nav ms-auto align-items-center">
					<?php if ($address) : ?>
					<li class="nav-item me-1 me-xxl-3">
						<a class="nav-link">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/location-ico.svg" alt="" class="mobile-ico">
								<span class="address-footer"><?php echo mytheme_kses_br($address); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>

					<?php if ($job_time) : ?>
					<li class="nav-item me-1 me-xxl-3">
						<a class="nav-link">
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/ico/clock-ico.svg" alt="" class="mobile-ico">
								<span class="time-footer"><?php echo mytheme_kses_br($job_time); ?></span>
							</div>
						</a>
					</li>
					<?php endif; ?>

					<li class="nav-item me-1 me-xxl-3">
						<a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#callbackModal">
							<div style="display: flex;">
								<div class="nav-li-float-left">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/callback-ico.svg">
								</div>
								<div class="nav-li-float-right max-width-1400 nav-wrap">
									Обратный звонок
								</div>
								<div style="clear: both;"></div>
							</div>
						</a>
					</li>
					<li class="nav-item me-1 me-xxl-4">
						<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#calculatePriceWithDownloadModal">
							<div style="display: flex;">
								<div class="nav-li-float-left">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/calculator-ico.svg">
								</div>
								<div class="nav-li-float-right max-width-1400 nav-wrap">
									Рассчитать стоимость
								</div>
								<div style="clear: both;"></div>
							</div>
						</a>
					</li>

					<?php if ($add_phone && $add_phone_link) : ?>
					<li class="nav-item me-1 me-xxl-4">
						<a class="top-menu-tel nav-link" href="tel:<?php echo esc_attr($add_phone_link); ?>">
							<div style="display: flex;">
								<div class="nav-li-float-left">
								</div>
								<div class="nav-li-float-right" style="min-width: 128px;">
									<?php echo esc_html($add_phone); ?>
								</div>
								<div style="clear: both;"></div>
							</div>
						</a>
					</li>
					<?php endif; ?>

					<?php if ($main_phone && $main_phone_link) : ?>
					<li class="nav-item me-1 me-xxl-4">
						<a class="top-menu-tel nav-link" href="tel:<?php echo esc_attr($main_phone_link); ?>">
							<div style="display: flex;">
								<div class="nav-li-float-left">
									<img src="<?php echo get_template_directory_uri(); ?>/img/ico/mobile-phone-ico.svg">
								</div>
								<div class="nav-li-float-right" style="min-width: 128px;">
									<?php echo esc_html($main_phone); ?>
								</div>
								<div style="clear: both;"></div>
							</div>
						</a>
					</li>
					<?php endif; ?>

					<?php if ($telegram) : ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url($telegram); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/telegram-ico.svg">
						</a>
					</li>
					<?php endif; ?>

					<?php if ($whatsapp) : ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url($whatsapp); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/whatsapp-ico.svg">
						</a>
					</li>
					<?php endif; ?>

					<?php if ($max_link) : ?>
					<li class="nav-item">
						<a class="nav-link ico-button" href="<?php echo esc_url($max_link); ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/img/ico/max.svg">
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
	<!-- /Header nav top -->

	<!-- Header nav bottom -->
	<nav class="header-nav-bottom navbar navbar-expand-lg navbar-light py-0">
		<div class="container">
			<a class="navbar-brand" href="/">
				<img src="<?php echo get_template_directory_uri(); ?>/img/ico/logo-light.svg" class="custom-logo" alt="Логотип в шапке" decoding="async" />
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobail-header-collapse" aria-controls="mobail-header-collapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="mobail-header-collapse">
				<?php
                    wp_nav_menu(array(
                        'theme_location' => 'main-menu',
                        'container' => false,
                        'menu_class' => '',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '
                            <ul id="%1$s" class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0 %2$s">%3$s
                                <!-- Mobile menu -->
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link active" href="#" data-bs-toggle="modal" data-bs-target="#callbackModal">Вызов замерщика</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    ' . ($address ? '
                                    <div style="font-size: 12px; font-family: HelveticaNeueCyr-Light; text-transform: none;">
                                        <img src="' . get_template_directory_uri() . '/img/ico/location-ico.svg" style="width: 10px;" class="me-1">
                                        <span>' . esc_html($address) . '</span>
                                    </div>' : '') . '
                                    ' . ($main_phone && $main_phone_link ? '
                                    <a class="nav-link top-menu-tel pt-1 pb-1" href="tel:' . esc_attr($main_phone_link) . '">' . esc_html($main_phone) . '</a>' : '') . '
                                    ' . ($job_time ? '
                                    <div class="mb-2" style="font-size: 12px; font-family: HelveticaNeueCyr-Light; text-transform: none;">
                                        <img src="' . get_template_directory_uri() . '/img/ico/clock-ico.svg" style="width: 10px; position: relative; top: 2px;" class="me-1 mb-2">' . esc_html($job_time) . '
                                    </div>' : '') . '
                                </li>
                                <li class="nav-item d-lg-none pb-4">
                                    ' . ($whatsapp ? '<a class="ico-button pe-2" href="' . esc_url($whatsapp) . '" target="_blank"><img src="' . get_template_directory_uri() . '/img/ico/whatsapp-ico.svg"></a>' : '') . '
                                    ' . ($telegram ? '<a class="ico-button pe-2" href="' . esc_url($telegram) . '" target="_blank"><img src="' . get_template_directory_uri() . '/img/ico/telegram-ico.svg"></a>' : '') . '
                                    ' . ($max_link ? '<a class="ico-button pe-0" href="' . esc_url($max_link) . '" target="_blank"><img src="' . get_template_directory_uri() . '/img/ico/max.svg"></a>' : '') . '
                                </li>
                                <!-- End mobile menu -->
                            </ul>
                        ',
                        'depth' => 2,
                        'walker' => new bootstrap_5_wp_nav_menu_walker()
                    ));
                    ?>
			</div>

		</div>
	</nav>
	<!-- /Header nav bottom -->
</header>

<header id="sliding-header" class="shadow">
	<!-- Header nav bottom -->
	<nav class="header-nav-bottom navbar navbar-expand-lg navbar-light py-1 py-lg-0">
		<div class="container">
			<a class="navbar-brand" href="/">
				<img src="<?php echo get_template_directory_uri(); ?>/img/ico/logo-dark.svg" class="custom-logo" alt="Логотип в шапке" decoding="async" />
			</a>

			<div class="d-lg-none">
				<a class="top-menu-tel pt-1 pb-0" style="font-size: 14px;" href="tel:<?php echo esc_attr($main_phone_link); ?>"><?php echo esc_html($main_phone); ?></a>
				<div style="font-size: 10px; font-family: Gilroy-Light; text-transform: none;">
					<img src="<?php echo get_template_directory_uri(); ?>/img/ico/clock-ico.svg" style="width: 12px; position: relative; top: -1px;" class="me-1">Пн-Сб: с 10.00-19.00<br>Вс: с 10.00-17.00
				</div>
			</div>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sliding-header-collapse" aria-controls="sliding-header-collapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="sliding-header-collapse">
				<?php
                    wp_nav_menu(array(
                        'theme_location' => 'main-menu',
                        'container' => false,
                        'menu_class' => '',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '
                            <ul id="%1$s" class="navbar-nav ms-auto mb-2 mb-lg-0 %2$s">%3$s
                                <!-- Mobile menu -->
                                <li class="nav-item d-lg-none">
                                    <a class="nav-link active" href="#" data-bs-toggle="modal" data-bs-target="#callbackModal">Вызов замерщика</a>
                                </li>
                                <li class="nav-item d-lg-none">
                                    ' . ($address ? '
                                    <div style="font-size: 12px; font-family: Gilroy-Light; text-transform: none;">
                                        <img src="' . get_template_directory_uri() . '/img/ico/location-ico.svg" style="width: 10px; position: relative; top: -2px;" class="me-1">
                                        <span>' . esc_html($address) . '</span>
                                    </div>' : '') . '
                                    ' . ($main_phone && $main_phone_link ? '
                                    <a class="nav-link top-menu-tel pt-1 pb-1" href="tel:' . esc_attr($main_phone_link) . '">' . esc_html($main_phone) . '</a>' : '') . '
                                    ' . ($job_time ? '
                                    <div class="mb-2" style="font-size: 12px; font-family: Gilroy-Light; text-transform: none;">
                                        <img src="' . get_template_directory_uri() . '/img/ico/clock-ico.svg" style="width: 10px; position: relative; top: 3px;" class="me-1 mb-2">' . esc_html($job_time) . '
                                    </div>' : '') . '
                                </li>
                                <li class="nav-item d-lg-none pb-2">
                                    ' . ($whatsapp ? '<a class="ico-button pe-2" href="' . esc_url($whatsapp) . '" target="_blank"><img src="' . get_template_directory_uri() . '/img/ico/whatsapp-ico.svg"></a>' : '') . '
                                    ' . ($telegram ? '<a class="ico-button pe-2" href="' . esc_url($telegram) . '" target="_blank"><img src="' . get_template_directory_uri() . '/img/ico/telegram-ico.svg"></a>' : '') . '
                                    ' . ($max_link ? '<a class="ico-button pe-0" href="' . esc_url($max_link) . '" target="_blank"><img src="' . get_template_directory_uri() . '/img/ico/max.svg"></a>' : '') . '
                                </li>
                                <!-- End mobile menu -->
                            </ul>
                            ',
                        'depth' => 2,
                        'walker' => new bootstrap_5_wp_nav_menu_walker()
                    ));
                    ?>
			</div>

		</div>
	</nav>
	<!-- /Header nav bottom -->
</header><!-- site-navbar -->
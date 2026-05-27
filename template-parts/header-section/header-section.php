<?php
/*
 * Шаблон шапки сайта.
 *
 * Подключается через get_template_part( 'template-parts/header-section/header-section', null, ['variant' => 'white'] ).
 * Если $args['variant'] === 'white' — белый прилипающий хедер (страницы товаров, внутренние страницы).
 * Если $args['variant'] === 'dark' (или не передан) — тёмный хедер на фоне hero-изображения (главная и т.п.).
 *
 * Структура файла:
 *   1. Переменные с контактными данными из настроек темы
 *   2. Аргументы wp_nav_menu (общие для всех трёх навигаций)
 *   3. $mobile_menu   — дополнительные пункты в конце мобильного дропдауна (d-lg-none)
 *   4. $top_bar_items — пункты верхней полоски: адрес, часы, телефоны, соцсети
 *   5. $mobile_bar_info — телефон + время для прилипающего nav на мобайле
 *   6. WHITE HEADER  — один nav с top bar + прилипающий nav (#header-2-bottom)
 *   7. DARK HEADER   — статичный nav (только lg+) + скользящий #sliding-header
 */

/* --- Вариант хедера, переданный через $args --- */
$variant  = $args['variant'] ?? 'dark';
$is_white = $variant === 'white';

/* --- Контактные данные из настроек темы (Customizer) --- */
$main_phone      = mytheme_get_phone('main');
$main_phone_link = mytheme_get_phone_link('main');
$add_phone       = mytheme_get_phone('additional');
$add_phone_link  = mytheme_get_phone_link('additional');
$address         = mytheme_get_address();
$job_time        = mytheme_get_job_time();
$telegram        = mytheme_get_telegram();
$whatsapp        = mytheme_get_whatsapp();
$max_link        = mytheme_get_max();

/* --- Путь к папке с иконками темы --- */
$img = get_template_directory_uri() . '/img/ico/';

/*
 * Базовые аргументы для wp_nav_menu.
 * Используются во всех трёх навигациях.
 * items_wrap дополняется индивидуально в каждом вызове (добавляется $mobile_menu).
 */
$nav_menu_args = [
	'theme_location' => 'main-menu',
	'container'      => false,
	'menu_class'     => '',
	'fallback_cb'    => '__return_false',
	'depth'          => 2,
	'walker'         => new bootstrap_5_wp_nav_menu_walker(),
];


/*
 * ── $mobile_menu ────────────────────────────────────────────────────────────
 * Дополнительные <li> в конце раскрытого мобильного меню (видны только < lg).
 * Вставляются в items_wrap после пунктов из меню через админку.
 * Содержит: кнопку «Вызов замерщика», адрес + телефон + часы, иконки соцсетей.
 * ────────────────────────────────────────────────────────────────────────────
 */
ob_start(); ?>

<li class="nav-item d-lg-none">
	<a class="nav-link active" href="#" data-bs-toggle="modal" data-bs-target="#callbackModal">
		Вызов замерщика
	</a>
</li>

<li class="nav-item d-lg-none">
	<?php if ($address): ?>
	<div class="mobile-bar-time">
		<img src="<?= $img ?>location-ico.svg" class="mobile-menu-ico me-1">
		<?= esc_html($address) ?>
	</div>
	<?php endif; ?>

	<?php if ($main_phone && $main_phone_link): ?>
	<a class="nav-link top-menu-tel pt-1 pb-1" href="tel:<?= esc_attr($main_phone_link) ?>">
		<?= esc_html($main_phone) ?>
	</a>
	<?php endif; ?>

	<?php if ($job_time): ?>
	<div class="mobile-bar-time mb-2">
		<img src="<?= $img ?>clock-ico.svg" class="mobile-menu-ico me-1">
		<?= esc_html($job_time) ?>
	</div>
	<?php endif; ?>
</li>

<li class="nav-item d-lg-none pb-2">
	<?php if ($whatsapp): ?>
	<a class="ico-button pe-2" href="<?= esc_url($whatsapp) ?>" target="_blank">
		<img src="<?= $img ?>whatsapp-ico.svg">
	</a>
	<?php endif; ?>

	<?php if ($telegram): ?>
	<a class="ico-button pe-2" href="<?= esc_url($telegram) ?>" target="_blank">
		<img src="<?= $img ?>telegram-ico.svg">
	</a>
	<?php endif; ?>

	<?php if ($max_link): ?>
	<a class="ico-button pe-0" href="<?= esc_url($max_link) ?>" target="_blank">
		<img src="<?= $img ?>max.svg">
	</a>
	<?php endif; ?>
</li>

<?php $mobile_menu = ob_get_clean();


/*
 * ── $top_bar_items ──────────────────────────────────────────────────────────
 * Пункты верхней полоски (header-nav-top).
 * Видна только на десктопе (d-none d-lg-block на самом <nav>).
 * Содержит: адрес, часы, «Обратный звонок» (модалка), «Рассчитать стоимость»
 * (модалка), дополнительный телефон, основной телефон, Telegram, WhatsApp, MAX.
 * ────────────────────────────────────────────────────────────────────────────
 */
ob_start(); ?>

<?php if ($address): ?>
<li class="nav-item me-1 me-xxl-3">
	<a class="nav-link">
		<div class="d-flex align-items-center gap-2">
			<img src="<?= $img ?>location-ico.svg" alt="" class="mobile-ico">
			<span class="address-footer"><?= mytheme_kses_br($address) ?></span>
		</div>
	</a>
</li>
<?php endif; ?>

<?php if ($job_time): ?>
<li class="nav-item me-1 me-xxl-3">
	<a class="nav-link">
		<div class="d-flex align-items-center gap-2">
			<img src="<?= $img ?>clock-ico.svg" alt="" class="mobile-ico">
			<span class="time-footer"><?= mytheme_kses_br($job_time) ?></span>
		</div>
	</a>
</li>
<?php endif; ?>

<li class="nav-item me-1 me-xxl-3">
	<a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#callbackModal">
		<div class="d-flex align-items-center gap-2">
			<img src="<?= $img ?>callback-ico.svg" class="mobile-ico">
			<span class="nav-wrap">Обратный звонок</span>
		</div>
	</a>
</li>

<li class="nav-item me-1 me-xxl-4">
	<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#calculatePriceWithDownloadModal">
		<div class="d-flex align-items-center gap-2">
			<img src="<?= $img ?>calculator-ico.svg" class="mobile-ico">
			<span class="nav-wrap">Рассчитать стоимость</span>
		</div>
	</a>
</li>

<?php if ($add_phone && $add_phone_link): ?>
<li class="nav-item me-1 me-xxl-4">
	<a class="top-menu-tel nav-link" href="tel:<?= esc_attr($add_phone_link) ?>">
		<div class="d-flex align-items-center gap-2">
			<img src="<?= $img ?>mobile-phone-ico.svg" class="mobile-ico">
			<span><?= esc_html($add_phone) ?></span>
		</div>
	</a>
</li>
<?php endif; ?>

<?php if ($main_phone && $main_phone_link): ?>
<li class="nav-item me-1 me-xxl-4">
	<a class="top-menu-tel nav-link" href="tel:<?= esc_attr($main_phone_link) ?>">
		<div class="d-flex align-items-center gap-2">
			<img src="<?= $img ?>mobile-phone-ico.svg" class="mobile-ico">
			<span><?= esc_html($main_phone) ?></span>
		</div>
	</a>
</li>
<?php endif; ?>

<?php if ($telegram): ?>
<li class="nav-item">
	<a class="nav-link ico-button" href="<?= esc_url($telegram) ?>" target="_blank">
		<img src="<?= $img ?>telegram-ico.svg">
	</a>
</li>
<?php endif; ?>

<?php if ($whatsapp): ?>
<li class="nav-item">
	<a class="nav-link ico-button" href="<?= esc_url($whatsapp) ?>" target="_blank">
		<img src="<?= $img ?>whatsapp-ico.svg">
	</a>
</li>
<?php endif; ?>

<?php if ($max_link): ?>
<li class="nav-item">
	<a class="nav-link ico-button" href="<?= esc_url($max_link) ?>" target="_blank">
		<img src="<?= $img ?>max.svg">
	</a>
</li>
<?php endif; ?>

<?php $top_bar_items = ob_get_clean();


/*
 * ── $mobile_bar_info ────────────────────────────────────────────────────────
 * Компактный блок с телефоном и временем работы для прилипающего nav на мобайле.
 * Располагается между логотипом и бургером (div.d-lg-none внутри navbar).
 * На десктопе (lg+) скрыт через родительский d-lg-none.
 * ────────────────────────────────────────────────────────────────────────────
 */
ob_start(); ?>

<?php if ($main_phone && $main_phone_link): ?>
<a class="top-menu-tel mobile-bar-tel pt-1 pb-0" href="tel:<?= esc_attr($main_phone_link) ?>">
	<?= esc_html($main_phone) ?>
</a>
<?php endif; ?>

<?php if ($job_time): ?>
<div class="mobile-bar-time mobile-bar-time-top">
	<img src="<?= $img ?>clock-ico.svg" class="mobile-bar-ico me-1">
	<?= esc_html($job_time) ?>
</div>
<?php endif; ?>

<?php $mobile_bar_info = ob_get_clean();


/* ============================================================
   WHITE HEADER
   Используется на страницах с белым меню.
   Состоит из двух nav:
     1. header-nav-top — верхняя информационная полоска (lg+)
     2. #header-2-bottom — основная навигация с меню и логотипом.
        На мобайле фиксируется сразу (JS: setMobileFixed).
        На десктопе прилипает после прокрутки топ-бара (JS: checkDesktopPosition).
   ============================================================ */
if ($is_white) : ?>

<header class="header-2">

	<!-- Верхняя информационная полоска: адрес, часы, телефоны, соцсети (только lg+) -->
	<nav class="header-nav-top navbar navbar-expand-lg navbar-light bg-light d-none d-lg-block py-0">
		<div class="container">
			<div class="collapse navbar-collapse">
				<ul class="navbar-nav ms-auto align-items-center">
					<?= $top_bar_items ?>
				</ul>
			</div>
		</div>
	</nav>
	<!-- /Верхняя полоска -->

	<!-- Основная навигация: логотип + меню. Прилипает после скролла (JS: prelipalo). -->
	<nav id="header-2-bottom" class="header-nav-bottom navbar navbar-expand-lg navbar-light bg-white shadow py-1 py-lg-0">
		<div class="container">

			<a class="navbar-brand" href="/">
				<img src="<?= $img ?>logo-dark.svg" class="custom-logo" alt="Логотип в шапке" decoding="async">
			</a>

			<!-- Телефон + время между логотипом и бургером (только мобайл) -->
			<div class="d-lg-none">
				<?= $mobile_bar_info ?>
			</div>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#header-white-nav" aria-controls="header-white-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<!-- Пункты меню из админки + дополнительные мобильные пункты ($mobile_menu) -->
			<div class="collapse navbar-collapse" id="header-white-nav">
				<?php wp_nav_menu(array_merge($nav_menu_args, [
					'items_wrap' => '
						<ul id="%1$s" class="navbar-nav align-items-lg-center ms-auto mb-2 mb-lg-0 %2$s">%3$s' 
							. $mobile_menu . 
						'</ul>',
				])); ?>
			</div>

		</div>
	</nav>
	<!-- /Основная навигация -->

</header>

<?php
/* ============================================================
   DARK HEADER
   Используется на главной и страницах с hero-фоном.
   Состоит из двух отдельных <header>:
     1. <header class="d-none d-lg-block"> — статичный хедер, видим только на lg+.
        Содержит топ-бар и основную навигацию поверх hero-изображения.
     2. <header id="sliding-header"> — скользящий компактный хедер.
        На десктопе скрыт выше экрана (top: -100px) и выезжает через JS
        (initHeader) после прокрутки 400px.
        На мобайле фиксируется сразу сверху (CSS media query).
   ============================================================ */
else : ?>

<!-- Статичный хедер поверх hero (только lg+) -->
<header class="d-none d-lg-block">

	<!-- Верхняя информационная полоска: адрес, часы, телефоны, соцсети -->
	<nav class="header-nav-top navbar navbar-expand-lg navbar-light d-none d-lg-block py-0">
		<div class="container">
			<div class="collapse navbar-collapse">
				<ul class="navbar-nav ms-auto align-items-center">
					<?= $top_bar_items ?>
				</ul>
			</div>
		</div>
	</nav>
	<!-- /Верхняя полоска -->

	<!-- Основная навигация: логотип + меню (светлый логотип для тёмного фона) -->
	<nav class="header-nav-bottom navbar navbar-expand-lg navbar-light py-0">
		<div class="container">

			<a class="navbar-brand" href="/">
				<img src="<?= $img ?>logo-light.svg" class="custom-logo" alt="Логотип в шапке" decoding="async">
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#header-dark-nav" aria-controls="header-dark-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<!-- Пункты меню из админки + дополнительные мобильные пункты ($mobile_menu) -->
			<div class="collapse navbar-collapse" id="header-dark-nav">
				<?php wp_nav_menu(array_merge($nav_menu_args, [
					'items_wrap' => 
					'<ul id="%1$s" class="navbar-nav align-items-lg-center ms-auto mb-2 mb-lg-0 %2$s">%3$s' 
						. $mobile_menu . 
					'</ul>',
				])); ?>
			</div>

		</div>
	</nav>
	<!-- /Основная навигация -->

</header>

<!--
	Скользящий компактный хедер.
	На десктопе: спрятан выше экрана (CSS top: -100px), выезжает через JS после скролла 400px.
	На мобайле: фиксируется сразу сверху — это основной хедер для мобильных устройств.
	Использует тёмный логотип (logo-dark.svg), т.к. фон всегда белый.
-->
<header id="sliding-header" class="shadow">
	<nav class="header-nav-bottom navbar navbar-expand-lg navbar-light py-1 py-lg-0">
		<div class="container">

			<a class="navbar-brand" href="/">
				<img src="<?= $img ?>logo-dark.svg" class="custom-logo" alt="Логотип в шапке" decoding="async">
			</a>

			<!-- Телефон + время между логотипом и бургером (только мобайл) -->
			<div class="d-lg-none"><?= $mobile_bar_info ?></div>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#header-sliding-nav" aria-controls="header-sliding-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<!-- Пункты меню из CMS + дополнительные мобильные пункты ($mobile_menu) -->
			<div class="collapse navbar-collapse" id="header-sliding-nav">
				<?php wp_nav_menu(array_merge($nav_menu_args, [
					'items_wrap' => 
					'<ul id="%1$s" class="navbar-nav align-items-lg-center ms-auto mb-2 mb-lg-0 %2$s">%3$s' 
						. $mobile_menu .
					'</ul>',
				])); ?>
			</div>

		</div>
	</nav>
</header>

<?php endif; ?>
<?php // Footer for vyezjalo() function ?>

<?php
    // Не показываем на странице с шаблоном "Кучина"
    $is_cucina_page = is_page_template( 'cucina.php' );

    if ( $is_cucina_page ) : ?>
<script>
			localStorage.setItem('cucinaPopupLastClosed', Date.now().toString());
		</script>
<?php else : ?>

<div class="popup-overlay" id="cucinaPopup">
	<div class="popup">
		<div class="popup-bg"></div>

		<button class="popup-close" id="cucinaPopupClose">
			<img src="<?php echo get_template_directory_uri(); ?>/img/ico/close-popup.svg" alt="Закрыть">
		</button>

		<div class="popup-content">

			<div class="popup-logo">
				<a href="https://мозаика62.рф/кухни-cucina-в-рязани/">
					<img src="<?php echo get_template_directory_uri(); ?>/img/cucina.png" alt="Cucina" style="max-width: 220px;">
				</a>
			</div>

			<div class="popup-description">
				Мы являемся официальным представителем кухонь <strong>Cucina</strong> в Рязани
			</div>

			<div class="popup-buttons">
				<a href="https://мозаика62.рф/кухни-cucina-в-рязани/" class="popup-btn-main btn-corporate-color-1 btn btn-lg">Подробнее</a>
				<button class="popup-btn-dismiss" id="cucinaPopupDismiss">Не интересует</button>
			</div>

		</div>
	</div>
</div>

<script>
			document.addEventListener('DOMContentLoaded', function () {
					const popup      = document.getElementById('cucinaPopup');
					const btnClose   = document.getElementById('cucinaPopupClose');
					const btnDismiss = document.getElementById('cucinaPopupDismiss');
					const HIDE_DURATION = 12 * 60 * 60 * 1000;  // 12 часов в мс

					function shouldShow() {
							const lastClosed = localStorage.getItem('cucinaPopupLastClosed');
							if ( ! lastClosed ) return true;
							return ( Date.now() - parseInt(lastClosed) ) > HIDE_DURATION;
					}

					function closePopup() {
							popup.classList.remove('active');
							localStorage.setItem('cucinaPopupLastClosed', Date.now().toString());
					}

					// Показываем через 15 сек если нужно
					if ( shouldShow() ) {
							const isFirstVisit = ! localStorage.getItem('cucinaPopupSeen');
							const delay = isFirstVisit ? 15 * 1000 : 0;

							// Фиксируем что уже был первый показ
							localStorage.setItem('cucinaPopupSeen', '1');

							setTimeout(function () {
									popup.classList.add('active');
							}, delay);
					}

					// Закрытие крестиком
					btnClose.addEventListener('click', closePopup);

					// Закрытие «Не интересует»
					btnDismiss.addEventListener('click', closePopup);
			});
	</script>
<?php endif; ?>

<?php get_template_part('template-parts/footer/footer'); ?>


<!-- Показываем сообщение об успешной отправки -->
<div style="display: <?php echo $_SESSION['display'] ?>;" onclick="modalClose();">
	<div id="background-msg" style="display: <?php echo $_SESSION['display'] ?>;"></div>
	<button id="btn-close" type="button" class="btn-close btn-close-white" onclick="modalClose();" style="position: absolute; z-index: 9999; top: 15px; right: 15px;"></button>
	<div id="message">
		<?php echo $_SESSION['recaptcha'];
		unset($_SESSION['recaptcha']); ?>
	</div>
</div>


<!-- callbackModalConsul Modal -->
<div class="modal fade" id="callbackModalConsul" tabindex="-1" aria-labelledby="callbackModalConsulLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<form method="post" action="<?php echo get_template_directory_uri(); ?>/mails/callback-consul.php" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="callbackModalConsulLabel">Записаться на консультацию</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6 mb-3 mb-md-0">
						<input type="text" name="name" class="form-control" placeholder="Ваше имя">
					</div>
					<div class="col-md-6">
						<input type="text" name="tel" class="form-control telMask" placeholder="Ваш телефон*" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="g-recaptcha-response-callback-consul" name="g-recaptcha-response">
				<button type="submit" class="btn btn-lg btn-corporate-color-1 mx-auto">Жду звонка</button>
			</div>
		</form>
	</div>
</div>
<!-- /callbackModalConsul Modal -->


<!-- Callback Modal -->
<div class="modal fade" id="callbackModal" tabindex="-1" aria-labelledby="callbackModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<form method="post" action="<?php echo get_template_directory_uri(); ?>/mails/callback-mail.php" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="callbackModalLabel">Обратный звонок</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<p><small>Мы свяжемся с Вами в течение 10 минут и ответим на все вопросы! Для звонка введите Ваше имя и
								телефон.</small></p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3 mb-md-0">
						<input type="text" name="name" class="form-control" placeholder="Ваше имя">
					</div>
					<div class="col-md-6">
						<input type="text" name="tel" class="form-control telMask" placeholder="Ваш телефон*" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="g-recaptcha-response-callback" name="g-recaptcha-response">
				<button type="submit" class="btn btn-lg btn-corporate-color-1 mx-auto">Жду звонка</button>
			</div>
		</form>
	</div>
</div>
<!-- /Callback Modal -->

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<form method="post" action="<?php echo get_template_directory_uri(); ?>/mails/callback-mail.php" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="orderModalLabel">Оставить заявку</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<p><small>Мы свяжемся с Вами в ближайшее время и ответим на все вопросы по заявке! Для звонка введите Ваше имя и телефон.</small></p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3 mb-md-0">
						<input type="text" name="name" class="form-control" placeholder="Ваше имя">
					</div>
					<div class="col-md-6">
						<input type="text" name="phone" class="form-control telMask" placeholder="Ваш телефон*" inputmode="text" required>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<input type="hidden" id="g-recaptcha-response-order" name="g-recaptcha-response">
				<button type="submit" class="btn btn-corporate-color-1 mx-auto">Жду звонка</button>
			</div>
		</form>
	</div>
</div>
<!-- /Order Modal -->

<!-- Рассчитать стоимость с загрузкой изображения -->
<div class="modal fade" id="calculatePriceWithDownloadModal" tabindex="-1" aria-labelledby="calculatePriceWithDownloadLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<form method="post" action="<?php echo get_template_directory_uri(); ?>/mails/get_calculate_2.php" class="modal-content" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title" id="calculatePriceWithDownloadLabel">Рассчитать стоимость</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row pb-2">
					<div class="col-12">
						<p><small>Опишите кухню своими словами, укажите форму, размеры, материалы и другую информацию</small></p>
					</div>
					<div class="col-12 mb-3">
						<textarea type="text" rows="3" name="mes" class="form-control form-control-corporate-color-1" placeholder=""></textarea>
					</div>
					<div class="col-12">
						<p><small>Вы можете прикрепить проект, изображение или схематично нарисованный рисунок кухни.</small></p>
					</div>
					<div class="mb-3">
						<div class="input-group custom-file-button">
							<label class="btn btn-lg btn-corporate-color-1 input-group-text" style="border-radius: 8px;" for="inputGroupFile">Прикрепить</label>
							<input type="file" name="file[]" class="form-control" id="inputGroupFile" accept=".jpg,.jpeg,.png,.pdf,.heic" multiple>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<input type="text" name="name" class="form-control form-control-corporate-color-1" placeholder="Ваше имя">
					</div>
					<div class="col-md-6 mb-3">
						<input type="text" name="tel" class="form-control form-control-corporate-color-1 telMask" placeholder="Ваш телефон*" required>
					</div>
					<div class="col-md-6">
						<input type="hidden" id="g-recaptcha-response-calculatePriceWithDownload" name="g-recaptcha-response">
						<button type="submit" class="btn btn-lg btn-corporate-color-1">Отправить</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- /Рассчитать стоимость с загрузкой изображения -->


<!-- Рассчитать стоимость без загрузки изображения -->
<div class="modal fade" id="calculatePriceWithoutDownloadModal" tabindex="-1" aria-labelledby="calculatePriceWithoutDownloadLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<form method="post" action="<?php echo get_template_directory_uri(); ?>/mails/get_calculate.php" class="modal-content" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title" id="calculatePriceWithoutDownloadLabel">Рассчитать стоимость</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row pb-2">
					<div class="col-12">
						<p><input type="text" name="product" style="border: none; outline: none;" value="<?php echo get_the_title(); ?>"></p>
					</div>
					<div class="col-12 mb-3">
						<textarea type="text" rows="3" name="mes" class="form-control form-control-corporate-color-1" placeholder="При желании укажите форму, размеры, материалы кухни или другую информацию"></textarea>
					</div>
					<div class="col-md-6 mb-3">
						<input type="text" name="name" class="form-control form-control-corporate-color-1" placeholder="Ваше имя">
					</div>
					<div class="col-md-6 mb-3">
						<input type="text" name="tel" class="form-control form-control-corporate-color-1 telMask" placeholder="Ваш телефон*" required>
					</div>
					<div class="col-md-6">
						<input type="hidden" id="g-recaptcha-response-calculatePriceWithoutDownload" name="g-recaptcha-response">
						<button type="submit" class="btn btn-corporate-color-1">Отправить</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- /Рассчитать стоимость без загрузки изображения -->



<!-- Callback button HTML -->
<div class="callback-button-wrapper">
	<div id="callbackBtn" class="callback-button" onclick="callbackButtonClick();">
		<div id="btnIco" class="callback-button-ico"></div>
	</div>

	<div id="formBtn" class="callback-form-button" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-title="Перезвонить Вам?">
		<a data-bs-toggle="modal" data-bs-target="#callbackModal">
			<div class="callback-form-button-ico"></div>
		</a>
	</div>
	<div id="phoneBtn" class="callback-phone-button" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-title="Позвонить">
		<a href="tel:<?php echo esc_attr(mytheme_get_phone_link('main')); ?>">
			<div class="callback-phone-button-ico"></div>
		</a>
	</div>
	<div id="whatsappBtn" class="callback-whatsapp-button" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-title="Whatsapp">
		<!-- Не открывает ссылку с ПК если не установлено приложение WhatsApp
		<a href="whatsapp://send?phone=+79361385058"><div class="callback-whatsapp-button-ico"></div></a> -->
		<!-- Другой вариант ссылки. Все равно не открывает Whatsapp если нет приложения -->
		<a href="<?php echo esc_url(mytheme_get_whatsapp()); ?>" target="blank">
			<div class="callback-whatsapp-button-ico"></div>
		</a>
		<!-- Еще варианты -->
		<!--a href="https://api.whatsapp.com/send/?phone=79361385058&text=Привет"><div class="callback-whatsapp-button-ico"></div></a-->
		<!--a href="https://wa.clck.bar/79361385058?text=%D0%9F%D1%80%D0%B8%D0%B2%D0%B5%D1%82!"><div class="callback-whatsapp-button-ico"></div></a-->
	</div>
	<div id="telegramBtn" class="callback-telegram-button" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-title="Telegram">
		<a href="<?php echo esc_url(mytheme_get_telegram()); ?>" target="_blank">
			<div class="callback-telegram-button-ico"></div>
		</a>
	</div>

	<div id="maxBtn" class="callback-max-button" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" data-bs-title="Max">
		<a href="<?php echo esc_url(mytheme_get_max()); ?>" target="_blank">
			<div class="callback-max-button-ico"></div>
		</a>
	</div>
</div>
<!-- /Callback button HTML -->

<!-- Callback button JS -->
<script>
    function callbackButtonClick() {
        
        let formBtn = document.getElementById('formBtn').style.top;
        
        if ( formBtn == "0px" || formBtn == 0 ) {
            document.getElementById('callbackBtn').style.animation = "none";
            document.getElementById('btnIco').style.animation = "change2 linear .5s";
            document.getElementById('btnIco').style.webkitAnimation = "change2 linear .5s";
            document.getElementById('btnIco').style.webkitTransition ="transform 1s ease-in-out";
            
            document.getElementById('btnIco').style.webkitTransform = "rotate(180deg)";
            document.getElementById('btnIco').style.transform = "rotate(180deg)";
            
            
            document.getElementById('btnIco').style.backgroundImage = "url(<?php echo get_template_directory_uri(); ?>/img/ico/callback-button-close.png)";
            document.getElementById('btnIco').style.backgroundPosition = "center";
            document.getElementById('btnIco').style.backgroundRepeat = "no-repeat";
            
            document.getElementById('btnIco').style.webkitBackgroundSize = "cover";
            document.getElementById('btnIco').style.backgroundSize = "cover";
            
            
            document.getElementById('formBtn').style.top = "-60px";
            document.getElementById('formBtn').style.opacity = "1";
            
            document.getElementById('phoneBtn').style.top = "-120px";
            document.getElementById('phoneBtn').style.opacity = "1";
            
            document.getElementById('whatsappBtn').style.top = "-180px";
            document.getElementById('whatsappBtn').style.opacity = "1";
            
            document.getElementById('telegramBtn').style.top = "-240px";
            document.getElementById('telegramBtn').style.opacity = "1";

            document.getElementById('maxBtn').style.top = "-300px";
            document.getElementById('maxBtn').style.opacity = "1";
        } else {
            document.getElementById('callbackBtn').style.animation = "waves linear 2s infinite";
            document.getElementById('btnIco').style.animation = "change linear 16s infinite";
            document.getElementById('btnIco').style.webkitTransition ="transform 1s ease-in-out";
            document.getElementById('btnIco').style.webkitAnimation = "change linear 16s infinite";
            document.getElementById('btnIco').style.transform = "rotate(180deg)";
            document.getElementById('btnIco').style.webkitTransform = "rotate(180deg)";
            document.getElementById('btnIco').style.backgroundImage = "url(<?php echo get_template_directory_uri(); ?>/img/ico/callback-button-ico.png)";
            document.getElementById('btnIco').style.backgroundPosition = "center";
            document.getElementById('btnIco').style.backgroundRepeat = "no-repeat";
            
            document.getElementById('btnIco').style.webkitBackgroundSize = "cover";
            document.getElementById('btnIco').style.backgroundSize = "cover";
            
            
            document.getElementById('formBtn').style.top = "0px";
            document.getElementById('formBtn').style.opacity = "0";
            
            document.getElementById('phoneBtn').style.top = "0px";
            document.getElementById('phoneBtn').style.opacity = "0";
            
            document.getElementById('whatsappBtn').style.top = "0px";
            document.getElementById('whatsappBtn').style.opacity = "0";
            
            document.getElementById('telegramBtn').style.top = "0px";
            document.getElementById('telegramBtn').style.opacity = "0";
            
            document.getElementById('maxBtn').style.top = "0px";
            document.getElementById('maxBtn').style.opacity = "0";
        }
    }
</script>
<!-- /Callback button JS -->


<!-- Dounloads Bootstrap Bundle with Popper -->
<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.bundle.min.js"></script>

<!-- Scripts for Quiz and for add loader -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.min.js"></script>
<!--script src="https://code.jquery.com/jquery-3.6.0.min.js"></script-->

<!-- Font Awesome -->
<!-- <script src="https://kit.fontawesome.com/064ae6a0a2.js"></script> -->


<!-- Theme JS -->
<script src="<?php echo get_template_directory_uri(); ?>/js/theme.js"></script>


<!-- Выбераем функцию для header -->
<?php if (is_product()) { ?>
<script>prilipalo();</script>
<?php } else { ?>
<script>vyezjalo();</script>
<?php } ?>


<!-- Telephone number mask -->
<script src="<?php echo get_template_directory_uri(); ?>/js/inputmask.min.js"></script>
<script>
	var telMask = document.getElementsByClassName("telMask");
	var im = new Inputmask("+7(999)999-99-99");
	im.mask(telMask);
</script>


<!-- Загрузка изображений с приоритетом
<script>
	if ("loading" in HTMLImageElement.prototype) {
		const images = document.querySelectorAll('img[loading="lazy"]');
		images.forEach((img) => {
			img.src = img.dataset.src;
		});
	} else {
		// Dynamically import the LazySizes library
		const script = document.createElement("script");
		script.src = "https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.8/lazysizes.min.js";
		document.body.appendChild(script);
	}
</script> -->


<!-- reCaptcha v3 New from Google
<script src='https://www.google.com/recaptcha/api.js?render=6LdV1IcUAAAAADRQAhpGL8dVj5_t0nZDPh9m_0tn'></script>
<script>
	grecaptcha.ready(function () {
		grecaptcha.execute('6LdV1IcUAAAAADRQAhpGL8dVj5_t0nZDPh9m_0tn', { action: 'action_name' }).then(function (token) {

			if (document.getElementById('g-recaptcha-response-callback')) {
				document.getElementById('g-recaptcha-response-callback').value = token;
			}

            if (document.getElementById('g-recaptcha-response-callback-consul')) {
                document.getElementById('g-recaptcha-response-callback-consul').value = token;
            }

			if (document.getElementById('g-recaptcha-response-calculatePriceWithDownload')) {
				document.getElementById('g-recaptcha-response-calculatePriceWithDownload').value = token;
			}
			if (document.getElementById('g-recaptcha-response-calculatePriceWithoutDownload')) {
				document.getElementById('g-recaptcha-response-calculatePriceWithoutDownload').value = token;
			}
			// Order
			if ( document.getElementById('g-recaptcha-response-order') ) {
				document.getElementById('g-recaptcha-response-order').value=token;
			}

            if ( document.getElementById('g-recaptcha-response-order-2') ) {
                document.getElementById('g-recaptcha-response-order-2').value=token;
            }
		});
	});
</script> -->
<!-- <?php include get_template_directory() . '/inc/snowflake/snowflake.php'; ?> -->

</body>

</html>
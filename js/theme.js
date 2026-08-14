/* Parallax home section */
$(window).scroll(function (e) {
    var scrolled = $(window).scrollTop();
    $('.parallax-home-section').css('top', (-(scrolled * .35)) + 'px'); // 35 - скорость прокрутки
    $('.parallax-home-section-closets').css('top', (-(scrolled * .35)) + 'px'); // 35 - скорость прокрутки
    $('.index-parallax-home-section').css('top', (-(scrolled * .35)) + 'px'); // 35 - скорость прокрутки
    $('.jobs-home-section').css('top', (-(scrolled * .35)) + 'px'); // 35 - скорость прокрутки
    $('.closets-home-section').css('top', (-(scrolled * .35)) + 'px'); // 35 - скорость прокрутки
    $('.parallax-home-section-portfolio').css('top', (-(scrolled * .35)) + 'px'); // 35 - скорость прокрутки
    $('.home-cucina-parallax-home-section').css('top', (-(scrolled * .35)) + 'px'); // 35 - скорость прокрутки
});

/* End parallax home section */

jQuery(function ($) {
    if (!$('.parallax-background').length) return;
    $(window).on('scroll', function () {
        var scrollTop = $(window).scrollTop();
        var sectionTop = $('.parallax-background').offset().top;
        var sectionHeight = $('.parallax-background').outerHeight();
        if (scrollTop + window.innerHeight > sectionTop && scrollTop < sectionTop + sectionHeight) {
            var offset = (scrollTop - sectionTop) * 0.35;
            $('.parallax-background').css('transform', 'translateY(' + offset + 'px)');
        }
    });
});

/* Инициализация хедера:*/
function initHeader() {
    var slidingHeader = document.getElementById('sliding-header');

    if (slidingHeader) {
        window.addEventListener('scroll', function () {
            if (window.innerWidth >= 992) {
                slidingHeader.style.top = window.pageYOffset > 400 ? '0px' : '-100px';
            }
        });
    } else {
        prilipalo();
    }
}

/* Прилипающий белый хедер */
function prilipalo() {
    var headerNavBottom = document.getElementById('header-2-bottom');
    var headerNavTop = document.querySelector('.header-nav-top');

    if (!headerNavBottom) return;

    function setMobileFixed() {
        if (window.innerWidth < 992) {
            var menuHeight = headerNavBottom.offsetHeight;
            headerNavBottom.classList.add('fixed-top');
            headerNavBottom.style.position = 'fixed';
            headerNavBottom.style.top = '0';
            document.body.style.paddingTop = menuHeight + 'px';
        }
    }

    function checkDesktopPosition() {
        if (window.innerWidth >= 992) {
            var topMenuHeight = headerNavTop ? headerNavTop.offsetHeight : 0;
            if (window.pageYOffset > topMenuHeight) {
                var menuHeight = headerNavBottom.offsetHeight;
                headerNavBottom.classList.add('fixed-top');
                headerNavBottom.style.position = 'fixed';
                headerNavBottom.style.top = '0';
                document.body.style.paddingTop = menuHeight + 'px';
            }
        }
    }

    setMobileFixed();
    checkDesktopPosition();

    window.addEventListener('scroll', function () {
        var prokrutka = window.pageYOffset;
        var screenWidth = window.innerWidth;

        if (screenWidth >= 992) {
            var topMenuHeight = headerNavTop ? headerNavTop.offsetHeight : 0;
            if (prokrutka > topMenuHeight) {
                var menuHeight = headerNavBottom.offsetHeight;
                headerNavBottom.classList.add('fixed-top');
                headerNavBottom.classList.add('scrolled');
                headerNavBottom.style.position = 'fixed';
                headerNavBottom.style.top = '0';
                document.body.style.paddingTop = menuHeight + 'px';
            } else {
                headerNavBottom.classList.remove('fixed-top');
                headerNavBottom.classList.remove('scrolled');
                headerNavBottom.style.position = 'relative';
                headerNavBottom.style.top = '';
                document.body.style.paddingTop = '0';
            }
        }

        if (screenWidth < 992) {
            if (prokrutka > 0) {
                headerNavBottom.classList.add('scrolled');
            } else {
                headerNavBottom.classList.remove('scrolled');
            }
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth < 992) {
            setMobileFixed();
        } else {
            headerNavBottom.classList.remove('fixed-top');
            headerNavBottom.style.position = 'relative';
            headerNavBottom.style.top = '';
            document.body.style.paddingTop = '0';
            checkDesktopPosition();
        }
    });
}


/* Убираем сообщение об успешной отправки */
function modalClose() {
    document.getElementById('background-msg').style.display = 'none';
    document.getElementById('message').style.display = 'none';
    document.getElementById('btn-close').style.display = 'none';
}
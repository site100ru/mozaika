<?php
// INCLUDE STYLES AND SCRIPTS
add_action('wp_enqueue_scripts', function() {
    // 1. Bootstrap загружается первым (база)
    wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css');
    
    // 2. style.css загружается после Bootstrap и переопределяет его
    wp_enqueue_style('style-css', get_stylesheet_uri(), array('bootstrap-css'));
    
    // 3. Подключаем jQuery (обязательно указываем зависимость и ставим в footer)
    wp_enqueue_script('jquery');
});
// END INCLUDE STYLES AND SCRIPTS




/*** ДЕЛАЕМ АВТОМАТИЧЕСКОЕ ОБНОВЛЕНИЕ РОДИТЕЛЬСКОЙ ТЕМЫ ИЗ GITHUB RELEASE ***/
// Принудительная очистка кэша при заходе на страницу обновлений
add_action('load-update-core.php', function() {
    delete_site_transient('update_themes');
    wp_clean_themes_cache(true);
});

add_filter('pre_set_site_transient_update_themes', function($transient) {
    if (empty($transient) || !is_object($transient)) {
        return $transient;
    }
    
    // Данные о текущей теме
    $theme_stylesheet = get_template();
    $theme_data       = wp_get_theme($theme_stylesheet);
    $current_version  = $theme_data->get('Version');
    
    // Данные о репозитории GitHub
    $github_username = 'site100ru';
    $github_repo     = 'mozaika';
    $github_api_url  = "https://api.github.com/repos/{$github_username}/{$github_repo}/releases/latest";
    
    // Запрос к GitHub API
    $response = wp_remote_get($github_api_url, array(
        'headers' => array('User-Agent' => 'WordPress'),
        'timeout' => 10,
    ));
    
    // Проверка ответа
    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return $transient;
    }
    
    $release_data = json_decode(wp_remote_retrieve_body($response), true);
    
    // Проверка наличия данных
    if (empty($release_data['tag_name']) || empty($release_data['assets'][0]['browser_download_url'])) {
        return $transient;
    }
    
    $latest_version = ltrim($release_data['tag_name'], 'v');
    $download_url   = $release_data['assets'][0]['browser_download_url'];
    
    // Сравнение версий
    if (version_compare($latest_version, $current_version, '>')) {
        $transient->response[$theme_stylesheet] = array(
            'theme'       => $theme_stylesheet,
            'new_version' => $latest_version,
            'url'         => "https://github.com/{$github_username}/{$github_repo}",
            'package'     => $download_url,
        );
    }
    
    return $transient;
}, 10, 1);
// END ДЕЛАЕМ АВТОМАТИЧЕСКОЕ ОБНОВЛЕНИЕ РОДИТЕЛЬСКОЙ ТЕМЫ ИЗ GITHUB RELEASE




/*** MENU ***/
// Bootstrap 5 wp_nav_menu walker
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu {
	private $current_item;
	private $dropdown_menu_alignment_values = [
		'dropdown-menu-start',
		'dropdown-menu-end',
		'dropdown-menu-sm-start',
		'dropdown-menu-sm-end',
		'dropdown-menu-md-start',
		'dropdown-menu-md-end',
		'dropdown-menu-lg-start',
		'dropdown-menu-lg-end',
		'dropdown-menu-xl-start',
		'dropdown-menu-xl-end',
		'dropdown-menu-xxl-start',
		'dropdown-menu-xxl-end'
	];

	function start_lvl(&$output, $depth = 0, $args = null) {
		$dropdown_menu_class[] = '';
		foreach($this->current_item->classes as $class) {
			if(in_array($class, $this->dropdown_menu_alignment_values)) {
				$dropdown_menu_class[] = $class;
			}
		}
		$indent = str_repeat("\t", $depth);
		$submenu = ($depth > 0) ? ' sub-menu' : '';
		$output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		
		$this->current_item = $item;

		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$li_attributes = '';
		$class_names = $value = '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;

		$classes[] = ($args->walker->has_children) ? 'dropdown' : '';
		$classes[] = 'nav-item';
		$classes[] = 'nav-item-' . $item->ID;
		if ($depth && $args->walker->has_children) {
			$classes[] = 'dropdown-menu dropdown-menu-end';
		}

		$class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = ' class="' . esc_attr($class_names) . '"';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
		$id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';
		
		
		$output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';


		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

		$active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
		$nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
		$attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		
		

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		
		
		// Показываем точки в меню, первый вариант
		$item_title = $item->title;
		$dropdown = in_array( 'dropdown', $classes );
		if ( $item_title == 'Контакты' ) {
			$output .= '
				<li class="nav-item d-none">
					<span class="nav-link">
						<img src="'.get_stylesheet_directory_uri().'/img/ico/menu-decoration-point.svg" alt="">
					</span>
				</li>
			';
		} else if ( $dropdown == false AND $depth == 0 ) {
			$output .= '
				<li class="nav-item d-none d-xl-inline">
					<span class="nav-link">
						<img src="'.get_stylesheet_directory_uri().'/img/ico/menu-decoration-point.svg" alt="">
					</span>
				</li>
			';
		}
	}
}
/* End Bootstrap 5 wp_nav_menu walker */
/*** END MENU ***/



/*** ON WooCommerce support ***/
/* WooCommerce support */
add_action( 'after_setup_theme', 'furniture_catalog_add_woocommerce_support' );
function furniture_catalog_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
/*** END ON WooCommerce support ***/




/*** ВКЛЮЧАЕМ EXCERPT FOR PAGES (ОПИСАНИЕ ДЛЯ СТРАНИЦ) ***/
add_action('init', 'add_excerpt_to_pages');
function add_excerpt_to_pages() {
	add_post_type_support('page', 'excerpt');
}




/*** ДЕЛАЕМ ПРАВИЛЬНЫЙ TITLE ДЛЯ КАЖДОЙ СТРАНИЦЫ ***/ 
function echo_title() {
    
	// Если страница категории продукта woocommerce
    if ( is_product_category() ) {
        foreach( wp_get_post_terms( get_the_id(), 'product_cat' ) as $term ){
            if( $term ){
                if ( $term->name ) {
                    if ( $term->name == "Кухни" ) {
                        echo "Каталог кухонь &#8212; Декор-Север"; // Product category name
                    
                    } elseif ( $term->name == "Шкафы" ) {
                        echo "Каталог шкафов &#8212; Декор-Север"; // Product category name
                        
                    } elseif ( $term->name == "Корпусная мебель" ) {
                        echo "Каталог корпусной мебели &#8212; Декор-Север"; // Product category name
                    
                    } else {
                        echo $term->name; // Product category name
                    }
                }
            }
        }
    
    // Если страница портфолио
    } elseif ( is_post_type_archive( 'portfolio' ) ) {
        echo 'Наши выполненные работы &#8212; Декор-Север';
    
    // Если страница категорий портфолио
    } elseif ( is_tax( 'portfolio-cat' ) ) {
        $term = get_queried_object(); // Получаем текущий термин
        echo "Наши работы: " . $term->name . " &#8212; Декор-Север";
    
    } else {
        echo wp_get_document_title();
    }
}
/*** END ДЕЛАЕМ ПРАВИЛЬНЫЙ TITLE ДЛЯ КАЖДОЙ СТРАНИЦЫ ***/




/*** ДЕЛАЕМ ПРАВИЛЬНЫЙ DESCRIPTION ДЛЯ КАЖДОЙ СТРАНИЦЫ ***/
function echo_description() {
    
    // Если страница стандартной категории поста
    if ( is_category() ) {
        echo wp_strip_all_tags( category_description() );
    
    // Если страница продукта woocommerce
    } elseif ( is_product() ) {
        $product = wc_get_product( get_the_ID() ); 
        $short_description = $product->get_short_description();
        echo wp_strip_all_tags( $short_description );
    
    // Если страница категории продукта woocommerce
    } elseif ( is_product_category() ) {
        foreach( wp_get_post_terms( get_the_id(), 'product_cat' ) as $term ){
            if( $term ){
                //echo $term->name . '<br>'; // product category name
                if ( $term->description ) {
                    echo $term->description; // Product category description
                }
            }
        }
    
    // Если страница портфолио
    } elseif ( is_post_type_archive( 'portfolio' ) ) {
        echo 'Наши выполненные работы - Декор-Север';
    
    // Если страница категорий портфолио
    } elseif ( is_tax( 'portfolio-cat' ) ) {
        $term = get_queried_object(); // Получаем текущий термин
        echo $term->description;
    
    // Если страница магазина	
    } elseif ( is_shop() ) {
        $shop_page_id = wc_get_page_id('shop');
        echo get_the_excerpt($shop_page_id);
    
    // Если обычная страница
    } else {
        echo get_the_excerpt();
    }
}
/*** END ДЕЛАЕМ ПРАВИЛЬНЫЙ DESCRIPTION ДЛЯ КАЖДОЙ СТРАНИЦЫ ***/




/*** GEGISTER TAXONOMY ***/
add_action( 'init', 'create_taxonomy' );
function create_taxonomy() {
	
	// Таксономия - портфолио
	register_taxonomy( 'portfolio-cat', [ 'portfolio' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Наши работы',
			'singular_name'     => 'Категория портфолио',
			'search_items'      => 'Искать категорию портфолио',
			'all_items'         => 'Все категории портфолио',
			'view_item '        => 'View Genre',
			'parent_item'       => 'Parent Genre',
			'parent_item_colon' => 'Parent Genre:',
			'edit_item'         => 'Edit Genre',
			'update_item'       => 'Update Genre',
			'add_new_item'      => 'Add New Genre',
			'new_item_name'     => 'New Genre Name',
			'menu_name'         => 'Категории портфолио',
			'back_to_items'     => '← Вернуться к категориям портфолио',
		],
		'description'           => '', // описание таксономии
		'public'                => true,
		// 'publicly_queryable'    => null, // равен аргументу public
		// 'show_in_nav_menus'     => true, // равен аргументу public
		// 'show_ui'               => true, // равен аргументу public
		// 'show_in_menu'          => true, // равен аргументу show_ui
		// 'show_tagcloud'         => true, // равен аргументу show_ui
		// 'show_in_quick_edit'    => null, // равен аргументу show_ui
		'hierarchical'          => true,
		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
}
/*** END GEGISTER TAXONOMY ***/




/*** REGISTER POST TYPE ***/
add_action( 'init', 'register_post_types' );
function register_post_types() {
	
	// Add thumbnails
	add_theme_support('post-thumbnails');
	
	// Тип записи - наши работы (портфолио)
	register_post_type( 'portfolio', [
		'label'  => null,
		'labels' => [
			'name'               => 'Наши работы', // основное название для типа записи
			'singular_name'      => 'Наши работы', // название для одной записи этого типа
			'add_new'            => 'Добавить нашу работу', // для добавления новой записи
			'add_new_item'       => 'Добавление нашей работы', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование нашей работы', // для редактирования типа записи
			'new_item'           => 'Новая наша работа', // текст новой записи
			'view_item'          => 'Смотреть нашу работу', // для просмотра записи этого типа.
			'search_items'       => 'Искать нашу работу', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Наши работы', // название меню
		],
		'description'         => '',
		'public'              => true,
		// 'publicly_queryable'  => null, // зависит от public
		// 'exclude_from_search' => null, // зависит от public
		// 'show_ui'             => null, // зависит от public
		// 'show_in_nav_menus'   => null, // зависит от public
		'show_in_menu'        => null, // показывать ли в меню адмнки
		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => null,
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor' ], // 'title','editor','author','trackbacks','comments', 'thumbnail', 'custom-fields','revisions','page-attributes','post-formats', 'excerpt'
		'taxonomies'          => [ 'portfolio-cat' ],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}
/*** END REGISTER POST TYPE ***/




/*** ДЕЛАЕМ ВСЕ ЧТО СВЯЗАНО С КОНТАКТАМИ ***/
/*** ДОБАВЛЯЕМ ВОЗМОЖНОСТЬ В НАСТРОЙКАХ ТЕМЫ ДОБАВИТЬ КОНТАКТЫ И КОД СЧЕТЧИКА ***/
function mytheme_customize_register($wp_customize)
{
    // Добавляем секцию
    $wp_customize->add_section('mytheme_analytics', array(
        'title' => 'Аналитика и счетчики',
        'priority' => 200,
    ));

    // Поле для кода счетчика (head)
    $wp_customize->add_setting('mytheme_counter_head', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_counter_head', array(
        'label' => 'Код счетчика (в <head>)',
        'description' => 'Вставьте код, который должен быть в <head> (например, Google Analytics, Meta Pixel)',
        'section' => 'mytheme_analytics',
        'type' => 'textarea',
    ));

    // Поле для кода счетчика (body)
    $wp_customize->add_setting('mytheme_counter_body', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_counter_body', array(
        'label' => 'Код счетчика (перед </body>)',
        'description' => 'Вставьте код, который должен быть перед закрывающим тегом </body> (например, Яндекс.Метрика)',
        'section' => 'mytheme_analytics',
        'type' => 'textarea',
    ));


    /** ИСПОЛЬЗУЕМ ВЛОЖЕННЫЕ КОНТЕЙНЕРЫ **/
    /* КОНТАКТЫ */
    // Создаем панель (родительский контейнер)
    $wp_customize->add_panel('contact_panel', array(
        'title' => 'Контакты',
        'description' => 'Описание контактов',
        'priority' => 205,
    ));

    /* ОСНОВНОЙ НОМЕР ТЕЛЕФОНА */
    $wp_customize->add_section('mytheme_contacts', array(
        'title' => 'Основной номер телефона',
        'panel' => 'contact_panel',
        'priority' => 5
    ));

    $wp_customize->add_setting('mytheme_main_phone_country_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_main_phone_country_code', array(
        'label' => 'Код страны',
        'description' => 'Например: 8 или +7',
        'section' => 'mytheme_contacts',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('mytheme_main_phone_region_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_main_phone_region_code', array(
        'label' => 'Код региона',
        'description' => 'Например: 800, без скобок',
        'section' => 'mytheme_contacts',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('mytheme_main_phone_number', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_main_phone_number', array(
        'label' => 'Номер телефона',
        'description' => 'Например: 880-80-88',
        'section' => 'mytheme_contacts',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 100px; display: inline-block;',
        )
    ));


    /* ДОПОЛНИТЕЛЬНЫЙ НОМЕР ТЕЛЕФОНА */
    $wp_customize->add_section('additional_phone_number', array(
        'title' => 'Дополнительный номер телефона',
        'panel' => 'contact_panel',
        'priority' => 10
    ));

    $wp_customize->add_setting('additional_phone_country_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('additional_phone_country_code', array(
        'label' => 'Код страны',
        'description' => 'Например: 8 или +7',
        'section' => 'additional_phone_number',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('additional_phone_region_code', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('additional_phone_region_code', array(
        'label' => 'Код региона',
        'description' => 'Например: 800, без скобок',
        'section' => 'additional_phone_number',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 60px; display: inline-block;',
        )
    ));

    $wp_customize->add_setting('additional_phone_number', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('additional_phone_number', array(
        'label' => 'Номер телефона',
        'description' => 'Например: 880-80-88',
        'section' => 'additional_phone_number',
        'type' => 'input',
        'input_attrs' => array(
            'placeholder' => '',
            'style' => 'width: 100px; display: inline-block;',
        )
    ));


    /* ДОПОЛНИТЕЛЬНЫЕ НОМЕРА ТЕЛЕФОНОВ (повторитель) */
    $wp_customize->add_section('mytheme_contacts_phones_extra', array(
        'title' => 'Дополнительные номера телефонов',
        'panel' => 'contact_panel',
        'priority' => 15
    ));

    $wp_customize->add_setting('mytheme_phones_extra_json', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new Mytheme_Phone_Repeater_Control(
        $wp_customize,
        'mytheme_phones_extra_json',
        array(
            'label' => 'Дополнительные телефоны',
            'description' => 'Добавьте дополнительные номера телефонов. Можно добавить несколько.',
            'section' => 'mytheme_contacts_phones_extra',
        )
    ));


    /* EMAIL */
    $wp_customize->add_section('mytheme_contacts_email', array(
        'title' => 'Email',
        'panel' => 'contact_panel',
        'priority' => 20
    ));

    $wp_customize->add_setting('mytheme_email', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_email', array(
        'label' => 'Email',
        'section' => 'mytheme_contacts_email',
        'type' => 'input',
    ));


    /* ДОПОЛНИТЕЛЬНЫЕ EMAIL (повторитель) */
    $wp_customize->add_section('mytheme_contacts_emails_extra', array(
        'title' => 'Дополнительные почты для приема писем',
        'panel' => 'contact_panel',
        'priority' => 25
    ));

    $wp_customize->add_setting('mytheme_emails_extra_json', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new Mytheme_Email_Repeater_Control(
        $wp_customize,
        'mytheme_emails_extra_json',
        array(
            'label' => 'Дополнительные Email адреса',
            'description' => 'Добавьте дополнительные email адреса для приема почты. Можно добавить несколько.',
            'section' => 'mytheme_contacts_emails_extra',
        )
    ));


    /* Telegram */
    $wp_customize->add_section('mytheme_contacts_telegram', array(
        'title' => 'Telegram',
        'panel' => 'contact_panel',
        'priority' => 30
    ));

    $wp_customize->add_setting('mytheme_telegram', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_telegram', array(
        'label' => 'Telegram',
        'description' => 'Укажите ссылку на Telegram',
        'section' => 'mytheme_contacts_telegram',
        'type' => 'input',
    ));


    /* Whatsapp */
    $wp_customize->add_section('mytheme_contacts_whatsapp', array(
        'title' => 'Whatsapp',
        'panel' => 'contact_panel',
        'priority' => 35
    ));

    $wp_customize->add_setting('mytheme_whatsapp', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_whatsapp', array(
        'label' => 'Whatsapp',
        'description' => 'Укажите ссылку на Whatsapp',
        'section' => 'mytheme_contacts_whatsapp',
        'type' => 'input',
    ));


    /* VK */
    $wp_customize->add_section('mytheme_contacts_vk', array(
        'title' => 'Вконтакте',
        'panel' => 'contact_panel',
        'priority' => 40
    ));

    $wp_customize->add_setting('mytheme_vk', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_vk', array(
        'label' => 'Вконтакте',
        'description' => 'Укажите ссылку на Вконтакте',
        'section' => 'mytheme_contacts_vk',
        'type' => 'input'
    ));


    /* Instagram */
    $wp_customize->add_section('mytheme_contacts_instagram', array(
        'title' => 'Instagram',
        'panel' => 'contact_panel',
        'priority' => 45
    ));

    $wp_customize->add_setting('mytheme_instagram', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_instagram', array(
        'label' => 'Instagram',
        'description' => 'Укажите ссылку на Instagram',
        'section' => 'mytheme_contacts_instagram',
        'type' => 'input'
    ));


    /* Address */
    $wp_customize->add_section('mytheme_contacts_address', array(
        'title' => 'Адрес',
        'panel' => 'contact_panel',
        'priority' => 50
    ));

    $wp_customize->add_setting('mytheme_address', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_address', array(
        'label' => 'Адрес',
        'description' => 'Укажите адрес организации',
        'section' => 'mytheme_contacts_address',
        'type' => 'input'
    ));

    // Добавляем поле для ввода полного адреса
    $wp_customize->add_setting('mytheme_address_full', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_address_full', array(
        'label' => 'Адрес (полный)',
        'description' => 'Укажите полный адрес организации с подробностями',
        'section' => 'mytheme_contacts_address',
        'type' => 'textarea'
    ));
    /* End address */


    /* MAX */
    $wp_customize->add_section('mytheme_contacts_max', array(
        'title' => 'МАХ',
        'panel' => 'contact_panel',
        'priority' => 55
    ));

    $wp_customize->add_setting('mytheme_max', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_max', array(
        'label' => 'Адрес',
        'description' => 'Укажите ссылку на МАХ',
        'section' => 'mytheme_contacts_max',
        'type' => 'input'
    ));
    /* END MAX */


    /* Время работы */
    $wp_customize->add_section('mytheme_contacts_job_time', array(
        'title' => 'Время работы',
        'panel' => 'contact_panel',
        'priority' => 60
    ));

    $wp_customize->add_setting('mytheme_job_time', array(
        'default' => '',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('mytheme_job_time', array(
        'label' => 'Время работы',
        'description' => 'Укажите время работы',
        'section' => 'mytheme_contacts_job_time',
        'type' => 'input'
    ));
}
add_action('customize_register', 'mytheme_customize_register');


/**
 * Кастомные контролы - загружаются только в контексте кастомайзера
 */
if (class_exists('WP_Customize_Control')) {
    
    /**
     * Кастомный контрол для повторителя телефонов
     */
    class Mytheme_Phone_Repeater_Control extends WP_Customize_Control
    {
        public $type = 'phone_repeater';

        public function render_content()
        {
            $values = json_decode($this->value(), true);
            if (!is_array($values)) {
                $values = array();
            }
    ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>

            <div class="phone-repeater-list">
                <?php foreach ($values as $index => $phone) : ?>
                    <div class="phone-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="text" placeholder="Номер для отображения (напр: 8 (4912) 77-70-98)" value="<?php echo esc_attr($phone['display']); ?>" class="phone-display" style="width: 100%; margin-bottom: 5px;" />
                        <input type="text" placeholder="Номер для ссылки (напр: 84912777098)" value="<?php echo esc_attr($phone['link']); ?>" class="phone-link" style="width: 100%; margin-bottom: 5px;" />
                        <button type="button" class="button remove-phone" style="color: #a00;">Удалить</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button add-phone" style="margin-top: 10px;">+ Добавить телефон</button>

            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" class="phone-repeater-value" />

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var control = $('#customize-control-<?php echo esc_js($this->id); ?>');

                    function updateValue() {
                        var phones = [];
                        control.find('.phone-repeater-item').each(function() {
                            var display = $(this).find('.phone-display').val();
                            var link = $(this).find('.phone-link').val();
                            if (display || link) {
                                phones.push({
                                    display: display,
                                    link: link
                                });
                            }
                        });
                        control.find('.phone-repeater-value').val(JSON.stringify(phones)).trigger('change');
                    }

                    control.on('click', '.add-phone', function() {
                        var template = '<div class="phone-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">' +
                            '<input type="text" placeholder="Номер для отображения (напр: 8 (4912) 77-70-98)" class="phone-display" style="width: 100%; margin-bottom: 5px;" />' +
                            '<input type="text" placeholder="Номер для ссылки (напр: 84912777098)" class="phone-link" style="width: 100%; margin-bottom: 5px;" />' +
                            '<button type="button" class="button remove-phone" style="color: #a00;">Удалить</button>' +
                            '</div>';
                        control.find('.phone-repeater-list').append(template);
                    });

                    control.on('click', '.remove-phone', function() {
                        $(this).closest('.phone-repeater-item').remove();
                        updateValue();
                    });

                    control.on('input', '.phone-display, .phone-link', function() {
                        updateValue();
                    });
                });
            </script>
    <?php
        }
    }


    /**
     * Кастомный контрол для повторителя email
     */
    class Mytheme_Email_Repeater_Control extends WP_Customize_Control
    {
        public $type = 'email_repeater';

        public function render_content()
        {
            $values = json_decode($this->value(), true);
            if (!is_array($values)) {
                $values = array();
            }
    ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>

            <div class="email-repeater-list">
                <?php foreach ($values as $index => $email) : ?>
                    <div class="email-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="email" placeholder="Email адрес" value="<?php echo esc_attr($email['email']); ?>" class="email-address" style="width: 100%; margin-bottom: 5px;" />
                        <button type="button" class="button remove-email" style="color: #a00;">Удалить</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button add-email" style="margin-top: 10px;">+ Добавить email</button>

            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" class="email-repeater-value" />

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var control = $('#customize-control-<?php echo esc_js($this->id); ?>');

                    function updateValue() {
                        var emails = [];
                        control.find('.email-repeater-item').each(function() {
                            var email = $(this).find('.email-address').val();
                            if (email) {
                                emails.push({
                                    email: email
                                });
                            }
                        });
                        control.find('.email-repeater-value').val(JSON.stringify(emails)).trigger('change');
                    }

                    control.on('click', '.add-email', function() {
                        var template = '<div class="email-repeater-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">' +
                            '<input type="email" placeholder="Email адрес" class="email-address" style="width: 100%; margin-bottom: 5px;" />' +
                            '<button type="button" class="button remove-email" style="color: #a00;">Удалить</button>' +
                            '</div>';
                        control.find('.email-repeater-list').append(template);
                    });

                    control.on('click', '.remove-email', function() {
                        $(this).closest('.email-repeater-item').remove();
                        updateValue();
                    });

                    control.on('input', '.email-address', function() {
                        updateValue();
                    });
                });
            </script>
    <?php
        }
    }
}
/*** END ДОБАВЛЯЕМ ВОЗМОЖНОСТЬ В НАСТРОЙКАХ ТЕМЫ ДОБАВИТЬ КОНТАКТЫ И КОД СЧЕТЧИКА ***/


/**
 * Получить отформатированный телефон для отображения
 */
function mytheme_get_phone($type = 'main') {
    if ($type === 'main') {
        $country_code = get_theme_mod('mytheme_main_phone_country_code', '');
        $region_code = get_theme_mod('mytheme_main_phone_region_code', '');
        $number = get_theme_mod('mytheme_main_phone_number', '');
    } else {
        $country_code = get_theme_mod('additional_phone_country_code', '');
        $region_code = get_theme_mod('additional_phone_region_code', '');
        $number = get_theme_mod('additional_phone_number', '');
    }
    
    // Проверяем, что все части заполнены
    if (empty($country_code) || empty($region_code) || empty($number)) {
        return '';
    }
    
    // Форматируем: 8 (800) 880-80-88 или +7 (800) 880-80-88
    return $country_code . ' (' . $region_code . ') ' . $number;
}

/**
 * Получить телефон в формате для ссылки tel:
 */
function mytheme_get_phone_link($type = 'main') {
    if ($type === 'main') {
        $country_code = get_theme_mod('mytheme_main_phone_country_code', '');
        $region_code = get_theme_mod('mytheme_main_phone_region_code', '');
        $number = get_theme_mod('mytheme_main_phone_number', '');
    } else {
        $country_code = get_theme_mod('additional_phone_country_code', '');
        $region_code = get_theme_mod('additional_phone_region_code', '');
        $number = get_theme_mod('additional_phone_number', '');
    }
    
    // Проверяем, что все части заполнены
    if (empty($country_code) || empty($region_code) || empty($number)) {
        return '';
    }
    
    // Убираем все символы кроме цифр и +
    $phone_link = $country_code . $region_code . $number;
    $phone_link = preg_replace('/[^0-9+]/', '', $phone_link);
    
    return $phone_link;
}

/**
 * Получить email
 */
function mytheme_get_email() {
    return get_theme_mod('mytheme_email', '');
}

/**
 * Получить ссылку на email с mailto
 */
function mytheme_get_email_link() {
    $email = get_theme_mod('mytheme_email', '');
    return !empty($email) ? 'mailto:' . $email : '';
}

/**
 * Получить ссылку на Telegram
 */
function mytheme_get_telegram() {
    return get_theme_mod('mytheme_telegram', '');
}

/**
 * Получить ссылку на WhatsApp
 */
function mytheme_get_whatsapp($with_params = true) {
    $whatsapp = get_theme_mod('mytheme_whatsapp', '');
    
    if (empty($whatsapp)) {
        return '';
    }
    
    // Если нужны параметры и их ещё нет в ссылке
    if ($with_params && strpos($whatsapp, '?') === false) {
        $whatsapp .= '?web=1&app_absent=1';
    }
    
    return $whatsapp;
}

/**
 * Получить ссылку на VK
 */
function mytheme_get_vk() {
    return get_theme_mod('mytheme_vk', '');
}

/**
 * Получить адрес
 */
function mytheme_get_address() {
    return get_theme_mod('mytheme_address', '');
}

/**
 * Получить полный адрес
 */
function mytheme_get_address_full() {
    return get_theme_mod('mytheme_address_full', '');
}

/**
 * Получить время работы
 */
function mytheme_get_job_time() {
    return get_theme_mod('mytheme_job_time', '');
}

/**
 * Получить ссылку на MAX
 */
function mytheme_get_max() {
    return get_theme_mod('mytheme_max', '');
}

/**
 * Получить ссылку на Instagram
 */
function mytheme_get_instagram() {
    return get_theme_mod('mytheme_instagram', '');
}

/**
 * Получить дополнительные телефоны из повторителя
 */
function mytheme_get_phones_extra() {
    $phones_json = get_theme_mod('mytheme_phones_extra_json', '');
    $phones = json_decode($phones_json, true);
    return is_array($phones) ? $phones : array();
}

/**
 * Получить дополнительные email из повторителя
 */
function mytheme_get_emails_extra() {
    $emails_json = get_theme_mod('mytheme_emails_extra_json', '');
    $emails = json_decode($emails_json, true);
    return is_array($emails) ? $emails : array();
}
/*** ДЕЛАЕМ ВСЕ ЧТО СВЯЗАНО С КОНТАКТАМИ ***/

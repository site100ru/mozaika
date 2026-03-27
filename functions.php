<?php
// INCLUDE STYLES
add_action('wp_enqueue_scripts', function() {
    // 1. Bootstrap загружается первым (база)
    wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css');
    
    // 2. style.css загружается после Bootstrap и переопределяет его
    wp_enqueue_style('style-css', get_stylesheet_uri(), array('bootstrap-css'));
});
// END INCLUDE STYLES




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

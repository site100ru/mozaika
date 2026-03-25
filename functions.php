<?php
// Подключаем стили
add_action('wp_enqueue_scripts', function() {
    // 1. Bootstrap загружается первым (база)
    wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css');
    
    // 2. style.css загружается после Bootstrap и переопределяет его
    wp_enqueue_style('style-css', get_stylesheet_uri(), array('bootstrap-css'));
});




/*
 Автоматическое обновление родительской темы из GitHub Releases
 */

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
<?php
/**
 * Автоматическое обновление родительской темы из GitHub Releases
 * Вставьте этот код в functions.php вашей родительской темы
 */

add_filter( 'pre_set_site_transient_update_themes', 'check_parent_theme_for_updates', 10, 1 );

function check_parent_theme_for_updates( $transient ) {
    // Проверяем, что объект транзиента существует
    if ( empty( $transient ) || ! is_object( $transient ) ) {
        return $transient;
    }

    // Данные о текущей теме
    $theme_stylesheet = get_template();           // папка родительской темы
    $theme_data       = wp_get_theme( $theme_stylesheet );
    $current_version  = $theme_data->get( 'Version' );

    // Данные о репозитории GitHub (ИЗМЕНИТЕ НА СВОИ)
    $github_username = 'site100ru';             // например, 'site100ru'
    $github_repo     = 'mozaika';         // например, 'dekorsever-parent'

    // GitHub API URL для получения последнего релиза
    $github_api_url = "https://api.github.com/repos/{$github_username}/{$github_repo}/releases/latest";

    // Получаем данные о последнем релизе
    $response = wp_remote_get( $github_api_url, array(
        'headers' => array(
            'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
        ),
        'timeout' => 10,
    ) );

    // Проверяем на ошибки HTTP-запроса
    if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
        return $transient;
    }

    $release_data = json_decode( wp_remote_retrieve_body( $response ), true );

    // Проверяем, что в ответе есть все нужные поля
    if ( empty( $release_data['tag_name'] ) || empty( $release_data['assets'][0]['browser_download_url'] ) ) {
        return $transient;
    }

    $latest_version = ltrim( $release_data['tag_name'], 'v' ); // убираем 'v' из тега, если он есть
    $download_url   = $release_data['assets'][0]['browser_download_url'];

    // Сравниваем версии
    if ( version_compare( $latest_version, $current_version, '>' ) ) {
        $transient->response[ $theme_stylesheet ] = array(
            'theme'       => $theme_stylesheet,
            'new_version' => $latest_version,
            'url'         => "https://github.com/{$github_username}/{$github_repo}",
            'package'     => $download_url,
        );
    }

    return $transient;
}
<?php

	session_start();
	
	if ( isset( $_SESSION['win'] ) ) {
		unset( $_SESSION['win'] );
		$_SESSION['display'] = "block";
	} else { $_SESSION['display'] = "none"; }

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<!-- Meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo_description(); ?>" />
	<meta property="og:description" content="<?php echo_description(); ?>" />
	<meta name="keywords" content="<?php echo wp_get_document_title(); ?>" />
	<meta property="og:locale" content="ru_RU" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	<meta property="og:title" content="<?php echo wp_get_document_title(); ?>" />
	
	<!-- Quiz CSS -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/quiz.css">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/ico/favicon.jpg" type="image/x-icon" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900">
	
	<?php wp_head(); ?>
	
	<title><?php echo wp_get_document_title(); ?></title>
</head>
<body>

<?php
$template=service('template');
$user=service('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?= base_url(); ?>">
	<meta charset="UTF-8">
	<?php echo $template->metadata() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- new -->

    <link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/fonts/css/all.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/lib/bootstrap.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/lib/flickity.min.css'); ?>">
    <link rel='stylesheet' type="text/css" href="<?php echo theme_url('assets/css/lib/magnific-popup.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/lib/owl.carousel.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/lib/slick.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/lib/aos.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/navbar.css'); ?>">

    <!-- CSS TEMPLATE STYLES -->
    <link rel="stylesheet" href="<?php echo theme_url('assets/css/main.css?v=1'); ?>">
    <link rel="stylesheet" href="<?php echo theme_url('assets/css/stylesheet.css'); ?>">
    <link rel="stylesheet" href="<?php echo theme_url('assets/css/responsive.css'); ?>">

    <!-- MODERNIZR LIBRARY -->
    <script src="<?php echo theme_url('assets/js/modernizr-custom.js'); ?>"></script>

	<script type="text/javascript">
		var BASE_URL = '<?php echo base_url(); ?>';
		var THEME_URL = '<?php echo theme_url(); ?>';
	</script>

	<?php echo $template->stylesheets(); ?>

</head>

<body class="animsitions">

<!-- PRELOADER START -->
<?php 
//print_r($template);
if(isset($header) && $header){?>
<div id="loader-wrapper">
    <div class="loader">
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
    </div>
</div>
<!-- PRELOADER END -->

<!-- Header -->
<header>

    <!-- NAV START -->
    <nav class="navbar navbar-expand-lg navbar-dark py-2">
        <div class="container-fluid">
            <a href="index.php" class="ml-5 navbar-brand"><img src="images/logos/govt-if-logo.svg" class="img-fluid" alt=""></a>
            <a href="index.php" class="col">
				<h4 class="text-center">
					<span class="tagline1">Special Programme for Promotion of Integrated Farming</span><br>
					<span class="tagline2">An initiative of Dept. of Aggriculture & FE Government of Odisha</span>
				</h4>
                <!--<img src="images/logos/sppif-header.svg" class="w-85" alt="">-->
            </a>
            <div class="mr-5"><img src="images/logos/wassan-ncds-logo.svg" class="img-fluid"></div>
            <button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#main-nav">
                <span class="menu-icon-bar"></span>
                <span class="menu-icon-bar"></span>
                <span class="menu-icon-bar"></span>
            </button>
        </div>
    </nav>

    <div class="navbar navbar-expand-lg">
        <div id="main-nav" class="collapse navbar-collapse">
            <?=$menu?>
        </div>
    </div>
    <!-- NAV END -->

</header>
<?}?>
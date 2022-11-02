<?php 
$template=service('template');
$user=service('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?= base_url() ?>">
	<meta charset="UTF-8">
	<?php echo $template->metadata() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="<?php echo theme_url('assets/images/icons/favicon.png'); ?>"/>
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/fonts/linearicons-v1.0.0/icon-font.min.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/animate/animate.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/css-hamburgers/hamburgers.min.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/animsition/css/animsition.min.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/select2/select2.min.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/daterangepicker/daterangepicker.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/slick/slick.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/lightbox2/css/lightbox.min.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/perfect-scrollbar/perfect-scrollbar.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/revolution/css/layers.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/revolution/css/navigation.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/vendor/revolution/css/settings.css'); ?>">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/util.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/css/main.css?v=1'); ?>">

	<script type="text/javascript">
		var BASE_URL = '<?php echo base_url(); ?>';
		var THEME_URL = '<?php echo theme_url(); ?>';
	</script>

	<?php echo $template->stylesheets() ?>
</head>
<body class="animsitions">

<!-- Header -->
<header>
	<!-- Header desktop -->
	<div class="wrap-menu-desktop d-none d-md-flex">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-1">
					<a href="<?php echo base_url(); ?>" class="sppif-logo ml-auto">
					<img src="images/sppif-logo.jpg" class="img-fluid" alt="IMG-LOGO"></a>
				</div>
				<div class="col-sm-6">
					<div class="logo-wrapper d-flex h-100">
					<a href="<?php echo base_url(); ?>" class="align-self-center">
					<h2>Special Programme for Promotion of Integrated Farming</h2>
					<!--<img src="images/sppif-text-logo.png" class="img-fluid" alt="IMG-LOGO">-->
					</a>
					</div>
				</div>
				<div class="col-sm-2 ml-auto">
					<div class="other-logo">
						<img src="images/other-logos.png" alt="IMG-LOGO" class="img-fluid">
					</div>
				</div>

			</div>
			<div class="row bg-menu">
				<ul class="main-menu">
					<li class="active-menu">
						<a href="<?php echo base_url(); ?>">Home</a>
					</li>

					<li>
						<a href="about">About</a>
						<ul class="sub-menu">
							<li><a href="background">Background</a></li>
							<li><a href="project-brief">Project Brief</a></li>
						</ul>
					</li>

					<li>
						<a href="program-component">Program Component</a>
						<ul class="sub-menu">
							<li><a href="agriculture">Agriculture</a></li>
							<li><a href="horticulture">Horticulture</a></li>
							<li>
								<a href="livestock">Livestock</a>
								<ul class="sub-menu">
									<li><a href="poultry">Poultry</a></li>
									<li><a href="goatry">Goatry</a></li>
								</ul>
							</li>
							<li><a href="fishery">Fishery</a></li>
						</ul>
					</li>

					<li>
						<a href="resources">Resources</a>
						<ul class="sub-menu">
							<li><a href="guidelines">Guidelines</a></li>
							<li><a href="publications">Publications</a></li>
							<li><a href="case-studies">Case studies</a></li>
							<li><a href="success-stories">Success stories</a></li>
						</ul>
					</li>

<!--                            <li><a href="http://app.integratedfarming.in/home/dashboard" target="_blank">Dashboard</a></li>-->
					<li><a href="eoi">EoI</a></li>
					<li>
						<a href="contact">Contact</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<!-- Header Mobile -->
	<div class="wrap-header-mobile">

		<!-- Logo moblie -->
		<a href="<?php echo base_url(); ?>" class="govt-logo"><img src="images/odishalogo.png" alt="IMG-LOGO"></a>
		<div class="logo-mobile">
			<a href="<?php echo base_url(); ?>"><img src="images/logo.png" alt="IMG-LOGO"></a>
		</div>

		<!-- Icon header -->
		<div class="wrap-icon-header flex-w flex-r-m h-full wrap-menu-click m-r-15">
			<div class="h-full flex-m">
				<div class="icon-header-item flex-c-m trans-04 js-show-modal-search">
					<img src="images/icons/icon-search.png" alt="SEARCH">
				</div>
			</div>
		</div>

		<!-- Button show menu -->
		<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
		<span class="hamburger-box">
			<span class="hamburger-inner"></span>
		</span>
		</div>
	</div>


	<!-- Menu Mobile -->
	<div class="menu-mobile">
		<ul class="main-menu-m">
			<li>
				<a href="index.html">Home</a>
				<span class="arrow-main-menu-m">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</span>
			</li>

			<li>
				<a href="about">About</a>
				<ul class="sub-menu-m">
					<li><a href="background">Background</a></li>
					<li><a href="project-brief">Project Brief</a></li>
				</ul>

				<span class="arrow-main-menu-m">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</span>
			</li>

			<li>
				<a href="program-component">Program Component</a>
				<ul class="sub-menu-m">
					<li><a href="agriculture">Agriculture</a></li>
					<li><a href="horticulture">Horticulture</a></li>
					<li>
						<a href="livestock">Livestock</a>
						<ul class="sub-menu-m">
							<li><a href="poultry">Poultry</a></li>
							<li><a href="goatry">Goatry</a></li>
						</ul>
					</li>
					<li><a href="fishery">Fishery</a></li>
				</ul>

				<span class="arrow-main-menu-m">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</span>
			</li>

			<li>
				<a href="resources">Resources</a>
				<ul class="sub-menu-m">
					<li><a href="case-studies">Case studies</a></li>
					<li><a href="success-stories">Success stories</a></li>
				</ul>

				<span class="arrow-main-menu-m">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</span>
			</li>

			<li>
				<a href="contact">Contact</a>

				<span class="arrow-main-menu-m">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</span>
			</li>
		</ul>
	</div>

	<!-- Modal Search -->
	<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
		<button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
			<span class="lnr lnr-cross"></span>
		</button>

		<div class="container-search-header">
			<form class="wrap-search-header flex-w">
				<button class="flex-c-m trans-04">
					<span class="lnr lnr-magnifier"></span>
				</button>
				<input class="plh1" type="text" name="search" placeholder="Search...">
			</form>
		</div>
	</div>
</header>

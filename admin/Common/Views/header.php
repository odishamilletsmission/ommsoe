<?php 
$template=service('template');
$user=service('user');
?>
<!doctype html>
<html lang="en" class="no-focus">
	<head>
		<base href="<?=base_url()?>">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="WASSAN IT TEAM">
		<meta name="robots" content="noindex, nofollow">	
		<?php echo $template->metadata() ?>
		
		<!-- Icons -->
		<!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
		<link rel="shortcut icon" href="<?=theme_url('assets/media/favicons/favicon.png');?>">
		<link rel="icon" type="image/png" sizes="192x192" href="<?=theme_url('assets/media/favicons/favicon-192x192.png');?>">
		<link rel="apple-touch-icon" sizes="180x180" href="<?=theme_url('assets/media/favicons/apple-touch-icon-180x180.png');?>">
		<!-- END Icons -->

		<!-- Fonts and Codebase framework -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
		<link rel="stylesheet" id="css-main" href="<?php echo theme_url('assets/css/codebase.min.css');  ?>">
		<link rel="stylesheet" id="css-main" href="<?php echo theme_url('assets/css/custom.css');  ?>">
		<!-- Controller Defined Stylesheets -->
		<?php echo $template->stylesheets() ?>
        <script src="<?=theme_url('assets/js/codebase.core.min.js');?>"></script>
		<script type="text/javascript">
			var BASE_URL = '<?php echo base_url(); ?>';
			var ADMIN_URL = '<?php echo admin_url(); ?>';
			var THEME_URL = '<?php echo theme_url(); ?>';
		</script>
		<!-- Controller Defined JS Files -->
		<?php echo $template->javascripts() ?>

	</head>
	<body>
		
		<?php
		if ($user->isLogged() && $header){	
		?>
           
		<div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed">
			<nav id="sidebar">
                <!-- Sidebar Content -->
                <div class="sidebar-content">
                    <!-- Side Header -->
                    <div class="content-header content-header-fullrow px-15">
                        <!-- Mini Mode -->
                        <div class="content-header-section sidebar-mini-visible-b">
                            <!-- Logo -->
                            <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                            </span>
                            <!-- END Logo -->
                        </div>
                        <!-- END Mini Mode -->

                        <!-- Normal Mode -->
                        <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                            <!-- Close Sidebar, Visible only on mobile screens -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                            <!-- END Close Sidebar -->

                            <!-- Logo -->
                            <div class="content-header-item">
                                <a href="<?=admin_url()?>" class="link-effect font-w700">
									<?php if ($logo) { ?>
									<img width="100%" height="40px" src="<?php echo $logo; ?>" title="<?php echo $site_name; ?>" alt="<?php echo $site_name; ?>"  />
									<?php } ?>
									<i class="si si-fire text-primary"></i>
									<span class="font-size-xl text-dual-primary-dark">OMM Portal</span><span class="font-size-xl text-primary"> 2.0</span>
                                </a>
								
                            </div>
                            <!-- END Logo -->
                        </div>
                        <!-- END Normal Mode -->
                    </div>
                    <!-- END Side Header -->

                    <!-- Side User -->
                    
                    <!-- END Side User -->

                    <!-- Side Navigation -->
                    <div class="content-side content-side-full">
                        <?=$menu?>
                        <?php if($user->getGroupId()==1){?>
                        <ul class="nav-main">
                            <li class="nav-main-heading"><span class="sidebar-mini-visible">DEV</span><span class="sidebar-mini-hidden">Developer Tools</span></li>
                            <li>
                                <a href="<?=admin_url('module')?>"><i class="si si-globe-alt"></i><span class="sidebar-mini-hide">HMVC Module</span></a>

                            </li>
                        </ul>
                        <?}?>
                    </div>
                    <!-- END Side Navigation -->
                </div>
                <!-- Sidebar Content -->
            </nav>
			
			<header id="page-header">
                <!-- Header Content -->
                <div class="content-header">
                    <!-- Left Section -->
                    <div class="content-header-section">
                        <!-- Toggle Sidebar -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                            <i class="fa fa-navicon"></i>
                        </button>
                        <!-- END Toggle Sidebar -->

                    </div>
                    <!-- END Left Section -->

                    <!-- Right Section -->
                    <div class="content-header-section">
                        <a href="<?=base_url();?>" target="_blank" class="btn btn-circle btn-dual-secondary" >
                            <i class="fa fa-globe"></i>
                        </a>
						<!-- User Dropdown -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block"><?=$name?></span>
                                <i class="fa fa-angle-down ml-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                <a class="dropdown-item" href="<?=site_url('profile')?>">
                                    <i class="si si-user mr-5"></i> Profile
                                </a>
                                
                                <div class="dropdown-divider"></div>

                                <!-- Toggle Side Overlay -->
                                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                                    <i class="si si-wrench mr-5"></i> Settings
                                </a>
                                <!-- END Side Overlay -->

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=$logout?>">
                                    <i class="si si-logout mr-5"></i> Sign Out
                                </a>
                            </div>
                        </div>
                        <?php if($relogin){?>
                        <a href="<?=admin_url('relogin')?>" class="btn btn-circle btn-dual-secondary">
                            <i class="fa fa-times text-danger"></i>
                        </a>
                        <?}?>
                        <!-- END User Dropdown -->
						
                    </div>
                    <!-- END Right Section -->
                </div>
                <!-- END Header Content -->

                <!-- Header Search -->
                <div id="page-header-search" class="overlay-header">
                    <div class="content-header content-header-fullrow">
                        <form action="be_pages_generic_search.html" method="post">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <!-- Close Search Section -->
                                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                    <button type="button" class="btn btn-secondary" data-toggle="layout" data-action="header_search_off">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <!-- END Close Search Section -->
                                </div>
                                <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Header Search -->

                <!-- Header Loader -->
                <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
                <div id="page-header-loader" class="overlay-header bg-primary">
                    <div class="content-header content-header-fullrow text-center">
                        <div class="content-header-item">
                            <i class="fa fa-sun-o fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
                <!-- END Header Loader -->
            </header>
            
			
		<?}else{?>
		<div id="page-container" class="main-content-boxed">
		<?}?>
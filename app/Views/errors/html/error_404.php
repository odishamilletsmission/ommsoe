<!doctype html>
<html lang="en" class="no-focus">
<head>
    <base href="<?=base_url()?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Error 404 | Integrated Farming</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="<?=base_url('themes/admin/assets/media/favicons/favicon.png');?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?=base_url('themes/admin/assets/media/favicons/favicon-192x192.png');?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url('themes/admin/assets/media/favicons/apple-touch-icon-180x180.png');?>">
    <!-- END Icons -->

    <!-- Stylesheets -->

    <!-- Fonts and Codebase framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
    <link rel="stylesheet" id="css-main" href="<?=base_url('themes/admin/assets/css/codebase.min.css');?>">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <!-- END Stylesheets -->
</head>
<body>

<div id="page-container" class="main-content-boxed">

    <!-- Main Container -->
    <main id="main-container">

        <!-- Page Content -->
        <div class="hero bg-white">
            <div class="hero-inner">
                <div class="content content-full">
                    <div class="py-30 text-center">
                        <div class="display-3 text-danger">
                            <i class="fa fa-warning"></i> 404
                        </div>
                        <h1 class="h2 font-w700 mt-30 mb-10">Oops.. You just found an error page..</h1>
                        <h2 class="h3 font-w400 text-muted mb-50">We are sorry but the page you are looking for was not found..</h2>
                        <a class="btn btn-hero btn-rounded btn-alt-secondary" href="<?=previous_url()?>">
                            <i class="fa fa-arrow-left mr-10"></i> Back to Website
                        </a>
                        <a class="btn btn-hero btn-rounded btn-alt-secondary" href="<?=base_url('admin')?>">
                            <i class="fa fa-arrow-left mr-10"></i> Back to Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->

    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<script src="assets/js/codebase.core.min.js"></script>

<script src="assets/js/codebase.app.min.js"></script>
</body>
</html>
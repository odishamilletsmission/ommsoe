<?php
$template = service('template');
?>
<main id="main-container">

        <!-- Page Content -->
        <div class="bg-body-dark bg-pattern" style="background-image: url('assets/media/various/bg-pattern-inverse.png');">
            <div class="row mx-0 justify-content-center">
                <div class="hero-static col-lg-6 col-xl-4">
                    <div class="content content-full overflow-hidden">
                        <!-- Header -->
                        <div class="py-30 text-center">
                            <a class="link-effect font-w700" href="<?=base_url()?>">
                                <i class="si si-graph"></i>
                                <span class="font-size-xl text-primary-dark">Integrated</span><span class="font-size-xl">Farming</span>
                            </a>
                            <h1 class="h4 font-w700 mt-30 mb-10">Welcome</h1>
                            <h2 class="h5 font-w400 text-muted mb-0">It’s a great day today!</h2>
                        </div>
                        <!-- END Header -->

                        <!-- Sign In Form -->
                        <!-- jQuery Validation functionality is initialized with .js-validation-signin class in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js -->
                        <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                        <?php echo form_open('login',array('class' => 'js-validation-signin', 'id' => 'form-signin','role'=>'form')); ?>
							<div class="block block-themed block-rounded block-shadow">
                                <div class="block-header bg-gd-dusk">
                                    <h3 class="block-title">Please Sign In</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-wrench"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="login-username">Username</label>
                                            <input type="text" class="form-control" placeholder="Username" id="login-username" name="username">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="login-password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                        </div>
                                    </div>
                                    <?php if($login_error) { ?>
                                    <div class="form-group row">
                                        <div class="col-12">
                                                <strong class="text-danger pull-left">Error: <?=$login_error?></strong>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-6 d-sm-flex align-items-center push">
                                            <div class="custom-control custom-checkbox mr-auto ml-0 mb-0">
                                                <input type="checkbox" class="custom-control-input" id="login-remember-me" name="login-remember-me">
                                                <label class="custom-control-label" for="login-remember-me">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-sm-right push">
                                            <button type="submit" class="btn btn-alt-primary">
                                                <i class="si si-login mr-10"></i> Sign In
                                            </button>
                                        </div>
                                    </div>
                                </div>
<!--                                <div class="block-content bg-body-light">-->
<!--                                    <div class="form-group text-center">-->
<!--                                        <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="op_auth_signup3.html">-->
<!--                                            <i class="fa fa-plus mr-5"></i> Create Account-->
<!--                                        </a>-->
<!--                                        <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="op_auth_reminder3.html">-->
<!--                                            <i class="fa fa-warning mr-5"></i> Forgot Password-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        <?php echo form_close(); ?>
                        <!-- END Sign In Form -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->

    </main>
   
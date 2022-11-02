<?php 
$template=service('template');
$user=service('user');
?>
<!-- FOOTER START -->
<?php if($header){?>
<footer class="site-footer mt-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 footer-col left-col">
                <img src="images/logos/sppif-header.svg" alt="" class="img-fluid mb-3">
                <p>The SPPIF is for enhancing production, income and household level food security & reducing climate risks will be the focus.</p>
                <div class="footer-social">
                    <ul>
                        <li><a href="index.html#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="index.html#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="index.html#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="index.html#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 footer-col spacing-m-center">
                <h5>Quick Links</h5>
                <ul class="quick-links left-layer">
                    <li><a href="/">Home</a></li>
                    <li><a href="privacy.php">About</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <ul class="quick-links right-layer d-none">
                    <li><a href="faq.html">FAQ</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="gallery-grid.html">Gallery</a></li>
                    <li><a href="blog-grid.html">Blogs</a></li>
                </ul>
            </div>
            <div class="col-lg-3 footer-col">
                <h5>NEWSLETTER</h5>
                <p>Subscribe to our newsletter to receive
                    the latest news about our services.</p>
                <div class="newsletter">
                    <form action="index.html#" method="post" name="sign-up">
                        <input type="email" class="input" id="email" name="email" placeholder="Your email address"
                               required>
                        <input type="submit" class="button" id="submit" value="SIGN UP">
                    </form>
                </div>
            </div>
        </div>
        <hr class="footer">
        <div class="bottom-footer">
            <p>© 2021 DAFP, Odisha. All rights reserved.</p>
        </div>
    </div>
</footer>
<!-- FOOTER END -->

<!--SCROLL TOP START-->
<a href="index.html#0" class="cd-top">Top</a>
<!--SCROLL TOP START-->
<?}?>

<!-- JAVASCRIPTS -->
<script src="<?php echo theme_url('assets/js/lib/jquery-3.5.1.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/lib/bootstrap.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/lib/plugins.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/lib/nav.fixed.top.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/lib/contact.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/slider.js'); ?>"></script>
<!-- JAVASCRIPTS END -->

<?php echo $template->footer_javascript() ?>
<script src="<?php echo theme_url('assets/js/main.js'); ?>"></script>

</body>
</html>
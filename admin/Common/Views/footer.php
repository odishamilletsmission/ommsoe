<?php 
$template=service('template');
$user=service('user');
?>
<footer id="page-footer" class="opacity-0">
	<div class="content py-20 font-size-sm clearfix">
		<div class="float-right">
			Crafted with <i class="fa fa-heart text-pulse"></i> by <a class="font-w600" href="" target="_blank">WASSAN Odisha IT Team</a>
		</div>
	</div>
</footer>
</div>
<script src="<?=theme_url('assets/js/codebase.app.js');?>"></script>
<script src="<?=theme_url('assets/js/common.js');?>"></script>
<script src="//cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<?php echo $template->footer_javascript() ?>

<script>
    show = '<?=$show_old_portal?>';
    $(function () {
        if(show==false){
            $('.old-portal-login').hide();
        }
    });
</script>
</body>
</html>
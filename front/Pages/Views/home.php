<?php if($banners){?>
<section class="home-banner">
	<div class="home-slider">
		<div class="hero-slider" data-carousel="">
			<?php foreach($banners as $banner){?>
			<div class="carousel-cell" style="background-image:url(<?=$banner['image']?>);"></div>
			<?}?>
		</div>
	</div>
</section>
<?}?>
<?php echo $content; ?>
<div class="container-fluid mt-5 mb-3">
	<div class="section-title">
		<h2>Gallery</h2>
		<h3>Our Works</h3>
	</div>
	<?php if($sliders){?>
		<div class="grid grid-four-col kc_image_gallery" id="kehl-grid">
			<div class="grid-sizer">&nbsp;</div>
			<?php foreach($sliders as $slider){?>
			<div class="grid-box fruits">
				<a class="image-popup-vertical-fit kc-image-link" href="<?=$slider['image']?>">
					<img alt="" src="<?=$slider['image']?>" /> 
				</a>
			</div>
			<?}?>
		</div>
	<?}?>
</div>
<?php js_start(); ?>
<script>
    $(function () {
        /*$('.kc_image_gallery').lightGallery({
            selector: '.kc-image-link',
            thumbnail:false,
        });*/
    })
    
</script>
<?php js_end(); ?>



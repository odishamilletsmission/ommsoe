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
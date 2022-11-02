<h2>Slider</h2>
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
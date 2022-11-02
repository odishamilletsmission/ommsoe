<?php
/**
 * Created by PhpStorm.
 * User: Niranjan
 * Date: 07-02-2022
 * Time: 05:36 PM
 */
global $template;

$template = service('template');

add_shortcode('proceeding', function($atts, $content){
    return view_cell('Front\Proceeding\Controllers\Proceeding::getShortcode');
});

add_shortcode('gallery', function($atts, $content){
    return view_cell('Front\Banner\Controllers\Banner::getGallery',$atts);
});

add_shortcode('slider', function($atts, $content){
    return view_cell('Front\Banner\Controllers\Banner::getSlider',$atts);
});
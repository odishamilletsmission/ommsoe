/* ==================================================
  * Template: Farmey
  * Version:  1
  * Date:     April 27 2021
===================================================== */

/*==================================================*/
/* [Table of contents] */
/*==================================================*/

/*	
    1. PRELOADER
    2. SCROLL TOP
    3. COUNTER
    4. MAGNIFIC POPUP GALLERY
    5. YOUTUBE POPUP
    6. FILTER GALLERY
    7. MASONRY GALLERY
    8. FAQ ACCORDION
    9. ANIMATION
    10. VIDEO POPUP
    11. SLIDER
*/

/* ===============================================
    Functions Call
=============================================== */

jQuery(document).ready(function () {
    "use strict";

    // here all ready functions

    loader();
    scroll_top();
    magnific_popup();
    accordion();

});

/* ===============================================
    1. PRELOADER
=============================================== */
function loader() {
    "use strict";
    setTimeout(function () {
        $('#loader-wrapper').fadeOut();
    }, 1000);
};

function readmore(obj) {
	
	var parentdiv=$(obj).parent();
	var moreText = $(parentdiv).find(".more");
	if($(parentdiv).find(".expand").length){
		$(moreText).removeClass("expand");
		$(moreText).css("display","none");
		$(obj).html("Read more");
	}else{
		$(moreText).addClass("expand");
		$(moreText).css("display","inline");
		$(obj).html("Read less");
	}
  
} 

/* ===============================================
    2. SCROLL TOP
=============================================== */
function scroll_top() {
    "use strict";
    var offset = 300,
        offset_opacity = 1200,
        scroll_top_duration = 700,
        $back_to_top = $('.cd-top');

    $(window).scroll(function () {
        ($(this).scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible'): $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if ($(this).scrollTop() > offset_opacity) {
            $back_to_top.addClass('cd-fade-out');
        }
    });

    $back_to_top.on('click', function (event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0,
        }, scroll_top_duration);
    });

};

/* ===============================================
    3. COUNTER
=============================================== */
$('.counter').each(function () {
    var $this = $(this),
        countTo = $this.attr('data-count');
    $({
        countNum: $this.text()
    }).animate({
            countNum: countTo
        },

        {
            duration: 8000,
            easing: 'linear',
            step: function () {
                $this.text(Math.floor(this.countNum));
            },
            complete: function () {
                $this.text(this.countNum);
                //alert('finished');
            }

        });
});

/* ===============================================
    4. MAGNIFIC POPUP GALLERY
=============================================== */
function magnific_popup() {
    $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom',
        gallery: {
            enabled: true
        },
        zoom: {
            enabled: true,

            duration: 300, // duration of the effect, in milliseconds
            easing: 'ease-in-out', // CSS transition easing function

            opener: function (openerElement) {

                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });
};

/* ===============================================
    5. YOUTUBE POPUP
=============================================== */
function video_popup() {
    var $btnLoadMore = $(
        '<div class="btn-wrapper text-center"><a href="#" class="btn load-more">Load More</a></div>'
    );
    var items = $(".youtube-popup[data-listnum]");
    var count = items.length;
    var slice = 2;
    var current = 0;

    if (items.length > slice) {
        //bind load more event
        $btnLoadMore.on("click", function (e) {
            e.preventDefault();
            loadMoreNews();
        });
        //append load more button
        items.closest(".salvattore-grid").after($btnLoadMore);
    }

    function getItem(listnum) {
        return items
            .filter(function (index) {
                if ($(this).attr("data-listnum") == listnum) {
                    return true;
                }
            });
    }

    function loadMoreNews() {
        var end = current + slice;
        if (end >= count) {
            end = count;
            $btnLoadMore.hide();
        }
        while (current < end) {
            var listnum = current + 1; //data-listnum : 1-based
            var item = getItem(listnum);
            if (item) {
                item.fadeIn();
            }
            current++;
        }
    }

    //youtube popup
    $(".popup-youtube").magnificPopup({
        type: "iframe",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
        iframe: {
            markup: '<div class="mfp-iframe-scaler">' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                "</div>",
            patterns: {
                youtube: {
                    index: "youtube.com/",
                    id: "v=",
                    src: "//www.youtube.com/embed/%id%?autoplay=1&rel=0&showinfo=0"
                }
            },
            srcAction: "iframe_src"
        }
    });

    //init load
    loadMoreNews();
};

/* ===============================================
    6. FILTER GALLERY
=============================================== */
$(function () {
    var $margin = $("#kehl-grid").isotope({
        itemSelector: ".grid-box",
        // Different transition duration
        transitionDuration: "0.5s"
    });

    // on filter button click
    $(".filter-container li").click(function (e) {
        var $this = $(this);

        // Prevent default behaviour
        e.preventDefault();
        $('.filter li').removeClass('active');
        $this.addClass('active');

        // Get the filter data attribute from the button
        var $filter = $this.attr("data-filter");

        // filter
        $margin.isotope({
            filter: $filter
        });
    });
});

/* ===============================================
    7. MASONRY GALLERY
=============================================== */
var $grid = $('.grid').imagesLoaded(function () {
    $grid.masonry({
        itemSelector: '.grid-box',
        percentPosition: true,
        columnWidth: '.grid-sizer'
    });
});

/* ===============================================
    8. FAQ ACCORDION
=============================================== */
function accordion() {};
$('.accordion > li:eq(0) a').addClass('active').next().slideDown();

$('.accordion a').click(function (j) {
    var dropDown = $(this).closest('li').find('p');

    $(this).closest('.accordion').find('p').not(dropDown).slideUp();

    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
    } else {
        $(this).closest('.accordion').find('a.active').removeClass('active');
        $(this).addClass('active');
    }

    dropDown.stop(false, true).slideToggle();

    j.preventDefault();
});
(jQuery)
/* ===============================================
    9. ANIMATION
=============================================== */
AOS.init({
    duration: 1200,
})

/* ===============================================
    10. VIDEO POPUP
=============================================== */
$('.popup-youtube, .popup-vimeo').magnificPopup({
    type: 'iframe',
    disableOn: 700,
    type: 'iframe',
    mainClass: 'mfp-fade',
    removalDelay: 160,
    preloader: false,
    fixedContentPos: false,
    markup: '<div class="mfp-iframe-scaler">' +
        '<div class="mfp-close"></div>' +
        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
        '</div>', // HTML markup of popup, `mfp-close` will be replaced by the close button
    iframe: {
        patterns: {
            youtube: {
                index: 'youtube.com/',
                id: 'v=',
                src: 'https://www.youtube.com/embed/%id%?autoplay=1'
            }
        }
    }
});

/* ===============================================
    11. CLIENT CAROUSEL
=============================================== */
$('.clients-carousel .owl-carousel').owlCarousel({
    stagePadding: 0,
    loop: true,
    dots: true,
    margin: 10,
    nav: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    navContainer: '.clients-carousel .custom-nav',
    responsive: {
        0: {
            items: 2
        },
        576 : {
            items: 3
        },
        767 : {
         items: 4
        },
        1200: {
            items: 5
        }
    }
});

/* ===============================================
    12. TEAM CAROUSEL
=============================================== */
$('.team-carousel .owl-carousel').owlCarousel({
    autoplay: false,
    autoplayTimeout: 2500,
    autoplayHoverPause: false,
    loop: true,
    dots: true,
    margin: 0,
    nav: true,
    center: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    navContainer: '.team-carousel .custom-nav',
    responsive: {
        0: {
            items: 1
        },
        650: {
            items: 2
        },
        1000: {
            items: 3
        }
    }
});

/* ===============================================
    13. BLOG NEWS CAROUSEL
=============================================== */
$('.news-carousel .owl-carousel').owlCarousel({
    stagePadding: 0,
    loop: true,
    dots: true,
    margin: 0,
    nav: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    navContainer: '.blog-news-carousel .custom-nav',
    responsive: {
        0: {
            items: 1
        },
        767: {
            items: 2
        },
        1200: {
            items: 3
        }
    }
});

/* ===============================================
    14. TESTIMONIAL CAROUSEL
=============================================== */

$('.testimonials-carousel .owl-carousel').owlCarousel({
    stagePadding: 0,
    loop: true,
    dots: true,
    margin: 0,
    nav: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    navContainer: '.testimonials-carousel .custom-nav',
    responsive: {
        0: {
            items: 1
        }
    }
});
$('.tt-carousel .owl-carousel').owlCarousel({
    stagePadding: 0,
    loop: true,
    dots: true,
    margin: 0,
    nav: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    navContainer: '.testimonials-carousel .custom-nav',
    responsive: {
        0: {
            items: 3
        }
    }
});

/* ===============================================
    15. SERVICES CAROUSEL
=============================================== */

$('.services-carousel .owl-carousel').owlCarousel({
    stagePadding: 0,
    loop: true,
    dots: true,
    margin: 0,
    nav: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    navContainer: '.testimonials-carousel .custom-nav',
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1200: {
            items: 3
        }
    }
});

particlesJS('particles-js', {
        "particles": {
            "number": {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#ffffff"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 5,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 400,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true,
        "config_demo": {
            "hide_card": false,
            "background_color": "#b61924",
            "background_image": "",
            "background_position": "50% 50%",
            "background_repeat": "no-repeat",
            "background_size": "cover"
        }
    }

);

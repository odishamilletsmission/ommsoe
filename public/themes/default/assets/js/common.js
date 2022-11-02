/**
 * Created by Rakesh on 11-12-2018.
 */
// Element Attribute Helper
function attrDefault($el, data_var, default_val)
{
    if(typeof $el.data(data_var) != 'undefined')
    {
        return $el.data(data_var);
    }

    return default_val;
}

// Count Anything
$("[data-from][data-to]").each(function(i, el)
{
    var $el = $(el),
        sm = scrollMonitor.create(el);

    sm.fullyEnterViewport(function()
    {
        var opts = {
                useEasing: 		attrDefault($el, 'easing', true),
                useGrouping:	attrDefault($el, 'grouping', true),
                separator: 		attrDefault($el, 'separator', ','),
                decimal: 		attrDefault($el, 'decimal', '.'),
                prefix: 		attrDefault($el, 'prefix', ''),
                suffix:			attrDefault($el, 'suffix', ''),
            },
            $count		= attrDefault($el, 'count', 'this') == 'this' ? $el : $el.find($el.data('count')),
            from        = attrDefault($el, 'from', 0),
            to          = attrDefault($el, 'to', 100),
            duration    = attrDefault($el, 'duration', 2.5),
            delay       = attrDefault($el, 'delay', 0),
            decimals	= new String(to).match(/\.([0-9]+)/) ? new String(to).match(/\.([0-9]+)$/)[1].length : 0,
            counter 	= new countUp($count.get(0), from, to, decimals, duration, opts);

        setTimeout(function(){ counter.start(); }, delay * 1000);

        sm.destroy();
    });
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
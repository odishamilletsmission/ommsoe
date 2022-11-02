<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| AIO ADMIN Version
|--------------------------------------------------------------------------
|
| Defines the version number for aio admin
|
*/
define('AIOADMIN_VERSION', '2.0.0');


define('DIR_PUBLIC',  ROOTPATH . '/public/');
define('DIR_UPLOAD',   ROOTPATH . '/public/uploads/');
define('DIR_TEMPLATE',   ROOTPATH . '/public/templates/');
define('IMAGE_CACHE',   'image-cache');
define('DIR_ADMIN_MODULE', ROOTPATH . '/admin/');



$packages = array(
    'visualeditor' => array(
        'javascript' => array(
            'plugins/vvvebjs/js/jquery.hotkeys.js',
            'plugins/vvvebjs/libs/builder/builder.js',
            'plugins/vvvebjs/libs/builder/undo.js',
            'plugins/vvvebjs/libs/builder/inputs.js',
            'plugins/VvvebJs/libs/media/media.js',
            'plugins/VvvebJs/libs/media/ccsearch.js',
            'plugins/VvvebJs/libs/builder/plugin-media.js',
            'plugins/vvvebjs/libs/builder/components-bootstrap4.js',
            'plugins/vvvebjs/libs/builder/components-widgets.js',
        ),
        'stylesheet' => array(
            'plugins/vvvebjs/css/editor.css',
            'plugins/VvvebJs/libs/media/media.css',

        ),
    ),
    'superfish' => array(
        'javascript' => array(
            'storage/plugins/superfish/js/superfish.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/superfish/css/superfish.css',
        ),
    ),
    'particle' => array(
        'javascript' => array(
            'plugins/particles/particles.min.js',
        )
    ),
    'tablednd' => array(
        'javascript' => array(
            'plugins/TableDnD/js/jquery.tablednd.js',
        ),
        'stylesheet' => array(
            'plugins/TableDnD/tablednd.css',
        ),
    ),
    'flatpickr' => array(
        'javascript' => array(
            'plugins/flatpickr/flatpickr.min.js',
        ),
        'stylesheet' => array(
            'plugins/flatpickr/flatpickr.min.css',
        ),
    ),
    'datetimepicker' => array(
        'javascript' => array(
            'storage/plugins/datetimepicker/moment.js',
            'storage/plugins/datetimepicker/bootstrap-datetimepicker.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/datetimepicker/bootstrap-datetimepicker.min.css',

        ),
    ),
    'timepicker' => array(
        'javascript' => array(
            'storage/plugins/datetimepicker/moment.js',
            'storage/plugins/timepicker/bootstrap-timepicker.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/timepicker/bootstrap-timepicker.min.css',

        ),
    ),
    'datepicker' => array(
        'javascript' => array(
            'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        ),
        'stylesheet' => array(
            'plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
        ),
    ),
    'select2' => array(
        'javascript' => array(
            'plugins/select2/js/select2.min.js',
        ),
        'stylesheet' => array(
            'plugins/select2/css/select2.min.css',
        ),
    ),
    'ladda' => array(
        'javascript' => array(
            'storage/plugins/Ladda/dist/spin.min.js',
            'storage/plugins/Ladda/dist/ladda.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/Ladda/dist/ladda.min.css',
        ),
    ),
    'sweetalert' => array(
        'javascript' => array(
            'plugins/sweetalert2/sweetalert2.min.js',
        ),
        'stylesheet' => array(
            'plugins/sweetalert2/sweetalert2.min.css',
        ),
    ),
    'elevatezoom' => array(
        'javascript' => array(
            'storage/plugins/elevatezoom/jquery.elevatezoom.js',
        ),

    ),
    'jsmediatags' => array(
        'javascript' => array(
            'storage/plugins/jsmediatags/dist/jsmediatags.min.js',
        ),
    ),
    'nprogress' => array(
        'javascript' => array(
            'storage/plugins/nprogress/nprogress.js',
        ),
        'stylesheet' => array(
            'storage/plugins/nprogress/nprogress.css',
        ),
    ),
    'colorpicker' => array(
        'javascript' => array(
            'storage/plugins/colorpicker/bootstrap-colorpicker.js',
        ),
        'stylesheet' => array(
            'storage/plugins/colorpicker/colorpicker.css',
        ),
    ),
    'tags' => array(
        'javascript' => array(
            'storage/plugins/tagsinput/jquery.tagsinput.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/tagsinput/jquery.tagsinput.css',
        ),
    ),

    'notification' => array(
        'javascript' => array(
            'storage/plugins/notifyjs/dist/notify.min.js',
            'storage/plugins/notifications/notify-metro.js',
            'storage/plugins/notifications/notifications.js',
        ),
        'stylesheet' => array(
            'storage/plugins/notifications/notification.css',
        ),
    ),

    'toast' => array(
        'javascript' => array(
            'storage/plugins/iziToast/dist/js/iziToast.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/iziToast/dist/css/iziToast.min.css',
        ),
    ),

    'selectize' => array(
        'javascript' => array(
            'storage/plugins/selectize/dist/js/standalone/selectize.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/selectize/dist/css/selectize.css',
        ),
    ),

    'jqueryform' => array(
        'javascript' => array(
            'storage/plugins/jquery_form/jquery.form.js',
        )
    ),

    'mediaelement' => array(
        'javascript' => array(
            'storage/plugins/mediaelement/build/mediaelement-and-player.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/mediaelement/build/mediaelementplayer.min.css',
        ),
    ),

    'jplayer' => array(
        'javascript' => array(
            'storage/plugins/jPlayer/dist/jplayer/jquery.jplayer.min.js',
        )
    ),

    'jqueryupload' => array(
        'javascript' => array(
            'storage/plugins/JavaScript-Load-Image/js/load-image.all.min.js',
            'storage/plugins/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.iframe-transport.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.fileupload.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.fileupload-process.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.fileupload-image.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.fileupload-audio.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.fileupload-video.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.fileupload-validate.js',
            'storage/plugins/jQuery-File-Upload/js/jquery.fileupload-ui.js',
        ),
        'stylesheet' => array(
            'storage/plugins/jQuery-File-Upload/css/jquery.fileupload.css',
            /*'storage/plugins/jQuery-File-Upload/css/jquery.fileupload-ui.css',*/
        ),
    ),

    'pace' => array(
        'javascript' => array(
            'storage/plugins/pace/pace.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/pace/pace.css',
        ),
    ),
    'ckeditor' => array(
        'javascript' => array(
            'plugins/ckeditor/ckeditor.js',
            'plugins/ckeditor/adapters/jquery.js',
        ),
    ),
    'ckfinder' => array(
        'javascript' => array(
            'plugins/ckfinder/ckfinder.js',
        ),
    ),
    'colorbox' => array(
        'javascript' => array(
            'plugins/colorbox/jquery.colorbox.js',
        ),
        'stylesheet' => array(
            'plugins/colorbox/colorbox.css',
        ),
    ),
    'datatable' => array(
        'javascript' => array(
            'plugins/datatables/datatables.min.js',
        ),
        'stylesheet' => array(
            'plugins/datatables/datatables.min.css',
        ),
    ),
    'justifiedgallery'=>array(
        'javascript' => array(
            'plugins/justifiedGallery/jquery.justifiedGallery.min.js',
        ),
        'stylesheet' => array(
            'plugins/justifiedGallery/justifiedGallery.min.css',
        ),
    ),
    'lightgallery' => array(
        'javascript' => array(
            'plugins/lightgallery/js/lightgallery-all.min.js',
        ),
        'stylesheet' => array(
            'plugins/lightgallery/css/lightgallery.css',
        ),
    ),
    'lightgallery-new' => array(
        'javascript' => array(
            'plugins/lightGallery/lightgallery.umd.js',
            'plugins/lightGallery/plugins/thumbnail/lg-thumbnail.umd.js',
            'plugins/lightGallery/plugins/zoom/lg-zoom.umd.js',
        ),
        'stylesheet' => array(
            'plugins/lightGallery/css/lightgallery-bundle.css',
        ),
    ),
    'tablesorter' => array(
        'javascript' => array(
            'plugins/tablesorter/dist/js/jquery.tablesorter.js',
            'plugins/tablesorter/dist/js/jquery.tablesorter.widgets.js',
            'plugins/tablesorter/dist/js/extras/jquery.tablesorter.pager.min.js',
        ),
        'stylesheet' => array(
            'plugins/tablesorter/dist/css/theme.bootstrap_4.min.css',
            'plugins/tablesorter/dist/css/jquery.tablesorter.pager.min.css',
        ),
    ),


    'morris_chart' => array(
        'javascript' => array(
            'storage/plugins/morris.js/morris.min.js',
            'storage/plugins/raphael/raphael-min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/morris.js/morris.css',
        ),
    ),

    'sliderPro' => array(
        'javascript' => array(
            'storage/plugins/slider-pro-master/js/jquery.sliderPro.min.js',
        ),

    ),

    'masonary' => array(
        'javascript' => array(
            'plugins/bootstrap4-masonry/js/bootstrap4.masonry.min.js',
        ),

    ),

    'isotope' => array(
        'javascript' => array(
            'storage/plugins/isotope/js/isotope.pkgd.min.js',
            'storage/plugins/imagesloaded/js/imagesloaded.pkgd.js',
            'storage/plugins/magnific-popup/js/magnific-popup.min.js',
        )

    ),

    'magnific-popup'=>array(
        'javascript' => array(
            'storage/plugins/magnific-popup/js/magnific-popup.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/magnific-popup/css/magnific-popup.css',
        ),
    ),

    'fancybox' => array(
        'javascript' => array(
            'storage/plugins/fancybox/dist/jquery.fancybox.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/fancybox/dist/jquery.fancybox.min.css',
        ),
    ),

    'owlcarousel' => array(
        'javascript' => array(
            'storage/plugins/owl.carousel/js/owl.carousel.min.js',
        ),
        'stylesheet' => array(
            'storage/plugins/owl.carousel/css/owl.carousel.min.css',
        ),
    ),

    'bdropdown' => array(
        'javascript' => array(
            'admin/storage/js/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        ),
    ),
    'slimscroll' => array(
        'javascript' => array(
            'admin/storage/js/plugins/jquery.slimscroll/jquery.slimscroll.min.js',
        ),
    ),
    'sidebar' => array(
        'javascript' => array(
            'admin/storage/js/sidebar.js',
        ),
    ),
    'panels' => array(
        'javascript' => array(
            'admin/storage/js/panels.js',
        ),
    ),

    'app' => array(
        'javascript' => array(
            'admin/storage/js/app.js',
        ),
    ),

    'admin_jqueryui' => array(
        'javascript' => array(
            'admin/storage/js/jqueryui/jquery-ui-1.10.4.custom.js',
            'admin/storage/js/jquery-ui-timepicker-addon.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/jqueryui/smoothness/jquery-ui-1.10.4.custom.css',
        ),
    ),
    'icheck' => array(
        'javascript' => array(
            'admin/storage/js/plugins/icheck/icheck.min.js',
        ),
        'stylesheet' => array(
            'admin/storage/css/plugins/icheck/square/grey.css',
        ),
    ),

    'helpers' => array(
        'javascript' => array(
            'admin/storage/js/helpers.js',
        ),
    ),

    'nestedSortable' => array(
        'javascript' => array(
            'admin/storage/js/nested_sortable/jquery.ui.nestedSortable.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/nested_sortable/jquery.ui.nestedSortable.css',
        ),
    ),
    'jquerynestable' => array(
        'javascript' => array(
            'plugins/jquery_nestable/jquery.nestable.min.js',
        ),
        'stylesheet' => array(
            'plugins/jquery_nestable/jquery.nestable.min.css',
        ),
    ),
    'codemirror' => array(
        'javascript' => array(
            'admin/storage/js/codemirror-2.25/lib/codemirror.js',
            'admin/storage/js/codemirror-2.25/mode/xml/xml.js',
            'admin/storage/js/codemirror-2.25/mode/javascript/javascript.js',
            'admin/storage/js/codemirror-2.25/mode/css/css.js',
            'admin/storage/js/codemirror-2.25/mode/clike/clike.js',
            'admin/storage/js/codemirror-2.25/mode/php/php.js',
        ),
        'stylesheet' => array(
            'admin/storage/js/codemirror-2.25/lib/codemirror.css',
        ),
    ),
    'uploader' => [
        'javascript' => [
            'plugins/uploader/js/jquery.dm-uploader.min.js'
        ],
        'stylesheet' => [
            'plugins/uploader/css/jquery.dm-uploader.min.css'
        ],
    ],
    'jquery_loading' => [
        'javascript' => [
            'plugins/loader/jquery.loading.min.js',
            'plugins/loader/loadingoverlay.min.js'
        ]
    ]

);

define('PACKAGES', serialize($packages));

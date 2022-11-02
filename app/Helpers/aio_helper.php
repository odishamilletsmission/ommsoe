<?php
global $template;

$template = service('template');
/*
 * Print Recursive
 *
 * Simply wraps a print_r() in pre tags for debugging.
 *
 * @param mixed
 * @return string
 */
if (!function_exists('printr')) {
    function printr($a)
    {
        ob_end_clean();
        echo "<pre>";
        print_r($a);
        echo "</pre>";
    }
}

// ------------------------------------------------------------------------

/*
 * Variable Dump
 *
 * Simply wraps a var_dump() in pre tags for debugging.
 *
 * @param mixed
 * @return string
 */
if (!function_exists('vardump')) {
    function vardump($a)
    {
        ob_end_clean();
        echo "<pre>";
        var_dump($a);
        echo "</pre>";
    }
}

// ------------------------------------------------------------------------

/*
 * Array to Object
 * 
 * Converts an array to an object
 *
 * @param array
 * @return object
 */
if (!function_exists('array_to_object')) {
    function array_to_object($array)
    {
        $Object = new stdClass();
        foreach ($array as $key => $value) {
            $Object->$key = $value;
        }

        return $Object;
    }
}

// ------------------------------------------------------------------------

/*
 * Object to Array
 * 
 * Converts an object to an array
 * 
 * @param object
 * @return array
 */
if (!function_exists('object_to_array')) {
    function object_to_array($Object)
    {
        $array = get_object_vars($Object);

        return $array;
    }
}

// ------------------------------------------------------------------------

/*
 * Is Ajax
 *
 * Returns true if request is ajax protocol
 *
 * @return bool
 */
if (!function_exists('is_ajax')) {
    function is_ajax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
}


if (!function_exists('load_controller')) {
    function load_controller($controller, $method = 'index')
    {
        $class = require_once(APPPATH . 'controllers/' . $controller . '.php');
        //echo $class;
        $controller = new $class;
        return $controller->$method();
    }
}


// ------------------------------------------------------------------------

/*
 * Image Thumb
 *
 * Creates an image thumbnail and caches the image
 *
 * @param string
 * @param int
 * @param int
 * @param bool
 * @param array
 * @return string
 */
if (!function_exists('image_thumb')) {
    function image_thumb($source_image, $width = 0, $height = 0, $crop = FALSE, $props = array())
    {
        //echo $source_image;
        $CI =& get_instance();
        $CI->load->library('image_cache');

        $props['source_image'] = '/' . str_replace(base_url(), '', $source_image);
        //echo $props['source_image'];
        $props['width'] = $width;
        $props['height'] = $height;
        $props['crop'] = $crop;

        $CI->image_cache->initialize($props);
        $image = $CI->image_cache->image_cache();
        $CI->image_cache->clear();

        return $image;
    }
}
if (!function_exists('resize')) {
    function resize($filename, $width, $height)
    {

        //echo $filename;
        //$CI->load->library('image_lib');

        if (!is_file(DIR_UPLOAD . $filename) || substr(str_replace('\\', '/', realpath(DIR_UPLOAD . $filename)), 0, strlen(WRITEPATH)) != str_replace('\\', '/', WRITEPATH)) {
            //return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $image_old = $filename;
        $image_new = 'image-cache' . '/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        if (!is_file(DIR_UPLOAD . $image_new) || (filectime(DIR_UPLOAD . $image_old) > filectime(DIR_UPLOAD . $image_new))) {
            list($width_orig, $height_orig, $image_type) = getimagesize(DIR_UPLOAD . $image_old);

            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                return DIR_UPLOAD . $image_old;
            }

            $path = '';

            $directories = explode('/', dirname($image_new));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_UPLOAD . $path)) {
                    @mkdir(DIR_UPLOAD . $path, 0777);
                }
            }

            if ($width_orig != $width || $height_orig != $height) {


                $image = \Config\Services::image()
                    ->withFile(DIR_UPLOAD . $image_old)
                    ->resize($width, $height, true, 'height')
                    ->save(DIR_UPLOAD . $image_new);

            } else {
                copy(DIR_UPLOAD . $image_old, DIR_UPLOAD . $image_new);
            }
        }


        return base_url('uploads/' . $image_new);

    }
}
// ------------------------------------------------------------------------

/*
 * BR 2 NL
 *
 * Converts html <br /> to new line \n
 *
 * @param string
 * @return string
 */
if (!function_exists('br2nl')) {
    function br2nl($text)
    {
        return preg_replace('/<br\\s*?\/??>/i', '', $text);
    }
}

// ------------------------------------------------------------------------

/* 
 * Option Array Value
 *
 * Returns single dimension array from an Array of objects with the key and value defined
 *
 * @param array
 * @param string
 * @param string
 * @param array
 * @return array
 */
if (!function_exists('option_array_value')) {
    function option_array_value($object_array, $key, $value, $default = array())
    {

        $option_array = array();

        if (is_array($default)) {
            $option_array = $default;
        } else if (!empty($default)) {
            $option_array[] = $default;
        }

        foreach ($object_array as $Object) {
            $option_array[$Object->$key] = $Object->$value;
        }

        return $option_array;
    }
}

if (!function_exists('option_array_values')) {
    function option_array_values($object_array, $key, $value, $default = array())
    {
        $option_array = array();

        if (is_array($default)) {
            $option_array = $default;
        } else if (!empty($default)) {
            $option_array[] = $default;
        }

        foreach ($object_array as $Object) {

            $option_array[$Object[$key]] = $Object[$value];
        }

        return $option_array;
    }
}

// ------------------------------------------------------------------------

/* 
 * Theme Partial
 *
 * Load a theme partial
 *
 * @param string
 * @param array
 * @param bool
 * @return string
 */
if (!function_exists('theme_partial')) {
    function theme_partial($view, $vars = array(), $return = TRUE)
    {
        $CI =& get_instance();
        $CI->load->library('parser');
        return $CI->parser->parse_string($CI->load->theme($CI->template->theme . '/partials/' . trim($view, '/'), $vars, $return, $CI->template->theme_path), $vars, $return);
    }
}

// ------------------------------------------------------------------------

/* 
 * Theme Url
 *
 * Create a url to the current theme
 *
 * @param string
 * @return string
 */
if (!function_exists('theme_url')) {
    function theme_url($uri = '')
    {
        global $template;
        return base_url($template->theme_path . '/' . $template->get_theme() . '/' . trim($uri, '/'));
    }
}
if (!function_exists('front_url')) {
    function front_url($url)
    {
        $CI =& get_instance();
        $CI->db->where('route', $url);
        $query = $CI->db->get('slug');
        $row = $query->row();
        if ($row) {
            return base_url($row->slug);
        } else {
            return base_url($url);
        }
    }
}

if (!function_exists('admin_url')) {
    function admin_url($uri = '')
    {
        global $template;
        return base_url(env('app.adminDIR') . trim($uri, '/'));

    }
}

if (!function_exists('upload_url')) {
    function upload_url($uri = '')
    {
        return base_url('uploads/' . $uri);
    }
}

// ------------------------------------------------------------------------

/* 
 * Domain Name
 *
 * Returns the site domain name and tld
 *
 * @return string
 */
if (!function_exists('domain_name')) {
    function domain_name()
    {
        $CI =& get_instance();

        $info = parse_url($CI->config->item('base_url'));
        //print_r($info);
        $host = $info['host'];
        if ($host != "localhost") {
            $host_names = explode(".", $host);
            return $host_names[count($host_names) - 2] . "." . $host_names[count($host_names) - 1];
        } else {
            return $host;
        }
    }
}

// ------------------------------------------------------------------------

/*
 * Glob Recursive
 *
 * Run glob function recursivley on a directory
 *
 * @param string
 * @return array
 */
if (!function_exists('glob_recursive')) {
    // Does not support flag GLOB_BRACE

    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }

        return $files;
    }
}
if (!function_exists('dir_recursive')) {
    function dir_recursive($pattern, $flags = 0)
    {
        $CI =& get_instance();
        $CI->load->helper('file');
        $files = get_dir_file_info($pattern, $top_level_only = TRUE);
        //printr($files);
        foreach ($files as $key => $file) {
            if (is_dir($key)) {
                $files[$key]['filename'] = dir_recursive($file['relative_path'] . '/' . $file['name'], $flags);
            }
        }

        return $files;
    }
}

// ------------------------------------------------------------------------

/*
 * URL Base64 Encode
 * 
 * Encodes a string as base64, and sanitizes it for use in a CI URI.
 * 
 * @param string
 * @return string
 */
if (!function_exists('url_base64_encode')) {
    function url_base64_encode(&$str = "")
    {
        return strtr(
            base64_encode($str),
            array(
                '+' => '.',
                '=' => '-',
                '/' => '~'
            )
        );
    }
}

// ------------------------------------------------------------------------

/*
 * URL Base64 Decode
 *
 * Decodes a base64 string that was encoded by ci_base64_encode.
 * 
 * @param string
 * @return string
 */
if (!function_exists('url_base64_decode')) {
    function url_base64_decode(&$str = "")
    {
        return base64_decode(strtr(
            $str,
            array(
                '.' => '+',
                '-' => '=',
                '~' => '/'
            )
        ));
    }
}

// ------------------------------------------------------------------------

/*
 * Output XML
 *
 * Sets the header content type to XML and
 * outputs the <?php xml tag
 * 
 * @param string
 * @return string
 */
if (!function_exists('xml_output')) {
    function xml_output()
    {
        $CI =& get_instance();
        $CI->output->set_content_type('text/xml');
        $CI->output->set_output("<?xml version=\"1.0\"?>\r\n");
    }
}

// ------------------------------------------------------------------------

/*
 * JS Head Start
 *
 * Starts output buffering to place javascript in the <head> of the template
 * 
 * @return void
 */
if (!function_exists('js_start')) {
    function js_start()
    {
        ob_start();
    }
}

// ------------------------------------------------------------------------

/*
 * JS Head End
 *
 * Ends output buffering to place javascript in the <head> of the template
 * 
 * @return void
 */
if (!function_exists('js_end')) {
    function js_end()
    {
        global $template;
        $template->add_script(ob_get_contents(), true);
        ob_end_clean();
    }
}

// ------------------------------------------------------------------------

/*
 * String to Boolean
 *
 * This function analyzes a string and returns false if the string is empty, false, or 0
 * and true for everything else
 * 
 * @param string
 * @return bool
 */
if (!function_exists('str_to_bool')) {
    function str_to_bool($str)
    {
        if (is_bool($str)) {
            return $str;
        }

        $str = (string)$str;

        if (in_array(strtolower($str), array('false', '0', ''))) {
            return false;
        } else {
            return true;
        }
    }
}

// ------------------------------------------------------------------------

/*
 * Is Inline Editable
 *
 * Returns true if inline editing is enabled, admin toolbar is enabled, and user is an administrator
 *
 * @return bool
 */
if (!function_exists('is_inline_editable')) {
    function is_inline_editable($content_type_id = null)
    {
        $CI =& get_instance();
        $CI->load->model('content_types_model');

        if ($CI->settings->enable_inline_editing && $CI->settings->enable_admin_toolbar && $CI->secure->group_types(array(ADMINISTRATOR))->is_auth()) {
            if (empty($content_type_id)) {
                return TRUE;
            }

            if ($CI->Group_session->type != SUPER_ADMIN) {
                // Check if we have already cached permissions for this content type
                if (!isset($CI->content_types_model->has_permission_cache[$content_type_id])) {
                    $Content_types_model = new Content_types_model();

                    // No permission for this content type has been cached yet.
                    // Query to see if current user has permission to this content type
                    $Content_type = $Content_types_model->group_start()
                        ->where('restrict_admin_access', 0)
                        ->or_where_related('admin_groups', 'group_id', $CI->Group_session->id)
                        ->group_end()
                        ->get_by_id($content_type_id);

                    $CI->content_types_model->has_permission_cache[$content_type_id] = ($Content_type->exists()) ? TRUE : FALSE;
                }

                return $CI->content_types_model->has_permission_cache[$content_type_id];
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }
}
// Override form_hidden to implement an id attribute
if (!function_exists('form_hidden')) {
    function form_hidden($name, $value = '', $id = false)
    {
        if (!is_array($name)) {
            return '<input type="hidden" id="' . ($id ? $id : $name) . '" name="' . $name . '" value="' . form_prep($value) . '" />';
        }

        $form = '';

        foreach ($name as $name => $value) {
            $form .= "\n";
            $form .= '<input type="hidden"  id="' . ($id ? $id : $name) . '" name="' . $name . '" value="' . form_prep($value) . '" />';
        }

        return $form;
    }
}

// Override validaton_errors to add error class to paragraph tags
if (!function_exists('validation_errors')) {
    function validation_errors($prefix = '', $suffix = '')
    {
        if (FALSE === ($OBJ =& _get_validation_object())) {
            return '';
        }

        if ($prefix == '' && $suffix == '') {
            $prefix = '<p class="error">';
            $suffix = '</p>';
        }

        return $OBJ->error_string($prefix, $suffix);
    }
}
if (!function_exists('tz_list')) {
    function tz_list()
    {
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            $country = explode('/', $zone);
            date_default_timezone_set($zone);
            if (isset($country[1]) != '') {
                $zones_array[$country[0]][$zone] = str_replace('_', ' ', $country[1]);
            } else {
                $zones_array[$country[0]][$zone] = str_replace('_', ' ', $country[0]);
            }
        }
        return $zones_array;
    }
}
if (!function_exists('get_nested')) {

    function get_nested($treelist, $parent = 0)
    {
        /*echo "<pre>";
        print_r($treelist);*/
        $array = array();
        foreach ($treelist as $tree) {
            if ($tree->parent_id == $parent) {
                $tree->sub = isset($tree->sub) ? $tree->sub : get_nested($treelist, $tree->id);
                $array[] = $tree;
            }
        }

        return $array;
    }
}
if (!function_exists('get_menu_nested')) {

    function get_menu_nested($menulist, $parent = 0)
    {
        /*echo "<pre>";
        print_r($treelist);*/
        $return = array();
        foreach ($menulist as $tree) {
            $returnSubSubArray = array();
            if (isset($tree['children'])) {
                $returnSubSubArray = get_menu_nested($tree['children'], $tree['id']);
            }
            $return[] = array('id' => $tree['id'], 'parentID' => $parent);
            $return = array_merge($return, $returnSubSubArray);

        }

        return $return;;
    }
}
if (!function_exists('list_menu_nav')) {
    function list_menu_nav($list, $depth = 1)
    {


        $nav = '<ol class="dd-list">';


        foreach ($list as $Item) {
            $nav .= '<li class="dd-item" data-id="' . $Item->id . '">';
            $nav .= '<div class="dd-handle">';
            $nav .= '<div class="bar">';
            $nav .= '<span class="title">' . $Item->title . '</span>';
            $nav .= '</div>';
            $nav .= '</div>';
            $nav .= '<div class="info hide form" style="display: none;">';
            $nav .= '<div>';
            $nav .= '<label for="url">Type:</label>';
            $nav .= '<span>' . $Item->page_type . '</span>';
            $nav .= '</div>';
            $nav .= '<div>';
            $nav .= '<label for="url">Title:</label>';
            $nav .= '<input type="text" value="' . $Item->title . '" name="title[]">';
            $nav .= '</div>';
            if ($Item->page_type == "custom") {
                $nav .= '<div>';
                $nav .= '<label for="url">Url:</label>';
                $nav .= '<input type="text" value="' . $Item->url . '" name="url[]">';
                $nav .= '</div>';
            }
            $nav .= '<p class="input-item"><a onclick="remove_item(this);" class="remove" href="javascript:void(0);">Remove This Menu Item</a>';
            $nav .= '<a onclick="update_item(this);" style="float:right" class="update" href="javascript:void(0);">Update Menu Item</a></p>';

            $nav .= '</div>';
            $nav .= '<a onclick="explane(this)" class="explane" href="javascript:void(0);">Explane</a>';

            if (!empty($Item->sub)) {
                $nav .= list_menu_nav($Item->sub, $depth + 1);
            }

            $nav .= '</li>';
        }

        $nav .= '</ol>';

        return $nav;
    }
}
if (!function_exists('list_select_nav')) {
    function list_select_nav($list, $parent_id, $depth = 1)
    {

        $nav = '';
        foreach ($list as $Item) {
            $selected = ($parent_id == $Item->id) ? "selected='selected'" : "";
            $nav .= '<option value="' . $Item->id . '"' . $selected . '>';
            if ($depth == 1) {
                $nav .= $Item->title . '</option>';
            } else {
                $space = '';
                for ($j = 1; $j <= $depth; $j++) {
                    if ($depth == 2) {
                        $space .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                    } else {
                        $space .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }

                $nav .= $space . $Item->title . '</option>';
            }
            if (!empty($Item->sub)) {
                $nav .= list_select_nav($Item->sub, $parent_id, $depth + 1);
            }
        }
        return $nav;
    }
}
if (!function_exists('categorylist')) {
    function categorylist($categories, $parent_id)
    {
        $html = '';
        foreach ($categories as $category) {
            $html .= '<ul><li>';
            $html .= '<label><input type="checkbox" value="' . $category->id . '" name="category_id[]" />' . $category->title . '</label>';
            $html .= categorylist($category->sub, $parent_id);
            $html .= '</li></ul>';
        }
        return $html;
    }
}

if (!function_exists('load_module')) {
    function load_module($module, $params = array())
    {
        return Modules::run($module, $params);
    }
}

/*
 * Set Filter
 *
 * Retrieves the value of a specific filter field from
 * them filter session variable array
 *
 * @param string
 * @param string
 * @return string
 */
if (!function_exists('set_filter')) {
    function set_filter($filter_type, $filter)
    {
        $CI =& get_instance();

        $filter_array = $CI->session->userdata('filter[\'' . $filter_type . '\']');

        if (isset($filter_array[$filter])) {
            return $filter_array[$filter];
        } else {
            return '';
        }
    }
}


// ------------------------------------------------------------------------

/*
 * Process Filter
 *
 * Sets, Gets, and Clears filter session data 
 * for various filters in the admin panel
 *
 * @param string
 * @return array
 */
if (!function_exists('process_filter')) {
    function process_filter($filter_type)
    {
        $CI =& get_instance();

        // Process Filter
        if ($CI->input->post('clear_filter')) {
            $CI->session->unset_userdata('filter[\'' . $filter_type . '\']');
            redirect(current_url());
        } else {
            if ($filter = $CI->input->post('filter')) {
                foreach ($filter as $key => $value) {
                    if ($value == '') {
                        unset($filter[$key]);
                    }
                }

                $CI->session->set_userdata('filter[\'' . $filter_type . '\']', $filter);
                redirect(current_url());
            }
        }

        $filter = ($CI->session->userdata('filter[\'' . $filter_type . '\']')) ? $CI->session->userdata('filter[\'' . $filter_type . '\']') : array();

        return $filter;
    }
}

if (!function_exists('pagination')) {
    function pagination($site_url, $total, $page, $limit)
    {
        $CI =& get_instance();
        $CI->load->library('pagination');
        $config['base_url'] = $site_url;
        $config['per_page'] = $CI->settings->pagination_limit_admin;
        $config['uri_segment'] = '3';
        $config['num_links'] = 4;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['total_rows'] = $total;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = false;
        $config['last_link'] = false;

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo';
        $config['next_link'] = '&raquo';

        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $CI->pagination->initialize($config);
        $pagination['links'] = $CI->pagination->create_links();
        $pagination['results'] = sprintf($CI->lang->line('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

        return $pagination;
    }
}

if (!function_exists('query_string')) {
    function query_string($url)
    {

        $pos = strpos($url, '&');

        if ($pos == 0) {

            $url = str_replace('&', "?", $url);
        }

        return $url;
    }
}

if (!function_exists('query_string')) {
    function create_statement($table_name, array $exclude)
    {
        /* $exclude contains the columns we do not want */
        $column_names = $CI->list_fields($table_name);
        $statement = "";

        foreach ($column_names as $name) {
            if (!in_array($name, $exclude)) {
                if ($statement == "")
                    $statement = $name;
                else
                    $statement .= "," . $name;
            }
        }

        return $statement;
    }
}
if (!function_exists('filesize_dir')) {
    function filesize_dir($file)
    {
        exec('dir ' . $file, $inf);
        $size_raw = $inf[6];
        $size_exp = explode(" ", $size_raw);
        $size_ext = $size_exp[19];
        $size_int = (float)str_replace(chr(255), '', $size_ext);
        return $size_int;
    }
}

if (!function_exists('currency_format')) {
    function currency_format($number, $value = '', $format = true)
    {

        $decimal_place = 2;
        $symbol_left = "$";
        $symbol_right = "";
        $amount = $value ? (float)$number * $value : (float)$number;

        $amount = round($amount, (int)$decimal_place);

        if (!$format) {
            return $amount;
        }

        $string = '';

        if ($symbol_left) {
            $string .= $symbol_left;
        }

        $string .= number_format($amount, (int)$decimal_place, '.', ',');

        if ($symbol_right) {
            $string .= $symbol_right;
        }

        return $string;
    }
}

if (!function_exists('buildTree')) {
    function buildTree(array $arr, $parent_col = 'parent')
    {
        $new = [];
        foreach ($arr as $a) {
            $new[$a[$parent_col]][] = $a;
        }
        $tree = createTree($new, array($arr[0]));

        return $tree;
    }

    function createTree(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['id']])) {
                $l['children'] = createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }
}
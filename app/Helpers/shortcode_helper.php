<?php
	/*
         * Codeigniter Shortcode helper
         *
         * Author: Niranjan
         * Site URL: https://www.niranjan.com
         * License: GNU v3.
         */
global $shortcode;

$shortcode = service('shortcode');
/**
 * Add hook for shortcode tag.
 *
 * There can only be one hook for each shortcode. Which means that if another
 * plugin has a similar shortcode, it will override yours or yours will override
 * theirs depending on which order the plugins are included and/or ran.
 *
 * Simplest example of a shortcode tag using the API:
 *
 * <code>
 * // [footag foo="bar"]
 * function footag_func($atts) {
 * 	return "foo = {$atts[foo]}";
 * }
 * add_shortcode('footag', 'footag_func');
 * </code>
 *
 * Example with nice attribute defaults:
 *
 * <code>
 * // [bartag foo="bar"]
 * function bartag_func($atts) {
 * 	extract(shortcode_atts(array(
 * 		'foo' => 'no foo',
 * 		'baz' => 'default baz',
 * 	), $atts));
 *
 * 	return "foo = {$foo}";
 * }
 * add_shortcode('bartag', 'bartag_func');
 * </code>
 *
 * Example with enclosed content:
 *
 * <code>
 * // [baztag]content[/baztag]
 * function baztag_func($atts, $content='') {
 * 	return "content = $content";
 * }
 * add_shortcode('baztag', 'baztag_func');
 * </code>
 *
 * @since 1.0
 * @uses $shortcode_tags
 *
 * @param string $tag Shortcode tag to be searched in post content.
 * @param callable $func Hook to run when shortcode is found.
 */

if (!function_exists('add_shortcode')) {

    function add_shortcode($tag = '', $func = '') {
        global $shortcode;
        $shortcode->add($tag, $func);
    }
}

/**
* Search content for shortcodes and filter shortcodes through their hooks.
*
* If there are no shortcode tags defined, then the content will be returned
* without any filtering. This might cause issues when plugins are disabled but
* the shortcode will still show up in the post or content.
*
* @since 1.0
* @uses self::$shortcode_tags
* @uses get_regex() Gets the search pattern for searching shortcodes.
*
* @param string $content Content to search for shortcodes
* @return string Content with shortcodes filtered out.
*/

if (!function_exists('do_shortcode')) {

    function do_shortcode($content) {
        global $shortcode;
        return $shortcode->do($content);
    }
}

/**
 * Removes hook for shortcode.
 *
 * @since 1.0
 * @uses $shortcode_tags
 *
 * @param string $tag shortcode tag to remove hook for.
 */

if (!function_exists('remove_shortcode')) {
    function remove_shortcode($tag) {
        $chi =& get_instance();
        $chi->load->library('shortcode');
        $chi->shortcode->remove($tag);
    }
}
/**
 * Clear all shortcodes.
 *
 * This function is simple, it clears all of the shortcode tags by replacing the
 * shortcodes global by a empty array. This is actually a very efficient method
 * for removing all shortcodes.
 *
 * @since 1.0
 * @uses $shortcode_tags
 */

if (!function_exists('remove_all_shortcodes')) {
    function remove_all_shortcodes() {
        $chi =& get_instance();
        $chi->load->library('shortcode');
        $chi->shortcode->remove_all();
    }
}

/**
 * Regular Expression callable for do() for calling shortcode hook.
 * @see get_regex for details of the match array contents.
 *
 * @since 1.0
 * @access private
 * @uses self::$shortcode_tags
 *
 * @param array $m Regular expression match array
 * @return mixed False on failure.
 */

if (!function_exists('do_shortcode_tag')) {

    function do_shortcode_tag($m = '')
    {
        $chi =& get_instance();
        $chi->load->library('shortcode');
        return $chi->shortcode->do_tag($m);
    }
}

/**
 * Retrieve all attributes from the shortcodes tag.
 *
 * The attributes list has the attribute name as the key and the value of the
 * attribute as the value in the key/value pair. This allows for easier
 * retrieval of the attributes, since all attributes have to be known.
 *
 * @since 1.0
 *
 * @param string $text
 * @return array List of attributes and their value.
 */

if (!function_exists('shortcode_parse_atts')) {

    function shortcode_parse_atts($text = '')
    {
        $chi =& get_instance();
        $chi->load->library('shortcode');
        return $chi->shortcode->parse_attrs($text);
    }
}

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 * @since 1.0
 *
 * @param array $pairs Entire list of supported attributes and their defaults.
 * @param array $atts User defined attributes in shortcode tag.
 * @return array Combined and filtered attribute list.
 */

if (!function_exists('shortcode_atts')) {

    function shortcode_atts($pairs = '', $attrs = '')
    {
        $chi =& get_instance();
        $chi->load->library('shortcode');
        return $chi->shortcode->attrs($pairs, $attrs);
    }
}

/**
 * Remove all shortcode tags from the given content.
 *
 * @since 1.0
 * @uses $shortcode_tags
 *
 * @param string $content Content to remove shortcode tags.
 * @return string Content without shortcode tags.
 */
if (!function_exists('strip_shortcodes')) {

    function strip_shortcodes($content = '')
    {
        $chi =& get_instance();
        $chi->load->library('shortcode');
        return $chi->shortcode->strip($content);
    }
}

/**
 * Extract specific shortcode tag from a content.
 *
 * @since 1.2
 * @uses self::$shortcode_tags, self::get_regex, self::parse_atts
 *
 * @param string $tag tag name of the shortcode to be extracted.
 * @param string $content Content to extract shortcode tags.
 */

if (!function_exists('extract_shortcodes')) {

    function extract_shortcodes($tag = '', $content = '')
    {
        $chi =& get_instance();
        $chi->load->library('shortcode');
        return $chi->shortcode->extract($tag, $content);
    }
}

<?php 
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Template extends BaseConfig
{
	/*
	|--------------------------------------------------------------------------
	| Parser Enabled
	|--------------------------------------------------------------------------
	|
	| Should the Parser library be used for the entire page?
	|
	| Can be overridden with $this->template->enable_parser(TRUE/FALSE);
	|
	|   Default: TRUE
	|
	*/
	public $parser_enabled = FALSE;



	/*
	|--------------------------------------------------------------------------
	| Parser Enabled for Body
	|--------------------------------------------------------------------------
	|
	| If the parser is enabled, do you want it to parse the body or not?
	|
	| Can be overridden with $this->template->enable_parser(TRUE/FALSE);
	|
	|   Default: FALSE
	|
	*/
	public $parser_body_enabled = FALSE;


	/*
	|--------------------------------------------------------------------------
	| Title Separator
	|--------------------------------------------------------------------------
	|
	| What string should be used to separate title segments sent via $this->template->title('Foo', 'Bar');
	|
	|   Default: ' | '
	|
	*/

	public $title_separator = ' | ';

	/*
	|--------------------------------------------------------------------------
	| Layout
	|--------------------------------------------------------------------------
	|
	| Which layout file should be used? When combined with theme it will be a layout file in that theme
	|
	| Change to 'main' to get /application/views/layouts/main.php
	|
	|   Default: 'default'
	|
	*/

	public $layout = 'default';

	/*
	|--------------------------------------------------------------------------
	| Theme
	|--------------------------------------------------------------------------
	|
	| Which theme to use by default?
	|
	| Can be overriden with $this->template->set_theme('foo');
	|
	|   Default: ''
	|
	*/

	public $theme = '';

	/*
	|--------------------------------------------------------------------------
	| Theme Locations
	|--------------------------------------------------------------------------
	|
	| Where should we expect to see themes?
	|
	|	Default: array( FCPATH.'themes/' => '../themes/')
	|
	*/

	public $theme_locations = array(
		 FCPATH.'themes/'
	);
}
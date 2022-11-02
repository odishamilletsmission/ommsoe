<?php

namespace Config;


use App\Libraries\Shortcode;
use CodeIgniter\Config\BaseService;
use Config\Template as TemplateConfig;
use App\Libraries\User;
use App\Libraries\Template;
use App\Libraries\OdkCentral;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
	// public static function example($getShared = true)
	// {
	//     if ($getShared)
	//     {
	//         return static::getSharedInstance('example');
	//     }
	//
	//     return new \CodeIgniter\Example();
	// }

    public static function template($getShared = true)
    {
        if ($getShared)
        {
            return static::getSharedInstance('template');
        }
        $tconfig = new \Config\Template();
        if (empty($config) || ! (is_array($config) || $config instanceof TemplateConfig))
        {
            $config = config('Template');
        }
        return new Template($config);
    }
    public static function user($getShared = true)
    {
        if ($getShared)
        {
            return static::getSharedInstance('user');
        }

        return new User();
    }
	
	public static function odkcentral($getShared = true)
	 {
	     if ($getShared)
	     {
	         return static::getSharedInstance('odkcentral');
	     }

	     return new OdkCentral();
	 }

    public static function shortcode($getShared = true)
    {
        if ($getShared)
        {
            return static::getSharedInstance('shortcode');
        }

        return new Shortcode();
    }
}

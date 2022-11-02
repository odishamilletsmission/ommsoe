<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ControllerCreate extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Development';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'module:controller';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Create CodeIgniter Controller in specific HMVC Modules';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'module:controller [ControllerName] [Options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = ['ControllerName' => 'Controller name to be created'];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options      = [
        '-f' => 'Set module folder inside app path (default Modules)',
        '-v' => 'Set view folder inside app path (default front/module/Views)',
    ];
	
	 /**
     * Parent Controller Name 
     */
	
	protected $parent_controller;

    /**
     * Module Name to be Created
     */
    protected $module_name;


    /**
     * Module folder (default /Modules)
     */
    protected $module_folder;


	/**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
       //
    }
}

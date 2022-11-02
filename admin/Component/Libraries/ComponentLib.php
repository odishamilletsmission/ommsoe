<?php 
namespace Admin\Component\Libraries;
use Admin\Component\Models\ComponentModel;

class ComponentLib {

    public function __construct() {
        $config = config(App::class);
        $this->response = new Response($config);
    }

}
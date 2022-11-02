<?php 
namespace Admin\Transaction\Libraries;
use Admin\Transaction\Models\TransactionModel;

class TransactionLib {

    public function __construct() {
        $config = config(App::class);
        $this->response = new Response($config);
    }

}
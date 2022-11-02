<?php 
namespace Admin\Reports\Controllers;
use App\Controllers\AdminController;

class Reports extends AdminController
{
    public function index() {
        $data = [];
        helper(['form']);
        return $this->template->view('Admin\Reports\Views\index', $data);
    }
}

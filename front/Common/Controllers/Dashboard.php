<?php 
namespace Front\Common\Controllers;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
   
    public function index()
	{
		//echo "front";
		$data=[];
		return view('welcome_message', $data);
	}

}
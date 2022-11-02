<?php 
namespace Front\Common\Controllers;
use CodeIgniter\Controller;

class Map extends Controller {
   
    public function index() {
		$data['map_url'] = base_url('common/map/data');
		$data['svg_map'] = view('svg_map');
		$data['gps'] = [
			'total_gps' => 0,
			'total_villages' => 0
		];
		$data['total_farmers'] = 0;
		return view('map', $data);
	}
	
	public function plain() {
		$data = [];
		return view('plain_map', $data);
	}
	
	public function data() {
		
	}

}
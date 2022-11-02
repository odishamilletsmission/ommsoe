<?php

namespace Admin\Banner\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'banner';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [
		'title' => array(
			'label' => 'Title', 
			'rules' => 'trim|required|max_length[100]'
		),
	
		'status' => array(
			'field' => 'status', 
			'label' => 'Status', 
			'rules' => 'trim|required'
		)
	];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	//protected $db;
	
	public function __construct(){
		parent::__construct();
		//$this->db = \Config\Database::connect();
	}
	
	public function getBanners($data = array()){
		$builder=$this->db->table("{$this->table} b");
		$this->filter($builder,$data);
		
		$builder->select("b.*");
		
		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "b.title";
		}

		if (isset($data['order']) && ($data['order'] == 'desc')) {
			$order = "desc";
		} else {
			$order = "asc";
		}
		$builder->orderBy($sort, $order); 
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}
			$builder->limit((int)$data['limit'],(int)$data['start']);
		}
		//$builder->where($this->deletedField, null);

		$res = $builder->get()->getResult();
		return $res;
	
	}
	
	public function getTotalBanners($data = array()) {
		$builder=$this->db->table("{$this->table} b");
		$this->filter($builder,$data);
		$count = $builder->countAllResults();
		return $count;
	}
	
	public function getBanner($id) {
		$builder=$this->db->table("{$this->table} b");
		$builder->where("id",$id);
		$res = $builder->get()->getRow();
		return $res;
	}
	
	public function editBanner($id, $data) {
		$builder=$this->db->table("{$this->table}");
		
		$bannerdata=array(
			"title"=>$data['title'],
			"status"=>$data['status']
		);
	
		$builder->where("id",$id);
		$builder->update($bannerdata);
		
		$builder=$this->db->table("banner_images");
		$builder->where("banner_id",$id);
		$builder->delete();
		
      if (isset($data['banner_image'])) {
			$sort_order=1;
			foreach ($data['banner_image'] as $banner_image) {
				$banner_image_data=array(
					"banner_id"=>$id,
					"image"=>$banner_image['image'],
					"title"=>$banner_image['title'],
					"link"=>$banner_image['link'],
					"description"=>$banner_image['description'],
					"sort_order"=>$sort_order
				);
				$builder->insert($banner_image_data);
				$sort_order++;
			}
		}	
       
      return "success";
	}
	
	public function addBanner($data) {
		
		$bannerdata=array(
			"title"=>$data['title'],
			"status"=>$data['status']
		);
		$this->db->table('banner')->insert($bannerdata);
		//$db->insert("banners", $bannerdata);
		$id=$this->db->insertID() ;

		if (isset($data['banner_image'])) {
			$sort_order=1;
			foreach ($data['banner_image'] as $banner_image) {
				$banner_image_data=array(
					"banner_id"=>$id,
					"image"=>$banner_image['image'],
					"title"=>$banner_image['title'],
					"link"=>$banner_image['link'],
					"description"=>$banner_image['description'],
					"sort_order"=>$sort_order
				);
				$this->db->table("banner_images")->insert($banner_image_data);
				$sort_order++;
			}
		}
	}
	

	public function getBannerImages($id) {
		$builder = $this->db->table('banner_images')
					->orderBy("sort_order", "asc")
					->Where(['banner_id' => $id])
					->get();
					
		$banner_image_data = $builder->getResultArray();
		return $banner_image_data;
	}
	
	private function filter($builder,$data){
		
		
		if (!empty($data['filter_search'])) {
			$builder->where("
				b.title LIKE '%{$data['filter_search']}%'"
			);
		}
    }
	
	public function deleteBanner($selected=[]){
		$builder = $this->db->table('banner');
		$builder->whereIn("id", $selected)->delete();
		
		$builder = $this->db->table('banner_images');
		$builder->whereIn("banner_id", $selected)->delete();
		
	}
	
}

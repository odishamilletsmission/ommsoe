<?php
namespace Admin\Pages\Models;
use CodeIgniter\Model;

class PagesModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'pages';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes       = true;
	protected $protectFields        = false;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = true;
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
	protected $bulider;
	
	
	
	public function editPage($id, $data) {
		$page_data=array(
			"parent_id"=>$data['parent_id'],
			"slug"=>$data['slug'],
			"title"=>$data['title'],
			"content"=>$data['content'],
			"layout"=>$data['layout'],
			"meta_title"=>$data['meta_title'],
			"meta_description"=>$data['meta_description'],
			"meta_keywords"=>$data['meta_keywords'],
			"feature_image"=>$data['feature_image'],
			"status"=>$data['status'],
			"visibilty"=>$data['visibilty'],
			"sort_order"=>$data['sort_order'],
			"date_modified"=>date("Y-m-d")
			
		);
		
		$this->where("id",$id);
      	$this->update("pages", $page_data);
		
		$this->where("route_id",$id);
		$this->delete("slug");
		
		if ($data['slug']) {
			$slugdata=array(
				"slug"=>$this->input->post('slug'),
				"route"=>"pages/index/$id"
			);
			$this->insert("slug", $slugdata);
		}
	}
	
	public function deletePage($selected){
		
		$this->where_in("id",$selected);
		$this->delete("pages");
		
		$this->where("route","pages/index/$id");
		$this->delete("slug");
		
	}
	
	public function getPages($data = array()){
		$builder=$this->db->table("{$this->table} p");
		$this->filter($builder,$data);
		
		$builder->select("p.*");
		
		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "p.title";
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
	
	public function getTotalPages($data = array()) {
		$builder=$this->db->table("{$this->table} p");
		$this->filter($builder,$data);
		$count = $builder->countAllResults();
		return $count;
		
		
	}
	
	public function getPage($id) {
		$this->where("id",$id);
		$result=$this->get('pages')->row();
		return $result;
	}

	public function getParents($page_id=""){
		//$bulider=$this->db->table($this->table);
		$this->select('id,title,parent_id');
		//$this->from('pages');
		if($page_id)
		{
			$this->where('id !='.$page_id);
		}
		$this->orderBy("id", "asc");
		$res = $this->findAll();
		return $res;
	}
	
	private function filter($builder,$data){
		
		if (!empty($data['filter_search'])) {
			$builder->where("
				p.title LIKE '%{$data['filter_search']}%'"
			);
		}
    }
	
	public function getCustom(){
		$result=$this->table($this->table)
				 ->get()
				 ->getResult()	;
		return $result;
	}
	
}

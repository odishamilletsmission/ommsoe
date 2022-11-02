<?php

namespace Admin\Menu\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'menu';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes       = false;
	protected $protectFields        = false;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';



	// Validation
    protected $validationRules      = [
        'menu_name' => array(
            'label' => 'Menu Name',
            'rules' => 'trim|required|max_length[100]'
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

    public function addMenuGroup($data){
        $builder=$this->db->table("menu_group");
        $builder->insert($data);
        $menugroupid=$this->db->insertID() ;
        return $menugroupid;
    }

    public function editMenuGroup($id, $data){
        $builder=$this->db->table("menu_group");
        $builder->where('id', $id);
        $builder->update($data);
    }

    public function deleteMenuGroup($id){
        $builder=$this->db->table("menu_group");
        $builder->where("id",$id);
        $builder->delete();

        $builder=$this->db->table("menu");
        $builder->where("menu_group_id",$id);
        $builder->delete();

        return true;
    }

	public function getMenus($filter) {
        $sql = "SELECT m.* FROM menu_group mg RIGHT JOIN menu m ON mg.id= m.menu_group_id WHERE 1=1";

        if(isset($filter['theme_location'])){
            $sql.= " AND mg.theme_location='".$filter['theme_location']."'";
        }
        if(!empty($filter['menu_group_id'])){
            $sql.= " AND m.menu_group_id='".$filter['menu_group_id']."'";
        }
        $sql.= " ORDER BY sort_order ASC";
        $results=$this->db->query($sql)->getResult();
        $menus=[];
        foreach($results as $result){
            $menus[$result->id]=$result;
        }
        return $menus;
	}

    public function getMenuGroups() {
        $builder=$this->db->table("menu_group");
        $res = $builder->get()->getResultArray();
        return $res;
    }

    public function getMenuGroup($id) {
        $builder=$this->db->table("menu_group");
        $builder->where("id",$id);
        $res = $builder->get()->getRowArray();
        return $res;
    }

    public function getMenuItems($id) {
        $builder=$this->db->table("menu");

        $builder->where("menu_group_id",$id);
        $builder->orderBy("sort_order", "ASC");
        $menu_item_data = $builder->get()->getResult();
        return $menu_item_data;
    }

    public function addMenuItem($data){
        $builder=$this->db->table("menu");
        $builder->insert($data);
        return $this->db->insertID() ;
    }
    public function editMenuItem($id, $data){
        $builder=$this->db->table("menu");
        $builder->where("id",$id);
        $builder->update($data);
    }

    public function deleteMenuItem($id){
        $builder=$this->db->table("menu");
        $builder->where('id', $id);
        $builder->delete();
        return true;
    }

    public function updateMenuItemsOrder($id,$data){
        $builder=$this->db->table("menu");
        $builder->where("id",$id);
        $builder->update($data);
    }

    public function getMaxSortorder($id){
        $builder=$this->db->table("menu");
        $builder->where("menu_group_id",$id);
        $builder->selectMax('sort_order');
        $result=$builder->get()->getRow();
        return $result->sort_order ;
    }

}

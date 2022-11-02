<?php

namespace Admin\Localisation\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'soe_districts';
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
	// Validation
	protected $validationRules      = [
		'code' => array(
			'label' => 'Code', 
			'rules' => 'trim|required|max_length[100]'
		),
		
		'name' => array(
			'label' => 'Name', 
			'rules' => "trim|required|max_length[255]"
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

    public function getAll($data = array()){
        $builder=$this->db->table($this->table);
        $this->filter($builder,$data);

        $builder->select("*");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "name";
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

    public function getTotals($data = array()) {
        $builder=$this->db->table($this->table);
        $this->filter($builder,$data);
        $count = $builder->countAllResults();
        return $count;
    }

    private function filter($builder,$data){

        if (!empty($data['filter_search'])) {
            $builder->where("
				name LIKE '%{$data['filter_search']}%'"
            );
        }
    }

    public function getDistrictsByFundAgency($fund_agency_id=null) {
        $sql = "SELECT
  sd.id,
  sd.name
FROM soe_blocks sb
  LEFT JOIN soe_districts sd
    ON sb.district_id = sd.id";
        if($fund_agency_id){
            $sql .= " WHERE sb.fund_agency_id = ".$fund_agency_id;
        }
$sql .= " GROUP BY sd.id";

        return $this->db->query($sql)->getResultArray();
    }

    public function getNewDistricts() {
        return $this->db->query('SELECT
  sb.district_id,
  phase
FROM soe_blocks sb
GROUP BY sb.district_id
HAVING phase = 2')->getResultArray();

    }
}

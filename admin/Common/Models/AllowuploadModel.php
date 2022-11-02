<?php

namespace Admin\Common\Models;

use CodeIgniter\Model;

class AllowuploadModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'vw_allow_uploads_block';
	protected $primaryKey           = 'upload_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = '';
	protected $updatedField         = '';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
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

	protected function bypass(array $data){

	    $_data = &$data['data'];
        $_data['enabled'] = true;
        $_data['month'] = getMonthIdByMonth(date('m'));
        $_data['year'] = getCurrentYearId();
        return $data;
    }

    public function uploadAllowed($filter=[]) {
        $sql = "SELECT * FROM vw_allow_uploads_block WHERE 1=1";

        if(isset($filter['block_id']) && $filter['block_id']){
            $sql .= " AND block_id='".$filter['block_id']."'";
        }

        if(isset($filter['district_id']) && $filter['district_id']){
            $sql .= " AND district_id='".$filter['district_id']."'";
        }

        if(isset($filter['agency_type_id']) && $filter['agency_type_id']){
            $sql .= " AND agency_type_id='".$filter['agency_type_id']."'";
        }

        if(isset($filter['month']) && $filter['month']){
            $sql .= " AND month='".$filter['month']."'";
        }

        if(isset($filter['year']) && $filter['year']){
            $sql .= " AND year='".$filter['year']."'";
        }
//echo $sql;exit;
        $month = $this->db->query($sql)->getRowArray();

        if((isset($month['date_extended']) && (strtotime('today') < strtotime($month['date_extended']))) && (strtotime('today') >= strtotime($month['from_date']))){
            return $month;
        }

        if((strtotime('today') >= strtotime($month['from_date'])) && (strtotime('today') <= strtotime($month['to_date']))){
            return $month;
        }

        return [];
    }

    public function getByDate($filter=[]) {
	    $date = date('Y-m-d');

        $sql = "SELECT * FROM vw_allow_uploads_block WHERE 1=1";

        if(isset($filter['block_id']) && $filter['block_id']){
            $sql .= " AND block_id='".$filter['block_id']."'";
        }

        if(isset($filter['district_id']) && $filter['district_id']){
            $sql .= " AND district_id='".$filter['district_id']."'";
        }

        if(isset($filter['agency_type_id']) && $filter['agency_type_id']){
            $sql .= " AND agency_type_id='".$filter['agency_type_id']."'";
        }

        $sql .= " AND DATE('".$date."') BETWEEN DATE(from_date) AND IF(date_extended IS NOT NULL,date_extended,to_date) ORDER BY upload_id DESC";

        $month = $this->db->query($sql)->getRowArray();

        return $month;
    }
}

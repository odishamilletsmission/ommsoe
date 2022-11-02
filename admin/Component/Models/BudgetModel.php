<?php

namespace Admin\Component\Models;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'soe_budgets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAgencyBudgets($filter=[]) {
        $sql = "SELECT
  sc.id,
  sc.number,
  sc.description,
  sc.parent,
  sc.sort_order,
  sc.row_type,
  sac.agency_type_id,
  ug.name agency_type,
  sb.physical,
  sb.financial
FROM (SELECT
    *
  FROM soe_agency_components
  WHERE 1=1";
        if(!empty($filter['block_id'])){
            $sql .= " AND block_id = ".$filter['block_id'];
        } else {
            $sql .= " AND block_id = 0";
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND district_id = ".$filter['district_id'];
        } else {
            $sql .= " AND district_id = 0";
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND user_group = ".$filter['agency_type_id'];
        } else {
            $sql .= " AND user_group = 0";
        }
        $sql .= " ) sac
  LEFT JOIN soe_components sc
    ON sac.component_id = sc.id
  LEFT JOIN user_group ug
    ON sac.agency_type_id = ug.id
  LEFT JOIN (SELECT * FROM soe_budgets WHERE 1=1";
        if(!empty($filter['block_id'])){
            $sql .= " AND block_id = ".$filter['block_id'];
        } else {
            $sql .= " AND block_id = 0";
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND district_id = ".$filter['district_id'];
        } else {
            $sql .= " AND district_id = 0";
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND agency_type_id = ".$filter['agency_type_id'];
        } else {
            $sql .= " AND agency_type_id = 0";
        }
        $sql .= ") sb
    ON sac.component_id = sb.component_id
WHERE sac.deleted_at IS NULL
AND sc.deleted_at IS NULL AND sb.deleted_at IS NULL ORDER BY sort_order";

        return $this->db->query($sql)->getResultArray();
    }
}

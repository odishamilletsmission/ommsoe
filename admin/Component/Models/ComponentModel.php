<?php

namespace Admin\Component\Models;

use CodeIgniter\Model;

class ComponentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'soe_components';
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

    public function getAll() {
        return $this->select('id,number,description,parent,sort_order,row_type')
            ->where('deleted_at',null)
            ->orderBy('sort_order','ASC')
            ->asArray()
            ->findAll();
    }

    public function getByBlock($block_id) {
        $sql = "SELECT
  c.id component_id,
  c.number,
  c.description,
  c.parent,
  c.sort_order,
  c.row_type
FROM soe_agency_components ac
  LEFT JOIN soe_components c
    ON ac.component_id = c.id WHERE block_id=$block_id
ORDER BY c.sort_order";

        return $this->db->query($sql)->getResultArray();
    }

    public function getComponents($filter=[])
    {
        $sql = "SELECT
  c.id component_id,
  c.number,
  c.description,
  c.parent,
  c.sort_order,
  c.row_type,
  `at`.name agency_type
FROM soe_agency_components ac
  LEFT JOIN soe_components c
    ON ac.component_id = c.id
  LEFT JOIN user_group `at`
    ON `at`.id = ac.agency_type_id
WHERE ac.deleted_at IS NULL
AND c.deleted_at IS NULL";
        if(!empty($filter['block_id'])){
            $sql .= " AND block_id = ".$filter['block_id'];
        }

        if(!empty($filter['district_id'])){
            $sql .= " AND district_id = ".$filter['district_id'];
        }

        if (!empty($filter['user_group'])) {
            $sql .= " AND user_group = " . $filter['user_group'];
        }
        $sql .= " GROUP BY component_id
ORDER BY c.sort_order";
        return $this->db->query($sql)->getResultArray();
    }
}

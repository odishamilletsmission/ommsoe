<?php

namespace Admin\Component\Models;

use Admin\Common\Models\CommonModel;
use CodeIgniter\Model;

class AgencyComponentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'soe_agency_components';
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
    protected $createdField  = 'created_at';
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

    public function getAssignedBlocks($filter=[]) {
        $sql = "SELECT * FROM soe_agency_components WHERE 1=1";
        if(!empty($filter['year'])){
            $sql .= " AND year=6";
        }
        $sql .= " GROUP BY block_id";

        return $this->db->query($sql)->getResultArray();
    }

    public function getComponentsTable($array) {
        $html = '';

        $cm = new CommonModel();
        $agency_types = $cm->getAgencyTypes();
        foreach ($array as $item) {
            if($item['row_type']=='heading') {
                $html .= '<tr>
                    <th><input type="checkbox" name="components['.$item['id'].'][id]" id="cb' . $item['id'] . '" value="' . $item['id'] . '"></th>
                    <th>' . $item['number'] . '</th>
                    <th>' . $item['description'] . '</th>
                    <th><input type="hidden" name="components['.$item['id'].'][agency_type]" value="0"> - </th>
                ';
            } else {
                $html .= '<tr>
                    <td><input type="checkbox" name="components['.$item['id'].'][id]" id="cb' . $item['id'] . '" value="' . $item['id'] . '"></td>
                    <td><label for="cb' . $item['id'] . '">' . $item['number'] . '</label></td>
                    <td><label for="cb' . $item['id'] . '">' . $item['description'] . '</label></td>
                    <td><select class="form-control" name="components['.$item['id'].'][agency_type]">
                    <option value="0">Choose</option>';
                foreach ($agency_types as $agency_type) {
                    $html .= '<option value="'.$agency_type->id.'">'.$agency_type->name.'</option>';
                }
            }
            if (!empty($item['children'])){
                $html .= $this->getComponentsTable($item['children']);
            }
            $html .= '</tr>';
        }

        return $html;

    }

    public function getAgencyComponents($filter=[]) {
        $sql = "SELECT
  sc.id,
  sc.number,
  sc.description,
  sc.parent,
  sc.sort_order,
  sc.row_type,
  sac.agency_type_id,
  ug.name agency_type
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
WHERE sac.deleted_at IS NULL
AND sc.deleted_at IS NULL ORDER BY sort_order";

        return $this->db->query($sql)->getResultArray();
    }
}

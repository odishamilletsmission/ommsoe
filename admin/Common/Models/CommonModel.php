<?php

namespace Admin\Common\Models;

use CodeIgniter\Model;

class CommonModel extends Model {

    public function getAgencyTypes($as_array=false){
        $res = $this->db
            ->table('user_group')
            ->where('id>=',5)
            ->orderBy('name')
            ->get();
        if($as_array){
            return $res->getResultArray();
        } else {
            return $res->getResult();
        }
    }

    public function getFundAgency($agency_id) {
        return $this->setTable('soe_fund_agency')->where('id',$agency_id)->first();
    }

    public function getModules() {
        return $this->db->query('SELECT * FROM vw_modules')->getResultArray();
    }
}
<?php 
namespace Admin\Reports\Models;
use CodeIgniter\Model;

class MPRUploadModel extends Model {
    protected $DBGroup              = 'default';
    protected $table                = 'soe_mpr_uploads';
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

    public function getMPRUploads($filter=[]) {
        if(empty($filter['district_id'])){
            $filter['district_id'] = 0;
        }
        if(empty($filter['district_id'])){
            $filter['fund_agency_id'] = 0;
        }
        if(empty($filter['year_id'])){
            $filter['year_id'] = getCurrentYearId();
        }

        $sql = "SELECT
  vaym.year_id,
  vaym.month_id,
  vaym.year,
  vaym.month,
  smu.file,
  smu.created_at
FROM vw_all_year_month vaym
  LEFT JOIN (SELECT
      *
    FROM soe_mpr_uploads
    WHERE district_id = '".$filter['district_id']."'
    AND fund_agency_id = '".$filter['fund_agency_id']."') smu
    ON vaym.year_id = smu.year
    AND vaym.month_id = smu.month
WHERE vaym.year_id = '".$filter['year_id']."'";
        
        return $this->db->query($sql)->getResult();
    }
}
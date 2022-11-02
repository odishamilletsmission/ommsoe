<?php 
namespace Admin\MIS\Models;
use CodeIgniter\Model;
use App\Libraries\User;

class MISModel extends Model 
{
    protected $table = 'mis_submissions';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'object';
    protected $useSoftDeletes        = true;
    protected $protectFields        = false;
//	protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

	public function getAll($filter=[]) {
        $transaction_type = '';
        if(!empty($filter['transaction_type'])){
            $transaction_type = $filter['transaction_type'];
        }

        $sql = "SELECT
  ms.id,
  d.name district,
  b.name block,
  ug.name agency_type,
  m.name `month`,
  y.name `year`,
  ms.created_at,
  ms.status
FROM mis_submissions ms
  LEFT JOIN soe_years y
    ON y.id = ms.year
  LEFT JOIN soe_blocks b
    ON b.id = ms.block_id
  LEFT JOIN soe_districts d
    ON d.id = ms.district_id
  LEFT JOIN soe_months m
    ON m.id = ms.month
  LEFT JOIN user_group ug
    ON ug.id = ms.agency_type_id
WHERE 1=1";
        
        $sql .= " AND ms.deleted_at IS NULL";

        if (!empty($filter['filter_search'])) {
            $sql .= " AND (ms.name LIKE '%{$filter['filter_search']}%'
                OR y.name LIKE '%{$filter['filter_search']}%'
            )";
        }
        if (!empty($filter['agency_type_id'])) {
            $sql .= " AND ms.agency_type_id=".$filter['agency_type_id'];
        }
        if (!empty($filter['district_id'])) {
            $sql .= " AND ms.district_id=".$filter['district_id'];
        }
        if (!empty($filter['user_id'])) {
            $sql .= " AND ms.user_id=".$filter['user_id'];
        }

        if (isset($filter['sort']) && $filter['sort']) {
            $sort = $filter['sort'];
        } else {
            $sort = "ms.year,ms.month";
        }

        if (isset($filter['order']) && ($filter['order'] == 'asc')) {
            $order = "asc";
        } else {
            $order = "desc";
        }
        $sql .= " GROUP BY ms.id ORDER BY $sort $order ";

        if (isset($filter['start']) || isset($filter['limit'])) {
            if ($filter['start'] < 0) {
                $filter['start'] = 0;
            }

            if ($filter['limit'] < 1) {
                $filter['limit'] = 10;
            }
        }

        $sql .= " LIMIT ".$filter['start'].', '.$filter['limit'];
        
        return $this->db->query($sql)->getResult();
    }

    public function getTotal($filter=[]) {

        $sql = "SELECT
  COUNT(*) total
FROM mis_submissions ms
  LEFT JOIN soe_years y
    ON y.id = ms.year
  LEFT JOIN soe_blocks b
    ON b.id = ms.block_id
  LEFT JOIN soe_districts d
    ON d.id = ms.district_id
WHERE ms.deleted_at IS NULL";

  
        
        if (!empty($filter['user_id'])) {
            $sql .= " AND ms.user_id=".$filter['user_id'];
        }
        if (!empty($filter['filter_search'])) {
            $sql .= " AND (ms.name LIKE '%{$filter['filter_search']}%'
                OR y.name LIKE '%{$filter['filter_search']}%'
            )";
        }

        $count = $this->db->query($sql)->getRow()->total;

//        $count = $this->countAllResults();

        return $count;
    }
	
	public function canEdit($txn) {
        $this->user = new User();
        $this_user_id = $this->user->user_id;
        $this_district_id = $this->user->district_id;
        $this_user_type = $this->user->agency_type_id;

        if($this_user_id==$txn->user_id){
            return true;
        }
        if($this_district_id==$txn->district_id && $this_user_type!=$txn->agency_type_id){
            return true;
        }

        return false;
    }


}
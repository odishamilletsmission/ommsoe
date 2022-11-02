<?php

namespace Admin\Transaction\Models;

use CodeIgniter\Model;

class MisctransactionModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'soe_misc_transactions';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes        = true;
	protected $protectFields        = false;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
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

    public function getHeads($agency_type_id) {
        $sql = "SELECT * FROM soe_misc_txn_heads WHERE agency_type_id=$agency_type_id";
        return $this->db->query($sql)->getResult();
	}

    public function getAll($filter=[]) {
        $sql = "SELECT
  mt.id,
  y.name `year`,
  m.name `month`,
  ug.name agency_type,
  fa.name fund_agency,
  mt.created_at,
  in_amt.credit,
  out_amt.debit,
  mt.status
FROM soe_misc_transactions mt
  LEFT JOIN soe_months m ON m.id=mt.month
  LEFT JOIN user_group ug ON ug.id=mt.agency_type_id
  LEFT JOIN soe_fund_agency fa ON fa.id=mt.fund_agency_id
  LEFT JOIN (SELECT
      mta.txn_id,
      SUM(mta.amount) credit
    FROM soe_misc_txn_amt mta
      LEFT JOIN soe_misc_txn_heads mth
        ON mta.head_id = mth.id
    WHERE mth.txn_type = 'in'
    GROUP BY mta.txn_id) in_amt
    ON mt.id = in_amt.txn_id
  LEFT JOIN (SELECT
      mta.txn_id,
      SUM(mta.amount) debit
    FROM soe_misc_txn_amt mta
      LEFT JOIN soe_misc_txn_heads mth
        ON mta.head_id = mth.id
    WHERE mth.txn_type = 'out'
    GROUP BY mta.txn_id) out_amt
    ON mt.id = out_amt.txn_id LEFT JOIN soe_years y ON y.id=mt.year WHERE mt.deleted_at IS NULL";
        if (!empty($filter['agency_type_id'])) {
            $sql .= " AND mt.agency_type_id=".$filter['agency_type_id'];
        }
        if (!empty($filter['user_id'])) {
            $sql .= " AND mt.user_id=".$filter['user_id'];
        }

        if (isset($filter['sort']) && $filter['sort']) {
            $sort = $filter['sort'];
        } else {
            $sort = "mt.year DESC,mt.month ";
        }

        if (isset($filter['order']) && ($filter['order'] == 'asc')) {
            $order = "ASC";
        } else {
            $order = "DESC";
        }
        $sql .= " ORDER BY $sort $order ";

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
//        return
    }

    public function getTotal($filter=[]) {
        $sql = "SELECT
  COUNT(*) total
FROM soe_misc_transactions t
WHERE t.deleted_at IS NULL";

        if (!empty($filter['agency_type_id'])) {
            $sql .= " AND t.agency_type_id=".$filter['agency_type_id'];
        }
        if (!empty($filter['user_id'])) {
            $sql .= " AND t.user_id=".$filter['user_id'];
        }

        $count = $this->db->query($sql)->getRow()->total;

//        $count = $this->countAllResults();

        return $count;
    }

    public function getTotalAmount($filter=[]) {
        $sql = "SELECT
 COALESCE(SUM(IF(mt.txn_type = 'out', -1, 1) * mt.total),0) total
FROM (SELECT
    COALESCE(SUM(mta.amount), 0) total,
    mth.txn_type
  FROM soe_misc_transactions mt
    LEFT JOIN soe_misc_txn_amt mta
      ON mt.id = mta.txn_id
    LEFT JOIN soe_misc_txn_heads mth
      ON mta.head_id = mth.id
  WHERE mt.deleted_at IS NULL
  AND mta.deleted_at IS NULL AND status=1";
        if(!empty($filter['block_id'])){
            $sql .= " AND mt.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND mt.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND mt.year = ".$filter['year'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND mt.month IN (".implode(',',getMonthsArray($filter['month']-1)).")";
        }

        $sql .= " GROUP BY mth.txn_type) mt";

        $this->db->query($sql)->getRow()->total;
    }

    //not used yet
    public function getTransactionDetail($transaction_id) {
        $result = $this->db
            ->table('soe_misc_transactions t')
            ->select('u.firstname, c.name cluster,d.name district,ut.name agency_type,y.name year,month,t.created_at,t.status,t.agency_type_id,t.remarks')
            ->join('soe_blocks b','b.id=t.block_id','LEFT')
            ->join('soe_districts d','d.id=t.district_id','LEFT')
            ->join('user_group ut','ut.id=t.agency_type_id','LEFT')
            ->join('soe_years y','y.id=t.year','LEFT')
            ->join('users u','u.id=t.user_id','LEFT')
            ->where('t.id',$transaction_id)->get()->getRow();

        return $result;
    }

    public function getHeadTotal($filter=[]) {
        $sql = "SELECT
  smta.head_id,
  SUM(smta.amount) total
FROM soe_misc_transactions smt
  LEFT JOIN soe_misc_txn_amt smta
    ON smt.id = smta.txn_id
WHERE smt.deleted_at IS NULL
AND smta.deleted_at IS NULL AND status=1";
        if(!empty($filter['year'])){
            $sql .= " AND smt.year = ".$filter['year'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND smt.month = ".$filter['month'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND smt.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND smt.fund_agency_id = ".$filter['fund_agency_id'];
        }
        if(!empty($filter['head_id'])){
            $sql .= " AND smta.head_id = ".$filter['head_id'];
        }
        $sql .= " GROUP BY smta.head_id";

        return $this->db->query($sql)->getRow();
    }

    public function getHeadwiseAmount($filter=[]) {
        $sql = "SELECT
  hd.name head,
  hd.txn_type,
  IF(txn_type = 'in', '1', '-1') `sign`,
  amt.head_id,
  amt.total
FROM (SELECT
    *
  FROM soe_misc_txn_heads smth
  WHERE 1=1";
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND smth.agency_type_id = ".$filter['agency_type_id'];
        } else {
            $sql .= " AND smth.agency_type_id = 5";
        }
        $sql .= " ) hd
  LEFT JOIN (SELECT
      smta.head_id,
      SUM(smta.amount) total
    FROM soe_misc_txn_amt smta
      LEFT JOIN soe_misc_transactions smt
        ON smta.txn_id = smt.id
    WHERE smt.deleted_at IS NULL
    AND smta.deleted_at IS NULL
    AND smt.status = 1";
        if(!empty($filter['block_id'])){
            $sql .= " AND smt.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND smt.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND smt.month = ".$filter['month'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND smt.year = ".$filter['year'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND smt.agency_type_id = ".$filter['agency_type_id'];
        }
        $sql .= " 
    GROUP BY smta.head_id) amt
    ON amt.head_id = hd.id";

        return $this->db->query($sql)->getResult();
    }

    public function getTotalRefund($filter=[]) {
        $sql = "SELECT
  COALESCE(SUM(financial),0) total
FROM soe_transactions st
  LEFT JOIN soe_transaction_components stc
    ON st.id = stc.transaction_id
WHERE st.deleted_at IS NULL
AND stc.deleted_at IS NULL
AND st.transaction_type = 'refund'";
        if(!empty($filter['district_id'])) {
            $sql .= " AND st.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month'])) {
            $sql .= " AND st.month = ".$filter['month'];
        }
        if(!empty($filter['year'])) {
            $sql .= " AND st.year = ".$filter['year'];
        }
        if(!empty($filter['fund_agency_id'])) {
            $sql .= " AND st.fund_agency_id = ".$filter['fund_agency_id'];
        }
        if(!empty($filter['agency_type_id'])) {
//            $sql .= " AND st.agency_type_id = ".$filter['agency_type_id'];
            if($filter['agency_type_id']==$this->settings->district_user){
                $sql .= " AND st.block_id != 0";
            }
        }

        return $this->db->query($sql)->getFirstRow()->total;
    }
}

<?php 
namespace Admin\Transaction\Models;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use App\Libraries\User;
use CodeIgniter\Model;

class TransactionModel extends Model 
{
    protected $table                = 'soe_transactions';
    protected $DBGroup              = 'default';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'object';
    protected $useSoftDeletes        = true;
    protected $protectFields        = false;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
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
    protected $afterFind            = ['appendDetails'];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];


    public function getAll($filter=[]) {
        $transaction_type = '';
        if(!empty($filter['transaction_type'])){
            $transaction_type = $filter['transaction_type'];
        }

        $sql = "SELECT
  t.id,
  d.name district,
  b.name block,
  ug.name agency_type,
  m.name `month`,
  y.name `year`,
  t.date_added,
  t.transaction_type,
  t.status,
  SUM(COALESCE(tc.physical, 0)) phy,
  SUM(COALESCE(tc.financial, 0)) fin
FROM soe_transactions t
  LEFT JOIN soe_transaction_components tc
    ON t.id = tc.transaction_id
  LEFT JOIN soe_years y
    ON y.id = t.year
  LEFT JOIN soe_blocks b
    ON b.id = t.block_id
  LEFT JOIN soe_districts d
    ON d.id = t.district_id
  LEFT JOIN soe_months m
    ON m.id = t.month
  LEFT JOIN user_group ug
    ON ug.id = t.agency_type_id
WHERE 1=1";
        if($transaction_type) {
            $sql .= " AND transaction_type = '$transaction_type'";
        }
        $sql .= " AND t.deleted_at IS NULL
AND tc.deleted_at IS NULL";

        if (!empty($filter['filter_search'])) {
            $sql .= " AND (c.name LIKE '%{$filter['filter_search']}%'
                OR y.name LIKE '%{$filter['filter_search']}%'
            )";
        }
        if (!empty($filter['agency_type_id'])) {
            $sql .= " AND t.agency_type_id=".$filter['agency_type_id'];
        }
        if (!empty($filter['district_id'])) {
            $sql .= " AND t.district_id=".$filter['district_id'];
        }
        if (!empty($filter['user_id'])) {
            $sql .= " AND t.user_id=".$filter['user_id'];
        }

        if (isset($filter['sort']) && $filter['sort']) {
            $sort = $filter['sort'];
        } else {
            $sort = "t.year,t.month";
        }

        if (isset($filter['order']) && ($filter['order'] == 'asc')) {
            $order = "asc";
        } else {
            $order = "desc";
        }
        $sql .= " GROUP BY t.id ORDER BY $sort $order ";

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
FROM soe_transactions t
  LEFT JOIN soe_years y
    ON y.id = t.year
  LEFT JOIN soe_blocks b
    ON b.id = t.block_id
  LEFT JOIN soe_districts d
    ON d.id = t.district_id
WHERE t.deleted_at IS NULL";

        if (!empty($filter['agency_type_id'])) {
            $sql .= " AND t.agency_type_id=".$filter['agency_type_id'];
        }
        if (!empty($filter['user_id'])) {
            $sql .= " AND t.user_id=".$filter['user_id'];
        }
        if (!empty($filter['filter_search'])) {
            $sql .= " AND (c.name LIKE '%{$filter['filter_search']}%'
                OR y.name LIKE '%{$filter['filter_search']}%'
            )";
        }

        $count = $this->db->query($sql)->getRow()->total;

//        $count = $this->countAllResults();

        return $count;
    }

    //for transaction report
    public function getBlockDistrictReport($filter=[]) {
        $sql = "SELECT
  res.component_id,
  number,
  description,
  agency_type,
  parent,
  sort_order,
  row_type,
  (res.fr_upto_phy - res.exp_upto_phy) ob_phy,
  (res.fr_upto_fin - res.exp_upto_fin) ob_fin,
  res.fr_upto_cy_phy fr_upto_phy,
  res.fr_upto_cy_fin fr_upto_fin,
  res.fr_mon_phy,
  res.fr_mon_fin,
  (res.fr_upto_cy_phy + res.fr_mon_phy) fr_cum_phy,
  (res.fr_upto_cy_fin + res.fr_mon_fin) fr_cum_fin,
  res.exp_upto_cy_phy exp_upto_phy,
  res.exp_upto_cy_fin exp_upto_fin,
  res.exp_mon_phy,
  res.exp_mon_fin,
  (res.exp_upto_cy_phy + res.exp_mon_phy) exp_cum_phy,
  (res.exp_upto_cy_fin + res.exp_mon_fin) exp_cum_fin,
  (res.fr_upto_phy - res.exp_upto_phy + res.fr_mon_phy - res.exp_mon_phy) cb_phy,
  (res.fr_upto_fin - res.exp_upto_fin + res.fr_mon_fin - res.exp_mon_fin) cb_fin
FROM (SELECT
    comp.*,
    COALESCE(expn_mon.phy, 0) exp_mon_phy,
    COALESCE(expn_mon.fin, 0) exp_mon_fin,
    COALESCE(fr_mon.phy, 0) fr_mon_phy,
    COALESCE(fr_mon.fin, 0) fr_mon_fin,
    COALESCE(exp_upto.phy, 0) exp_upto_phy,
    COALESCE(exp_upto.fin, 0) exp_upto_fin,
    COALESCE(fr_upto.phy, 0) fr_upto_phy,
    COALESCE(fr_upto.fin, 0) fr_upto_fin,
    COALESCE(exp_upto_cy.phy, 0) exp_upto_cy_phy,
    COALESCE(exp_upto_cy.fin, 0) exp_upto_cy_fin,
    COALESCE(fr_upto_cy.phy, 0) fr_upto_cy_phy,
    COALESCE(fr_upto_cy.fin, 0) fr_upto_cy_fin
  FROM (SELECT
      c.id component_id,
      c.number,
      c.description,
      c.parent,
      c.sort_order,
      c.row_type,
      `at`.name agency_type
    FROM soe_agency_components ac
      LEFT JOIN soe_components c
        ON ac.component_id = c.id LEFT JOIN user_group `at` ON `at`.id=ac.agency_type_id
    WHERE ac.deleted_at IS NULL AND c.deleted_at IS NULL";
        if(!empty($filter['block_id'])){
            $sql .= " AND block_id = ".$filter['block_id'];
        } else {
            $sql .= " AND block_id = 0";
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND district_id = ".$filter['district_id'];
            $filter['user_group'] = null;
        } else {
            $sql .= " AND district_id = 0";
        }

        if (!empty($filter['user_group'])) {
            $sql .= " AND agency_type_id = " . $filter['user_group'];
        }

        $sql .= " 
    ORDER BY c.sort_order) comp
    LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'expense'";
      //not required
        //$sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND t.month = ".$filter['month'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND t.year = ".$filter['year'];
        }
        $sql .= " GROUP BY tc.component_id) expn_mon
      ON comp.component_id = expn_mon.component_id
    LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'fund_receipt'";
      //not required
        //$sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND t.month = ".$filter['month'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND t.year = ".$filter['year'];
        }
        $sql .= " GROUP BY tc.component_id) fr_mon
      ON comp.component_id = fr_mon.component_id
    LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'expense'";

        //not required
        //$sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['month']) && !empty($filter['year'])){
            $last_year = ($filter['year'] - 1);
            $last_month = ($filter['month'] - 1);
            $sql .= " AND ((t.year BETWEEN 0 AND $last_year)
      OR (t.year = ".$filter['year']."
      AND t.month BETWEEN 0 AND $last_month))";
        }
       $sql .= " GROUP BY tc.component_id) exp_upto
      ON comp.component_id = exp_upto.component_id
    LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'fund_receipt'";
      //not required
        //$sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['month']) && !empty($filter['year'])){
            $last_year = ($filter['year'] - 1);
            $last_month = ($filter['month']);
            $sql .= " AND ((t.year BETWEEN 0 AND $last_year)
      OR (t.year = ".$filter['year']."
      AND t.month BETWEEN 0 AND $last_month))";
        }
      $sql .= " GROUP BY tc.component_id) fr_upto
      ON comp.component_id = fr_upto.component_id
    LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'expense'";

        //not required
        //$sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND t.year = ".$filter['year'];
        }
        if(!empty($filter['month']) && !empty($filter['year'])){
            $last_month = ($filter['month'] - 1);
            $sql .= " AND t.month BETWEEN 0 AND $last_month";
        }
       $sql .= " GROUP BY tc.component_id) exp_upto_cy
      ON comp.component_id = exp_upto_cy.component_id
    LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'fund_receipt'";
      //not required
        //$sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND t.year = ".$filter['year'];
        }
        if(!empty($filter['month']) && !empty($filter['year'])){
            $last_month = ($filter['month'] - 1);
            $sql .= " AND t.month BETWEEN 0 AND $last_month";
        }
      $sql .= " GROUP BY tc.component_id) fr_upto_cy
      ON comp.component_id = fr_upto_cy.component_id) res ORDER BY sort_order";

        return $this->db->query($sql)->getResultArray();
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

    public function appendDetails($txn) {
        if(is_array($txn['data'])){
            $model = new BlockModel();
            $block = $model->find($txn['data']['block_id']);
            $txn['data']['block'] = null;
            if($block)
                $txn['data']['block'] = $block->name;

            $model = new DistrictModel();
            $district = $model->find($txn['data']['district_id']);
            $txn['data']['district'] = null;
            if($district)
                $txn['data']['district'] = $district->name;

            $model = new TransactionComponentModel();
            $comp = $model->getTotalPhyFin($txn['data']->id);
            $txn['data']['physical'] = $comp->physical;
            $txn['data']['financial'] = $comp->financial;
        }
        if(is_object($txn['data'])){
            $model = new BlockModel();
            $block = $model->find($txn['data']->block_id);
            $txn['data']->block = null;
            if($block)
                $txn['data']->block = $block->name;

            $model = new DistrictModel();
            $district = $model->find($txn['data']->district_id);
            $txn['data']->district = null;
            if($district)
                $txn['data']->district = $district->name;

            $model = new TransactionComponentModel();
            $comp = $model->getTotalPhyFin($txn['data']->id);
            $txn['data']->physical = $comp->physical;
            $txn['data']->financial= $comp->financial;
        }

        return $txn;
    }

    public function getTotalAmount($filter=[]) {
        $sql = "SELECT
  COALESCE(SUM(tc.physical), 0) phy,
  COALESCE(SUM(tc.financial), 0) fin
FROM soe_transaction_components tc
  LEFT JOIN soe_transactions t
    ON tc.transaction_id = t.id
WHERE t.deleted_at IS NULL
AND tc.deleted_at IS NULL
AND t.transaction_type = 'expense'";
        if(!empty($filter['transaction_type'])){
            $sql .= " AND t.transaction_type = '".$filter['transaction_type']."'";
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND t.year = ".$filter['year'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND t.month BETWEEN 0 AND ".($filter['month']-1);
        }

        return $this->db->query($sql)->getRow();
    }

    public function getTransactionStatus($filter=[]) {
        $sql = "SELECT
  bmym.district_id,
  sd.name district,
  bmym.block_id,
  bmym.block,
  sts.id txn_id,
  sts.created_at,
  bmym.module,
  sts.transaction_type,
  IFNULL(sts.status, 3) status,
  bmym.year_id,
  bmym.year,
  bmym.month_id,
  bmym.month,
  sts.agency_type_id,
  ug.name agency_type,
  sts.user_id,
  sts.fund_agency_id
FROM (SELECT
    *
  FROM vw_block_modules
    CROSS JOIN vw_all_year_month vaym
  WHERE 1=1";
        if(!empty($filter['district_id'])){
            $sql .= " AND district_id = ".$filter['district_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND block_id = ".$filter['block_id'];
        }
        if(!empty($filter['year_id'])){
            $sql .= " AND year_id = ".$filter['year_id'];
        }
        if(!empty($filter['year_id']) && !empty($filter['month_id'])){
            $sql .= " AND month_id = ".$filter['month_id'];
        }
        $sql .= " ) bmym
  LEFT JOIN (SELECT
      st.id,
      st.year,
      st.month,
      st.date_added created_at,
      st.transaction_type,
      st.block_id,
      st.district_id,
      st.agency_type_id,
      st.user_id,
      st.status,
      st.fund_agency_id
    FROM soe_transactions st
    WHERE st.deleted_at IS NULL
    UNION
    (SELECT
        smt.id,
        smt.year,
        smt.month,
        smt.created_at,
        'other_receipt' transaction_type,
        smt.block_id,
        smt.district_id,
        smt.agency_type_id,
        smt.user_id,
        smt.status,
      smt.fund_agency_id
      FROM soe_misc_transactions smt
      WHERE smt.deleted_at IS NULL)
    UNION
    (SELECT
        scb.id,
        scb.year,
        scb.month,
        scb.created_at,
        'closing_balance' transaction_type,
        scb.block_id,
        scb.district_id,
        scb.agency_type_id,
        scb.user_id,
        scb.status,
      sb.fund_agency_id
      FROM soe_closing_balances scb
        LEFT JOIN soe_blocks sb
          ON scb.block_id = sb.id
      WHERE scb.deleted_at IS NULL
      AND scb.year > 0
      AND scb.month > 0)) sts
    ON bmym.block_id = sts.block_id
    AND sts.year = bmym.year_id
    AND sts.month = bmym.month_id
    AND sts.transaction_type = bmym.modulecode
  LEFT JOIN soe_districts sd
    ON bmym.district_id = sd.id
  LEFT JOIN user_group ug
    ON sts.agency_type_id = ug.id HAVING 1=1";
        if(!empty($filter['user_id'])){
            $sql .= " AND (user_id !=".$filter['user_id'].' OR user_id IS NULL)';
        }
        if(!empty($filter['transaction_type'])){
            $sql .= " AND transaction_type = '".$filter['transaction_type'].'\'';
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND (sts.fund_agency_id =".$filter['fund_agency_id'].' OR fund_agency_id IS NULL)';
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND agency_type_id =".$filter['agency_type_id'];
        }
$sql .= " ORDER BY district_id,block_id,bmym.month_id, sts.transaction_type";

        return $this->db->query($sql)->getResult();
    }
}
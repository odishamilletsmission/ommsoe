<?php 
namespace Admin\Reports\Models;
use CodeIgniter\Model;

class ReportsModel extends Model {

    public function getMpr($filter=[]) {

        $last_year = ($filter['year_id']-1);

        $sql = "SELECT
  res.component_id,
  `number`,
  description,
  agency_type,
  parent,
  sort_order,
  row_type,
  (res.fr_upto_phy - res.exp_upto_phy) ob_phy,
  (res.fr_upto_fin - res.exp_upto_fin) ob_fin,
  res.bud_phy,
  res.bud_fin,
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
    bud.phy bud_phy,
    bud.fin bud_fin,
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
  sc.id component_id,
  sc.number,
  sc.description,
  sc.parent,
  sc.sort_order,
  sc.row_type,
  ug.name agency_type,
  sc.category
FROM (SELECT * FROM soe_components
WHERE deleted_at IS NULL)sc
  LEFT JOIN (SELECT * FROM soe_budgets2
  GROUP BY component_id) sb 
    ON sb.component_id = sc.id
  LEFT JOIN user_group ug
    ON sb.agency_type_id = ug.id WHERE 1=1";
        if (!empty($filter['agency_type_id'])) {
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND ( sb.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND ( sb.agency_type_id = " . $filter['agency_type_id'];
            }
            $sql .= " OR sb.agency_type_id is NULL OR sc.row_type = 'heading')";
        }
        if(!empty($filter['category'])){
            if(is_array($filter['category'])){
                $sql .= " AND sc.category IN (" . implode(',',$filter['category']) . ")";
            } else {
                $sql .= " AND sc.category = '".$filter['category']."'";
            }
        }
        $sql .= " ) comp LEFT JOIN
    (SELECT
  sbb.block_id,
  sbb.budget_id,
  sbb.district_id,
  sb.component_id,
  sb.year,
  sb.unit_cost,
  SUM(sb.physical) phy,
  SUM(sb.financial) fin,
  sb.agency_type_id,sb.block_category
  FROM soe_block_budgets sbb
  LEFT JOIN soe_budgets2 sb
    ON sbb.budget_id = sb.id
WHERE sb.deleted_at IS NULL
AND sbb.deleted_at IS NULL";
        if(!empty($filter['block_id'])){
            $sql .= " AND sbb.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND sbb.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['year_id'])){
            $sql .= " AND sb.year = ".$filter['year_id'];
        }
        if (!empty($filter['agency_type_id'])) {
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND ( sb.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND ( sb.agency_type_id = " . $filter['agency_type_id'];
            }
            $sql .= " OR sb.agency_type_id is NULL)";
        }
$sql .= " GROUP BY sb.component_id) bud ON bud.component_id=comp.component_id";
        /* ******************transaction starts****************** */
        /* ******************month's expense****************** */
    $sql .= " LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'expense'";
        $sql .= " AND t.status = 1";

        if(isset($filter['block_user']) && $filter['block_user']){
            if(!empty($filter['user_id'])){
                $sql .= " AND t.user_id = ".$filter['user_id'];
            }
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month_id'])){
            $sql .= " AND t.month = ".$filter['month_id'];
        }
        if(!empty($filter['year_id'])){
            $sql .= " AND t.year = ".$filter['year_id'];
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND t.fund_agency_id = ".$filter['fund_agency_id'];
        }
        $sql .= " GROUP BY tc.component_id) expn_mon
      ON comp.component_id = expn_mon.component_id";
        /* ******************month's fundreceipt****************** */
    $sql .= " LEFT JOIN (SELECT
        tc.component_id,
        SUM(physical) phy,
        SUM(financial) fin
      FROM soe_transactions t
        LEFT JOIN soe_transaction_components tc
          ON t.id = tc.transaction_id
      WHERE t.deleted_at IS NULL
      AND tc.deleted_at IS NULL
      AND t.transaction_type = 'fund_receipt'";
        $sql .= " AND t.status = 1";
        if(isset($filter['block_user']) && $filter['block_user']){
            if(!empty($filter['user_id'])){
                $sql .= " AND t.user_id = ".$filter['user_id'];
            }
        } else if(empty($filter['block_id'])) {
            // exclude block fund receipt when user is not block user.
            $sql .= " AND t.agency_type_id NOT IN (" . implode(',',$filter['block_users']).")";
        }

        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
            if(!empty($filter['block_user_id'])){
                $sql .= " AND t.user_id = ".$filter['block_user_id'];
            }
        }
        if(!empty($filter['month_id'])){
            $sql .= " AND t.month = ".$filter['month_id'];
        }
        if(!empty($filter['year_id'])){
            $sql .= " AND t.year = ".$filter['year_id'];
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND t.fund_agency_id = ".$filter['fund_agency_id'];
        }
        $sql .= " GROUP BY tc.component_id) fr_mon
      ON comp.component_id = fr_mon.component_id";
        /* ******************expense upto last month****************** */
    $sql .= "
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
        $sql .= " AND t.status = 1";
        if(isset($filter['block_user']) && $filter['block_user']){
            if(!empty($filter['user_id'])){
                $sql .= " AND t.user_id = ".$filter['user_id'];
            }
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $this_month = ($filter['month_id'] - 1);
            $sql .= " AND ((t.year BETWEEN 0 AND $last_year)
      OR (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $this_month))";
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND t.fund_agency_id = ".$filter['fund_agency_id'];
        }
        $sql .= " GROUP BY tc.component_id) exp_upto
      ON comp.component_id = exp_upto.component_id";
        /* ******************fundreceipt upto last month****************** */
    $sql .= "
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
        $sql .= " AND t.status = 1";
        if(isset($filter['block_user']) && $filter['block_user']){
            if(!empty($filter['user_id'])){
                $sql .= " AND t.user_id = ".$filter['user_id'];
            }
        } else if(empty($filter['block_id'])) {
            // exclude block fund receipt when user is not block user.
            $sql .= " AND t.agency_type_id NOT IN (" . implode(',',$filter['block_users']).")";
        }

        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
            if(!empty($filter['block_user_id'])){
                $sql .= " AND t.user_id = ".$filter['block_user_id'];
            }
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $last_month = ($filter['month_id'] - 1);
            $sql .= " AND ((t.year BETWEEN 0 AND $last_year)
      OR (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $last_month))";
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND t.fund_agency_id = ".$filter['fund_agency_id'];
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
      AND t.transaction_type = 'fund_receipt'";
        $sql .= " AND t.status = 1";
        if(isset($filter['block_user']) && $filter['block_user']){
            if(!empty($filter['user_id'])){
                $sql .= " AND t.user_id = ".$filter['user_id'];
            }
        } else if(empty($filter['block_id'])) {
            // exclude block fund receipt when user is not block user.
            $sql .= " AND t.agency_type_id NOT IN (" . implode(',',$filter['block_users']).")";
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
            if(!empty($filter['block_user_id'])){
                $sql .= " AND t.user_id = ".$filter['block_user_id'];
            }
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $last_month = ($filter['month_id'] - 1);
            $sql .= " AND (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $last_month)";
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND t.fund_agency_id = ".$filter['fund_agency_id'];
        }
        $sql .= " GROUP BY tc.component_id) fr_upto_cy
      ON comp.component_id = fr_upto_cy.component_id
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
        $sql .= " AND t.status = 1";
        if(isset($filter['block_user']) && $filter['block_user']){
            if(!empty($filter['user_id'])){
                $sql .= " AND t.user_id = ".$filter['user_id'];
            }
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $last_month = ($filter['month_id'] - 1);
            $sql .= " AND (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $last_month)";
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND t.fund_agency_id = ".$filter['fund_agency_id'];
        }
        $sql .= " GROUP BY tc.component_id) exp_upto_cy
      ON comp.component_id = exp_upto_cy.component_id) res ORDER BY sort_order";
//        echo $sql;exit;
        return $this->db->query($sql)->getResultArray();

    }

    public function getAbstractMpr($filter=[]) {

        $last_year = ($filter['year_id']-1);

        $sql = "SELECT mpr.block_category,
SUM(mpr.ob_phy) ob_phy,
SUM(mpr.ob_fin) ob_fin,
SUM(mpr.bud_phy) bud_phy,
SUM(mpr.bud_fin) bud_fin,
SUM(mpr.fr_upto_phy) fr_upto_phy,
SUM(mpr.fr_upto_fin) fr_upto_fin,
SUM(mpr.fr_mon_phy) fr_mon_phy,
SUM(mpr.fr_mon_fin) fr_mon_fin,
SUM(mpr.fr_cum_phy) fr_cum_phy,
SUM(mpr.fr_cum_fin) fr_cum_fin,
SUM(mpr.exp_upto_phy) exp_upto_phy,
SUM(mpr.exp_upto_fin) exp_upto_fin,
SUM(mpr.exp_mon_phy) exp_mon_phy,
SUM(mpr.exp_mon_fin) exp_mon_fin,
SUM(mpr.exp_cum_phy) exp_cum_phy,
SUM(mpr.exp_cum_fin) exp_cum_fin,
SUM(mpr.cb_phy) cb_phy,
SUM(mpr.cb_fin) cb_fin
FROM (SELECT
  res.component_id,
  `number`,
  description,
  agency_type,
  parent,
  sort_order,
  row_type,
  res.block_category,
  (res.fr_upto_phy - res.exp_upto_phy) ob_phy,
  (res.fr_upto_fin - res.exp_upto_fin) ob_fin,
  res.bud_phy,
  res.bud_fin,
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
    bud.block_category,
    bud.phy bud_phy,
    bud.fin bud_fin,
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
  sc.id component_id,
  sc.number,
  sc.description,
  sc.parent,
  sc.sort_order,
  sc.row_type,
  ug.name agency_type,
  sc.category
FROM (SELECT * FROM soe_components
WHERE deleted_at IS NULL)sc
  LEFT JOIN (SELECT * FROM soe_budgets2
  GROUP BY component_id) sb 
    ON sb.component_id = sc.id
  LEFT JOIN user_group ug
    ON sb.agency_type_id = ug.id WHERE 1=1";
        if (!empty($filter['agency_type_id'])) {
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND ( sb.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND ( sb.agency_type_id = " . $filter['agency_type_id'];
            }
            $sql .= " OR sb.agency_type_id is NULL OR sc.row_type = 'heading')";
        }
        if(!empty($filter['category'])){
            if(is_array($filter['category'])){
                $sql .= " AND sc.category IN (" . implode(',',$filter['category']) . ")";
            } else {
                $sql .= " AND sc.category = '".$filter['category']."'";
            }
        }
        $sql .= " ) comp LEFT JOIN
    (SELECT
  sbb.block_id,
  sbb.budget_id,
  sbb.district_id,
  sb.component_id,
  sb.year,
  sb.unit_cost,
  SUM(sb.physical) phy,
  SUM(sb.financial) fin,
  sb.agency_type_id,sb.block_category
  FROM soe_block_budgets sbb
  LEFT JOIN soe_budgets2 sb
    ON sbb.budget_id = sb.id
WHERE sb.deleted_at IS NULL
AND sbb.deleted_at IS NULL";
        if(!empty($filter['block_id'])){
            $sql .= " AND sbb.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND sbb.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['year_id'])){
            $sql .= " AND sb.year = ".$filter['year_id'];
        }
        if (!empty($filter['agency_type_id'])) {
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND ( sb.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND ( sb.agency_type_id = " . $filter['agency_type_id'];
            }
            $sql .= " OR sb.agency_type_id is NULL)";
        }
$sql .= " GROUP BY sb.component_id) bud ON bud.component_id=comp.component_id
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
        $sql .= " AND t.status = 1";
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
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND t.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND t.agency_type_id = " . $filter['agency_type_id'];
            }
        }
        if(!empty($filter['month_id'])){
            $sql .= " AND t.month = ".$filter['month_id'];
        }
        if(!empty($filter['year_id'])){
            $sql .= " AND t.year = ".$filter['year_id'];
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
        $sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
            //exclude fr from block
            if(empty($filter['block_id'])) {
                //$sql .= " AND t.block_id = 0";
            }
        } else {
            //$sql .= " AND t.block_id = 0";
        }

        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND t.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND t.agency_type_id = " . $filter['agency_type_id'];
            }
        }
        if(!empty($filter['month_id'])){
            $sql .= " AND t.month = ".$filter['month_id'];
        }
        if(!empty($filter['year_id'])){
            $sql .= " AND t.year = ".$filter['year_id'];
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
        $sql .= " AND t.status = 1";
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
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND t.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND t.agency_type_id = " . $filter['agency_type_id'];
            }
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $this_month = ($filter['month_id'] - 1);
            $sql .= " AND ((t.year BETWEEN 0 AND $last_year)
      OR (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $this_month))";
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
        $sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
            //exclude fr from block
            if(empty($filter['block_id'])) {
                //$sql .= " AND t.block_id = 0";
            }
        }
        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND t.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND t.agency_type_id = " . $filter['agency_type_id'];
            }
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $last_month = ($filter['month_id'] - 1);
            $sql .= " AND ((t.year BETWEEN 0 AND $last_year)
      OR (t.year = ".$filter['year_id']."
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
      AND t.transaction_type = 'fund_receipt'";
        $sql .= " AND t.status = 1";
        if(!empty($filter['user_id'])){
            $sql .= " AND t.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['block_id'])){
            $sql .= " AND t.block_id = ".$filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND t.district_id = ".$filter['district_id'];
            //exclude fr from block
            if(empty($filter['block_id'])) {
                //$sql .= " AND t.block_id = 0";
            }
        }
        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND t.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND t.agency_type_id = " . $filter['agency_type_id'];
            }
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $last_month = ($filter['month_id'] - 1);
            $sql .= " AND (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $last_month)";
        }
        $sql .= " GROUP BY tc.component_id) fr_upto_cy
      ON comp.component_id = fr_upto_cy.component_id
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
        $sql .= " AND t.status = 1";
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
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND t.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND t.agency_type_id = " . $filter['agency_type_id'];
            }
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $last_month = ($filter['month_id'] - 1);
            $sql .= " AND (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $last_month)";
        }
        $sql .= " GROUP BY tc.component_id) exp_upto_cy
      ON comp.component_id = exp_upto_cy.component_id) res ) mpr GROUP BY block_category HAVING block_category is not null";
//echo $sql;exit;
        return $this->db->query($sql)->getResultArray();

    }

    //dashboard
    public function getOpeningBalanceTotal($filter=[]) {

        $filter['transaction_type'] = 'fund_receipt';
        $filter['month_upto'] = ($filter['month']-1);
        $filter['month'] = null;

        $fr = $this->getTransactionTotal($filter);

        $filter['transaction_type'] = 'expense';

        $ex = $this->getTransactionTotal($filter);

        return $fr-$ex;
    }

    public function getTransactionTotal($filter=[]) {
        $sql = "SELECT
  SUM(stc.financial) total
FROM soe_transactions st
  LEFT JOIN soe_transaction_components stc
    ON st.id = stc.transaction_id
WHERE st.deleted_at IS NULL
AND stc.deleted_at IS NULL AND st.status = 1";

        $sql .= $this->appendFilter($filter);

        return $this->db->query($sql)->getFirstRow()->total;
    }

    public function getClosingBalanceTotal($filter=[]) {
        $ob = (float)$this->getOpeningBalanceTotal($filter);

        $filter['transaction_type'] = 'fund_receipt';
        $fr = (float)$this->getTransactionTotal($filter);

        $filter['transaction_type'] = 'expense';
        $ex = (float)$this->getTransactionTotal($filter);

        return $ob+$fr-$ex;
    }

    protected function appendFilter($filter) {
        $sql = "";
        if(!empty($filter['user_id'])){
            $sql .= " AND st.user_id = ".$filter['user_id'];
        }
        if(!empty($filter['transaction_type'])){
            $sql .= " AND st.transaction_type = '".$filter['transaction_type']."'";
        }
        if(!empty($filter['block_id'])){
            if(is_array($filter['block_id'])){
                $sql .= " AND st.block_id IN (".implode(',',$filter['block_id']).")";
            } else {
                $sql .= " AND st.block_id = " . $filter['block_id'];
            }
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND st.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND st.year = ".$filter['year'];
        }
        if(!empty($filter['year_upto'])){
            $sql .= " AND st.year BETWEEN 0 AND ".$filter['year_upto'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND st.month = ".$filter['month'];
        }
        if(!empty($filter['month_upto'])){
            $sql .= " AND ((st.year BETWEEN 0 AND ".($filter['year']-1).')';
            $sql .= " OR (st.year = ".$filter['year']." AND st.month BETWEEN 0 AND ".$filter['month_upto'].'))';
        }
        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])) {
                $sql .= " AND st.agency_type_id IN (" . implode(',',$filter['agency_type_id']) . ")";
            } else {
                $sql .= " AND st.agency_type_id = " . $filter['agency_type_id'];
            }
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND st.fund_agency_id = ".$filter['fund_agency_id'];
        }

        return $sql;
    }

    public function getInterestReport($filter=[]) {

        $bi_head_id = 0;
        $ref_head_id = 0;
        if(!empty($filter['agency_type_id'])){
            if($filter['agency_type_id']==7){
                $bi_head_id = 6;
                $ref_head_id = 8;
            } else {
                $bi_head_id = 2;
                $ref_head_id = 4;
            }
        }
        $year = getCurrentYearId();
        $last_year = $year-1;
        if(!empty($filter['year_id'])){
            $year = $filter['year_id'];
            $last_year = ($filter['year_id']-1);
        }
        $month = getMonthIdByMonth(date('m'));
        $last_month = $month-1;
        if(!empty($filter['month_id'])){
            $month = $filter['month_id'];
            $last_month = ($filter['month_id']-1);
        }

        $sql = "SELECT
  user.user_id,
  user.block_id,
  user.user_group_id,
  user.block,
  COALESCE(int_upto.total_interest, 0) int_upto,
  COALESCE(int_mon.total_interest, 0) int_mon,
  COALESCE(int_ref.total_refund, 0) int_ref_block
FROM (SELECT
    u.id user_id,
    u.block_id,
    u.user_group_id,
    COALESCE(sb.name, 'ATMA') block
  FROM user u
    LEFT JOIN soe_blocks sb
      ON u.block_id = sb.id
  WHERE 1=1";
        if(!empty($filter['district_id'])){
            $sql .= " AND u.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND u.user_group_id IN (" . implode(',',$filter['agency_type_id']).')';
            } else {
                $sql .= " AND u.user_group_id = " . $filter['agency_type_id'];
            }
        }
        $sql .= " GROUP BY u.block_id) user
  LEFT JOIN (SELECT
      smt.user_id,
      smt.block_id,
      SUM(smta.amount) total_interest
    FROM soe_misc_transactions smt
      LEFT JOIN soe_misc_txn_amt smta
        ON smt.id = smta.txn_id
    WHERE smt.deleted_at IS NULL
    AND smta.deleted_at IS NULL
    AND smt.status = 1";
        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND smt.agency_type_id IN (" . implode(',',$filter['agency_type_id']).')';
            } else {
                $sql .= " AND smt.agency_type_id = " . $filter['agency_type_id'];
            }
        }
    $sql .= " AND ((smt.year BETWEEN 0 AND $last_year)
    OR (smt.year = $year
    AND smt.month BETWEEN 0 AND $last_month))
    AND smta.head_id = $bi_head_id
    GROUP BY smt.block_id) int_upto
    ON int_upto.block_id = user.block_id
  LEFT JOIN (SELECT
      smt.user_id,
      smt.block_id,
      SUM(smta.amount) total_interest
    FROM soe_misc_transactions smt
      LEFT JOIN soe_misc_txn_amt smta
        ON smt.id = smta.txn_id
    WHERE smt.deleted_at IS NULL
    AND smta.deleted_at IS NULL
    AND smt.status = 1";
        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND smt.agency_type_id IN (" . implode(',',$filter['agency_type_id']).')';
            } else {
                $sql .= " AND smt.agency_type_id = " . $filter['agency_type_id'];
            }
        }
    $sql .= " AND (smt.year = $year
    AND smt.month = $month)
    AND smta.head_id = $bi_head_id
    GROUP BY smt.block_id) int_mon
    ON int_mon.block_id = user.block_id
  LEFT JOIN (SELECT
      smt.user_id,
      smt.block_id,
      SUM(smta.amount) total_refund
    FROM soe_misc_transactions smt
      LEFT JOIN soe_misc_txn_amt smta
        ON smt.id = smta.txn_id
    WHERE smt.deleted_at IS NULL
    AND smta.deleted_at IS NULL
    AND smt.status = 1";
        if(!empty($filter['agency_type_id'])){
            if(is_array($filter['agency_type_id'])){
                $sql .= " AND smt.agency_type_id IN (" . implode(',',$filter['agency_type_id']).')';
            } else {
                $sql .= " AND smt.agency_type_id = " . $filter['agency_type_id'];
            }
        }
    $sql .= " AND ((smt.year BETWEEN 0 AND $last_year)
    OR (smt.year = $year
    AND smt.month BETWEEN 0 AND $month))
    AND smta.head_id = $ref_head_id
    GROUP BY smt.block_id) int_ref
    ON int_ref.block_id = user.block_id
  ORDER BY user_group_id, block";

        return $this->db->query($sql)->getResultArray();
    }

    public function getUploadStatus($filter=[]) {

        $sql = "SELECT * FROM 
(SELECT
  dist.id district_id,
  dist.name district,
  bl.id block_id,
  bl.name block,
  fch.status fch_status,
  fr.status fr_status,
  ex.status ex_status,
  `or`.status or_status,
  cb.status cb_status,
  mis.status mis_status
FROM (SELECT
    *
  FROM soe_blocks sb WHERE 1=1";
        if(!empty($filter['district_id'])){
            $sql .= " AND sb.district_id = '".$filter['district_id']."'";
        }
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND sb.fund_agency_id = '".$filter['fund_agency_id']."'";
        }
        $sql .= ") bl LEFT JOIN soe_districts dist ON bl.district_id=dist.id
  LEFT JOIN (SELECT
      *
    FROM soe_fund_receipt_check sfrc
    WHERE sfrc.month = ".$filter['month']."
    AND sfrc.year = ".$filter['year'].") fch
    ON bl.id = fch.block_id
  LEFT JOIN (SELECT
      *
    FROM soe_transactions st
    WHERE st.deleted_at IS NULL
    AND st.transaction_type = 'fund_receipt'
    AND st.month = ".$filter['month']."
    AND st.year = ".$filter['year']." AND st.user_id IN 
    (SELECT id FROM `user` u WHERE u.block_id=st.block_id AND u.user_group_id=5)) fr
    ON bl.id = fr.block_id
  LEFT JOIN (SELECT
      *
    FROM soe_transactions st
    WHERE st.deleted_at IS NULL
    AND st.transaction_type = 'expense'
    AND st.month = ".$filter['month']."
    AND st.year = ".$filter['year']." AND st.user_id IN 
    (SELECT id FROM `user` u WHERE u.block_id=st.block_id AND u.user_group_id=5)) ex
    ON bl.id = ex.block_id
  LEFT JOIN (SELECT
      *
    FROM soe_misc_transactions smt
    WHERE smt.deleted_at IS NULL
    AND smt.month = ".$filter['month']."
    AND smt.year = ".$filter['year']." AND smt.user_id IN 
    (SELECT id FROM `user` u WHERE u.block_id=smt.block_id AND u.user_group_id=5)) `or`
    ON bl.id = `or`.block_id
  LEFT JOIN (SELECT
      *
    FROM soe_closing_balances scb
    WHERE scb.deleted_at IS NULL
    AND scb.month = ".$filter['month']."
    AND scb.year = ".$filter['year']." AND scb.user_id IN 
    (SELECT id FROM `user` u WHERE u.block_id=scb.block_id AND u.user_group_id=5)) cb
    ON bl.id = cb.block_id
  LEFT JOIN (SELECT
      *
    FROM mis_submissions ms
    WHERE ms.deleted_at IS NULL
    AND ms.month = ".$filter['month']."
    AND ms.year = ".$filter['year']."
    AND ms.user_id IN (SELECT
        id
      FROM `user` u
      WHERE u.block_id = ms.block_id
      AND u.user_group_id = 5)) mis
    ON bl.id = mis.block_id GROUP BY block_id) bl_sts
    
    UNION ALL 

(SELECT
  dist.district_id,dist.district,
  dist.district_id block_id,
  CONCAT('ATMA ',dist.district) block,
  dist_frc.status fch_status,
  fr.status fr_status,
  ex.status ex_status,
  `or`.status or_status,
  cb.status cb_status,
  mis.status mis_status
FROM (SELECT
    sd.id district_id,
    sd.name district,
    u.id user_id
  FROM soe_districts sd LEFT JOIN user u ON sd.id=u.district_id
  WHERE 1=1 AND block_id=0";
        if(!empty($filter['fund_agency_id'])){
            $sql .= " AND fund_agency_id = '".$filter['fund_agency_id']."'";
        }
        if(!empty($filter['district_id'])){
            $sql .= " AND sd.id = '".$filter['district_id']."'";
        }
        $sql .= " ) dist
  LEFT JOIN (SELECT
      *
    FROM soe_fund_receipt_check sfrc
    WHERE sfrc.month = ".$filter['month']."
    AND sfrc.year = ".$filter['year']."
    AND sfrc.block_id = 0) dist_frc
    ON dist.district_id = dist_frc.district_id
  LEFT JOIN (SELECT
      *
    FROM soe_transactions st
    WHERE st.deleted_at IS NULL
    AND st.transaction_type = 'fund_receipt'
    AND st.month = ".$filter['month']."
    AND st.year = ".$filter['year']."
    AND st.block_id = 0
    AND st.user_id IN (SELECT
        id
      FROM `user` u
      WHERE u.district_id = st.district_id
      AND u.user_group_id = 7)) fr
    ON dist.district_id = fr.district_id
  LEFT JOIN (SELECT
      *
    FROM soe_transactions st
    WHERE st.deleted_at IS NULL
    AND st.transaction_type = 'expense'
    AND st.month = ".$filter['month']."
    AND st.year = ".$filter['year']."
    AND st.block_id = 0
    AND st.user_id IN (SELECT
        id
      FROM `user` u
      WHERE u.district_id = st.district_id
      AND u.user_group_id = 7)) ex
    ON dist.district_id = ex.district_id
  LEFT JOIN (SELECT
      *
    FROM soe_misc_transactions smt
    WHERE smt.deleted_at IS NULL
    AND smt.month = ".$filter['month']."
    AND smt.year = ".$filter['year']."
    AND smt.block_id = 0
    AND smt.user_id IN (SELECT
        id
      FROM `user` u
      WHERE u.district_id = smt.district_id
      AND u.user_group_id = 7)) `or`
    ON dist.district_id = `or`.district_id
  LEFT JOIN (SELECT
      *
    FROM soe_closing_balances scb
    WHERE scb.deleted_at IS NULL
    AND scb.month = ".$filter['month']."
    AND scb.year = ".$filter['year']."
    AND scb.block_id = 0
    AND scb.user_id IN (SELECT
        id
      FROM `user` u
      WHERE u.district_id = scb.district_id
      AND u.user_group_id IN (7))) cb
    ON dist.district_id = cb.district_id
  LEFT JOIN (SELECT
      *
    FROM mis_submissions ms
    WHERE ms.deleted_at IS NULL
    AND ms.month = ".$filter['month']."
    AND ms.year = ".$filter['year']."
    AND ms.block_id = 0
    AND ms.user_id IN (SELECT
        id
      FROM `user` u
      WHERE u.district_id = ms.district_id
      AND u.user_group_id = 7)) mis
    ON dist.district_id = mis.district_id) ORDER BY district,block";

        return $this->db->query($sql)->getResult();
    }
}
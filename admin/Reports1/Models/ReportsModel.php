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
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
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
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
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
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
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
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
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
        }
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
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
            $sql .= " AND t.agency_type_id = ".$filter['agency_type_id'];
        }
        if(!empty($filter['month_id']) && !empty($filter['year_id'])){
            $last_month = ($filter['month_id'] - 1);
            $sql .= " AND (t.year = ".$filter['year_id']."
      AND t.month BETWEEN 0 AND $last_month)";
        }
        $sql .= " GROUP BY tc.component_id) exp_upto_cy
      ON comp.component_id = exp_upto_cy.component_id) res ORDER BY sort_order";

        return $this->db->query($sql)->getResultArray();

    }

}
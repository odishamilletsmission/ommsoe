<?php
namespace Admin\UC\Models;

use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class UCAllotmentModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'soe_uc_allotment';
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
	protected $updatedField         = '';
	protected $deletedField         = 'deleted_at';

    public function getAllRecipients() {
        $sql = "SELECT * FROM soe_uc_recipients";

        return $this->db->query($sql)->getResultArray();
    }

    public function getUCReport($filter=[]) {
        $sql = "SELECT
  allt.recipient_id,
  allt.recipient,
  allt.district_id,
  COALESCE(allt.total_allotment,0) total_allotment,
  sbmt.date_submit,
  sbmt.letter_no,
  COALESCE(sbmt.uc_submit,0) uc_amount,
  COALESCE(upto.total_allotment,0) total_allotment_upto,
  COALESCE(upto.total_submitted,0) total_submitted_upto
FROM (SELECT
    sur.id recipient_id,
    sur.name recipient,
    sur.district_id,
    alt.total_allotment
  FROM (SELECT
      *
    FROM soe_uc_recipients
    WHERE fund_agency_id = ".$filter['fund_agency_id'].") sur
    LEFT JOIN (SELECT
        sua.id allotment_id,
        sua.recipient_id,
        SUM(sua.amount) total_allotment
      FROM soe_uc_allotment sua
      WHERE sua.deleted_at IS NULL
      AND sua.year = ".$filter['year']." GROUP BY sua.recipient_id) alt
      ON alt.recipient_id = sur.id
  GROUP BY sur.id) allt
  LEFT JOIN (SELECT
      sua.recipient_id,
      MAX(sus.date_submit) date_submit,
      GROUP_CONCAT(sus.letter_no SEPARATOR ', ') letter_no,
      SUM(sus.amount) uc_submit
    FROM soe_uc_submit sus
      LEFT JOIN soe_uc_allotment sua
        ON sus.allotment_id = sua.id
    WHERE sua.deleted_at IS NULL
    AND sus.deleted_at IS NULL
    AND sua.year = ".$filter['year']."
    GROUP BY sua.recipient_id) sbmt
    ON allt.recipient_id = sbmt.recipient_id
  LEFT JOIN (SELECT
      allt.recipient_id,
      allt.total_allotment,
      sbmt.total_submitted
    FROM (SELECT
        sua.recipient_id,
        SUM(sus.amount) total_submitted
      FROM soe_uc_allotment sua
        LEFT JOIN soe_uc_submit sus
          ON sua.id = sus.allotment_id
      WHERE sua.deleted_at IS NULL
      AND sus.deleted_at IS NULL
      AND sua.year BETWEEN 0 AND ".$filter['year']."
      GROUP BY sua.recipient_id) sbmt
      LEFT JOIN (SELECT
          sua.recipient_id,
          SUM(sua.amount) total_allotment
        FROM soe_uc_allotment sua
        WHERE sua.deleted_at IS NULL
        AND sua.year BETWEEN 0 AND ".$filter['year']."
        GROUP BY sua.recipient_id) allt
        ON allt.recipient_id = sbmt.recipient_id) upto
    ON allt.recipient_id = upto.recipient_id";

        return $this->db->query($sql)->getResult();
    }

    public function getAllotments($filter=[]) {
        $sql = "SELECT
  sua.id allotment_id,
  sur.name recipient,
  sy.name year,
  sua.amount,
  sua.allotment_date
FROM soe_uc_allotment sua
  LEFT JOIN soe_uc_recipients sur
    ON sua.recipient_id = sur.id
  LEFT JOIN soe_years sy
    ON sua.year = sy.id
WHERE sua.deleted_at IS NULL
AND sua.year = ".$filter['year']."
AND sua.recipient_id = ".$filter['recipient_id'];

        return $this->db->query($sql)->getResult();
    }

}

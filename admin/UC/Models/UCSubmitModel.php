<?php
namespace Admin\UC\Models;

use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class UCSubmitModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'soe_uc_submit';
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

    public function getRecipientId($district_id) {
        $sql = "SELECT id FROM soe_uc_recipients r WHERE r.district_id=".(int)$district_id;

        $res = $this->db->query($sql)->getFirstRow();

        if(!$res){
            return null;
        } else {
            return $res->id;
        }
	}

    public function getUCReport($filter=[]) {

        $recipient_id = 0;
        if(!empty($filter['recipient_id'])) {
            $recipient_id = $filter['recipient_id'];
        }
        if(!empty($filter['district_id'])) {
            $res = $this->getRecipientId($filter['district_id']);

            if (!$res) {
                return [];
            } else {
                $recipient_id = $res;
            }
        }

        $sql = "SELECT
  allt.recipient_id,
  allt.year year_id,
  y.name `year`,
  allt.allotment,
  sbmt.date_submit,
  sbmt.letter_no,
  sbmt.uc_submit,
  (allt.allotment - sbmt.uc_submit) balance
FROM (SELECT
    sua.recipient_id,
    sua.year,
    SUM(sua.amount) allotment
  FROM soe_uc_allotment sua
  WHERE sua.deleted_at IS NULL
  AND sua.recipient_id = ".$recipient_id."
  GROUP BY sua.year) allt
  LEFT JOIN (SELECT
      sua.recipient_id,
      sua.year,
      MAX(sus.date_submit) date_submit,
      GROUP_CONCAT(sus.letter_no SEPARATOR ', ') letter_no,
      SUM(sus.amount) uc_submit
    FROM soe_uc_submit sus
      LEFT JOIN (SELECT
          *
        FROM soe_uc_allotment
        WHERE recipient_id = ".$recipient_id.") sua
        ON sus.allotment_id = sua.id
    WHERE sua.deleted_at IS NULL
    AND sus.deleted_at IS NULL
    GROUP BY sua.year) sbmt
    ON allt.year = sbmt.year LEFT JOIN soe_years y ON y.id=allt.year";

        return $this->db->query($sql)->getResult();
	}

    public function getSubmissions($filter=[]) {
        $sql = "SELECT
  sus.id uc_id,
  sus.date_submit,
  sus.letter_no,
  sus.page_no,
  sus.amount,
  sus.document
FROM soe_uc_submit sus
  LEFT JOIN soe_uc_allotment sua
    ON sus.allotment_id = sua.id
WHERE sua.deleted_at IS NULL
AND sus.deleted_at IS NULL
AND sua.year = ".$filter['year']."
AND sua.recipient_id = ".$filter['recipient_id'];

        return $this->db->query($sql)->getResult();
	}
}

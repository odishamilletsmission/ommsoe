<?php 
namespace Admin\Transaction\Models;
use CodeIgniter\Model;

class TransactionComponentModel extends Model
{
    protected $table                = 'soe_transaction_components';
    protected $DBGroup              = 'default';
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
    protected $createdField         = '';
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

    public function getTotalPhyFin($id) {
        $sql = "SELECT
  SUM(stc.physical) physical,
  SUM(stc.financial) financial
FROM soe_transaction_components stc
WHERE stc.deleted_at IS NULL
AND stc.transaction_id = $id";

        return $this->db->query($sql)->getRow();
    }


}
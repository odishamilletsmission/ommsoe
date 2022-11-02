<?php

namespace Admin\Transaction\Models;

use App\Traits\ModelTrait;
use CodeIgniter\Model;

class ClosingbalanceModel extends Model
{
    use ModelTrait;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

	protected $DBGroup              = 'default';
	protected $table                = 'soe_closing_balances';
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

    public function getOpeningBalance(array $filter) {
        $this->select('advance + bank + cash as total',false);
        $where = [];
        if(!empty($filter['block_id'])){
            $where['block_id'] = $filter['block_id'];
        }
        if(!empty($filter['user_id'])){
            $where['user_id'] = $filter['user_id'];
        }
        if(!empty($filter['district_id'])){
            $where['district_id'] = $filter['district_id'];
        }
        if(!empty($filter['agency_type_id'])){
            $where['agency_type_id'] = $filter['agency_type_id'];
        }
        $this->orderBy('id','asc');
        $this->where($where);

        $total = $this->first()->total;

        return $total;
	}

    public function getClosingBalance(array $filter) {
        // cb = prv_cb + mon_fr + misc_rec - mon_exp

        $ob = $this->getOpeningBalance(['user_id'=>$filter['user_id']]);

        $upto = [
            'month' => $filter['month'],
            'year' => $filter['year'],
            'agency_type_id' => $filter['agency_type_id'],
        ];
        if(!empty($filter['block_id'])){
            $upto['block_id'] = $filter['block_id'];
        }
        if(!empty($filter['district_id'])){
            $upto['district_id'] = $filter['district_id'];
        }

        $txnModel = new TransactionModel();
        $upto['transaction_type'] = 'fund_receipt';
        $mon_fr = $txnModel->getTotalAmount($upto);

        $upto['transaction_type'] = 'expense';
        $mon_exp = $txnModel->getTotalAmount($upto);

        $miscTxnModel = new MisctransactionModel();
        $misc_rec = $miscTxnModel->getTotalAmount($upto);

        $cb = $ob + $mon_fr->fin + $misc_rec - $mon_exp->fin;

        return $cb;
	}

    public function getLedgerReport($filter=[],$return_type='object')
    {
        $block_id = $filter['block_id'];
        $year = $filter['year'];
        $agency_type_id = $filter['agency_type_id'];
        $user_id = $filter['user_id'];
        $fund_agency_id = $filter['fund_agency_id'];

        $sql = "CALL proc_get_ledger_statement($block_id,$year,$agency_type_id,$user_id,$fund_agency_id)";

        if($return_type=='array'){
            $func = 'getResultArray';
        } else {
            $func = 'getResult';
        }

        return $this->db->query($sql)->$func();
	}

    public function getTransactionDetail($id) {
        $result = $this->db
            ->table('soe_closing_balances t')
            ->select('u.firstname, c.name block,d.name district,ut.name agency_type,y.name year,month,t.created_at,t.status,t.agency_type_id,t.remarks')
            ->join('soe_blocks c','c.id=t.block_id','LEFT')
            ->join('soe_districts d','d.id=t.district_id','LEFT')
            ->join('user_group ut','ut.id=t.agency_type_id','LEFT')
            ->join('soe_years y','y.id=t.year','LEFT')
            ->join('users u','u.id=t.user_id','LEFT')
            ->where('t.id',$id)->get()->getRow();

        return $result;
    }

}

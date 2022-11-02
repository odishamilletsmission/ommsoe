<?php

namespace Admin\Common\Models;

use CodeIgniter\Model;

class MonthModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'soe_months';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes        = false;
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
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

    public function __construct()
    {
        parent::__construct();
        if(!$months = cache('months')){
            cache()->save('months',$this->asArray()->findAll(),60*60*24*365);
        }
	}

    public function getMonthById(int $id)
    {
        $months = cache()->get('months');
        $key = array_search($id,array_column($months,'id'));
        if($key!==false){
            return $months[$key];
        }
        return [];
    }
    public function getMonthIdByMonth(int $month_num)
    {
        $months = cache()->get('months');

        $key = array_search($month_num,array_column($months,'number'));

        if($key!==false){
            return $months[$key]['id'];
        }
        return 0;
    }
    public function getMonths()
    {
        return cache()->get('months');
    }
    public function getMonthsUpto($month_id)
    {
        $months = $this->where(['id'<$month_id])->asArray()->findAll();
        return $months;
    }
    public function getPreviousMonth($month=null)
    {
        if(!$month) {
            $month = date('m');
            $month = $this->getMonthIdByMonth($month);
        }

        $months = cache()->get('months');
        $key = array_search(($month-1),array_column($months,'id'));
        if($key!==false){
            return $months[$key];
        }
        return [];
    }
}

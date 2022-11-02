<?php 
namespace Admin\Customer\Models;
use CodeIgniter\Model;

class CustomerModel extends Model 
{
    protected $table = 'Customer';
    protected $allowedFields = [];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function beforeInsert(array $data) {
        return $data;
    }

    protected function beforeUpdate(array $data) {
        return $data;
    }
}
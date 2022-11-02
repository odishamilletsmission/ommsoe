<?php 
namespace Admin\MIS\Models;
use CodeIgniter\Model;

class MISDetailModel extends Model
{
    protected $table = 'mis_submission_details';
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
    protected $createdField         = '';
    protected $updatedField         = '';
    protected $deletedField         = 'deleted_at';


}
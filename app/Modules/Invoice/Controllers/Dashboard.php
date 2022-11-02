<?php namespace App\Modules\Invoice\Controllers;
use App\Modules\Invoice\Models\UserModel;
use CodeIgniter\Controller;
class Dashboard extends Controller
{
    private $userModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function index()
	{
		$data = [
		    'title' => 'Dashboard Page',
            'view' => 'invoice/dashboard',
            'data' => $this->userModel->getUsers(),
        ];
		return view('template/layout', $data);
	}
}

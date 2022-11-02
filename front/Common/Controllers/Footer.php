<?php
namespace Front\Common\Controllers;
use App\Controllers\BaseController;

class Footer extends BaseController
{
	public function index()
	{
		//return view('frontend/common/footer');
		return $this->template->view('Front\Common\Views\footer', [],true);
	}
}

return  __NAMESPACE__ ."\Footer";
?>
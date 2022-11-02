<?php
namespace Front\Proceeding\Controllers;
use Admin\Proceeding\Models\ProceedingModel;
use App\Controllers\BaseController;

class Proceeding extends BaseController
{
	private $error = array();
    private $proceedingModel;
    function __construct(){
		$this->proceedingModel = new ProceedingModel();
	}
    public function index()
	{
        $this->template->set_meta_title(lang('Proceeding.heading_title'));
        return $this->getList();
		
	}
    protected function getList($template=true) {

        $this->template->add_package(array('datatable','particle'),true);

        $data['datatable_url'] = base_url('proceeding/search');

        $data['heading_title'] = lang('Proceeding.heading_title');

        $data['text_list'] = lang('Proceeding.text_list');
        $data['text_no_results'] = lang('Proceeding.text_no_results');
        $data['text_confirm'] = lang('Proceeding.text_confirm');

        $data['button_add'] = lang('Proceeding.button_add');
        $data['button_edit'] = lang('Proceeding.button_edit');
        $data['button_delete'] = lang('Proceeding.button_delete');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->request->getPost('selected')) {
            $data['selected'] = (array)$this->request->getPost('selected');
        } else {
            $data['selected'] = array();
        }
        $data['feature_image'] = '';
        $this->template->set('header',true);
        $data['heading_title'] = "Proceeding";
        $data['meta_title'] = "Proceeding";

        if(!$template){
            return $this->template->view('Front\Proceeding\Views\proceeding', $data,true);
        }else {
            return $this->template->view('Front\Proceeding\Views\proceeding', $data);
        }
    }

    public function search() {
        $requestData= $_REQUEST;
        $totalData = $this->proceedingModel->getTotal();
        $totalFiltered = $totalData;

        $filter_data = array(

            'filter_search' => $requestData['search']['value'],
            'order'  		 => $requestData['order'][0]['dir'],
            'sort' 			 => $requestData['order'][0]['column'],
            'start' 			 => $requestData['start'],
            'limit' 			 => $requestData['length']
        );
        $totalFiltered = $this->proceedingModel->getTotal($filter_data);

        $filteredData = $this->proceedingModel->getAll($filter_data);
        //printr($filteredData);

        $icons = [
            'ppt' => 'ppt-download-icon.png',
            'pptx' => 'ppt-download-icon.png',
            'doc' => 'word-download-icon.png',
            'jpg' => 'word-download-icon.png',
			'jpeg' => 'word-download-icon.png',
            'docx' => 'word-download-icon.png',
            'pdf' => 'pdf-download-icon.png',
            'PDF' => 'pdf-download-icon.png',
            'xls' => 'excel-download-icon.png',
            'xlsx' => 'excel-download-icon.png',
        ];
        $datatable=array();
        foreach($filteredData as $result) {

            $action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('proceeding/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
            $action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('proceeding/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
            $action .= '</div>';
            $link='';
            if($result->attachment){
                $ext = pathinfo($result->attachment,PATHINFO_EXTENSION);

                $link='<a href="'.base_url('uploads/' .$result->attachment).'" data-title="Download"><img src="'.base_url('uploads/images/icons/'.$icons[$ext]).'" width="30"/></a>';

            }

            $datatable[]=array(
                $result->letter_date,
                $result->letter_no,
                $result->name,
                $link
            );

        }
        //printr($datatable);
        $json_data = array(
            "draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
            "recordsTotal"    => intval( $totalData ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $datatable
        );

        return $this->response->setContentType('application/json')
            ->setJSON($json_data);

    }

    public function getShortcode(){
        return $this->getList(false);
    }

}

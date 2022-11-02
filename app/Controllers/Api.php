<?php
namespace App\Controllers;
use Admin\Localisation\Models\DistrictModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\ClusterBlockModel;
use Admin\Localisation\Models\ClusterModel;
use Admin\Localisation\Models\GrampanchayatModel;
use Admin\Forms\Models\HouseholdModel;
use Admin\Forms\Models\AggricultureModel;
use Admin\Forms\Models\HorticultureModel;
use Admin\Users\Models\MemberModel;
use Admin\Localisation\Models\VillageModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ApiModel;

class Api extends ResourceController
{
	use ResponseTrait;
	private $apiModel;
	private $user; 
	private $odk;
	
	public function __construct(){
		helper("aio");
		$this->apiModel=new ApiModel();
		$this->user=service('user');
		$this->odk = service('odkcentral');
	}
	
	public function index(){
		$data['message'] = "Welcome to Integrated Farming";
		return $this->respond($data);
	}
	
	public function submission(){
		file_put_contents('test.txt', $_FILES);
		$json=array(
			"instanceId"=> "uuid:85cb9aff-005e-4edd-9739-dc9c1a829c44",
			"instanceName"=> "village third house",
			"submitterId"=> 23,
			"deviceId"=> "imei:123456",
			"reviewState"=> "approved",
			"createdAt"=> "2018-01-19T23:58:03.395Z",
			"updatedAt"=> "2018-03-21T12:45:02.312Z"
		);
		return $this->respond($json);
		exit;
	}
	
	public function login(){
		$json=array();
		if (!$this->validateLoginForm()) {
			if(isset($this->error['warning'])){
				$json['message'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['errors'] 	= $this->error['errors'];
			}
			$json['status']= false;
		}
		if (!$json) {
			$username=$this->user->login($this->request->getPost('username'), $this->request->getPost('password'));

			if($username){
				$user = $this->user->getUser();
				//$appUsers = $this->odk->projects(2)->appUsers()->get();
				$user->image= $this->user->getImage();
				$user->server_url="https://central.milletsodisha.com/v1/key/".$user->central_appuser_token."/projects/2";
				$json=array(
					'status'=>true,
					'user'=>$user,
					'message'=>"Login Successfully",
				);
			}else{
				$json=array(
					'status'=>false,
					'message'=>"Wrong Username and Password",
				);
			}
			
		}
		return $this->respond($json);
		
	}
	
	public function xform(){
		//$model = new ApiModel();
		//print_r($_POST);
		$json=array();
		$user_id=$this->request->getPost('userid');
        $serverurl=$this->request->getPost('serverurl');
		$role_id=$this->request->getPost('role_id');
		$district=$this->request->getPost('district');
		$block=$this->request->getPost('block');
		preg_match('/projects\/([\d]+)/', $serverurl, $matches);

		$project_id=(int)$matches[1];
		$xform=json_decode($this->request->getPost("xform"),true);

		//print_r($xform);
		if($role_id && $xform){
			foreach($xform as $form){
			    $xformdata=$this->odk->projects($project_id)->forms($form['formId'])->get();
				//printr($xformdata);
                if (strpos($serverurl, 'test') !== false) {
                    $downloadUrl=$serverurl.".xml";
                }else{
                    $downloadUrl=$serverurl."/forms/".$form['formId'].".xml";
                }
                $itemseturl='http://integratedfarming.in/api/itemset?formid='.$form['formId'].'&district='.$district.'&block='.$block.'&role_id='.$role_id;
				$json['forms'][]=array(
					'downloadUrl'=>$downloadUrl,
					'formId'=>$form['formId'],
					'formName'=>$form['formName'],
					'formVersion'=>$form['formVersion'],
					'hash'=>"md5:".$xformdata['hash'],
					'isNotOnDevice'=>false,
					'isUpdated'=>false,
					'manifest'=>[
						'hash'=>$xformdata['hash'],
						'mediaFile'=>[
							'downloadUrl'=>$itemseturl,
							'fileName'=>'itemsets.csv',
							'hash'=>"md5:".$this->fmd5($itemseturl)
						],
					],
					
				);
			}
			
		}else{
			$json['forms'][]=[];
		}
		return $this->respond($json);
		
				
	}
	
	public function dashboarddata(){
		$json=array();
		$householdModel=new HouseholdModel();
		$data['household']=$householdModel->getTotal();

        $aggricultureModel=new AggricultureModel();
        $data['aggriculture']=$aggricultureModel->getTotal();

        $horticultureModel=new HorticultureModel();
        $data['horticulture']=$horticultureModel->getTotal();
		
		$data['fishery']=0;
		
		$data['livestock']=0;
		
		$data['institution']=0;
		
		$json['results']=$data;
		return $this->respond($json);
		
	}
	
	public function formdata(){
	    $json=array();
		$householdModel=new HouseholdModel();
        $households=$householdModel->getAll();
		
		$aggricultureModel=new AggricultureModel();
        $aggricultures=$aggricultureModel->getAll();

        $horticultureModel=new HorticultureModel();
        $horticultures=$horticultureModel->getAll();
		
		if($households){
			foreach($households as $key=>$result){
				
				$districtModel=new DistrictModel();
				$district=$districtModel->where('code', $result['gp1']['district'])->findColumn('name');
				
				$blockModel=new BlockModel();
				$block=$blockModel->where('code', $result['gp1']['block'])->findColumn('name');
				
				$clusterModel=new ClusterModel();
				$cluster=$clusterModel->where('code', $result['gp1']['cluster'])->findColumn('name');
				//echo $clusterModel->getLastQuery();
				$gpModel=new GrampanchayatModel();
				$gp=$gpModel->where('code', $result['gp1']['grampanchayat'])->findColumn('name');
				
				$villageModel=new VillageModel();
				$village=$villageModel->where('code', $result['gp1']['village'])->findColumn('name');

				$json['households'][]=[
					'id'=>$result['__id'],
					'name'=>$result['gp2']['farmer_name'],
					'district'=>isset($district[0])?$district[0]:'',
					'block'=>isset($block[0])?$block[0]:'',
					'cluster'=>isset($cluster[0])?$cluster[0]:'',
					'gp'=>isset($gp[0])?$gp[0]:'',
					'village'=>isset($village[0])?$village[0]:'',
					'aadhaar'=>$result['gp2']['aadhaar_no'],
					'account'=>$result['gp5']['account_no'],
					'phone'=>$result['gp2']['phone'],
					'date'=>date("Y-m-d",strtotime($result['__system']['submissionDate'])),
					'area'=>$result['gp6']['totalland'],
					'status'=>true
				];
			}
			
		}
		
		
        return $this->respond($json);
	}
	
	protected function fmd5($url=''){
		$opts = array(
		  'http'=>array(
			'method'=>"GET",
			'header'=>"Accept-language: en\r\n" .
					  "Cookie: foo=bar\r\n"
		  )
		);

		$context = stream_context_create($opts);
		//$url='https://crops.wassan.org/api/collect/itemset?formid=testform';
		
		// Open the file using the HTTP headers set above
		$file = file_get_contents($url, false, $context);
		//echo md5($file);
		return md5($file);
	}
	
	public function itemset(){
        $json=[];
		$role_id=(int)$this->request->getVar('role_id');
		$district=(int)$this->request->getVar('district');
		$block=(int)$this->request->getVar('block');
		$formid=$this->request->getVar('formid');


		$filter=array(
			'role_id'=>$role_id,
			'filter_district'=>$district,
			'filter_block'=>$block,
			'formid'=>$formid
		);
		
		switch($formid){
			case "household":
				$this->generateGVItemset($filter);
				break;
			case "agriculture_basic":
            case "fishery_basic":
            case "horticulture_basic":
            case "livestock_basic":
				$this->generateGVFItemset($filter);
				break;
			case "agriculture_transact_planting":
				$this->generateATPItemset($filter);
				break;
			case "fieldvisit":
				$this->generateGVSItemset($filter);
				break;
			default:
				echo "";
		}

		
		//$data['message'] = "Welcome to Integrated Farming";
		//dd($data);
        //return $data;
        //return $this->respond($json);
	}
	
	private function generateGVItemset($filter){
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename=itemsets.csv'); 
		ob_end_clean();
		//$fp = fopen('php://temp', 'r+');
		$fp = fopen("php://output", "wb");
		$header=['list_name','name','label','district','block','cluster','grampanchayat'];
		fputcsv($fp,$header);

        $blockModel=new BlockModel();
        $blocklist=$blockModel->getAll($filter);

        $blocks=[];
        fputcsv($fp, $blocks);

        foreach($blocklist as $list){
            $blocks=['block',$list->code,$list->name,$list->dcode,'','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $blocks), ',', chr(0));

        }

        $clusterModel=new ClusterModel();
        $clusterlist=$clusterModel->getAll($filter);

        //$clusters=[];
        $clusters=['cluster','IND21C','No Cluster','','','',''];
        fputcsv($fp, $clusters);

        foreach($clusterlist as $list){
            $clusters=['cluster',$list->code,$list->name,'',$list->bcode,'',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $clusters), ',', chr(0));

        }

        $gpModel=new GrampanchayatModel();

		$gplist=$gpModel->getAll($filter);

        $gps=[];
		fputcsv($fp, $gps);
		foreach($gplist as $list){
			$gps=['grampanchayat',$list->code,$list->name,'',$list->bcode,$list->ccode,''];
			fputcsv($fp, array_map(array($this, 'encodeFunc'), $gps), ',', chr(0));
			
		}

        $villageModel=new VillageModel();
		$villagelist=$villageModel->getAll($filter);
		//printr($villagelist);
        $villages=[];
		fputcsv($fp, $villages);
		foreach($villagelist as $list){
			$villages=['village',$list->code,$list->name,'','','',$list->gcode];
			fputcsv($fp, array_map(array($this, 'encodeFunc'), $villages), ',', chr(0));
			
		}

		//rewind($fp);
		//$data = fread($fp, 10485766);
		fclose($fp);
        exit;
		//return $data;
		
	}
	
	private function generateGVFItemset($filter){
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename=itemsets.csv'); 
		ob_end_clean();
		//$fp = fopen('php://temp', 'r+');
		$fp = fopen("php://output", "wb");
		$header=['list_name','name','label','district','block','cluster','grampanchayat','village'];
		fputcsv($fp,$header);

        $blockModel=new BlockModel();
        $blocklist=$blockModel->getAll($filter);

        $blocks=[];
        fputcsv($fp, $blocks);

        foreach($blocklist as $list){
            $blocks=['block',$list->code,$list->name,$list->dcode,'','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $blocks), ',', chr(0));

        }

        $clusterModel=new ClusterModel();
        $clusterlist=$clusterModel->getAll($filter);

        //$clusters=[];
        $clusters=['cluster','IND21C','No Cluster','','','',''];
        fputcsv($fp, $clusters);

        foreach($clusterlist as $list){
            $clusters=['cluster',$list->code,$list->name,'',$list->bcode,'',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $clusters), ',', chr(0));

        }

        $gpModel=new GrampanchayatModel();

		$gplist=$gpModel->getAll($filter);

        $gps=[];
		fputcsv($fp, $gps);
		foreach($gplist as $list){
			$gps=['grampanchayat',$list->code,$list->name,'',$list->bcode,$list->ccode,''];
			fputcsv($fp, array_map(array($this, 'encodeFunc'), $gps), ',', chr(0));
			
		}

        $villageModel=new VillageModel();
		$villagelist=$villageModel->getAll($filter);
		//printr($villagelist);
        $villages=[];
		fputcsv($fp, $villages);
		foreach($villagelist as $list){
			$villages=['village',$list->code,$list->name,'','','',$list->gcode];
			fputcsv($fp, array_map(array($this, 'encodeFunc'), $villages), ',', chr(0));
			
		}
		
		 // Listing all submissions on a form
        $projectId=2;
        $xmlFormId="household";
        // Our form
        $form = $this->odk->projects($projectId)->forms($xmlFormId);
        $submissions = $form->odata('Submissions')->get();
        $farmers=[];
        fputcsv($fp, $farmers);
        foreach($submissions['value'] as $submission){
            $farmer_id=$submission['__id'];
            $farmer_name=trim($submission['gp2']['farmer_name']).'('.$submission['gp2']['aadhaar_no'].')';
            $farmers=['farmer',$farmer_id,$farmer_name,'','','','',$submission['gp1']['village']];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $farmers), ',', chr(0));
        }



		//rewind($fp);
		//$data = fread($fp, 10485766);
		fclose($fp);
        exit;
		//return $data;
		
	}

    private function generateATPItemset($filter){
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=itemsets.csv');
		ob_end_clean();	
        //$fp = fopen('php://temp', 'r+');
        $fp = fopen("php://output", "wb");
        $header=['list_name','name','label','district','block','cluster','grampanchayat','village','year','season','farmer'];
        fputcsv($fp,$header);

        $blockModel=new BlockModel();
        $blocklist=$blockModel->getAll($filter);

        $blocks=[];
        fputcsv($fp, $blocks);

        foreach($blocklist as $list){
            $blocks=['block',$list->code,$list->name,$list->dcode,'','','','','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $blocks), ',', chr(0));
			//fputcsv($fp,  $blocks, ',', chr(0));
        }

        $clusterModel=new ClusterModel();
        $clusterlist=$clusterModel->getAll($filter);

        //$clusters=[];
        $clusters=['cluster','IND21C','No Cluster','','','','','','',''];
        fputcsv($fp, $clusters);

        foreach($clusterlist as $list){
            $clusters=['cluster',$list->code,$list->name,'',$list->bcode,'','','','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $clusters), ',', chr(0));
			//fputcsv($fp, $clusters, ',', chr(0));
        }

        $gpModel=new GrampanchayatModel();

        $gplist=$gpModel->getAll($filter);

        $gps=[];
        fputcsv($fp, $gps);
        foreach($gplist as $list){
            $gps=['grampanchayat',$list->code,$list->name,'',$list->bcode,$list->ccode,'','','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $gps), ',', chr(0));
			//fputcsv($fp, $gps, ',', chr(0));
        }

        $villageModel=new VillageModel();
        $villagelist=$villageModel->getAll($filter);
        //printr($villagelist);
        $villages=[];
        fputcsv($fp, $villages);
        foreach($villagelist as $list){
            $villages=['village',$list->code,$list->name,'','','',$list->gcode,'','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $villages), ',', chr(0));
			//fputcsv($fp,  $villages, ',', chr(0));

        }
		
		
		$projectId=2;
        $xmlFormId="household";
        // Our form
        $form = $this->odk->projects($projectId)->forms($xmlFormId);
        $submissions = $form->odata('Submissions')->get();
        $farmers=[];
        fputcsv($fp, $farmers);
        foreach($submissions['value'] as $submission){
            $farmer_id=$submission['__id'];
            $farmer_name=trim($submission['gp2']['farmer_name']).'('.$submission['gp2']['aadhaar_no'].')';
            $farmers=['farmer',$farmer_id,$farmer_name,'','','','',$submission['gp1']['village'],'','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $farmers), ',', chr(0));
			//fputcsv($fp, $farmers, ',', chr(0));
        }


        // Listing all submissions on a form
        $household=new HouseholdModel();
		$projectId=2;
        $xmlFormId="agriculture_basic";
        // Our form
        $form = $this->odk->projects($projectId)->forms($xmlFormId);
        $submissions = $form->odata('Submissions')->get();
		//printr($submissions);
		$agriculture_basic=[];
        fputcsv($fp, $agriculture_basic);
        foreach($submissions['value'] as $submission){
            $aggriculturebasic_id=$submission['__id'];
			$farmer_name=$household->getFarmerName($submission['gp1']['farmer']);
            //echo $farmer_name;
			$agriculture_basic_name=trim($farmer_name).'-'.$submission['gp2']['year'].'-'.$submission['gp2']['season'];
            //echo $agriculture_basic_name;
			//$agriculture_basic_name="avb";
			$agriculture_basic=['aggriculturebasic',$aggriculturebasic_id,$agriculture_basic_name,'','','','','',date('Y',strtotime($submission['gp2']['year'])),$submission['gp2']['season'],$submission['gp1']['farmer']];
            
			fputcsv($fp, array_map(array($this, 'encodeFunc'), $agriculture_basic), ',', chr(0));
			//fputcsv($fp, $agriculture_basic, ',', chr(0));
        }
		
		


        //rewind($fp);
        //$data = fread($fp, 10485766);
        fclose($fp);
        exit;
        //return $data;

    }

    
    private function generateGVSItemset($filter){
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=itemsets.csv');
		ob_end_clean();
        //$fp = fopen('php://temp', 'r+');
        $fp = fopen("php://output", "wb");
        $header=['list_name','name','label','district','block','grampanchayat','designation'];
        fputcsv($fp,$header);

        $blockModel=new BlockModel();
        $blocklist=$blockModel->getAll($filter);

        $blocks=[];
        fputcsv($fp, $blocks);

        foreach($blocklist as $list){
            $blocks=['block',$list->code,$list->name,$list->dcode,'','',''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $blocks), ',', chr(0));

        }

        $gpModel=new GrampanchayatModel();

        $gplist=$gpModel->getAll($filter);

        $gps=[];
        fputcsv($fp, $gps);
        foreach($gplist as $list){
            $gps=['grampanchayat',$list->code,$list->name,'',$list->bcode,$list->ccode,''];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $gps), ',', chr(0));

        }

        $villageModel=new VillageModel();
        $villagelist=$villageModel->getAll($filter);
        //printr($villagelist);
        $villages=[];
        fputcsv($fp, $villages);
        foreach($villagelist as $list){
            $villages=['village',$list->code,$list->name,'','','',$list->gcode];
            fputcsv($fp, array_map(array($this, 'encodeFunc'), $villages), ',', chr(0));

        }


        $staffModel=new MemberModel();
        $stafflist=$staffModel->findAll();


        $staffs=[];
        fputcsv($fp, $staffs);
        foreach($stafflist as $list){
            $staff_id=$list->id;
            $staffs=['vistingstaff',$staff_id,$list->name,'','','',$list->designation];
            fputcsv($fp, $staffs);
        }

        //rewind($fp);
        //$data = fread($fp, 10485766);
        fclose($fp);
        exit;
        //return $data;

    }
	public function farmer(){
        $projectId=2;
        $xmlFormId="household";
        // Our form
        $form = $this->odk->projects($projectId)->forms($xmlFormId);
        $submissions = $form->odata('Submissions')->get();
        print_r($submissions);
    }
	
	public function code(){
	    $l=$this->request->getVar('l');
	    if($l=="block") {
            $blockModel=new BlockModel();
            $blocks=$blockModel->getAll();
            //printr($blocks);
            foreach($blocks as $block){
                $laststr=$block->code;
                $larr=str_split($laststr, strlen($laststr) - 2);
                if($larr[0]==$block->tcode){
                    $tcode=$block->dcode.'B'.$larr[1];
                    $udata=[
                        'tcode'=>$tcode
                    ];

                    $blockModel->update($block->id,$udata);
                    echo "Updated successfully.<br>";
                }

            }
        }else if($l=="gp"){
            $gpModel=new GrampanchayatModel();
            $gps=$gpModel->getAll();

            foreach($gps as $gp){
                $laststr=$gp->code;
                $larr=str_split($laststr, strlen($laststr) - 2);

                if($larr[0]==$gp->tcode){
                    $tcode=$gp->bcode.'G'.$larr[1];
                    $udata=[
                        'tcode'=>$tcode
                    ];

                    $gpModel->update($gp->id,$udata);
                    echo "Updated successfully.<br>";
                }

            }
        }else if($l=="village"){
            $villageModel=new VillageModel();
            $villages=$villageModel->getAll();
            //printr($villages);
            //exit;
            foreach($villages as $village){
                $laststr=$village->code;
                $larr=str_split($laststr, strlen($laststr) - 2);

                if($larr[0]==$village->tcode){
                    $inumber=sprintf("%02d", $larr[1]);
                    $tcode=$village->gcode.'V'.$inumber;
                    $udata=[
                        'tcode'=>$tcode
                    ];

                    $villageModel->update($village->id,$udata);
                    echo "Updated successfully.<br>";
                }

            }
        }
    }
	
	protected function validateLoginForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		
		//$rules = $this->pagesModel->validationRules;
		$rules=array(
			'username' => array(
				'label' => 'Username', 
				'rules' => 'trim|required'
			),
			'password' => array(
				'label' => 'Password', 
				'rules' => 'trim|required'
			),
		);
		
		if ($this->validate($rules)){
			return true;
    	}
		else{
			//printr($validation->getErrors());
			$this->error['warning']="Wrong Username and Password!";
			$this->error['errors'] = $validation->getErrors();
			return false;
    	}
		return !$this->error;
	}

    protected function encodeFunc($value) {
        return "\"$value\"";
    }

    protected function arraysearch($array,$search){
        foreach ($array as $element){
            if(strpos($element,$search)!==FALSE){
                return TRUE;
            }
        }
        return FALSE;
    }
}

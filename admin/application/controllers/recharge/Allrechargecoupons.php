<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet;

class Allrechargecoupons extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: AFSAR ALI
	 + + Purpose  		: This function used for index
	 + + Date 			: 08 APRIL 2022
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'recharge';
		$data['activeSubMenu'] 				= 	'allrechargecoupons';
		
		if($this->input->get('searchField') && $this->input->get('searchValue')):
			
			if($this->input->get('searchField') == "coupon_code"):

				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				$whereCon1['where'][$this->input->get('searchField')]			=	$this->input->get('searchValue');

			    $tblName1							= 	'da_coupon_code_only';
				$ResulCoupontData 							= 	$this->common_model->getData('single',$tblName1,$whereCon1,$shortField,'0','0');

				$whereCon['where']	 = 	array('rc_id' =>  (int)$ResulCoupontData['rc_id']);	
			elseif($this->input->get('searchField') == "created_for_mobile"):
				$sField							=	$this->input->get('searchField');
				$sValue							=	(int)$this->input->get('searchValue');
				$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
			else:	
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
			
			endif;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

				
		// $whereCon['where']		 			= 	'';		
		$shortField 						= 	array('rc_seq_id'=>'desc');
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('ALLCOUPONSDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_rechargecoupons';
		$con 								= 	'';
		$totalRows 							= 	$this->common_model->getData('count',$tblName,$whereCon,$shortField,'0','0');
		
		if($this->input->get('showLength') == 'All'):
			$perPage	 					= 	$totalRows;
			$data['perpage'] 				= 	$this->input->get('showLength');  
		elseif($this->input->get('showLength')):
			$perPage	 					= 	$this->input->get('showLength'); 
			$data['perpage'] 				= 	$this->input->get('showLength'); 
		else:
			$perPage	 					= 	SHOW_NO_OF_DATA;
			$data['perpage'] 				= 	SHOW_NO_OF_DATA; 
		endif;

		$uriSegment 						= 	getUrlSegment();
	    $data['PAGINATION']					=	adminPagination($baseUrl,$suffix,$totalRows,$perPage,$uriSegment);

       if($this->uri->segment(getUrlSegment())):
           $page = $this->uri->segment(getUrlSegment());
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$baseUrl; 
		if($totalRows):
			$first							=	(int)($page)+1;
			$data['first']					=	$first;
			
			if($data['perpage'] == 'All'):
				$pageData 					=	$totalRows;
			else:
				$pageData 					=	$data['perpage'];
			endif;
			
			$last							=	((int)($page)+$pageData)>$totalRows?$totalRows:((int)($page)+$pageData);
			$data['noOfContent']			=	'Showing '.$first.'-'.$last.' of '.$totalRows.' items';
		else:
			$data['first']					=	1;
			$data['noOfContent']			=	'';
		endif;
		
		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page);

		$this->layouts->set_title('All Copons | Copons | Dealz Arabia');
		$this->layouts->admin_view('rechargecoupons/allrechargecoupons/index',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : addeditdata
	 + + Developed By  : AFSAR ALI
	 + + Purpose  	   : This function used for Add Edit data
	 + + Date 		   : 08 APRIL 2022
	 + + Updated Date  : 
	 + + Updated By    :
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function addeditdata($editId='')
	{		
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'recharge';
		$data['activeSubMenu'] 				= 	'allrechargecoupons';

		$userWcon['where']					=	array('users_type' => array('$ne' => 'Users'), 'status' => 'A');
		
		$data['ALLUSERS']					=	$this->common_model->getData('multiple','da_users',$userWcon,array('users_name'=>'asc'));

		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']				=	$this->common_model->getDataByParticularField('da_rechargecoupons','rc_id',(int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			//echo 'Oops!! Work in progress. Please wait.';die();
			$this->form_validation->set_rules('aed', 'AED', 'trim');
			$this->form_validation->set_rules('ipoints', 'iPoints', 'trim');
			$this->form_validation->set_rules('qty', 'Quantity', 'trim');

			if($this->form_validation->run() && $error == 'NO'): 
				$param['aed']			= 	addslashes($this->input->post('aed'));
				$param['ipoints']		= 	addslashes($this->input->post('ipoints'));
				$param['qty']			= 	addslashes($this->input->post('qty'));
				$param['generate_for']	= 	addslashes($this->input->post('generate_for'));

				$userData = $this->common_model->getDataByParticularField('da_users','users_id',(int)$this->input->post('users')); //Update 05-07-2023

				if($this->input->post('CurrentDataID') ==''):
					$param['rc_id']				=	(int)$this->common_model->getNextSequence('da_rechargecoupons');
					$param['rc_seq_id']			=	$this->common_model->getNextIdSequence('rc_seq_id','coupons');
					
					//Update 05-07-2023
					$param['created_for_user_id'] 	= $userData['users_id'];
					$param['created_for_email'] 	= $userData['users_email']?$userData['users_email']:"";
					$param['created_for_mobile'] 	= $userData['users_mobile'];
					//end
					
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$param['status']			=	'A';
					$alastInsertId				=	$this->common_model->addData('da_rechargecoupons',$param);
					$qty 		=	$this->input->post('qty');
					A:
					$insertParam = [];
					$couponData = [];
					for($i=0; $i < $qty; $i++){
						$whereCon['coupon_code']	=	'';
						if($this->input->post('coupon_length')):
							$coupon_code	=	(int)$this->input->post('coupon_length');							
						else:
							$coupon_code	=	8;
						endif;

						$code						=	generateRandomString2($coupon_code,"n");
						array_push($couponData,$code);
						$couponParam['rc_id']				= 	$param['rc_id'];
						$couponParam['coupon_code'] 		= 	$code;
						$couponParam['coupon_code_amount'] 	= 	$param['ipoints'];
						$couponParam['coupon_code_statys']	=	'Active';	//	Redeem / Expired
						$couponParam['created_for_user_id']	=	$param['created_for_user_id'];
						$couponParam['created_date']		=	$param['creation_date'];
						$couponParam['expair_date']		=	date('Y-m-d',strtotime('+1 years'));
						array_push($insertParam,$couponParam);
					}
					$check 	= 	$this->common_model->checkBulkDuplicate('da_coupon_code_only',$couponData);
					if($check == 0){
						$this->common_model->addManyData('da_coupon_code_only',$insertParam);
					}else{
						goto A;
					}
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$couponId					=	$this->input->post('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$this->common_model->editData('da_rechargecoupons',$param,'rc_id',(int)$couponId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;

				redirect(correctLink('MASTERDATAPRIZETYPE',getCurrentControllerPath('index')));
			endif;
		endif;
		
		$this->layouts->set_title('Add/Edit Prize');
		$this->layouts->admin_view('rechargecoupons/allrechargecoupons/addeditdata',array(),$data);
	}	// END OF FUNCTION	

	/***********************************************************************
	** Function name 	: changestatus
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for change status
	** Date 			: 08 APRIL 
	** updated by  		: Dilip Halder
	** Date 			: 31 March 2023
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('edit_data');
		echo $param['status']		=	$statusType;
		$this->common_model->editData('da_rechargecoupons',$param,'rc_id',(int)$changeStatusId);

		if($statusType == 'I'):
			// Inactivae multiple vouchers 
			$whereCondition =  array('rc_id' => (int)$changeStatusId , 'coupon_code_statys' => 'Active' );
			
			$param1['coupon_code_statys']	=	"Inactive";
			
			$this->common_model->editMultipleDataByMultipleCondition('da_coupon_code_only',$param1,$whereCondition);
		endif;

		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('ALLCOUPONSDATA',getCurrentControllerPath('index')));
	}


	/***********************************************************************
	** Function name 	: deletedata
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for delete data
	** Date 			: 08 APRIL 2022
	************************************************************************/
	function deletedata($deleteId='')
	{  
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_rechargecoupons','rc_id',(int)$deleteId);
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('ALLCOUPONSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: exportexcel
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for export deleted users data
	** Date 			: 09 APRIL 2022
	** Updated Date 	: 14 FEBRUARY 2023
	** Updated By   	: DILIP HALDER
	************************************************************************/
	function exportexcel($rc_ID='')
	{  
		/* Export excel button code */
		$couponData				=	$this->common_model->getDataByParticularField('da_rechargecoupons','rc_id',(int)$rc_ID);
		$wcon['where']          =   array('rc_id'=>(int)$rc_ID);
		$data        			=   $this->common_model->getData('multiple','da_coupon_code_only',$wcon);
		
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'ID');
		$sheet->setCellValue('C1', 'COUPON CODE');
		$sheet->setCellValue('D1', 'Value');
		$sheet->setCellValue('E1', 'GENERAT FOR');

		$sheet->setCellValue('F1', 'GENERAT FOR EMAIL');
		$sheet->setCellValue('G1', 'GENERAT FOR MOBILE');


		$sheet->setCellValue('H1', 'CREATION DATE');
		$sheet->setCellValue('I1', 'EXPIRY DATE');

		$sheet->setCellValue('J1', 'Redeem by Email');
		$sheet->setCellValue('K1', 'Redeem by Mobile');

		$sheet->setCellValue('L1', 'Stage');
		$sheet->setCellValue('M1', 'Status');
		
		$slno = 1;
		$start = 2;
		foreach($data as $d){
			// createding expiring date from created date.
			$date = date('d-m-Y H:i:s', $d['created_date']);
		 	$expiringdate = date('d-m-Y H:i:s', strtotime($date. ' + 1 years'));


		 	if($d['coupon_code_statys'] == 'Active' || $d['coupon_code_statys'] == 'Inactive' || $d['coupon_code_statys'] ==  'Expire'):
		 		$status = "Not used";
		 	elseif($d['coupon_code_statys'] == 'Redeem'):

		 		if($d['redeemed_by_whom'] !=""  && $d['redeemed_date'] ){
		 			$date = strtotime($d['redeemed_date']);
		 			$status = "Used by " .$d['redeemed_by_whom']." on ".date('H M Y h:m', $date);
		 		}else{
		 			$status = "Used";
		 		}

		 	endif;

		 	$rcID = 	substr($d['rc_id'], -4);

			$sheet->setCellValue('A'.$start, $slno);
			$sheet->setCellValue('B'.$start, $rcID);
			$sheet->setCellValue('C'.$start, $d['coupon_code']);
			$sheet->setCellValue('D'.$start, $d['coupon_code_amount']);
			$sheet->setCellValue('E'.$start, $couponData['generate_for']);

			$sheet->setCellValue('F'.$start, $couponData['created_for_email']);
			$sheet->setCellValue('G'.$start, $couponData['created_for_mobile']);


			$sheet->setCellValue('H'.$start, date('d-m-Y H:i:s', $d['created_date']));
			$sheet->setCellValue('I'.$start, $expiringdate);

			$sheet->setCellValue('J'.$start, $d['redeemed_by_whom']);
			$sheet->setCellValue('K'.$start, $d['redeemed_by_mobile']);

			$sheet->setCellValue('L'.$start, $d['coupon_code_statys']);
			$sheet->setCellValue('M'.$start, $status);
			
			$start = $start+1;
			$slno = $slno+1;
		}

		$styleThinBlackBorderOutline = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => Border::BORDER_THIN,
							'color' => ['argb' => 'FF000000'],
						],
					],
				];
		//Font BOLD
		$no = count($data) + 1;
		$sheet->getStyle('A1:M1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:M'.$no)->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		//$sheet->getStyle('A1:D10')->getFont()->setSize(12);
		//$sheet->getStyle('A1:D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		//$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(25);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(30);
		$sheet->getColumnDimension('I')->setWidth(30);
		$sheet->getColumnDimension('J')->setWidth(30);
		$sheet->getColumnDimension('K')->setWidth(30);
		$sheet->getColumnDimension('L')->setWidth(30);
		$sheet->getColumnDimension('M')->setWidth(30);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Recharge-Coupon'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		//endif;
		/* Export excel END */
	}

	public function getSubCategory(){
		echo "string"; die();
	}
}

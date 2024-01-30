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


class Allcoupons extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('admin_model','emailtemplate_model','emailsendgrid_model','sms_model','notification_model','order_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for index
	 + + Date 			: 30 January 2024
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'uwin';
		$data['activeSubMenu'] 				= 	'allcoupons';

		if($this->input->get('searchField') == 'status'):
			if($this->input->get('fromDate')):
				$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->get('fromDate')));  //2023-03-16 15:13
				$whereCon['where_gte'] 			= 	array(array("update_date",strtotime($data['fromDate'])));
			endif;

			if($this->input->get('toDate')):
				$data['toDate'] 				=   date('Y-m-d 23:59 ', strtotime($this->input->get('toDate')));  //2023-03-16 15:13
				$whereCon['where_lte'] 			= 	array(array("update_date",strtotime($data['toDate'])));
			endif;
		else:
			if($this->input->get('fromDate')):
				$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->get('fromDate')));  //2023-03-16 15:13
				$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
			endif;

			if($this->input->get('toDate')):
				$data['toDate'] 				=   date('Y-m-d 23:59 ', strtotime($this->input->get('toDate')));  //2023-03-16 15:13
				$whereCon['where_lte'] 			= 	array(array("created_at",$data['toDate']));
			endif;
		endif;

		// Where conditions section.
		if($this->input->get('searchField') && $this->input->get('searchValue')):
			$sField							=	$this->input->get('searchField');
			$sValue							=	$this->input->get('searchValue');
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;

			if($sField == 'ticket'):
				$whereCon['where']		 			= 	array($sField=> "[[".$sValue."]]" );	


			elseif( $sField == "available_coupon"):
				// $whereCon['where']		 			= 	array($sField=> "[[".$sValue."]]" );	

			$data['result'] = $this->CheckAvailableUWinCoupons($sValue);

			else:
				if(is_numeric($sValue)):
					$whereCon['where']		 		= 	array($sField=> (int)$sValue );	
				else:
					$whereCon['where']		 		= 	array($sField=> $sValue );	
				endif;
			endif;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
			$whereCon['where']		 		= 	array('order_status'=> array('$ne' => 'Initialize'));	
		endif;

		$shortField 						= 	array('sequence_id'=> -1);
		$baseUrl 							= 	getCurrentControllerPath('index');
		
		$this->session->set_userdata('ALLORDERSDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_lotto_orders';
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
	 
		$OrdersDetails	= $data['ALLDATA']  = 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page);
		// echo '<pre>';print_r($data);die();
		
		$this->layouts->set_title('U Win | Dealz Arabia');
		$this->layouts->admin_view('uwin/allcoupons/index',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : addeditdata
	 + + Developed By  : Dilip Halder
	 + + Purpose  	   : This function used for Add Edit data
	 + + Date 		   : 30 January 2024
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function addeditdata($editId='')
	{		
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'orders';
		$data['activeSubMenu'] 				= 	'alllottooders';
		
		if($editId):
			$data['orderData']				=	$this->common_model->getDataByParticularField('da_lotto_orders','order_id',$editId);
		else:
			redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
		endif;
		
		$this->layouts->set_title('U Win | Dealz Arabia');
		$this->layouts->admin_view('uwin/allcoupons/addeditdata',array(),$data);
	}	// END OF FUNCTION	

	/***********************************************************************
	** Function name 	: changestatus
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for change status
	** Date 			: 30 January 2024
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('edit_data');
		$param['status']		=	$statusType;
		$this->common_model->editData('da_category',$param,'category_id',(int)$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: cancelationorder
	** Developed By 	: Dilip Kumar Halder
	** Purpose  		: This function used for order cancelation by admin.
	** Date 			: 30 January 2024
	************************************************************************/
	function cancelationorder($changeStatusId='')
	{  
		
		$this->admin_model->authCheck('edit_data');
		// $this->common_model->editData('da_category',$param,'category_id',(int)$changeStatusId);
		$tblName 				= 'da_orders';
		$whereCon['where']		=	 array('order_id' => $changeStatusId );
		$shortField 			= array('sequence_id' => -1);
		$cancleOrderData 		= 	$this->common_model->getData('single',$tblName,$whereCon,$shortField,'0','0');


			//Adding calcelation variable and value.
			$param1['status']			= 'CL';
			$param1['update_ip']		=	currentIp();
			$param1['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
			$param1['refund_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
			$param1['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');


			$this->common_model->editData('da_orders',$param1,'order_id',$changeStatusId);
			$this->common_model->editData('da_orders_details',$param1,'order_id',$changeStatusId);		

			// Checking Sender User.
			$userid = $cancleOrderData['user_id'];
			
			$tblName 				=   'da_users';
			$whereCon['where']		=	array('users_id' => $userid , 'status'=> 'A' );
			$shortField 			=   array('users_id' => -1);
			$UserData 			= 	$this->common_model->getData('single',$tblName,$whereCon,$shortField,'0','0');

			$refund_amount = $cancleOrderData['total_price'];
			/* Load Balance Table -- after buy product*/
		    $refundparam["load_balance_id"]			=	(int)$this->common_model->getNextSequence('da_loadBalance');
		    $refundparam['order_id']				=	$cancleOrderData['order_id'];
			$refundparam["user_id_cred"] 			=	(int)$userid;
			$refundparam["user_id_deb"]				=	(int)0;
			$refundparam["arabian_points"] 			=	(float)$refund_amount;
			$refundparam["availableArabianPoints"] 	=	(float)$UserData["availableArabianPoints"];
	        $refundparam["end_balance"] 			=	(float)$UserData["availableArabianPoints"] + (float)$refund_amount ;
		    $refundparam["arabian_points_from"] 	=	'Refund';
		    $refundparam["record_type"] 			=	'Credit';
		    $refundparam["remarks"]					=	"Arabian points added from admin.";
		    $refundparam["creation_ip"] 			=	$this->input->ip_address();
		    $refundparam["created_at"] 				=	date('Y-m-d H:i');
		    $refundparam["created_by"] 				=	"Admin";
		    $refundparam["status"] 					=	"A";
		    $refundparam["created_user_id"] 		=	 (int)$this->session->userdata('HCAP_ADMIN_ID');

		    $this->common_model->addData('da_loadBalance', $refundparam);

			// Refunded Sender Cancelation Order Amount.
			if($UserData['availableArabianPoints']):
				$param['availableArabianPoints'] = $UserData['availableArabianPoints'] + $cancleOrderData['total_price'];
				$this->common_model->editData('da_users',$param,'users_id',(int)$userid);	
			endif;

			// User detail  used for email and sms  
			$this->emailsendgrid_model->sendOrderMailToUser($refundparam['order_id']);
			$this->sms_model->sendTicketDetails($refundparam['order_id']);

		$this->session->set_flashdata('alert_success',lang('ordercenclesuccess'));
		
		redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: deletedata
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for delete data
	** Date 			: 14 JULY 2022
	************************************************************************/
	function deletedata($deleteId='')
	{  
		
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_category','category_id',(int)$deleteId);
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
	}
	 
 	/***********************************************************************
	** Function name 	: exportexcel
	** Developed By 	: Dilip halder
	** Purpose  		: This function used for export order data
	** Date 			: 30 January 2024
	************************************************************************/
	function exportexcel()
	{	
		$this->admin_model->authCheck();

		if($this->input->post('searchField') == 'status'):
			if($this->input->post('fromDate')):
				$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->post('fromDate')));  //2023-03-16 15:13
				$whereCon['where_gte'] 			= 	array(array("update_date",strtotime($data['fromDate'])));
			endif;

			if($this->input->post('toDate')):
				$data['toDate'] 				=   date('Y-m-d 23:59 ', strtotime($this->input->post('toDate')));  //2023-03-16 15:13
				$whereCon['where_lte'] 			= 	array(array("update_date",strtotime($data['toDate'])));
			endif;
		else:
			if($this->input->post('fromDate')):
				$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->post('fromDate')));  //2023-03-16 15:13
				$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
			endif;

			if($this->input->post('toDate')):
				$data['toDate'] 				=   date('Y-m-d 23:59 ', strtotime($this->input->post('toDate')));  //2023-03-16 15:13
				$whereCon['where_lte'] 			= 	array(array("created_at",$data['toDate']));
			endif;
		endif;

		// Where conditions section.
		if($this->input->post('searchField') && $this->input->post('searchValue')):
			$sField							=	$this->input->post('searchField');
			$sValue							=	$this->input->post('searchValue');
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;

			if($sField == 'ticket'):
				$whereCon['where']		 			= 	array($sField=> "[[".$sValue."]]" );	


			elseif( $sField == "available_coupon"):
				// $whereCon['where']		 			= 	array($sField=> "[[".$sValue."]]" );	

				$tblName 	 		=  'da_uwin_available_coupons';
				$shortField  		=  array('products_id'=> -1);
				$whereCon['where']  =  array('products_id' => (int)$sValue);
				$result	 			=  $this->common_model->getData('single',$tblName,$whereCon,$shortField);
				// echo "<pre>";print_r($result);die();

			else:
				if(is_numeric($sValue)):
					$whereCon['where']		 		= 	array($sField=> (int)$sValue );	
				else:
					$whereCon['where']		 		= 	array($sField=> $sValue );	
				endif;
			endif;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
			$whereCon['where']		 		= 	array('order_status'=> array('$ne' => 'Initialize'));	
		endif;
		$shortField 						= 	array('sequence_id'=> -1);

		if(empty($result)):
			$tblName  = 'da_lotto_orders'; 
			$ALLDATA  = $this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page);
		endif;
		// echo '<pre>';print_r($ALLDATA);die();

		if($result):
			
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Sl.No');
			$sheet->setCellValue('B1', 'AVAILABLE COUPONS');

			$slno = 1;
			$start = 2;
			foreach($result['available_coupons'] as $couponArray):
				$coupon = implode(', ', $couponArray);
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, $coupon);
				$slno = $slno+1;
				$start = $start+1;
			endforeach;

			$styleThinBlackBorderOutline = [
						'borders' => [
							'allBorders' => [
								'borderStyle' => Border::BORDER_THIN,
								'color' => ['argb' => 'FF000000'],
							],
						],
					];
			//Font BOLD
			$sheet->getStyle('A1:B1')->getFont()->setBold(true);		
			$sheet->getStyle('A1:B1')->applyFromArray($styleThinBlackBorderOutline);
			
			//Custom width for Individual Columns
			$sheet->getColumnDimension('A')->setWidth(10);
			$sheet->getColumnDimension('B')->setWidth(30);

			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'U-WIN-Available-Coupons-'.$result['products_id'].'-'.$curdate;
			ob_end_clean();
		else:
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Sl.No');
			$sheet->setCellValue('B1', 'ORDER ID');
			$sheet->setCellValue('C1', 'Product Name');
			$sheet->setCellValue('D1', 'Quantity');
			$sheet->setCellValue('E1', 'SELLER Name');
			$sheet->setCellValue('F1', 'SELLER Mobile');
			$sheet->setCellValue('G1', 'SELLER Email');
			$sheet->setCellValue('H1', 'PURCHASE DATE');
			$sheet->setCellValue('I1', 'TOTAL AMOUNT');
			$sheet->setCellValue('J1', 'AVAILABLE ARABIANPOINTS');
			$sheet->setCellValue('K1', 'END BALANCE');
			$sheet->setCellValue('L1', 'PAYMENT MODE');
			$sheet->setCellValue('M1', 'PAYMENT STATUS');
			$sheet->setCellValue('N1', 'STATUS');
			
			$slno = 1;
			$start = 2;
			foreach($ALLDATA as $ALLDATAINFO):
	          	$wcon['where']  = array('users_id'=> $ALLDATAINFO['user_id'] );
	          	$sellersDetails = $this->common_model->getData('single','da_users',$wcon);
	    	    $PurchaseDate   = date('d M Y h:i:s A', strtotime($ALLDATAINFO['created_at']));
				 
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, $ALLDATAINFO['order_id']);
				$sheet->setCellValue('C'.$start, $ALLDATAINFO['product_title']);
				$sheet->setCellValue('D'.$start, $ALLDATAINFO['product_qty']);
				$sheet->setCellValue('E'.$start, $sellersDetails['users_name']);
				$sheet->setCellValue('F'.$start, $sellersDetails['users_mobile']);
				$sheet->setCellValue('G'.$start, $sellersDetails['users_email']);
				$sheet->setCellValue('H'.$start, $PurchaseDate);
				$sheet->setCellValue('I'.$start, number_format($ALLDATAINFO['total_price'],2));
				$sheet->setCellValue('J'.$start, $ALLDATAINFO['availableArabianPoints']);
				$sheet->setCellValue('K'.$start, $ALLDATAINFO['end_balance']);
				$sheet->setCellValue('L'.$start, $ALLDATAINFO['payment_mode']);
				$sheet->setCellValue('M'.$start, $ALLDATAINFO['order_status']);
				$sheet->setCellValue('N'.$start, $ALLDATAINFO['status']);
				$slno = $slno+1;
				$start = $start+1;
			endforeach;

			$styleThinBlackBorderOutline = [
						'borders' => [
							'allBorders' => [
								'borderStyle' => Border::BORDER_THIN,
								'color' => ['argb' => 'FF000000'],
							],
						],
					];
			//Font BOLD
			$sheet->getStyle('A1:N1')->getFont()->setBold(true);		
			$sheet->getStyle('A1:N1')->applyFromArray($styleThinBlackBorderOutline);
			//Alignment
			//fONT SIZE
			//$sheet->getStyle('A1:D10')->getFont()->setSize(12);
			//$sheet->getStyle('A1:D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			//$sheet->getStyle('A2:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			//Custom width for Individual Columns
			$sheet->getColumnDimension('A')->setWidth(5);
			$sheet->getColumnDimension('B')->setWidth(20);
			$sheet->getColumnDimension('C')->setWidth(30);
			$sheet->getColumnDimension('D')->setWidth(30);
			$sheet->getColumnDimension('E')->setWidth(30);
			$sheet->getColumnDimension('F')->setWidth(15);
			$sheet->getColumnDimension('G')->setWidth(30);
			$sheet->getColumnDimension('H')->setWidth(30);
			$sheet->getColumnDimension('I')->setWidth(15);
			$sheet->getColumnDimension('J')->setWidth(15);
			$sheet->getColumnDimension('K')->setWidth(30);
			$sheet->getColumnDimension('L')->setWidth(30);
			$sheet->getColumnDimension('M')->setWidth(30);
			$sheet->getColumnDimension('N')->setWidth(30);

			$curdate = date('d-m-Y H:i:s');
			$writer = new Xlsx($spreadsheet);
			$filename = 'U-WIN-Data-'.$curdate;
			ob_end_clean();

		endif;




		

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: generatecoupons
	** Developed By 	: Dilip halder
	** Purpose  		: This function used for generate coupons from admin panel.
	** Date 			: 20 July 2023
	** Updated Date 	:  
	** Updated By   	:  
	************************************************************************/
	public function generatecoupons()
	{
		$oid = $this->input->post('order_id');
		// $oid = "LZIDN3160191";
		//Get current order of user.
		$wcon['where']					=	[ 'order_id' => $oid ];
		$data['orderData'] 				=	$this->common_model->getData('single', 'da_orders', $wcon);
		
		if($data['orderData']['user_id'] === 0):
			$user_phone  =  $data['orderData']['user_phone'];
			// $url = "http://localhost/d-arabia/api/telrOrderSuccess?user_phone=$user_phone&order_id=$oid";
			$url = "https://dealzarabia.com/api/telrOrderSuccess?user_phone=$user_phone&order_id=$oid";
			
		else:
			$users_id  =  $data['orderData']['user_id'];
			$url = "https://dealzarabia.com/api/telrOrderSuccess?users_id=$users_id&order_id=$oid";
			// $url = "http://localhost/d-arabia/api/telrOrderSuccess?users_id=$users_id&order_id=$oid";
		endif;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Apikey: c9d58f135dab835ecf44e7c64b978599',
				'Apidate: 2022-06-13',
				'Cookie: ci_session=2t9td2m3ihodk6ptpp5ml3s3vuni65p4; ci_session=r4n3192ojb4ng4mo30m5gdstb4hu5n06; MainLoad=web2|ZLaPN|ZLaPN; MainLoad=web1|ZLkzn|ZLkzj'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$result = json_decode($response);

		if($result->message == 'Your order placed successfully'):
			$this->session->set_flashdata('alert_success',lang('Coupon_Generaion'));
			redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
		else:
			$this->session->set_flashdata('alert_success',lang('Coupon_Not_Generaion'));
			redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
		endif;
	}


	/***********************************************************************
	** Function name 	: CheckAvailableUWinCoupons
	** Developed By 	: Dilip halder
	** Purpose  		: This function used for check available coupons.
	** Date 			: 29 January 2024
	************************************************************************/
	public function CheckAvailableUWinCoupons($products_id ='')
	{
		// Product Details
	 	$tblName 	 	   = 'da_lotto_products';
		$whereCon['where'] = array('products_id' => (int)$products_id ,'status' => 'A' );
		$productDetails    = $this->common_model->getData('single',$tblName,$whereCon,$shortField);

		

		// lotto Order Details
		$tblName 	 	   = 'da_lotto_orders';
		$whereCon['where'] = array('product_id' => (int)$products_id ,'status' => 'A' );
		$Field 			   = array('ticket','status');
		$orderDetails      = $this->common_model->getDataByNewQuery($Field,'multiple',$tblName,$whereCon);

		$soldoutNumber = array();
		foreach($orderDetails as $item):
			if($item && $item['status'] == 'A'):
				$tickets = json_decode($item['ticket']);
				foreach($tickets as $items):
					$soldoutNumber[] = $items;
				endforeach;
			endif;
		endforeach;

		$result = $this->AvailableUWinCoupons($soldoutNumber ,$productDetails );

		return $result;

	}


	public function AvailableUWinCoupons($soldoutNumber='', $productDetails='')
	{

		$lotto_type      = $productDetails['lotto_type'];
		$lotto_range     = $productDetails['lotto_range'];
		$products_id 	 = $productDetails['products_id'];

		$numbeUnique     = "Y";
		$required_ticket = 500;

		$resultNumbers = array();
		for ($i=0; $i <$required_ticket; $i++):
			$resultNumbers[] = $this->lottogenerate($lotto_type,$lotto_range ,$numbeUnique,$required_ticket);
		endfor;

		$UniqueCoupons = $this->checkresult($resultNumbers,$soldoutNumber);
		$result['unique_coupons'] = $UniqueCoupons;


		if($UniqueCoupons):

			$tblName 	 		= 	'da_uwin_available_coupons';
			$shortField  		= 	array('products_id'=> -1);
			$whereCon['where']  =  array('products_id' => (int)$products_id);
			$existdata	 		=	$this->common_model->getData('count',$tblName,$whereCon,$shortField);

			$param['products_id']		=	(int)$products_id;
			$param['available_coupons']	=	$UniqueCoupons;
			if($existdata):
				$param['update_ip']		=	currentIp();
				$param['update_date']	=	(int)$this->timezone->utc_time();//currentDateTime();
				$param['updated_by']	=	(int)$this->session->userdata('HCAP_ADMIN_ID');
				$this->common_model->editData('da_uwin_available_coupons',$param,'products_id',(int)$products_id);
			else:
				$param['creation_ip']	=	currentIp();
				$param['creation_date']	=	(int)$this->timezone->utc_time();//currentDateTime();
				$param['created_by']	=	(int)$this->session->userdata('HCAP_ADMIN_ID');
				$param['status']		=	'A';
				$alastInsertId			=	$this->common_model->addData('da_uwin_available_coupons',$param);
			endif;
			

		endif;



		return $result;
	}


	public function lottogenerate($lotto_type,$ticket_range ,$numbeUnique)
	{
		$uniqueNumbers = [];
		// Generate 5 unique random numbers and add them to the array

			while (count($uniqueNumbers) < $lotto_type):
			    $randomNumber = rand(1, $ticket_range); // Change the range as per your requirement
			    
			    if($numbeUnique == "Y" && !in_array($randomNumber, $uniqueNumbers) ):
			        $uniqueNumbers[] = $randomNumber;
			    endif;

			 	if($numbeUnique == "N"):
			        $uniqueNumbers[] = $randomNumber;
			    endif;
			endwhile;
			return $uniqueNumbers;
	}

	public function checkresult($resultNumbers,$soldoutNumbers='')
	{

	 	foreach ($soldoutNumbers as $key => $soldoutNumber):

	        if (count($soldoutNumber) == count($resultNumbers) && array_search($resultNumbers, $soldoutNumbers) !== false):
	            return true;
	        else:
				return $resultNumbers;
	        endif;

    	endforeach;
	}




}
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


class Allquickorders extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model','order_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: MANOJ KUMAR
	 + + Purpose  		: This function used for index
	 + + Date 			: 14 JULY 2022
	 + + Updated Date 	: Dilip Halder
	 + + Updated By   	: 15 June 2023
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'orders';
		$data['activeSubMenu'] 				= 	'allquickorders';
		
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
		
		if($this->input->get('searchField') && $this->input->get('searchValue')):
			
				$data['searchField'] 			= 	$this->input->get('searchField');
				$data['searchValue'] 			= 	$this->input->get('searchValue');

			if($this->input->get('searchField') == "created_at"):

				$sField							=	$this->input->get('searchField');
				$sValue							=	date('Y-m-d 00:00 ', strtotime($this->input->get('searchValue')));  //2023-03-16 15:13

				if($this->input->get('searchField') == 'created_at'):
					$data['fromDate'] 				=   date('Y-m-d 00:00', strtotime($this->input->get('searchValue')));  //2023-03-16 15:13
					$data['endDate'] 				=   date('Y-m-d 23:59', strtotime($this->input->get('searchValue')));  //2023-03-16 15:13
					$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
					$whereCon['where_lte'] 			= 	array(array("created_at",$data['endDate']));
				endif;
			
			elseif($this->input->get('searchField') == 'users_mobile'):
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');

				$wcon['where'] = array('users_mobile'=> (int)$sValue);

	          	$sellersDetails = $this->common_model->getData('single','da_users',$wcon);

				$whereCon['where'] 			= 	array('user_id' => (int)$sellersDetails['users_id'] );

			elseif($this->input->get('searchField') == 'product_id'):
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$whereCon['where'] 				= 	array($sField => (int)$sValue);

			else:
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;


			endif;

		else:
			$whereCon['search']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

		
				
		// $whereCon['where']		 			= 	array('order_status'=> array('$ne' => 'Initialize'));		
		$shortField 						= 	array('_id'=>-1);
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('ALLQUICKORDERSDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_ticket_orders';
		$con 								= 	'';
		// $totalRows 							= 	$this->order_model->getQuickordersList('count',$tblName,$whereCon,$shortField,'0','0');
		
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

		// echo "<pre>";
		// print_r($whereCon);
		// die();

		// $data['ALLDATA'] 					= 	$this->order_model->getQuickordersList('multiple',$tblName,$whereCon,$shortField,$page,$perPage);
		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page);

		$this->layouts->set_title('Quick Orders | Dealz Arabia');
		$this->layouts->admin_view('orders/quick/index',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : addeditdata
	 + + Developed By  : MANOJ KUMAR
	 + + Purpose  	   : This function used for Add Edit data
	 + + Date 		   : 14 JULY 2022
	 + + Updated Date  : 
	 + + Updated By    :
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function addeditdata($editId='')
	{		
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'orders';
		$data['activeSubMenu'] 				= 	'allquickorders';
		
		if($editId):
			$data['orderData']				=	$this->common_model->getDataByParticularField('da_ticket_orders','ticket_order_id',$editId);

			$ORDDwhere['where'] 			=	array('ticket_order_id'=>$editId);
			$ORDDorder						=	array('_id'=>-1);

			$data['orderDetails'] 			= 	$this->common_model->getData('single','da_ticket_coupons', $ORDDwhere, $ORDDorder);
		else:
			redirect(correctLink('ALLQUICKORDERSDATA',getCurrentControllerPath('index')));
		endif;

		// echo "<pre>";
		// print_r($data);
		// die();
		
		$this->layouts->set_title('Quick Orders | Dealz Arabia');
		$this->layouts->admin_view('orders/quick/addeditdata',array(),$data);
	}	// END OF FUNCTION	

	/***********************************************************************
	** Function name 	: changestatus
	** Developed By 	: MANOJ KUMAR
	** Purpose  		: This function used for change status
	** Date 			: 14 JULY 2022
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('edit_data');
		$param['status']		=	$statusType;
		$this->common_model->editData('da_category',$param,'category_id',(int)$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('ALLQUICKORDERSDATA',getCurrentControllerPath('index')));
	}


	/***********************************************************************
	** Function name 	: cancelationorder
	** Developed By 	: Dilip Kumar Halder
	** Purpose  		: This function used for Quick order cancelation by admin.
	** Date 			: 11 Apirl 2023
	************************************************************************/
	function cancelationorder($changeStatusId='')
	{  

		$this->admin_model->authCheck('edit_data');
		// $this->common_model->editData('da_category',$param,'category_id',(int)$changeStatusId);
		$tblName 				= 'da_ticket_orders';
		$whereCon['where']		=	 array('ticket_order_id' => $changeStatusId , 'payment_mode' => 'Arabian Points');
		$shortField 			= array('sequence_id' => -1);
		$cancleOrderData 		= 	$this->common_model->getData('single',$tblName,$whereCon,$shortField,'0','0');
		
		if($cancleOrderData): 

			//Adding calcelation variable and value.
			$param1['status']			= 'CL';
			$param1['update_ip']		=	currentIp();
			$param1['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
			$param1['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');

			$this->common_model->editData('da_ticket_orders',$param1,'ticket_order_id',$changeStatusId);		

			// Checking Sender User.
			$senderId = $cancleOrderData['user_id'];
			
			$tblName 				=   'da_users';
			$whereCon['where']		=	array('users_id' => $senderId , 'status'=> 'A' );
			$shortField 			=   array('users_id' => -1);
			$SenderData 		= 	$this->common_model->getData('single',$tblName,$whereCon,$shortField,'0','0');

			$refund_amount = $cancleOrderData['total_price'];
			/* Load Balance Table -- after buy product*/
		    $refundparam["load_balance_id"]			=	(int)$this->common_model->getNextSequence('da_loadBalance');
		    $refundparam['order_id']				=	$cancleOrderData['ticket_order_id'];
			$refundparam["user_id_cred"] 			=	(int)$senderId;
			$refundparam["user_id_deb"]				=	(int)$senderId;
			$refundparam["arabian_points"] 			=	(float)$refund_amount;
			$refundparam["availableArabianPoints"] 	=	(float)$SenderData["availableArabianPoints"];
	        $refundparam["end_balance"] 			=	(float)$SenderData["availableArabianPoints"] + (float)$refund_amount ;
		    $refundparam["record_type"] 			=	'Credit';
		    $refundparam["arabian_points_from"] 	=	'Refund';
		    $refundparam["creation_ip"] 			=	$this->input->ip_address();
		    $refundparam["created_at"] 				=	date('Y-m-d H:i');
		    $refundparam["created_by"] 				=	(int)$this->session->userdata('DZL_USERSTYPE');
		    $refundparam["status"] 					=	"A";

		    $this->common_model->addData('da_loadBalance', $refundparam);

			// Refunded Sender Cancelation Order Amount.
			if($SenderData['availableArabianPoints']):
				$param['availableArabianPoints'] = $SenderData['availableArabianPoints'] + $cancleOrderData['total_price'];
				$this->common_model->editData('da_users',$param,'users_id',(int)$senderId);	
			endif;

			$this->sms_model->sendCancelQuickBuy($refundparam['order_id']);

		endif;
		

		$this->session->set_flashdata('alert_success',lang('ordercenclesuccess'));
		
		redirect(correctLink('ALLQUICKORDERSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: deletedata
	** Developed By 	: MANOJ KUMAR
	** Purpose  		: This function used for delete data
	** Date 			: 14 JULY 2022
	************************************************************************/
	function deletedata($deleteId='')
	{  
		
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_category','category_id',(int)$deleteId);
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('ALLQUICKORDERSDATA',getCurrentControllerPath('index')));
	}


		
	/***********************************************************************
	** Function name 	: exportexcel
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for export order data
	** Date 			: 07 JAN 2023
	** Updated Date 	: 21 February 2022
	** Updated By   	: Dilip Halder
	************************************************************************/
	function exportexcel($load_balance_id='')
	{  
		/* Export excel button code */
		/* Export excel button code */
		if($this->input->post('searchField') == 'status'):

			if($this->input->post('fromDate')):
				$start_date = strtotime($this->input->post('fromDate'));
				$whereCon['where_gte'] 			= 	array(array("update_date",$start_date));
			endif;

			if($this->input->post('toDate')):
				$end_date = strtotime($this->input->post('toDate'));
				$whereCon['where_lte'] 			= 	array(array("update_date",$end_date));
			endif;
		else:

			if($this->input->post('fromDate')):
				$date1 = strtotime($this->input->post('fromDate'));
				$start_date = date('Y-m-d 00:00',$date1);
				$data['fromDate'] 				= 	$start_date;
				$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
			endif;
			
			if($this->input->post('toDate')):
				$date2 = strtotime($this->input->post('toDate'));
				$end_date = date('Y-m-d 23:59',$date2);
				$data['toDate'] 				= 	$end_date;
				$whereCon['where_lte'] 			= 	array(array("created_at",$data['toDate']));
			endif;

		endif;

		// echo "<pre>";
		// print_r($whereCon);
		// die();

		if($this->input->post('searchField') == 'users_mobile'):
				$sField							=	$this->input->post('searchField');
				$sValue							=	$this->input->post('searchValue');

				$wcon['where'] = array('users_mobile'=> (int)$sValue);

	          	$sellersDetails = $this->common_model->getData('single','da_users',$wcon);

				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$this->input->post('searchValue');

				$whereCon['where'] 			= 	array('user_id' => (int)$sellersDetails['users_id'] );

		elseif($this->input->post('searchField') == 'product_id'):
				$sField							=	$this->input->post('searchField');
				$sValue							=	$this->input->post('searchValue');
				$whereCon['where'] 				= 	array($sField => (int)$sValue);

		elseif($this->input->post('searchField') && $this->input->post('searchValue')):
			$sField							=	$this->input->post('searchField');
			$sValue							=	$this->input->post('searchValue');
			$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

		$tblName 							= 	'da_ticket_orders';
		$shortField 						= 	array('_id'=>-1);

		$order 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField);

		// echo "<pre>";
		// print_r($order);
		// die();


        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'Order No');
		$sheet->setCellValue('C1', 'Product Name');
		$sheet->setCellValue('D1', 'Product Quantity');
		$sheet->setCellValue('E1', 'First Name');
		$sheet->setCellValue('F1', 'Last Name');
		$sheet->setCellValue('G1', 'User Phone');
		$sheet->setCellValue('H1', 'User Email');
		$sheet->setCellValue('I1', 'Seller First Name');
		$sheet->setCellValue('J1', 'Seller Last Name');
		$sheet->setCellValue('K1', 'Seller Type');
		$sheet->setCellValue('L1', 'Seller Email');
		$sheet->setCellValue('M1', 'Bind with (Name)');
		$sheet->setCellValue('N1', 'Donated');
		$sheet->setCellValue('O1', 'Purchase Date');
		$sheet->setCellValue('P1', 'Purchase Time');
		$sheet->setCellValue('Q1', 'Total Amount');
		$sheet->setCellValue('R1', 'Payment Mode');
		$sheet->setCellValue('S1', 'Payment Status');
		$sheet->setCellValue('T1', 'Order Status');
		$sheet->setCellValue('U1', 'Voucher Code');
		$sheet->setCellValue('V1', 'Voucher Status');
		
		$sheet->setCellValue('W1', 'Coupon Code');
		$sheet->setCellValue('X1', 'Last Updated By');
		$sheet->setCellValue('Y1', 'Last Date');
		
		$slno = 1;
		$start = 2;

		foreach($order as $d){

				$wcon['where'] = array('users_id'=> (int)$d['user_id'] );
              	$sellersDetails = $this->common_model->getData('single','da_users',$wcon);
			
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, $d['ticket_order_id']);
				$sheet->setCellValue('C'.$start, stripslashes($d['product_title']));
				$sheet->setCellValue('D'.$start, stripslashes($d['product_qty']));
				
				//User Details..
				$sheet->setCellValue('E'.$start, stripslashes($d['order_first_name']));
				$sheet->setCellValue('F'.$start, stripslashes($d['order_last_name']));
				$sheet->setCellValue('G'.$start, stripslashes($d['order_users_mobile']));
				$sheet->setCellValue('H'.$start, stripslashes($d['order_users_email']));

				$sheet->setCellValue('I'.$start, stripslashes($sellersDetails['users_name']));
				$sheet->setCellValue('J'.$start, stripslashes($sellersDetails['last_name']));
				$sheet->setCellValue('K'.$start, stripslashes($sellersDetails['users_type']));
				$sheet->setCellValue('L'.$start, stripslashes($sellersDetails['users_email']));
				//Seller Details.
				
				 $wcon['where'] = array('users_id'=> (int)$sellersDetails['bind_person_id'] );
                 $bindWITH = $this->common_model->getData('single','da_users',$wcon);


                 // $bindPersonDetails = 'Name : '. $bindWITH['users_name'].PHP_EOL . 'Type : '.$bindWITH['users_type']. PHP_EOL . 'Email : '.$bindWITH['users_email']. PHP_EOL  . 'Mobile : '.$bindWITH['users_mobile']. PHP_EOL ; 
                 $bindPersonDetails =  $bindWITH['users_name']. " " .$bindWITH['last_name']; 

                 if($bindWITH):
						$sheet->setCellValue('M'.$start, $bindPersonDetails);
						// $sheet->getStyle('M'.$start)->getAlignment()->setWrapText(true);
                 else:
						$sheet->setCellValue('M'.$start,  '--');
                 endif;

				$sheet->setCellValue('N'.$start, $d['product_is_donate']);
				$sheet->setCellValue('O'.$start, date('d-F-Y',strtotime($d['created_at'])));
				$sheet->setCellValue('P'.$start, date('h:i A',strtotime($d['created_at'])));
				$sheet->setCellValue('Q'.$start, $d['total_price']);


		       $whereCon['where']   = array( 'isVoucher'=> 'Y' , 'ticket_order_id' => $d['ticket_order_id']);
		       $isVoucher           =  $this->common_model->getData('count','da_ticket_coupons',$whereCon);

		       if($isVoucher == 0):
		        $buy_voucher =  "Buy Product";
		       else:
		        $buy_voucher =  "Buy Vouchers";
		       endif;

				$sheet->setCellValue('R'.$start, $buy_voucher);




				if($d['status'] == 'CL'):
					$order_status = "Cancelled";
				else:
					$order_status = "-";
				endif;
				
				$sheet->setCellValue('S'.$start, $s['order_status']);
				$sheet->setCellValue('T'.$start, $order_status);


				$tblName 							= 	'da_ticket_coupons';
				$shortField 						= 	array('_id'=>-1);
				$whereCon2['where']					=	array("ticket_order_id" => $d['ticket_order_id']);
				$couponList 								= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField);
				
				

				$sheet->setCellValue('U'.$start, $couponList['voucher_code']);
				$sheet->setCellValue('V'.$start, $couponList['coupon_code_statys'] );

				foreach ($couponList['coupon_code'] as $co) {
					if(!empty($co)):
						$sheet->setCellValue('W'.$start, $co);
						$start = $start+1;
					endif;
				}

				$sheet->setCellValue('X'.$start, $d['updated_by']);
				if($d['update_date']):
					$update_date =  date('d-M-Y H:i A',$d['update_date']);
				else:
					$update_date = ' -- ';
				endif;


				$sheet->setCellValue('Y'.$start, $update_date);
				// $sheet->setCellValue('W'.$start, $d['update_date']);
				
				// $start = $start+1;
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
		$sheet->getStyle('A1:Y1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:Y1')->applyFromArray($styleThinBlackBorderOutline);
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
		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->getColumnDimension('M')->setWidth(15);
		$sheet->getColumnDimension('N')->setWidth(15);
		$sheet->getColumnDimension('O')->setWidth(25);
		$sheet->getColumnDimension('P')->setWidth(15);
		$sheet->getColumnDimension('Q')->setWidth(20);
		$sheet->getColumnDimension('R')->setWidth(25);
		$sheet->getColumnDimension('S')->setWidth(10);
		$sheet->getColumnDimension('T')->setWidth(20);
		$sheet->getColumnDimension('U')->setWidth(15);
		$sheet->getColumnDimension('V')->setWidth(20);
		$sheet->getColumnDimension('W')->setWidth(20);
		$sheet->getColumnDimension('X')->setWidth(25);
		$sheet->getColumnDimension('Y')->setWidth(50);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Quick Order Details'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		//endif;
		/* Export excel END */
		//redirect('orders/allorders/index');
	}


}
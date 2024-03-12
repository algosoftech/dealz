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


class Allorders extends CI_Controller {

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
	 + + Developed By 	: MANOJ KUMAR
	 + + Purpose  		: This function used for index
	 + + Date 			: 14 JULY 2022
	 + + Updated Date 	: 15 June 2023
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'orders';
		$data['activeSubMenu'] 				= 	'allorders';

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
			// Checking search result as per date
			if($this->input->get('searchField') == "created_at"):

				$sField							=	$this->input->get('searchField');
				$sValue							=	date('Y-m-d 00:00 ', strtotime($this->input->get('searchValue')));  //2023-03-16 15:13
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$this->input->get('searchValue');

				if($this->input->get('searchField') == 'created_at'):

					$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->get('searchValue')));  //2023-03-16 15:13
					$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
				endif;
			// Checking result as per product name	
			elseif($this->input->get('searchField') == 'product_name'  || $this->input->get('searchField') == 'product_id'):
				
				$sField								=	$this->input->get('searchField');
				if($sField  == 'product_id'):
					$sValue							=	(int)$this->input->get('searchValue');
				else:
					$sValue							=	$this->input->get('searchValue');
				endif;
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				$whereCon1['where']			 	= 	array($sField => $sValue);
				// $whereCon['where'] = array('order_sequence_id' => $ALLDATAINFO['sequence_id']);
             	$OrderDetails =  $this->common_model->getData('multiple','da_orders_details',$whereCon1  );
				
				if($OrderDetails):
					foreach($OrderDetails as $items):
							$OD_ORDERID[] =$items['order_id'];
					endforeach;
					
					$whereCon['where_in']		 			= 	array('0' => 'order_id'  ,'1'=> $OD_ORDERID);	
					if($data['searchField'] == 'searchField' && $data['searchValue'] != 'Initialize'):
						$whereCon['where']		 			= 	array('order_status'=> array('$ne' => 'Initialize'));
					endif;	
				else:
					$whereCon['where']		 			= 	array('product_name' => array('$eq'=> '')  ,'order_status'=> array('$ne' => 'Initialize')   );		
				endif;
			// Checking result as per user name	
			elseif($this->input->get('searchField') == 'users_name'):
				
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				$whereCon1['where']			 	= 	array($sField => $sValue);
				// $whereCon['where'] = array('order_sequence_id' => $ALLDATAINFO['sequence_id']);
             	$userDetails =  $this->common_model->getData('multiple','da_users',$whereCon1  );
				// echo '<pre>';print_r($userDetails);die();
				$odr_user_id = [];
				if($userDetails):
					foreach($userDetails as $items):
							array_push($odr_user_id,$items['users_id']);
					endforeach;
					
					$whereCon['where_in']		 			= 	array('user_id',$odr_user_id);	
					$whereCon['where']		 			= 	array('order_status'=> array('$ne' => 'Initialize'));	
				else:
					$whereCon['where']		 			= 	array('product_name' => array('$eq'=> '')  ,'order_status'=> array('$ne' => 'Initialize')   );		
				endif;
			// Checking result as per user last name	
			elseif($this->input->get('searchField') == 'last_name'):
				
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				$whereCon1['where']			 	= 	array($sField => $sValue);
             	$userDetails =  $this->common_model->getData('multiple','da_users',$whereCon1  );
				$odr_user_id = [];
				if($userDetails):
					foreach($userDetails as $items):
							array_push($odr_user_id,$items['users_id']);
					endforeach;
					
					$whereCon['where_in']		 			= 	array('user_id',$odr_user_id);	
					$whereCon['where']		 			= 	array('order_status'=> array('$ne' => 'Initialize'));	
				else:
					$whereCon['where']		 			= 	array('product_name' => array('$eq'=> '')  ,'order_status'=> array('$ne' => 'Initialize')   );		
				endif;
			// Checking result as per user phone	
			elseif($this->input->get('searchField') == 'user_phone'):
				
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				// $whereCon['where']			 	= 	array($sField => (int)$sValue);
				$whereCon['where']				=	array('$or' => array( 
													array( $sField => (int)$sValue ), 
													array($sField => $sValue) 
												));

			else:
				
				$sField							=	$this->input->get('searchField');
				$sValue							=	$this->input->get('searchValue');
				$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				//$whereCon['where']		 			= 	array('order_status'=> array('$ne' => 'Initialize'));	
			endif;

		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
			$whereCon['where']		 			= 	array('order_status'=> array('$ne' => 'Initialize'));	

		endif;
				
		$shortField 						= 	array('created_at'=>-1);
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('ALLORDERSDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_orders';
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

		// $data['ALLDATA'] 					= 	$this->order_model->getordersList('multiple',$tblName,$whereCon,$shortField,$page,$perPage);
		// $OrdersDetails	= $data['ALLDATA'] 				= 	$this->order_model->getordersList('multiple',$tblName,$whereCon,$shortField,$page,$perPage);
		// echo '<pre>';
		$OrdersDetails	= $data['ALLDATA']  = 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page);
		// echo "<pre>"; print_r($OrdersDetails);die();
		
		
		$where['where'] 		=	array('status'=>'A');
		$shortField 			=	array('_id'=> -1);
		$fields 				=	array('users_id','users_email','users_mobile','collection_point_name','collection_point_id');
		$data['collection_point'] = $this->common_model->getDataByNewQuery($fields,'multiple','da_emirate_collection_point',$where,$shortField);
		// echo '<pre>';print_r($data);die();
		$this->layouts->set_title('Orders | Dealz Arabia');
		$this->layouts->admin_view('orders/index',array(),$data);
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
		$data['activeSubMenu'] 				= 	'allorders';
		
		if($editId):
			$data['orderData']				=	$this->common_model->getDataByParticularField('da_orders','order_id',$editId);

			$ORDDwhere['where'] 			=	array('order_id'=>$editId);
			$ORDDorder						=	array('_id'=>-1);

			$data['orderDetails'] 			= 	$this->common_model->getData('multiple','da_orders_details', $ORDDwhere, $ORDDorder);
			$data['couponDetails'] 			= 	$this->common_model->getData('multiple','da_coupons', $ORDDwhere, $ORDDorder);
		else:
			redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
		endif;
		
		$this->layouts->set_title('Orders | Dealz Arabia');
		$this->layouts->admin_view('orders/addeditdata',array(),$data);
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
		
		redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: cancelationorder
	** Developed By 	: Dilip Kumar Halder
	** Purpose  		: This function used for order cancelation by admin.
	** Date 			: 05 June 2023
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
	** Developed By 	: MANOJ KUMAR
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
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for export order data
	** Date 			: 07 JAN 2023
	** Updated Date 	: 21 February 2022
	** Updated By   	: Dilip Halder
	************************************************************************/
	function exportexcel1($load_balance_id='')
	{  
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

		if($this->input->post('collection_point')){
			$collection_id = explode('|', $this->input->post('collection_point'));
			$index = count($collection_id) - 1;
			$whereCon['where']		=	array(
											'collection_point_id' => $collection_id[$index]
											);
		}
	   
		if($this->input->post('searchField') && $this->input->post('searchValue')):
			
			    $sField							=	$this->input->post('searchField');
				$sValue							=	$this->input->post('searchValue');
				$whereCon['search']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;

		else:
			$whereCon['search']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
		
		if($data['searchField'] == 'searchField' && $data['searchValue'] != 'Initialize'):
			$whereCon['where']		 			= 	array('order_status'=> array('$ne' => 'Initialize'));
		endif;		
		$shortField 						= 	array('_id'=>-1);
		$tblName 							= 	'da_orders';
		
		$order 					= 	$this->order_model->getordersList('multiple',$tblName,$whereCon,$shortField,$page,$perPage);
		
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'Order No');
		$sheet->setCellValue('C1', 'Product Name');
		$sheet->setCellValue('D1', 'Quantity');
		$sheet->setCellValue('E1', 'First Name');
		$sheet->setCellValue('F1', 'Last Name');
		$sheet->setCellValue('G1', 'User Mobile');
		$sheet->setCellValue('H1', 'User Type');
		$sheet->setCellValue('I1', 'Area Name');
		$sheet->setCellValue('J1', 'Collection Point');
		$sheet->setCellValue('K1', 'Donated');
		$sheet->setCellValue('L1', 'Web/APP');
		$sheet->setCellValue('M1', 'Purchase Date');
		$sheet->setCellValue('N1', 'Purchase Time');
		$sheet->setCellValue('O1', 'Payment Mode');
		$sheet->setCellValue('P1', 'Payment Status');
		$sheet->setCellValue('Q1', 'Total Amount');
		$sheet->setCellValue('R1', 'Coupons');
		$sheet->setCellValue('S1', 'Status');
	
		$slno = 1;
		$start = 2;
		foreach($order as $d){

			if(count($d['coupons']) > 0){
				$commaSeparatedCoupon = implode(', ', $d['coupons']);
			}else{
				$commaSeparatedCoupon = '';
			}
		
			$sheet->setCellValue('A'.$start, $slno);
			$sheet->setCellValue('B'.$start, $d['order_id']);
			$sheet->setCellValue('E'.$start, $d['user_name'][0]);
			$sheet->setCellValue('F'.$start, $d['last_name'][0]);
			$sheet->setCellValue('G'.$start, $d['user_phone']);
			$sheet->setCellValue('H'.$start, $d['user_type']);
			$sheet->setCellValue('I'.$start, $d['area_name']);
			$sheet->getStyle('I'.$start)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('J'.$start, $d['collection_point_name']);
			$sheet->setCellValue('K'.$start, $d['product_is_donate']);
			$sheet->setCellValue('L'.$start, $d['payment_from']);
			$sheet->setCellValue('M'.$start, date('d-F-Y',strtotime($d['created_at'])));
			$sheet->setCellValue('N'.$start, date('h:i A',strtotime($d['created_at'])));
			$sheet->setCellValue('O'.$start, $d['payment_mode']);
			$sheet->setCellValue('P'.$start, $d['order_status']);
			$sheet->setCellValue('Q'.$start, $d['total_price']);

			$sheet->setCellValue('R'.$start, $commaSeparatedCoupon);
			if($d['status']):
				$sheet->setCellValue('S'.$start, 'Cenceled');
			else:
				$sheet->setCellValue('S'.$start, $d['collection_status']);
			endif;
			
			foreach ($d['order_details'] as $key => $productdetail) {
				$sheet->setCellValue('C'.$start, $productdetail['product_name']);
				$sheet->setCellValue('D'.$start, $productdetail['quantity']);
				
				// $productName .=  stripslashes($productdetail['product_name']).PHP_EOL;
				// $productQuantity .=  $productdetail['quantity'].PHP_EOL;
				$start = $start+1;
			}
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
		$sheet->getStyle('A1:R1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:R1')->applyFromArray($styleThinBlackBorderOutline);
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
		$sheet->getColumnDimension('M')->setWidth(15);
		$sheet->getColumnDimension('N')->setWidth(20);
		$sheet->getColumnDimension('O')->setWidth(20);
		$sheet->getColumnDimension('P')->setWidth(20);
		$sheet->getColumnDimension('Q')->setWidth(20);
		$sheet->getColumnDimension('R')->setWidth(20);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Order Details'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		//endif;
		/* Export excel END */
		//redirect('orders/allorders/index');
	}

 	/***********************************************************************
	** Function name 	: exportexcel
	** Developed By 	: Dilip halder
	** Purpose  		: This function used for export order data
	** Date 			: 07 July 2023
	** Updated Date 	:  
	** Updated By   	:  
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

		
		if($this->input->post('searchField') && $this->input->post('searchValue')):
			// Checking search result as per date
			if($this->input->post('searchField') == "created_at"):

				$sField							=	$this->input->post('searchField');
				$sValue							=	date('Y-m-d 00:00 ', strtotime($this->input->post('searchValue')));  //2023-03-16 15:13
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$this->input->post('searchValue');

				if($this->input->post('searchField') == 'created_at'):

					$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->post('searchValue')));  //2023-03-16 15:13
					$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
				endif;
			// Checking result as per product name	
			elseif($this->input->post('searchField') == 'product_name'   || $this->input->post('searchField') == 'product_id' ):
				
				$sField							=	$this->input->post('searchField');
				if($sField  == 'product_id'):
					$sValue							=	(int)$this->input->post('searchValue');
				else:
					$sValue							=	$this->input->post('searchValue');
				endif;
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				$whereCon1['where']			 	= 	array($sField => $sValue);
				// $whereCon['where'] = array('order_sequence_id' => $ALLDATAINFO['sequence_id']);
             	$OrderDetails =  $this->common_model->getData('multiple','da_orders_details',$whereCon1  );
				
				if($OrderDetails):
					foreach($OrderDetails as $items):
							$OD_ORDERID[] =$items['order_id'];
					endforeach;
					
					$whereCon['where_in']		 			= 	array('0' => 'order_id'  ,'1'=> $OD_ORDERID);	
					
				else:
					$whereCon['where']		 			= 	array('product_name' => array('$eq'=> ''));		
				endif;
			// Checking result as per user name	
			elseif($this->input->post('searchField') == 'users_name'):
				
				$sField							=	$this->input->post('searchField');
				$sValue							=	$this->input->post('searchValue');
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				$whereCon1['where']			 	= 	array($sField => $sValue);
				// $whereCon['where'] = array('order_sequence_id' => $ALLDATAINFO['sequence_id']);
             	$userDetails =  $this->common_model->getData('multiple','da_users',$whereCon1  );
				// echo '<pre>';print_r($userDetails);die();
				$odr_user_id = [];
				if($userDetails):
					foreach($userDetails as $items):
							array_push($odr_user_id,$items['users_id']);
					endforeach;
					
					$whereCon['where_in']		 			= 	array('user_id',$odr_user_id);	
				else:
					$whereCon['where']		 			= 	array('product_name' => array('$eq'=> ''));		
				endif;
			// Checking result as per user last name	
			elseif($this->input->post('searchField') == 'last_name'):
				
				$sField							=	$this->input->post('searchField');
				$sValue							=	$this->input->post('searchValue');
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				$whereCon1['where']			 	= 	array($sField => $sValue);
             	$userDetails =  $this->common_model->getData('multiple','da_users',$whereCon1  );
				$odr_user_id = [];
				if($userDetails):
					foreach($userDetails as $items):
							array_push($odr_user_id,$items['users_id']);
					endforeach;
					
					$whereCon['where_in']		 			= 	array('user_id',$odr_user_id);	
				else:
					$whereCon['where']		 			= 	array('product_name' => array('$eq'=> '')  );		
				endif;
			// Checking result as per user phone	
			elseif($this->input->post('searchField') == 'user_phone'):
				
				$sField							=	$this->input->post('searchField');
				$sValue							=	$this->input->post('searchValue');
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
				
				// $whereCon['where']			 	= 	array($sField => (int)$sValue);

				$whereCon['where']				=	array('$or' => array( 
													array( $sField => (int)$sValue ), 
													array(	$sField => $sValue) 
												));
			else:
				
				$sField							=	$this->input->post('searchField');
				$sValue							=	$this->input->post('searchValue');
				$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
				$data['searchField'] 			= 	$sField;
				$data['searchValue'] 			= 	$sValue;
			endif;

		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';

		endif;
		 
		$tblName 							= 	'da_orders';
		$shortField 						= 	array('created_at'=>-1);
		 
		$order  = $this->common_model->getData('multiple',$tblName,$whereCon,$shortField);
		
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'Order No');
		$sheet->setCellValue('C1', 'Product Name');
		$sheet->setCellValue('D1', 'Quantity');
		$sheet->setCellValue('E1', 'User Id');
		$sheet->setCellValue('F1', 'First Name');
		$sheet->setCellValue('G1', 'Last Name');
		$sheet->setCellValue('H1', 'User Mobile');
		$sheet->setCellValue('I1', 'User Type');
		$sheet->setCellValue('J1', 'Area Name');
		$sheet->setCellValue('K1', 'Collection Point');
		$sheet->setCellValue('L1', 'Donated');
		$sheet->setCellValue('M1', 'Web/APP');
		$sheet->setCellValue('N1', 'Purchase Date');
		$sheet->setCellValue('O1', 'Purchase Time');
		$sheet->setCellValue('P1', 'Payment Mode');
		$sheet->setCellValue('Q1', 'Payment Status');
		$sheet->setCellValue('R1', 'Total Amount');
		$sheet->setCellValue('S1', 'Coupons');
		$sheet->setCellValue('T1', 'Status');


		$slno = 1;
		$start = 2;
		foreach($order as $d){


			$tableCoupon  = 'da_coupons';
			$whereCoupons['where'] =  array('order_id' => $d['order_id']);
			$CouponList   = $this->common_model->getData( 'multiple',$tableCoupon, $whereCoupons);

			if($CouponList):
				$coupons = [];
				foreach($CouponList as $co):
				 $coupons[] =  $co['coupon_code'];
				endforeach;
				$commaSeparatedCoupon = implode(', ', $coupons);
			else:
				$commaSeparatedCoupon = '';	 
			endif;

			// $field = array('users_name ,last_name');
			$field =  array('last_name' , 'users_name');
			$tbl_name = 'da_users';
			
			if($d['remark'] == 'Quick mobile Web' && $d['user_id'] == 0 || $d['user_id'] == 0): 
				
				// $quickUserWhere['where'] = array('country_code'=> $d['country_code'] , 'users_mobile'=> (int)$d['user_phone'] );
				$quickUserWhere['where'] = array('users_mobile'=> (int)$d['user_phone'] );
                $UserDetails = $this->common_model->getData('single','da_quick_users',$quickUserWhere);
                $users_name  =  $UserDetails['first_name'];
                $last_name   =  $UserDetails['last_name'];
                
			else:
				$users_name  = $this->common_model->getPaticularFieldByFields( 'users_name',$tbl_name,'users_id' ,(int)$d['user_id'] );
				$last_name   = $this->common_model->getPaticularFieldByFields( 'last_name',$tbl_name,'users_id' ,(int)$d['user_id'] );

			endif;


			 if($d['remark'] && $d['payment_from']):
         		$payment_from  = "QuickBuy - " . $d['payment_from'];
              else:
                $payment_from  =  "Normal - ". $d['payment_from'];

              endif;


			// $product_name  = $this->common_model->getPaticularFieldByFields( 'product_name','da_orders_details','order_id' ,$d['order_id'] );
			$ww['where'] = array('order_id' => $d['order_id']);
			$ProductList  = $this->common_model->getData( 'multiple','da_orders_details',$ww );
			
			$sheet->setCellValue('A'.$start, $slno);
			$sheet->setCellValue('B'.$start, $d['order_id']);
			 
			$sheet->setCellValue('E'.$start, $d['user_id']);
			$sheet->setCellValue('F'.$start, $users_name);
			$sheet->setCellValue('G'.$start, $last_name);
			$sheet->setCellValue('H'.$start, $d['user_phone']);
			$sheet->setCellValue('I'.$start, $d['user_type']);
			$sheet->setCellValue('J'.$start, $d['area_name']);
			$sheet->getStyle('J'.$start)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('K'.$start, $d['collection_point_name']);
			$sheet->setCellValue('L'.$start, $d['product_is_donate']);
			$sheet->setCellValue('M'.$start, $payment_from);
			$sheet->setCellValue('N'.$start, date('d-F-Y',strtotime($d['created_at'])));
			$sheet->setCellValue('O'.$start, date('h:i A',strtotime($d['created_at'])));
			$sheet->setCellValue('P'.$start, $d['payment_mode']);
			$sheet->setCellValue('Q'.$start, $d['order_status']);
			$sheet->setCellValue('R'.$start, $d['total_price']);

			$sheet->setCellValue('S'.$start, $commaSeparatedCoupon);
			if($d['status']):
				$sheet->setCellValue('T'.$start, 'Cenceled');
			else:
				$sheet->setCellValue('T'.$start, $d['collection_status']);
			endif;
			
			foreach ($ProductList as $key => $productdetail) {
				$sheet->setCellValue('C'.$start, $productdetail['product_name']);
				$sheet->setCellValue('D'.$start, $productdetail['quantity']);
				// $productName .=  stripslashes($productdetail['product_name']).PHP_EOL;
				// $productQuantity .=  $productdetail['quantity'].PHP_EOL;
				$start = $start+1;
			}
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
		$sheet->getStyle('A1:S1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:S1')->applyFromArray($styleThinBlackBorderOutline);
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
		$sheet->getColumnDimension('M')->setWidth(15);
		$sheet->getColumnDimension('N')->setWidth(20);
		$sheet->getColumnDimension('O')->setWidth(20);
		$sheet->getColumnDimension('P')->setWidth(20);
		$sheet->getColumnDimension('Q')->setWidth(20);
		$sheet->getColumnDimension('R')->setWidth(20);

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Order Details'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}	// END OF FUNCTION


	// function exportexcel()
	// {	
	// 	$this->admin_model->authCheck();

	// 	if($this->input->post('searchField') == 'status'):

	// 			if($this->input->post('fromDate')):
	// 				$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->post('fromDate')));  //2023-03-16 15:13
	// 				$whereCon['where_gte'] 			= 	array(array("update_date",strtotime($data['fromDate'])));
	// 			endif;

	// 			if($this->input->post('toDate')):
	// 				$data['toDate'] 				=   date('Y-m-d 23:59 ', strtotime($this->input->post('toDate')));  //2023-03-16 15:13
	// 				$whereCon['where_lte'] 			= 	array(array("update_date",strtotime($data['toDate'])));
	// 			endif;
	// 	else:

	// 			if($this->input->post('fromDate')):
	// 				$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->post('fromDate')));  //2023-03-16 15:13
	// 				$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
	// 			endif;

	// 			if($this->input->post('toDate')):
	// 				$data['toDate'] 				=   date('Y-m-d 23:59 ', strtotime($this->input->post('toDate')));  //2023-03-16 15:13
	// 				$whereCon['where_lte'] 			= 	array(array("created_at",$data['toDate']));
	// 			endif;

	// 	endif;

		
	// 	if($this->input->post('searchField') && $this->input->post('searchValue')):
	// 		// Checking search result as per date
	// 		if($this->input->post('searchField') == "created_at"):

	// 			$sField							=	$this->input->post('searchField');
	// 			$sValue							=	date('Y-m-d 00:00 ', strtotime($this->input->post('searchValue')));  //2023-03-16 15:13
	// 			$data['searchField'] 			= 	$sField;
	// 			$data['searchValue'] 			= 	$this->input->post('searchValue');

	// 			if($this->input->post('searchField') == 'created_at'):

	// 				$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->post('searchValue')));  //2023-03-16 15:13
	// 				$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
	// 			endif;
	// 		// Checking result as per product name	
	// 		elseif($this->input->post('searchField') == 'product_name'):
				
	// 			$sField							=	$this->input->post('searchField');
	// 			$sValue							=	$this->input->post('searchValue');
	// 			$data['searchField'] 			= 	$sField;
	// 			$data['searchValue'] 			= 	$sValue;
				
	// 			$whereCon1['where']			 	= 	array($sField => $sValue);
	// 			// $whereCon['where'] = array('order_sequence_id' => $ALLDATAINFO['sequence_id']);
    //          	$OrderDetails =  $this->common_model->getData('multiple','da_orders_details',$whereCon1  );
				
	// 			if($OrderDetails):
	// 				foreach($OrderDetails as $items):
	// 						$OD_ORDERID[] =$items['order_id'];
	// 				endforeach;
					
	// 				$whereCon['where_in']		 			= 	array('0' => 'order_id'  ,'1'=> $OD_ORDERID);	
					
	// 			else:
	// 				$whereCon['where']		 			= 	array('product_name' => array('$eq'=> ''));		
	// 			endif;
	// 		// Checking result as per user name	
	// 		elseif($this->input->post('searchField') == 'users_name'):
				
	// 			$sField							=	$this->input->post('searchField');
	// 			$sValue							=	$this->input->post('searchValue');
	// 			$data['searchField'] 			= 	$sField;
	// 			$data['searchValue'] 			= 	$sValue;
				
	// 			$whereCon1['where']			 	= 	array($sField => $sValue);
	// 			// $whereCon['where'] = array('order_sequence_id' => $ALLDATAINFO['sequence_id']);
    //          	$userDetails =  $this->common_model->getData('multiple','da_users',$whereCon1  );
	// 			// echo '<pre>';print_r($userDetails);die();
	// 			$odr_user_id = [];
	// 			if($userDetails):
	// 				foreach($userDetails as $items):
	// 						array_push($odr_user_id,$items['users_id']);
	// 				endforeach;
					
	// 				$whereCon['where_in']		 			= 	array('user_id',$odr_user_id);	
	// 			else:
	// 				$whereCon['where']		 			= 	array('product_name' => array('$eq'=> ''));		
	// 			endif;
	// 		// Checking result as per user last name	
	// 		elseif($this->input->post('searchField') == 'last_name'):
				
	// 			$sField							=	$this->input->post('searchField');
	// 			$sValue							=	$this->input->post('searchValue');
	// 			$data['searchField'] 			= 	$sField;
	// 			$data['searchValue'] 			= 	$sValue;
				
	// 			$whereCon1['where']			 	= 	array($sField => $sValue);
    //          	$userDetails =  $this->common_model->getData('multiple','da_users',$whereCon1  );
	// 			$odr_user_id = [];
	// 			if($userDetails):
	// 				foreach($userDetails as $items):
	// 						array_push($odr_user_id,$items['users_id']);
	// 				endforeach;
					
	// 				$whereCon['where_in']		 			= 	array('user_id',$odr_user_id);	
	// 			else:
	// 				$whereCon['where']		 			= 	array('product_name' => array('$eq'=> '')  );		
	// 			endif;
	// 		// Checking result as per user phone	
	// 		elseif($this->input->post('searchField') == 'user_phone'):
				
	// 			$sField							=	$this->input->post('searchField');
	// 			$sValue							=	$this->input->post('searchValue');
	// 			$data['searchField'] 			= 	$sField;
	// 			$data['searchValue'] 			= 	$sValue;
				
	// 			$whereCon['where']			 	= 	array($sField => (int)$sValue);
	// 		else:
				
	// 			$sField							=	$this->input->post('searchField');
	// 			$sValue							=	$this->input->post('searchValue');
	// 			$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
	// 			$data['searchField'] 			= 	$sField;
	// 			$data['searchValue'] 			= 	$sValue;
	// 		endif;

	// 	else:
	// 		$whereCon['like']		 		= 	"";
	// 		$data['searchField'] 			= 	'';
	// 		$data['searchValue'] 			= 	'';

	// 	endif;
		 
	// 	$tblName 							= 	'da_orders';
	// 	$shortField 						= 	array('created_at'=>-1);
		 
	// 	$order  = $this->common_model->getData('multiple',$tblName,$whereCon,$shortField);
		
	// 	$spreadsheet = new Spreadsheet();
	// 	$sheet = $spreadsheet->getActiveSheet();
	// 	$sheet->setCellValue('A1', 'Sl.No');
	// 	$sheet->setCellValue('B1', 'Order No');
	// 	$sheet->setCellValue('C1', 'Product Name');
	// 	$sheet->setCellValue('D1', 'Quantity');
	// 	$sheet->setCellValue('E1', 'First Name');
	// 	$sheet->setCellValue('F1', 'Last Name');
	// 	$sheet->setCellValue('G1', 'User Mobile');
	// 	$sheet->setCellValue('H1', 'User Type');
	// 	$sheet->setCellValue('I1', 'Area Name');
	// 	$sheet->setCellValue('J1', 'Collection Point');
	// 	$sheet->setCellValue('K1', 'Donated');
	// 	$sheet->setCellValue('L1', 'Web/APP');
	// 	$sheet->setCellValue('M1', 'Purchase Date');
	// 	$sheet->setCellValue('N1', 'Purchase Time');
	// 	$sheet->setCellValue('O1', 'Payment Mode');
	// 	$sheet->setCellValue('P1', 'Payment Status');
	// 	$sheet->setCellValue('Q1', 'Total Amount');
	// 	$sheet->setCellValue('R1', 'Coupons');
	// 	$sheet->setCellValue('S1', 'Status');


	// 	$slno = 1;
	// 	$start = 2;
	// 	foreach($order as $d){


	// 		$tableCoupon  = 'da_coupons';
	// 		$whereCoupons['where'] =  array('order_id' => $d['order_id']);
	// 		$CouponList   = $this->common_model->getData( 'multiple',$tableCoupon, $whereCoupons);

	// 		if($CouponList):
	// 			$coupons = [];
	// 			foreach($CouponList as $co):
	// 			 $coupons[] =  $co['coupon_code'];
	// 			endforeach;
	// 			$commaSeparatedCoupon = implode(', ', $coupons);
	// 		else:
	// 			$commaSeparatedCoupon = '';	 
	// 		endif;

	// 		// $field = array('users_name ,last_name');
	// 		$field =  array('last_name' , 'users_name');
	// 		$tbl_name = 'da_users';
			
	// 		$users_name  = $this->common_model->getPaticularFieldByFields( 'users_name',$tbl_name,'users_id' ,(int)$d['user_id'] );
	// 		$last_name   = $this->common_model->getPaticularFieldByFields( 'last_name',$tbl_name,'users_id' ,(int)$d['user_id'] );

	// 		// $product_name  = $this->common_model->getPaticularFieldByFields( 'product_name','da_orders_details','order_id' ,$d['order_id'] );
	// 		$ww['where'] = array('order_id' => $d['order_id']);
	// 		$ProductList  = $this->common_model->getData( 'multiple','da_orders_details',$ww );
			
	// 		$sheet->setCellValue('A'.$start, $slno);
	// 		$sheet->setCellValue('B'.$start, $d['order_id']);
			 
	// 		$sheet->setCellValue('E'.$start, $users_name);
	// 		$sheet->setCellValue('F'.$start, $last_name);
	// 		$sheet->setCellValue('G'.$start, $d['user_phone']);
	// 		$sheet->setCellValue('H'.$start, $d['user_type']);
	// 		$sheet->setCellValue('I'.$start, $d['area_name']);
	// 		$sheet->getStyle('I'.$start)->getAlignment()->setWrapText(true);
	// 		$sheet->setCellValue('J'.$start, $d['collection_point_name']);
	// 		$sheet->setCellValue('K'.$start, $d['product_is_donate']);
	// 		$sheet->setCellValue('L'.$start, $d['payment_from']);
	// 		$sheet->setCellValue('M'.$start, date('d-F-Y',strtotime($d['created_at'])));
	// 		$sheet->setCellValue('N'.$start, date('h:i A',strtotime($d['created_at'])));
	// 		$sheet->setCellValue('O'.$start, $d['payment_mode']);
	// 		$sheet->setCellValue('P'.$start, $d['order_status']);
	// 		$sheet->setCellValue('Q'.$start, $d['total_price']);

	// 		$sheet->setCellValue('R'.$start, $commaSeparatedCoupon);
	// 		if($d['status']):
	// 			$sheet->setCellValue('S'.$start, 'Cenceled');
	// 		else:
	// 			$sheet->setCellValue('S'.$start, $d['collection_status']);
	// 		endif;
			
	// 		foreach ($ProductList as $key => $productdetail) {
	// 			$sheet->setCellValue('C'.$start, $productdetail['product_name']);
	// 			$sheet->setCellValue('D'.$start, $productdetail['quantity']);
	// 			// $productName .=  stripslashes($productdetail['product_name']).PHP_EOL;
	// 			// $productQuantity .=  $productdetail['quantity'].PHP_EOL;
	// 			$start = $start+1;
	// 		}
	// 		$slno = $slno+1;
			
	// 		}


	// 	$styleThinBlackBorderOutline = [
	// 				'borders' => [
	// 					'allBorders' => [
	// 						'borderStyle' => Border::BORDER_THIN,
	// 						'color' => ['argb' => 'FF000000'],
	// 					],
	// 				],
	// 			];
	// 	//Font BOLD
	// 	$sheet->getStyle('A1:S1')->getFont()->setBold(true);		
	// 	$sheet->getStyle('A1:S1')->applyFromArray($styleThinBlackBorderOutline);
	// 	//Alignment
	// 	//fONT SIZE
	// 	//$sheet->getStyle('A1:D10')->getFont()->setSize(12);
	// 	//$sheet->getStyle('A1:D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	// 	//$sheet->getStyle('A2:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	// 	//Custom width for Individual Columns
	// 	$sheet->getColumnDimension('A')->setWidth(5);
	// 	$sheet->getColumnDimension('B')->setWidth(20);
	// 	$sheet->getColumnDimension('C')->setWidth(30);
	// 	$sheet->getColumnDimension('D')->setWidth(30);
	// 	$sheet->getColumnDimension('E')->setWidth(30);
	// 	$sheet->getColumnDimension('F')->setWidth(15);
	// 	$sheet->getColumnDimension('G')->setWidth(30);
	// 	$sheet->getColumnDimension('H')->setWidth(30);
	// 	$sheet->getColumnDimension('I')->setWidth(15);
	// 	$sheet->getColumnDimension('J')->setWidth(15);
	// 	$sheet->getColumnDimension('K')->setWidth(30);
	// 	$sheet->getColumnDimension('L')->setWidth(30);
	// 	$sheet->getColumnDimension('M')->setWidth(15);
	// 	$sheet->getColumnDimension('N')->setWidth(20);
	// 	$sheet->getColumnDimension('O')->setWidth(20);
	// 	$sheet->getColumnDimension('P')->setWidth(20);
	// 	$sheet->getColumnDimension('Q')->setWidth(20);
	// 	$sheet->getColumnDimension('R')->setWidth(20);

	// 	$curdate = date('d-m-Y H:i:s');
	// 	$writer = new Xlsx($spreadsheet);
	// 	$filename = 'Order Details'.$curdate;
	// 	ob_end_clean();
	// 	header('Content-Type: application/vnd.ms-excel');
	// 	header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
	// 	header('Cache-Control: max-age=0');
	// 	$writer->save('php://output');
	// }	// END OF FUNCTION



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
		
		// $users_id  =  $data['orderData']['user_id'];
		
		// // $url = "http://localhost/d-arabia/api/telrOrderSuccess?users_id=$users_id&order_id=$oid";
		// $url = "https://dealzarabia.com/api/telrOrderSuccess?users_id=$users_id&order_id=$oid";

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
	** Function name 	: changestatus
	** Developed By 	: MANOJ KUMAR
	** Purpose  		: This function used for change status
	** Date 			: 14 JULY 2022
	************************************************************************/
	function updatecollection($orderID='')
	{  

		$this->admin_model->authCheck('edit_data');

		// $tblName   		   = 'da_orders';
		// $whereCon['where'] = array('order_id' => $orderID);
		// $OrderData 		   = $this->common_model->getData('single',$tblName, $whereCon);

		// if($OrderData['collection_code']):
		// 	$param['collection_code']    = '';
		// 	$param['collection_status']  = 'Product Collected';
		// 	$param['collection_date']    =  date('Y-m-d h:i:s');
		// endif;

		$param['collection_code']    = '';
		$param['collection_status']  = 'Product Collected';
		$param['collection_date']    =  date('Y-m-d h:i:s');
		$this->common_model->editData('da_orders',$param,'order_id',$orderID);
		$this->session->set_flashdata('alert_success',lang('PRODUCT_COLLECTED'));
		
		redirect(correctLink('ALLORDERSDATA',getCurrentControllerPath('index')));
	}


}
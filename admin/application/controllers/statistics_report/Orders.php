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

class Orders extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 

	/* * *********************************************************************
	 * * Function name : designation
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for designation
	 * * Date : 14 JUNE 2021
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'statistics_report';
		$data['activeSubMenu'] 				= 	'orders';
		
		if($this->input->get('clearAllSearch')){
			redirect(correctLink('MASTERDATARECHARGETYPE',getCurrentControllerPath('index')));
		}
		if($this->input->get('fromDate') && $this->input->get('toDate')):
			$data['year']	 				= 	'';
			$data['fromDate']						=	$this->input->get('fromDate');
			$data['toDate']							=	$this->input->get('toDate');
			$whereCon['where_gte'] 			= 	array(array("created_at", $this->input->get('fromDate')));
			$whereCon['where_lte'] 			= 	array(array("created_at", date('Y-m-d',strtotime($this->input->get('toDate').'+1 Days'))));
		elseif($this->input->get('showLength')):
			$data['year']	 				= 	$this->input->get('showLength');
			$whereCon['where_gte'] 			= 	array(array("created_at", $data['year'].'-01-01'));
			$whereCon['where_lte'] 			= 	array(array("created_at", $data['year'].'-12-31'));
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
			$data['year']	 				= 	date('Y');
			$whereCon['where_gte'] 			= 	array(array("created_at", $data['year'].'-01-01'));
			$whereCon['where_lte'] 			= 	array(array("created_at", $data['year'].'-12-31'));
		endif;
		$whereCon['where'] = ['order_status' => 'Success'];
		$data['ALLDATA']['total_quick_buy']	= $this->common_model->getQuickTicketStatistics($whereCon);
		
		$whereCon['where'] = ['user_type' => 'Users'];
		$data['ALLDATA']['total_urser']		=	$this->common_model->getOrdersStatistics($whereCon);

		$whereCon['where'] = ['user_type' => 'Retailer'];
		$data['ALLDATA']['total_retailser']		=	$this->common_model->getOrdersStatistics($whereCon);

		$whereCon['where'] = ['user_type' => 'Sales Person'];
		$data['ALLDATA']['total_sales']		=	$this->common_model->getOrdersStatistics($whereCon);


		
		$this->layouts->set_title('Statistics Report | Order | Dealz Arabia');
		$this->layouts->admin_view('statistics/orders/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name	: getStatisticsByUserID
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for statistics list by user id
	 * * Date 			: 02 NOV 2022
	 * * **********************************************************************/
	public function getStatisticsByUserID()
	{
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'statistics_report';
		$data['activeSubMenu'] 				= 	'orders';

		if($this->input->get('clearAllSearch')){
			redirect(correctLink('MASTERDATARECHARGETYPE',getCurrentControllerPath('statistics_report/orders/getStatisticsByUserID')));
		}
		if($this->input->get()){
			$data['showEntry'] 	= 	$this->input->get('shortBy');
			$data['email']		=	$this->input->get('email');		
			$whereCon['where']		 			= 	array('user_email' => $data['email']);
		}else{
			$whereCon['where']		 			= 	array('user_email' => 'abcd');
		}
		
		if($this->input->get('fromDate')){
			$data['fromDate'] 				= 	$this->input->get('fromDate');
			$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
		}
		if($this->input->get('toDate')){
			$data['toDate'] 				= 	$this->input->get('toDate');
			$whereCon['where_lte'] 			= 	array(array("created_at",date('Y-m-d',strtotime($data['toDate'].'+1 Days'))));
		}
		$shortField 						= 	array('created_at'=> -1);
		$baseUrl 							= 	getCurrentControllerPath('statistics_report/orders/getStatisticsByUserID');
		$this->session->set_userdata('ALLRECHARGEDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_orders';
		$con 								= 	'';
		
		$totalRows 							= 	$this->common_model->getDataByNewQuery('*','count',$tblName,$whereCon,$shortField,'0','0');
		
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
		$data['excelExportCondition']		= 	base64_encode(json_encode($whereCon));
		$data['ALLDATA'] 					= 	$this->common_model->getDataByNewQuery('*','multiple',$tblName,$whereCon,$shortField,$perPage,$page);
		//echo '<pre>'; print_r($data);die();
		$this->layouts->set_title('Recharge | Users Statistics | Dealz Arabia');
		$this->layouts->admin_view('statistics/orders/order_list',array(),$data);
	}// END OF FUNCTION
	/***********************************************************************
	** Function name 	: exportexcel
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for export deleted users data
	** Date 			: 09 APRIL 2022
	** Updated Date 	: 
	** Updated By   	: 
	************************************************************************/
	function exportexcel($load_balance_id='')
	{  
		/* Export excel button code */
		$data = base64_decode($load_balance_id);
		$where = json_decode($data,true);
		//echo '<pre>';
		//print_r($where);die;
		$shortField 			= 	array('created_at'=> -1);
		$tblName 				= 	'da_orders';
		$data        						=   $this->common_model->getDataByNewQuery('*','multiple',$tblName,(array)$where,$shortField,0,0);
		//echo '<pre>';print_r($data);die();
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'Order ID');
		$sheet->setCellValue('C1', 'Product Name');
		$sheet->setCellValue('D1', 'QTY');
		$sheet->setCellValue('E1', 'Donated');
		$sheet->setCellValue('F1', 'Purchase Date');
		$sheet->setCellValue('G1', 'Total Amount');
		
		$slno = 1;
		$start = 2;
			foreach($data as $d){
				$ordDetails = $this->common_model->getDataByParticularField('da_orders_details', 'order_id', $d['order_id']);
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, $d['order_id']);
				$sheet->setCellValue('C'.$start, $ordDetails['product_name']);
				$sheet->setCellValue('D'.$start, $ordDetails['quantity']);
				if($ordDetails['is_donated'] == 'Y'){
					$sheet->setCellValue('E'.$start, 'Yes');
				}else{
					$sheet->setCellValue('E'.$start, 'No');
				}
				$sheet->setCellValue('F'.$start, date('d-F-Y h:i A',strtotime($d['created_at'])));	
				$sheet->setCellValue('G'.$start, $d['total_price']);
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
		$sheet->getStyle('A1:G1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:G1')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		//$sheet->getStyle('A1:D10')->getFont()->setSize(12);
		//$sheet->getStyle('A1:D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		//$sheet->getStyle('A2:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(10);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(25);
		$sheet->getColumnDimension('G')->setWidth(15);


		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Recharge-Coupon-list'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		//endif;
		/* Export excel END */
	}
	
}
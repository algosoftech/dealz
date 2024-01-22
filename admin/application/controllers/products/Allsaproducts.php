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

class Allsaproducts extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model','geneal_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for index
	 + + Date 			: 19 January 2024
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'products';
		$data['activeSubMenu'] 				= 	'allsaproducts';
		
		if($this->input->get('searchField') && $this->input->get('searchValue')):
			$sField							=	$this->input->get('searchField');
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	array('remarks' => array('$ne' => 'lotto-products'));
		$shortField 						= 	array('creation_date'=>-1);
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('ALLSAPRODUCTSDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_sa_products';
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

		$this->layouts->set_title('All Products (SA) | Products | Dealz Arabia');
		$this->layouts->admin_view('products/allsaproducts/index',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : addeditdata
	 + + Developed By  : Dilip Halder
	 + + Purpose  	   : This function used for Add Edit data
	 + + Date 		   : 19 January 2024
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function addeditdata($editId='')
	{		
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'products';
		$data['activeSubMenu'] 				= 	'allsaproducts';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']				=	$this->common_model->getDataByParticularField('da_sa_products','products_id',(int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			$TotalDataCount			=	$this->input->post('TotalDataCount');
			if($this->input->post('is_color') == 'Y'):
				if($TotalDataCount):  
					for($i=0; $i <= $TotalDataCount; $i++):
						if($this->input->post('color'.$i)):
							$this->form_validation->set_rules('color'.$i, 'color', 'trim');
							if($this->input->post('S'.$i)):
								$this->form_validation->set_rules('S'.$i, 'color', 'trim');
							elseif($this->input->post('M'.$i)):
								$this->form_validation->set_rules('M'.$i, 'color', 'trim');
							elseif($this->input->post('L'.$i)):
								$this->form_validation->set_rules('L'.$i, 'color', 'trim');
							elseif($this->input->post('XL'.$i)):
								$this->form_validation->set_rules('XL'.$i, 'color', 'trim');
							elseif($this->input->post('XXL'.$i)):
								$this->form_validation->set_rules('XXL'.$i, 'color', 'trim');
							elseif($this->input->post('FRS'.$i)):
								$this->form_validation->set_rules('FRS'.$i, 'color', 'trim');
							else:
								$this->form_validation->set_rules('S'.$i, 'color', 'trim');
							endif;
						endif;
						$i++;
					endfor;
				endif;
			endif;


			

			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
			$this->form_validation->set_rules('sub_category_id', 'Sub Category', 'trim|required');
			$this->form_validation->set_rules('description', 'Description', 'trim');
			$this->form_validation->set_rules('image', 'Image', 'trim');
			$this->form_validation->set_rules('stock', 'Quantity', 'trim|required');
			$this->form_validation->set_rules('target_stock', 'Target Quantity', 'trim|required');
			$this->form_validation->set_rules('points', 'Su Points', 'trim|required');
			$this->form_validation->set_rules('draw_date', 'Campaigns Draw Date', 'trim');
			$this->form_validation->set_rules('draw_time', 'Campaigns Draw Tme', 'trim');
			$this->form_validation->set_rules('commingSoon', 'Comming Soon', 'trim|required');
			$this->form_validation->set_rules('clossingSoon', 'Clossing Soon', 'trim|required');
			$this->form_validation->set_rules('soldout_status', 'Sold Status', 'trim|required');
			$this->form_validation->set_rules('validuptodate', 'Valid Upto Date', 'trim|required');
			$this->form_validation->set_rules('validuptotime', 'Valid Upto Time', 'trim|required');
			$this->form_validation->set_rules('share_limit', 'Share limit', 'trim|required');
			$this->form_validation->set_rules('share_percentage_first', 'Share Percentage', 'trim|required');
			$this->form_validation->set_rules('share_percentage_second', 'Share Percentage', 'trim|required');
			$this->form_validation->set_rules('sale_percentage', 'Sale Percentage', 'trim');
			$this->form_validation->set_rules('sale_percentage_final', 'Sale Percentage', 'trim');
			$this->form_validation->set_rules('countdown_status', 'Countdown', 'trim');
			$this->form_validation->set_rules('sponsored_coupon', 'Sponsored coupon', 'trim');

			if($this->form_validation->run() && $error == 'NO'):
				$color_size_details = [];
				if($this->input->post('is_color') == 'Y'):
					
					if($TotalDataCount):  
						for($i=0; $i <= $TotalDataCount; $i++):
							if($this->input->post('color'.$i) && $_FILES['color_img'.$i]):
								echo $i;
								$CSparam['color']	=	$this->input->post('color'.$i);
								$CSparam['S']		=	$this->input->post('S'.$i)?'Y':'N';
								$CSparam['M']		=	$this->input->post('M'.$i)?'Y':'N';
								$CSparam['L']		=	$this->input->post('L'.$i)?'Y':'N';
								$CSparam['XL']		=	$this->input->post('XL'.$i)?'Y':'N';
								$CSparam['XXL']		=	$this->input->post('XXL'.$i)?'Y':'N';
								$CSparam['FRS']		=	$this->input->post('FRS'.$i)?'Y':'N';
								if($_FILES['color_img'.$i]['name']):
									// $ufileName						= 	$_FILES['color_img'.$i]['name'];
									$ufileName						= 	str_replace(" ","_",$_FILES['color_img'.$i]['name']);
									$utmpName						= 	$_FILES['color_img'.$i]['tmp_name'];
									$ufileExt         				= 	pathinfo($ufileName);
									// $unewFileName 					= 	$this->common_model->microseconds().'.'.$ufileExt['extension'];

									$unewFileName 					= 	$_FILES['slider_image']['name'];
									$filePath =  fileFCPATH .'assets/productsImage/'.$_FILES['slider_image']['name'];
									 
									if(file_exists($filePath)):
										$unewFileName =	$ufileExt['filename'] .'_'.$this->common_model->random_strings(8).'.'.$ufileExt['extension'];
									endif;

									$this->load->library("upload_crop_img");
									$uimageLink						=	$this->upload_crop_img->_upload_image($ufileName,$utmpName,'productsImage',$unewFileName,'');
									if($uimageLink != 'UPLODEERROR'):
										$CSparam['image']		= 	$uimageLink;
									endif;
								else:
									$CSparam['image']		= 	$this->input->post('img_exist'.$i);
								endif;
								array_push($color_size_details, $CSparam);
							endif;
						endfor;
					endif;
				endif;
				
				$param['title']						= 	addslashes($this->input->post('title'));
				$param['title_slug']				= 	url_title(strtolower($this->input->post('title').' '.date('dMY',strtotime($this->input->post('draw_date')))));
				$param['description']				= 	addslashes($this->input->post('description'));

				$categoryData						=	explode('_____',$this->input->post('category_id'));
				$param['category_id']				= 	(int)$categoryData[0];
				$param['category_name']				= 	$categoryData[1];

				$sub_categoryData							= 	explode('____',$this->input->post('sub_category_id'));
				$param['sub_category_id']					= 	(int)$sub_categoryData[0];
				$param['sub_category_name']					= 	$sub_categoryData[1];

				if($_FILES['product_image']['name']):
					// $ufileName						= 	$_FILES['product_image']['name'];
					$ufileName						= 	str_replace(" ","_",$_FILES['product_image']['name']);
					$utmpName						= 	$_FILES['product_image']['tmp_name'];
					$ufileExt         				= 	pathinfo($ufileName);
					$unewFileName 					= 	$_FILES['slider_image']['name'];
					$filePath =  fileFCPATH .'assets/productsImage/'.$_FILES['slider_image']['name'];
					 
					if(file_exists($filePath)):
						$unewFileName =	$ufileExt['filename'] .'_'.$this->common_model->random_strings(8).'.'.$ufileExt['extension'];
					endif;

					$this->load->library("upload_crop_img");
					$uimageLink						=	$this->upload_crop_img->_upload_image($ufileName,$utmpName,'productsImage',$unewFileName,'');
					if($uimageLink != 'UPLODEERROR'):
						$param['product_image']		= 	$uimageLink;
					endif;
				endif;
				$param['product_image_alt']			= 	addslashes($this->input->post('product_image_alt'));
				
				$param['points']					= 	(float)addslashes($this->input->post('points'));
				$param['draw_date']					= 	addslashes($this->input->post('draw_date'));
				$param['draw_time']					= 	addslashes($this->input->post('draw_time'));
				$param['commingSoon']				= 	addslashes($this->input->post('commingSoon'));
				$param['clossingSoon']				= 	addslashes($this->input->post('clossingSoon'));
				$param['soldout_status']			= 	addslashes($this->input->post('soldout_status'));
				$param['is_show_closing']			= 	addslashes($this->input->post('is_show_closing'));
				$param['validuptodate']				= 	addslashes($this->input->post('validuptodate'));
				$param['validuptotime']				= 	addslashes($this->input->post('validuptotime'));
				$param['share_limit']				= 	(int)addslashes($this->input->post('share_limit'));
				$param['share_percentage_first']	= 	(float)addslashes($this->input->post('share_percentage_first'));
				$param['share_percentage_second']	= 	(float)addslashes($this->input->post('share_percentage_second'));
				$param['color_size_details']		=	$color_size_details;
				$param['seq_order']					=	$this->input->post('seq_order');
				$param['sale_percentage']			=	$this->input->post('sale_percentage');
				$param['sale_percentage_final']		=	$this->input->post('sale_percentage_final');
				$param['countdown_status']			=	$this->input->post('countdown_status');
				$param['sponsored_coupon']			=	(int)$this->input->post('sponsored_coupon');
				
				if($this->input->post('CurrentDataID') ==''):
					$param['totalStock']		= 	(int)addslashes($this->input->post('stock'));
					$param['stock']				= 	(int)addslashes($this->input->post('stock'));
					$param['target_stock']		= 	(int)addslashes($this->input->post('target_stock'));
					$param['inventory_stock']	=	(int)addslashes($this->input->post('stock'));
					$param['products_id']		=	(int)$this->common_model->getNextSequence('da_sa_products');
					$param['product_seq_id']	=	$this->common_model->getNextIdSequence('product_seq_id','products');
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$param['status']			=	'A';
					$alastInsertId				=	$this->common_model->addData('da_sa_products',$param);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:

					$categoryId					=	$this->input->post('CurrentDataID');
					$param['target_stock']		= 	(int)addslashes($this->input->post('target_stock'));
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$this->common_model->editData('da_sa_products',$param,'products_id',(int)$categoryId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;

				redirect(correctLink('MASTERDATAPRODUCTTYPE',getCurrentControllerPath('index')));
			endif;
		endif;
		
		$this->layouts->set_title('Add/Edit Products');
		$this->layouts->admin_view('products/allsaproducts/addeditdata',array(),$data);
	}	// END OF FUNCTION		

	/***********************************************************************
	** Function name 	: changestatus
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for change status
	** Date 			: 21 JUNE 2021
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('edit_data');
		$param['status']		=	$statusType;
		$this->common_model->editData('da_sa_products',$param,'products_id',(int)$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('ALLSAPRODUCTSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: deletedata
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for delete data
	** Date 			: 21 JUNE 2021
	************************************************************************/
	function deletedata($deleteId='')
	{  
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_sa_products','products_id',(int)$deleteId);
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('ALLSAPRODUCTSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: getsub_categoryData
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for delete data
	** Date 			: 05 APRIL 2022
	************************************************************************/
	public function getsubcategoryData(){
		$categoryData			=	explode('_____',$this->input->post('category_id'));
		$categoryId				= 	(int)$categoryData[0];
		$whereCon['where']		= 	array('category_id' => $categoryId);	
		$sub_categoryData 				=   $this->common_model->getData('multiple','da_sub_category',$whereCon);
		$sub_categoryIdData 			=   explode('_____',$this->input->post('sub_category_id'));
		$sub_category					= 	(int)$sub_categoryIdData[0];
		$sub_categoryName				= 	(int)$sub_categoryIdData[1];
		if($sub_categoryData <> ""):
			$html = '<option value="">Select Sub Category</option>';
			if($sub_category == ''): $sub_category = $sub_categoryData[0]['sub_category_id'];endif;
			$i=1;foreach($sub_categoryData as $sub_categoryData):
			//echo '<pre>';print_r($sub_categoryData);die;
				if($sub_category == stripslashes($sub_categoryData['sub_category_id'])): $select = 'selected="selected"'; else: $select = ''; endif;
		        $html .='<option '.$select.' value="'.stripslashes($sub_categoryData["sub_category_id"]).'____'.stripslashes($sub_categoryData["sub_category"]).'">'.stripslashes($sub_categoryData["sub_category"]).'</option>';
	        $i++;endforeach;
	    else:
	    	$html = '<option value="">No Sub Category</option>';
	    endif;
        echo $html;
        die();
	}

	/***********************************************************************
	** Function name : exportexcel
	** Developed By  : Dilip Halder
	** Purpose  	 : This function used for export deleted users data
	** Date 		 : 31 AUG 2021
	************************************************************************/
	function exportexcel()
	{  
		/* Export excel button code */
		$wcon['where']          =   '';
		$data        			=   $this->common_model->getData('multiple','da_sa_products',$wcon);//echo '<pre>';print_r($data);die;

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'HOSPITAL ID');
		$sheet->setCellValue('C1', 'HOSPITAL NAME');
		$sheet->setCellValue('D1', 'PHONE');
		$sheet->setCellValue('E1', 'ADDRESS');
		$sheet->setCellValue('F1', 'sub_category');
		$sheet->setCellValue('G1', 'STATE');
		$sheet->setCellValue('H1', 'CREATION DATE');
		$sheet->setCellValue('I1', 'CREATION IP');
		$sheet->setCellValue('J1', 'VACCINATION STATUS');
		
		$slno = 1;
		$start = 2;
			foreach($data as $d){
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, $d['product_seq_id']);
				$sheet->setCellValue('C'.$start, $d['title']);
				$sheet->setCellValue('D'.$start, $d['phone']);
				$sheet->setCellValue('E'.$start, $d['address']);
				$sheet->setCellValue('F'.$start, $d['sub_category_name']);
				$sheet->setCellValue('G'.$start, $d['state_name']);
				$sheet->setCellValue('H'.$start, date('d-m-Y H:i:s', $d['creation_date']));
				$sheet->setCellValue('I'.$start, $d['creation_ip']);
				$sheet->setCellValue('J'.$start, $d['vaccination_status']);
				
				
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
		$sheet->getStyle('A1:I1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:I1000')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:D10')->getFont()->setSize(12);
		$sheet->getStyle('A1:D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->getColumnDimension('H')->setWidth(30);
		$sheet->getColumnDimension('I')->setWidth(30);
		

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Hospital-list'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		//endif;
		/* Export excel END */
	}


    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for index
	 + + Date 			: 19 January 2024
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function getAllusers($id='')
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'producrs';
		$data['activeSubMenu'] 				= 	'allproducts';
		
		if($this->input->get('searchField') && $this->input->get('searchValue')):
			$sField							=	$this->input->get('searchField');
			$sValue							=	$this->input->get('searchValue');
			$whereCon['search']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['search']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	array('product_id'=>(int)$id);		
		$shortField 						= 	array('user_id'=>-1);
		
		$baseUrl 							= 	getCurrentControllerPath('getAllusers/'.$id);
		$this->session->set_userdata('ALLSAPRODUCTSDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_orders_details';
		$con 								= 	'';
		$totalRows 							= 	$this->geneal_model->getOrderData('count',$tblName,$whereCon,$shortField,'','');
		//echo $totalRows; die();
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

		$uriSegment 						= 	5;//getUrlSegment();
	    $data['PAGINATION']					=	adminPagination($baseUrl,$suffix,$totalRows,$perPage,$uriSegment);

       if($this->uri->segment($uriSegment)):
           $page = $this->uri->segment($uriSegment);
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$baseUrl; 
		if($totalRows):
			$first							=	(int)1;
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
		
		$data['ALLDATA'] 					= 	$this->geneal_model->getOrderData('multiple',$tblName,$whereCon,$shortField,$page,$perPage);

		$this->layouts->set_title('All Products | Products | Dealz Arabia');
		$this->layouts->admin_view('products/allsaproducts/getallusers',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: getCoupon
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for get all coupon
	 + + Date 			: 19 January 2024
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function getCoupon($id='')
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'products';
		$data['activeSubMenu'] 				= 	'allsaproducts';
		$data['products_id']				=	$id;
		
		if($this->input->get('searchField') && $this->input->get('searchValue')):
			$sField							=	$this->input->get('searchField');
			$sValue							=	$this->input->get('searchValue');
			$whereCon['search']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['search']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	array('product_id'=>(int)$id);		
		$shortField 						= 	array('user_id'=>-1);
		
		$baseUrl 							= 	getCurrentControllerPath('getCoupon/'.$id);
		$this->session->set_userdata('ALLSAPRODUCTSDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_sa_coupons';
		$con 								= 	'';
		$totalRows 							= 	$this->geneal_model->getCouponData('count',$tblName,$whereCon,$shortField,'','');
		
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

		$uriSegment 						= 	5;//getUrlSegment();
	    $data['PAGINATION']					=	adminPagination($baseUrl,$suffix,$totalRows,$perPage,$uriSegment);

	   if($this->uri->segment($uriSegment)):
	       $page = $this->uri->segment($uriSegment);
	   else:
	       $page = 0;
	   endif;
		
		$data['forAction'] 					= 	$baseUrl; 
		if($totalRows):
			$first							=	(int)1;
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

		$data['ALLDATA'] 					= 	$this->geneal_model->getCouponData('multiple',$tblName,$whereCon,$shortField,$page,$perPage);

		$this->layouts->set_title('All Coupons | Coupons | Dealz Arabia');
		$this->layouts->admin_view('products/allsaproducts/allcoupons',array(),$data);
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: couponExportExcel
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for export deleted users data
	** Date 			: 06 MAY 2022
	** Updated Date 	: 
	** Updated By   	: 
	************************************************************************/
	function couponExportExcel($pid='')
	{  
		/* Export excel button code */
		$wcon['where']          =   array( 'product_id' => (int)$pid );
		$shortField 			= 	array('user_id'=>-1);
		$data        			=   $this->geneal_model->getCouponData('multiple','da_coupons',$wcon,$shortField,[],[]);
		
	    $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'NAME');
		$sheet->setCellValue('C1', 'MOBILE NO.');
		$sheet->setCellValue('D1', 'EMAIL ID');
		$sheet->setCellValue('E1', 'PRODUCT ID');
		$sheet->setCellValue('F1', 'PRODUCT NAME');
		$sheet->setCellValue('G1', 'AED');
		$sheet->setCellValue('H1', 'COUPON CODE');
		$sheet->setCellValue('I1', 'ORDER ID');
		$sheet->setCellValue('J1', 'DATE & TIME');
		/*$sheet->setCellValue('H1', 'CREATION DATE');
		$sheet->setCellValue('I1', 'CREATION IP');
		$sheet->setCellValue('J1', 'VACCINATION STATUS');*/
		
		$slno = 1;
		$start = 2;
			foreach($data as $d){
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, $d['users_name']);
				$sheet->setCellValue('C'.$start, $d['users_mobile']);
				$sheet->setCellValue('D'.$start, $d['users_email']);
				$sheet->setCellValue('E'.$start, (int)$d['product_id']);
				$sheet->setCellValue('F'.$start, $d['product_title']);
				$sheet->setCellValue('G'.$start, $d['adepoints']);
				$sheet->setCellValue('H'.$start, $d['coupon_code']);
				$sheet->setCellValue('I'.$start, $d['order_id']);
				$sheet->setCellValue('J'.$start, date('d-m-Y H:i A', strtotime($d['created_at'])));
				/*$sheet->setCellValue('I'.$start, $d['creation_ip']);
				$sheet->setCellValue('J'.$start, $d['vaccination_status']);*/
				
				
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
		$sheet->getStyle('A1:I1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:I1000')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		$sheet->getStyle('A1:D10')->getFont()->setSize(12);
		$sheet->getStyle('A1:D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A2:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->getColumnDimension('H')->setWidth(30);
		$sheet->getColumnDimension('I')->setWidth(30);
		

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Coupons-list'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		//endif;
		/* Export excel END */
	}

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for index
	 + + Date 			: 19 January 2024
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function prizeList($pid='')
	{	

		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'products';
		$data['activeSubMenu'] 				= 	'allsaproducts';
		$data['productID'] 					= 	base64_decode($pid);
		
		if($this->input->get('searchField') && $this->input->get('searchValue')):
			$sField							=	$this->input->get('searchField');
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	array('product_id' => (int)$data['productID']);		
		$shortField 						= 	array('title'=>'ASC');
		
		$baseUrl 							= 	getCurrentControllerPath('prizeList/'.$pid);
		$this->session->set_userdata('ALLSAPRIZEDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_sa_prize';
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

		$uriSegment 						= 	5;//getUrlSegment();
	    $data['PAGINATION']					=	adminPagination($baseUrl,$suffix,$totalRows,$perPage,$uriSegment);

	   if($this->uri->segment($uriSegment)):
	       $page = $this->uri->segment($uriSegment);
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

		$this->layouts->set_title('All Prize | Prize | Dealz Arabia');
		$this->layouts->admin_view('products/allsaproducts/allprize',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : addprize
	 + + Developed By  : Dilip Halder
	 + + Purpose  	   : This function used for Add Edit data
	 + + Date 		   : 19 January 2024
	 + + Updated Date  : 
	 + + Updated By    :
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function addprize($editId='')
	{		
	    
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'prize';
		$data['activeSubMenu'] 				= 	'allprize';

		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']				=	$this->common_model->getDataByParticularField('da_sa_prize','prize_id',(int)$editId);
			//echo '<pre>';print_r($data['EDITDATA']);die;
		else:
			$this->admin_model->authCheck('add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';

			// Validation start 
			$this->form_validation->set_rules('title', 'Title', 'trim');
			$this->form_validation->set_rules('description', 'Description', 'trim');
			$this->form_validation->set_rules('prize_type', 'Prize Type', 'trim');
			$this->form_validation->set_rules('product_id', 'Product', 'trim');
			// Validation end 
			
			if($this->form_validation->run() && $error == 'NO'): 
				$param['product_id']		= 	(int)$this->session->userdata('productID4Prize');
				$param['title']				= 	addslashes($this->input->post('title'));
				$param['title_slug']		= 	url_title(strtolower($this->input->post('title')));
				$param['description']		= 	addslashes($this->input->post('description'));
				
				$param['prize_type']		= 	addslashes($this->input->post('prize_type'));

				if($param['prize_type'] == "Cash"):
					$param['prize1']			= 	(int)addslashes($this->input->post('prize1'));
					$param['prize2']			= 	(int)addslashes($this->input->post('prize2'));
					$param['prize3']			= 	(int)addslashes($this->input->post('prize3'));
				else:
					$param['prize1']			= 	'';
					$param['prize2']			= 	'';
					$param['prize3']			= 	'';
				endif;
				
				if($_FILES['prize_image']['name']):
					$ufileName						= 	$_FILES['prize_image']['name'];
					$utmpName						= 	$_FILES['prize_image']['tmp_name'];
					$ufileExt         				= 	pathinfo($ufileName);
					// $unewFileName 					= 	$this->common_model->microseconds().'.'.$ufileExt['extension'];

					$unewFileName 					= 	$_FILES['slider_image']['name'];
					$filePath =  fileFCPATH .'assets/prizeImage/'.$_FILES['slider_image']['name'];
					 
					if(file_exists($filePath)):
						$unewFileName =	$ufileExt['filename'] .'_'.$this->common_model->random_strings(8).'.'.$ufileExt['extension'];
					endif;

					$this->load->library("upload_crop_img");
					$uimageLink						=	$this->upload_crop_img->_upload_image($ufileName,$utmpName,'prizeImage',$unewFileName,'');
					if($uimageLink != 'UPLODEERROR'):
						$param['prize_image']		= 	$uimageLink;
					endif;
				endif;
				$param['prize_image_alt']	= 	addslashes($this->input->post('prize_image_alt'));

				if($this->input->post('CurrentDataID') ==''):
					$param['prize_id']		=	(int)$this->common_model->getNextSequence('da_sa_prize');
					$param['prize_seq_id']	=	$this->common_model->getNextIdSequence('prize_seq_id','prize');
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	date('Y-m-d H:i');
					$param['created_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$param['status']			=	'A';
					$alastInsertId				=	$this->common_model->addData('da_sa_prize',$param);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:

					$categoryId					=	$this->input->post('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$this->common_model->editData('da_sa_prize',$param,'prize_id',(int)$categoryId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;

				//redirect(correctLink('MASTERDATAPRODUCTTYPE',getCurrentControllerPath('index')));
				redirect('products/allsaproducts/prizeList/'.base64_encode($param['product_id']));
			endif;
		endif;
		
		$this->layouts->set_title('Add/Edit Prize');
		$this->layouts->admin_view('products/allsaproducts/addprize',array(),$data);
	}	// END OF FUNCTION	

	/***********************************************************************
	** Function name 	: deletePrize
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for delete data
	** Date 			: 16 MAY 2022
	************************************************************************/
	function deletePrize($deleteId='')
	{  
		$data	=	$this->common_model->getDataByParticularField('da_sa_prize','prize_id',(int)$deleteId);
		$imagepath =  fileFCPATH.$data['prize_image'];
		@unlink($imagepath);
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_sa_prize','prize_id',(int)$deleteId);
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('ALLSAPRIZEDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: updatestock
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for delete data
	** Date 			: 23 MAY 2022
	************************************************************************/
	function updatestock($pid=''){

		$data['error'] 						= 	'';
			$data['activeMenu'] 				= 	'products';
			$data['activeSubMenu'] 				= 	'allsaproducts';
			if($pid):
				$data['EDITDATA']				=	$this->common_model->getDataByParticularField('da_sa_products','products_id',(int)$pid);
			endif;
			if($this->input->post('SaveChanges')):
				$error					=	'NO';
				$this->form_validation->set_rules('stock', 'Stock', 'trim');
				if($this->form_validation->run() && $error == 'NO'): 
					$praduct			=	$this->common_model->getDataByParticularField('da_sa_products','products_id',(int)$pid);
					$totalstock 		= 	$praduct['totalStock'];
					$availablestock 	= 	$praduct['stock'];
					$inventory_stock	=	$praduct['inventory_stock'];

					$utotalstock 		= (int)$totalstock + (int)$this->input->post('stock');

					$uavailablestock 		= (int)$availablestock + (int)$this->input->post('stock');

					$uinventory_stock 		= (int)$inventory_stock + (int)$this->input->post('stock');

					$param['totalStock']		= 	(int)$utotalstock;
					$param['target_stock']		= 	(int)$utotalstock;
					$param['stock']				= 	(int)$uavailablestock;
					$param['inventory_stock']	= 	(int)$uinventory_stock;

					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$this->common_model->editData('da_sa_products',$param,'products_id',(int)$pid);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
					redirect('products/allsaproducts/index/');
				endif;
			endif;
			$this->layouts->set_title('Add Stock');
			$this->layouts->admin_view('products/allsaproducts/stockupdate',array(),$data);
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: orderstatus
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for change order status
	** Date 			: 23 MAY 2022
	************************************************************************/
	function orderstatus($changeStatusId='',$statusType='')
	{  
		//echo $changeStatusId; die();
		$this->admin_model->authCheck('edit_data');
		$param['order_status']		=	$statusType;
		$this->common_model->editData('da_orders',$param,'order_id',$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('ALLSAPRODUCTSDATA',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: changesoldoutstatus
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for change soldout status
	** Date 			: 17 MAY 2023
	************************************************************************/
	function changesoldoutstatus($changeStatusId='',$statusType='')
	{  
		// echo $changeStatusId; die();
		$this->admin_model->authCheck('edit_data');
		$param['isSoldout']		=	$statusType;
		// echo $param['isSoldout'];die();
		$this->common_model->editData('da_sa_products',$param,'products_id',(int)$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('ALLSAPRODUCTSDATA',getCurrentControllerPath('index')));
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allrechargevoucher extends CI_Controller {

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
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for Recharge Vocher view.
	 + + Date 			: 03 February 2023
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index($rcId="")
	{	

		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'recharge';
		$data['activeSubMenu'] 				= 	'allrechargecoupons';

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
				
		$whereCon['where']		 			= 	array('rc_id'=> (int)$rcId);		
		$shortField 						= 	array('created_date'=>'desc');

		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('ALLCOUPONSVOUCHERDATA',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_coupon_code_only';
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




       if($this->uri->segment(5)):
           $page = $this->uri->segment(5);
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$baseUrl."/".$rcId; 
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
		$this->layouts->admin_view('rechargecoupons/allrechargecoupons/viewvoucher',array(),$data);
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
	

	/***********************************************************************
	** Function name 	: changestatus
	** Developed By 	: Dilip Halder
	** Purpose  		: This function used for change status
	** Date 			: 03 Februray 2023
	************************************************************************/
	function changestatus($rc_id='',$coupon='',$statusType='')
	{  

		$this->admin_model->authCheck('edit_data');
		$whereCondition =  array('rc_id' => (int)$rc_id ,'coupon_code' => $coupon);

		if($statusType == 'A'):
			$param['coupon_code_statys']	=	"Active";
		elseif($statusType == 'E'):
			$param['coupon_code_statys']	=	"Expire";
		else:
			$param['coupon_code_statys']	=	"Inactive";
		endif;

		$this->common_model->editDataByMultipleCondition('da_coupon_code_only',$param,$whereCondition);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('ALLCOUPONSVOUCHERDATA',getCurrentControllerPath('index')));
	}

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for Recharge Vocher view.
	 + + Date 			: 03 February 2023
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function viewredeemcoupons()
	{	

		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'recharge';
		$data['activeSubMenu'] 				= 	'allrechargecoupons';

		if($this->input->get('searchField') && $this->input->get('searchValue')):
			$sField							=	$this->input->get('searchField');
			$sValue							=	$this->input->get('searchValue');

			$USERwhereCon['where_or'] = array( 'users_mobile' => (int)$sValue ,'users_email' => $sValue);
			$UserDetails = $this->common_model->getData('single','da_users',$USERwhereCon);
			$whereCon['where']		 			= 	array('coupon_code_statys'=> 'Redeem','redeemed_by_user_id' => (int)$UserDetails['users_id'] );		

			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			// $whereCon['like']		 		= 	"";
			$whereCon['where']		 			= 	array('coupon_code_statys'=> 'Redeem');		
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$shortField 						= 	array('redeemed_date'=>'desc');

		$baseUrl 							= 	base_url('recharge/allrechargevoucher/viewredeemcoupons');
		$this->session->set_userdata('ALLVIEWREDEEMCOUPONSDATE',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_coupon_code_only';
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




       if($this->uri->segment(4)):
           $page = $this->uri->segment(4);
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
		// echo '<pre>';print_r($data['ALLDATA']);die();
		$this->layouts->set_title('All Copons | Copons | Dealz Arabia');
		$this->layouts->admin_view('rechargecoupons/allrechargecoupons/viewredeemcoupons',array(),$data);
	}	// END OF FUNCTION

}

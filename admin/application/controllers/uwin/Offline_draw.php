<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offline_draw extends CI_Controller {

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
	 + + Function name 	: 	index
	 + + Developed By 	:	Dilip Kumar
	 + + Purpose  		: 	This function used to show offline winner List.
	 + + Date 			:	31 January 2024
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'uwin';
		$data['activeSubMenu'] 				= 	'offline_draw';
		
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
		
		$whereCon['where']		 			= 	array('status'=>'A');	
		$shortField 						= 	array('title_name'=>'ASC');
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('CMSTESTIMONIALS',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_uwin_testimonials';
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

		$this->layouts->set_title('Offline Draw List | U WIN | Dealz Arabia');
		$this->layouts->admin_view('uwin/offline_draw/index',array(),$data);
	}	// END OF FUNCTION
	

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : changestatus
	 + + Developed By  : Dilip Halder
	 + + Purpose  	   : This function used for change status
	 + + Date 		   : 31 January 2024
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('edit_data');
		$param['status']		=	$statusType;
		$this->common_model->editData('da_uwin_testimonials',$param,'testimonial_id',(int)$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('CMSTESTIMONIALS',$this->session->userdata('HCAP_ADMIN_CURRENT_PATH').$this->router->fetch_class().'/index'));
	}

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: deletedata
	 + + Developed By 	: Dilip Halder
	 + + Purpose  		: This function used for Delete Data
	 + + Date 			: 31 January 2024
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	function deletedata($deleteId='')
	{  	
		if($deleteId):
			$this->admin_model->authCheck('edit_data');
			$data			=	$this->common_model->getDataByParticularField('da_uwin_testimonials','testimonial_id',(int)$deleteId);
			$imageName = $data['image'];
			$this->load->library("upload_crop_img");
			$this->upload_crop_img->_delete_image(trim($imageName)); 
		endif;

		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_uwin_testimonials','testimonial_id',(int)$deleteId);
		
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('CMSTESTIMONIALS',$this->session->userdata('HCAP_ADMIN_CURRENT_PATH').$this->router->fetch_class().'/index'));
	}	


	 

}
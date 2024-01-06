<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usersagreement extends CI_Controller {


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
	 + + Developed By 	: Afsar Ali
	 + + Purpose  		: This function used for index
	 + + Date 			: 09 DECEMBER 2022
	 + + Updated Date 	: 
	 + + Updated By   	: 
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'cms';
		$data['activeSubMenu'] 				= 	'usersagreement';
		
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
				
		$whereCon['where']		 			= 	array('page_name'=>'Users Agreement');		
		$shortField 						= 	array('title'=>'ASC');
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('CMSCANCELLATIONPOLICY',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_cms';
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
	    //echo "<pre>"; print_r($uriSegment); die;
       if ($this->uri->segment(getUrlSegment())):
           $page = $this->uri->segment(getUrlSegment());
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$baseUrl; 
		if($totalRows):
			$first							=	(int)($page)+1;
			$data['first']					=	$first;
			$last							=	((int)($page)+$data['perpage'])>$totalRows?$totalRows:((int)($page)+$data['perpage']);
			$data['noOfContent']			=	'Showing '.$first.'-'.$last.' of '.$totalRows.' items';
		else:
			$data['first']					=	1;
			$data['noOfContent']			=	'';
		endif;
		
	
		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page);
		//print_r($data);die;
		
		$this->layouts->set_title('Users Agreement | CMS | Dealz Arabia');


		$this->layouts->admin_view('cms/usersagreement/index',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: Afsar Ali
	 + + Purpose  		: This function used for index
	 + + Date 			: 09 DECEMBER 2022
	 + + Updated Date 	: 
	 + + Updated By   	: 
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function addeditdata($editId='')
	{		
		
		$data['error'] 				= 	'';
		$data['activeMenu'] 				= 	'cms';
		$data['activeSubMenu'] 				= 	'usersagreement';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('da_cms','usersagreement_id',(int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;
		
		if($this->input->post('SaveChanges')): 
			$error					=	'NO';
			$this->form_validation->set_rules('description', 'Description', 'trim|required');
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):  
				
				$param['page_name']					= 	'Users Agreement';
				$param['description']				= 	stripslashes($this->input->post('description'));
				$param['title']						= 	stripslashes($this->input->post('title'));

				if($this->input->post('CurrentDataID') ==''):
					$param['usersagreement_id']		=	(int)$this->common_model->getNextSequence('da_cms');
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$param['status']			=	'A';
					$alastInsertId				=	$this->common_model->addData('da_cms',$param);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$NotificationTempId					=	$this->input->post('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$this->common_model->editData('da_cms',$param,'usersagreement_id',(int)$NotificationTempId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('CMSCANCELLATIONPOLICY',$this->session->userdata('HCAP_ADMIN_CURRENT_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Add/Edit Users Agreement_id | CMS | Dealz Arabia');
		$this->layouts->admin_view('cms/usersagreement/addeditdata',array(),$data);

	}	// END OF FUNCTION	
	
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : changestatus
	 + + Developed By : Ashish Umrao
	 + + Purpose  : This function used for change status
	 + + Date : 13 DECEMBER 2020
	 + + Updated Date :  
	 + + Updated By   :  
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('edit_data');
		$param['status']		=	$statusType;
		$this->common_model->editData('da_cms',$param,'usersagreement_id',(int)$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('CMSCANCELLATIONPOLICY',$this->session->userdata('HCAP_ADMIN_CURRENT_PATH').$this->router->fetch_class().'/index'));
	}

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : deletedata
	 + + Developed By : Ashish Umrao
	 + + Purpose  : This function used for delete data
	 + + Date : 13 DECEMBER 2020
	 + + Updated Date :  
	 + + Updated By   :  
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	function deletedata($deleteId='')
	{  
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_cms','contestrules_id',(int)$deleteId);
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('CMSCANCELLATIONPOLICY',$this->session->userdata('HCAP_ADMIN_CURRENT_PATH').$this->router->fetch_class().'/index'));
	}		
}
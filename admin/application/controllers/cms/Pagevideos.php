<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagevideos extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE); 
		//error_reporting(E_ALL); 
		$this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name 	: index
	 + + Developed By 	: Manoj Kumar
	 + + Purpose  		: This function used for index
	 + + Date 			: 31 MARCH 2022
	 + + Updated Date 	: 
	 + + Updated By   	:
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'cms';
		$data['activeSubMenu'] 				= 	'pagevideos';
		
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
				
		$whereCon['where']		 			= 	array('type'=>'V');		
		$shortField 						= 	array('slider_id'=>'DESC');
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set_userdata('CMSPAGEBANNER',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'da_homepage_slider';
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

		$this->layouts->set_title('Page Videos | CMS | Dealz Arabia');
		$this->layouts->admin_view('cms/pagevideos/index',array(),$data);
	}	// END OF FUNCTION

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : addeditdata
	 + + Developed By  : Manoj Kumar
	 + + Purpose  	   : This function used for Add Edit data
	 + + Date 		   : 14 JUNE 2021
	 + + Updated Date  : 
	 + + Updated By    :
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function addeditdata($editId='')
	{		
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'cms';
		$data['activeSubMenu'] 				= 	'pagevideos';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']				=	$this->common_model->getDataByParticularField('da_homepage_slider','slider_id',(int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;

		$data['PAGESDATA']					=	videoPagesListData();
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			if (empty($_FILES['slider_video']['name'])):
			    $this->form_validation->set_rules('slider_video', 'Image', 'trim');
			endif;
			//$this->form_validation->set_rules('slider_image_alt', 'Alt', 'trim');
			$this->form_validation->set_rules('slider_description', 'Description', 'trim');

			if($this->form_validation->run() && $error == 'NO'): 
				if($_FILES['slider_video']['name']):
					//Upload Video
					$configVideo['upload_path'] = 'assets/admin/pagevideo'; # check path is correct
					$configVideo['max_size'] = '1024000';
					$configVideo['allowed_types'] = 'mp4'; # add video extenstion on here
					$configVideo['overwrite'] = FALSE;
					$configVideo['remove_spaces'] = TRUE;
					$video_name = strtotime(date('Y-m-d H:s:i')).'.mp4';
					$configVideo['file_name'] = $video_name;
					
					$this->load->library('upload', $configVideo);
					$this->upload->initialize($configVideo);
					
					if (! $this->upload->do_upload('slider_video')):
						$param['video_url'] = '';
					else:
						$param['video_url'] = 'assets/admin/pagevideo/'.$video_name;
					endif;
				endif;
				
				$param['alt']						= 	stripslashes($this->input->post('slider_image_alt'));
				$param['slider_description']		= 	$this->input->post('slider_description');
				$param['page']                      =   stripslashes($this->input->post('pagename'));
				$param['type']						=	'V';
				
				if($this->input->post('CurrentDataID') ==''):
					$param['slider_id']			=	(int)$this->common_model->getNextSequence('da_homepage_slider');
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$param['status']			=	'A';
					$alastInsertId				=	$this->common_model->addData('da_homepage_slider',$param);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$categoryId					=	$this->input->post('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$this->common_model->editData('da_homepage_slider',$param,'slider_id',(int)$categoryId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;

				redirect(correctLink('CMSPAGEBANNER',getCurrentControllerPath('index')));
			endif;
		endif;
		
		$this->layouts->set_title('Add/Edit Page Banner | CMS | Dealz Arabia');
		$this->layouts->admin_view('cms/pagevideos/addeditdata',array(),$data);
	}	// END OF FUNCTION	

	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 14 JUNE 2021
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('edit_data');
		$param['status']		=	$statusType;
		$this->common_model->editData('da_homepage_slider',$param,'slider_id',(int)$changeStatusId);
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('CMSPAGEBANNER',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name : deletedata
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete data
	** Date : 14 JUNE 2021
	************************************************************************/
	function deletedata($deleteId='')
	{  
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('da_homepage_slider','slider_id',(int)$deleteId);
		$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		
		redirect(correctLink('CMSPAGEBANNER',getCurrentControllerPath('index')));
	}

	/***********************************************************************
	** Function name 	: imageDelete
	** Developed By 	: Ravi
	** Purpose  		: This function used to delete image
	** Date 			: 23 SEP 2021
	************************************************************************/
	function imageDelete()
	{  
		$imageName			=	$this->input->post('imageName');
		$id 				=	$this->input->post('id');
		$typ 				=	$this->input->post('typ');
		//echo $typ;die;
		if($typ == 'web'):
			$param['image']		=	''; 
		elseif($typ == 'app'):
			$param['image']		=	''; 
		endif;

		if($imageName):
			$this->load->library("upload_crop_img");
			$return	=	$this->upload_crop_img->_delete_image(trim($imageName)); 
			$this->common_model->editData('da_homepage_slider',$param,'slider_id',(int)$id);
		endif;
		if($typ == 'web'):
			$returnArray  		= 	array('status'=>1,'message'=>'Image deleted.');
		elseif($typ == 'app'):
			$returnArray  		= 	array('status'=>2,'message'=>'Image deleted.');
		endif;
		header('Content-type: application/json');
		echo json_encode($returnArray); die;
	}	// END OF FUNCTION

	function videoDelete(){
		$videoName 		=	$this->input->post('videoName');
		$id 			=	$this->input->post('id');
		$type 			=	$this->input->post('typ');
		
		if($videoName):
			$myfile = fileFCPATH.'admin/'.$videoName;
			unlink($myfile);
		endif;
		if($typ == 'web'):
			$returnArray  		= 	array('status'=>1,'message'=>'Video deleted.');
		elseif($typ == 'app'):
			$returnArray  		= 	array('status'=>2,'message'=>'Video deleted.');
		endif;
		$param['video_url']		=	'';
		$this->common_model->editData('da_homepage_slider',$param,'slider_id',(int)$id);
		header('Content-type: application/json');
		echo 'success'; die;
	}
}
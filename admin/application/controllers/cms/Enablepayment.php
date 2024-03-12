<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enablepayment extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('admin_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 
 
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 + + Function name : addeditdata
	 + + Developed By  : Dilip Halder
	 + + Purpose  	   : This function used for Add Edit data
	 + + Date 		   : 28 JULY 2021
	 + + Updated Date  : 
	 + + Updated By    :
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	public function index()
	{		
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'cms';
		$data['activeSubMenu'] 				= 	'Enable Payment';
		
		$tbl_name = 'da_paymentmode';
		$data['EDITDATA']				=	$this->common_model->getData('single',$tbl_name);
		

		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			
			// web
			$this->form_validation->set_rules('stripe', 'Stripe', 'trim');
			$this->form_validation->set_rules('telr', 'Telr', 'trim');
			$this->form_validation->set_rules('noon', 'Noon', 'trim');
			$this->form_validation->set_rules('ngenius', 'Ngenius', 'trim');
			$this->form_validation->set_rules('title_stripe', 'Stripe Title', 'trim');
			$this->form_validation->set_rules('title_telr', 'Telr Title', 'trim');
			$this->form_validation->set_rules('title_noon', 'Noon Title', 'trim');
			$this->form_validation->set_rules('title_ngenius', 'Ngenius Title', 'trim');
			// Ios
			$this->form_validation->set_rules('ios_stripe', 'Stripe', 'trim');
			$this->form_validation->set_rules('ios_telr', 'Telr', 'trim');
			$this->form_validation->set_rules('ios_noon', 'Noon', 'trim');
			$this->form_validation->set_rules('ios_ngenius', 'Ngenius', 'trim');
			$this->form_validation->set_rules('ios_title_stripe', 'Stripe Title', 'trim');
			$this->form_validation->set_rules('ios_title_telr', 'Telr Title', 'trim');
			$this->form_validation->set_rules('ios_title_noon', 'Noon Title', 'trim');
			$this->form_validation->set_rules('ios_title_ngenius', 'Ngenius Title', 'trim');
			// Android
			$this->form_validation->set_rules('android_stripe', 'Stripe', 'trim');
			$this->form_validation->set_rules('android_telr', 'Telr', 'trim');
			$this->form_validation->set_rules('android_noon', 'Noon', 'trim');
			$this->form_validation->set_rules('android_ngenius', 'Ngenius', 'trim');
			$this->form_validation->set_rules('android_title_stripe', 'Stripe Title', 'trim');
			$this->form_validation->set_rules('android_title_telr', 'Telr Title', 'trim');
			$this->form_validation->set_rules('android_title_noon', 'Noon Title', 'trim');
			$this->form_validation->set_rules('android_title_ngenius', 'Ngenius Title', 'trim');


			if($this->form_validation->run() && $error == 'NO'): 
				// web
				$param['stripe']				= 	stripslashes($this->input->post('stripe'));
				$param['telr']					= 	stripslashes($this->input->post('telr'));
				$param['noon']					= 	stripslashes($this->input->post('noon'));
				$param['ngenius']				= 	stripslashes($this->input->post('ngenius'));
				$param['title_stripe']			= 	stripslashes($this->input->post('title_stripe'));
				$param['title_telr']			= 	stripslashes($this->input->post('title_telr'));
				$param['title_noon']			= 	stripslashes($this->input->post('title_noon'));
				$param['title_ngenius']			= 	stripslashes($this->input->post('title_ngenius'));
				// Ios
				$param['ios_stripe']				= 	stripslashes($this->input->post('ios_stripe'));
				$param['ios_telr']					= 	stripslashes($this->input->post('ios_telr'));
				$param['ios_noon']					= 	stripslashes($this->input->post('ios_noon'));
				$param['ios_ngenius']				= 	stripslashes($this->input->post('ios_ngenius'));
				$param['ios_title_stripe']			= 	stripslashes($this->input->post('ios_title_stripe'));
				$param['ios_title_telr']			= 	stripslashes($this->input->post('ios_title_telr'));
				$param['ios_title_noon']			= 	stripslashes($this->input->post('ios_title_noon'));
				$param['ios_title_ngenius']			= 	stripslashes($this->input->post('ios_title_ngenius'));
				// Android
				$param['android_stripe']				= 	stripslashes($this->input->post('android_stripe'));
				$param['android_telr']					= 	stripslashes($this->input->post('android_telr'));
				$param['android_noon']					= 	stripslashes($this->input->post('android_noon'));
				$param['android_ngenius']				= 	stripslashes($this->input->post('android_ngenius'));
				$param['android_title_stripe']			= 	stripslashes($this->input->post('android_title_stripe'));
				$param['android_title_telr']			= 	stripslashes($this->input->post('android_title_telr'));
				$param['android_title_noon']			= 	stripslashes($this->input->post('android_title_noon'));
				$param['android_title_ngenius']			= 	stripslashes($this->input->post('android_title_ngenius'));


				if($this->input->post('CurrentDataID') ==''):
					$param['paymentmode_id']			=	(int)$this->common_model->getNextSequence('da_paymentmode');
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					$param['status']			=	'A';
					$alastInsertId				=	$this->common_model->addData('da_paymentmode',$param);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$aboutId					=	$this->input->post('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');
					
					$this->common_model->editData('da_paymentmode',$param,'paymentmode_id',(int)$aboutId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				redirect(correctLink('CMSENABLEPAYMENT',getCurrentControllerPath('index')));
			endif;
		endif;
		
		$this->layouts->set_title('Add/Edit enable payment | CMS | Dealz Arabia');
		$this->layouts->admin_view('cms/enablepayment/addeditdata',array(),$data);
	}	// END OF FUNCTION	
}
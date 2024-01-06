<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
public function  __construct() 
	{ 
		parent:: __construct();
		$this->load->model('geneal_model');
	} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for login
** Date 			: 14 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function index()
{  
	$data 					=	array();
	if($this->session->userdata('DZL_USERID')):
		redirect('my-profile');
	endif;
	/*--------------------Start Login--------------------*/
	if($this->input->post($_POST)):
		//print_r($_POST); die();
		$this->form_validation->set_rules('userid', 'Credential', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_error_delimiters("<div class='text-danger'>","</div>");

		if($this->form_validation->run()):
			$loginID 			=	$this->input->post('userid');
			$password 			=	md5($this->input->post('password'));
			$userDetails = array();
			if(is_numeric($loginID)):
				$where 			=	[ 'users_mobile' => (int)$loginID ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
				if(!empty($userDetails)): $data = $userDetails; endif;
				
			else:
				$where 			=	[ 'users_email' => $loginID ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
				if(!empty($userDetails)): $data = $userDetails; endif;
			endif;
			if(!empty($data) && $password == $data['password']):
				if($data['status'] == 'A'){
					$this->session->set_userdata('DZL_USERID', $data['users_id']);
					$this->session->set_userdata('DZL_USERNAME', $data['users_name']);
					$this->session->set_userdata('DZL_USEREMAIL', $data['users_email']);
					//$this->session->set_userdata('DZL_SEQID', $data['users_sequence_id']);
					$this->session->set_userdata('DZL_USERMOBILE', $data['users_mobile']);
					$this->session->set_userdata('DZL_TOTALPOINTS', $data['totalArabianPoints']);
					$this->session->set_userdata('DZL_AVLPOINTS', $data['availableArabianPoints']);
					$this->session->set_userdata('DZL_USERSTYPE', $data['users_type']);
					
					$expIN = date('Y-m-d', strtotime($data['created_at']. ' +12 months'));
					$today = strtotime(date('Y-m-d'));
					$dat = strtotime($expIN) - $today;
					$Tdate =  round($dat / (60 * 60 * 24));


					$this->session->set_userdata('DZL_EXPIRINGIN', $Tdate);

					redirect('login');
				}else{
					$this->session->set_flashdata('error', 'Your account is Inactive.');	
					redirect('login');
				}
			else:
				$this->session->set_flashdata('error', 'Invalid Credentials!');
			endif;
		else:	
		endif;
	endif;
	/*--------------------End Login--------------------*/
	$this->load->view('login',$data);
} //FND OF FUNCTION

/***********************************************************************
** Function name 	: logout
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for logout users
** Date 			: 14 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function logout()
{
	$this->session->unset_userdata(array('DZL_USERID',
										 'DZL_USERNAME',
										 'DZL_USEREMAIL',
										 'DZL_SEQID',));
	redirect('login');
}//FND OF FUNCTION

}

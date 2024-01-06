<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Profile extends My_Head {
public function  __construct() 
{ 
	parent:: __construct();
	$this->load->model('geneal_model');
} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for index
** Date 			: 14 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function index()
{  
	$data 					=	array();
	$tbl 					=	'da_users';
	$where 					=	['users_id' => $this->session->userdata('DZL_USERID')];
	$data['profileDetails']			=	$this->geneal_model->getOnlyOneData($tbl, $where);

	$this->load->view('myprofile',$data);
} //END OF FUNCTION

/***********************************************************************
** Function name 	: cart
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for cart list
** Date 			: 15 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function cart()
{  
	$data 					=	array();
	/*$tbl 					=	'da_users';
	$where 					=	['page' => 'Homeslider'];
	$data['homeSlider']		=	$this->geneal_model->getData($tbl, $where,[]);*/

	$this->load->view('user_cart',$data);
} //END OF FUNCTION

/***********************************************************************
** Function name 	: recharge
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for cart list
** Date 			: 15 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function recharge()
{  
	$data 					=	array();
	if($_POST){
		//echo $_POST['coupon_no'];die();
		$where = [ 'coupon_code' => $_POST['coupon_no'],
				   'status' 	=>	'A' ];
		$authCode = $this->geneal_model->getOnlyOneData('da_coupon_code_only', $where );

		//print_r($authCode); die();

		if(!empty($authCode)){
			$whereCon 	=	['rc_id' => $authCode['rc_id']];
			$couponData =	$this->geneal_model->getOnlyOneData('da_rechargecoupons', $whereCon);
				$avlAED = (int)$this->session->userdata('DZL_AEDPOINTS') + (int)$couponData['ipoints'];

			//print_r($couponData); die();

			if(!empty($couponData)){
				$insert_data = array(
					'avlAED' 	=>	(int)$avlAED
				);

				$isrecharge = $this->geneal_model->editData('da_users', $insert_data, 'users_id', (int)$this->session->userdata('DZL_USERID'));

				$data2 = array( 'status' => 'D' );

				$changeStatus = $this->geneal_model->editData('da_coupon_code_only', $data2, 'couponID', (int)$authCode['couponID']);
				$this->session->set_userdata('DZL_AEDPOINTS', $avlAED);

				$this->session->set_flashdata('success', $couponData['ipoints'].' ADE Added.');
				redirect('earning');
			}
		}else{
			$this->session->set_flashdata('error', 'Invaid coupon code : '.$_POST['coupon_no']);
			redirect('earning');
		}

	}
	$this->load->view('recharge',$data);
} //END OF FUNCTION

/***********************************************************************
** Function name 	: addUsers
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for cart list
** Date 			: 30 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function addUsers()
{  
	$data 					=	array();
	if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person'){
		$data['page']			=	'Retailer';
	}elseif ($this->session->userdata('DZL_USERSTYPE') == 'Retailer') {
		$data['page']			=	'Users';
	}
	
	if($this->input->post()){
		//print_r($_POST); die();
		if($_POST['page'] == 'Retailer'){
			$param["users_seq_id"]	=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Retailer');
			$param["store_name"]	=	$_POST['store'];
		}elseif ($_POST['page'] == 'Users') {
			$param["users_seq_id"]	=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Users');
		}	

			$param["users_id"]				=	(int)$this->geneal_model->getNextSequence('da_users');
			$param["users_type"] 			=	$_POST['page'];
			$param["users_name"]			=	$_POST['name'];
		    $param["users_email"] 			=	$_POST['email'];
		    $param["users_mobile"]			=	(int)$_POST['mobile'];
		    $param["password"]				=	md5($_POST['password']);
		    $param["totalArabianPoints"]	=	(int)$_POST['arabianPoints'];
		    $param["availableArabianPoints"]=	(int)$_POST['arabianPoints'];
		    $param["creation_ip"] 			=	$this->input->ip_address();
		    $param["created_at"] 			=	date('Y-m-d H:i');
		    $param["created_by"] 			=	$this->session->userdata('DZL_USERID');
		    $param["status"] 				=	"A";
	

		$insertID 	= $this->geneal_model->addData('da_users', $param);

		if($insertID){
			$this->session->set_flashdata('success', 'New User created.');			
		}else{
			$this->session->set_flashdata('error', 'Request Timeout.');			
		}
		redirect('my-profile');

	}
	//	echo "working"; die();
	/*$tbl 					=	'da_users';
	$where 					=	['page' => 'Homeslider'];
	$data['homeSlider']		=	$this->geneal_model->getData($tbl, $where,[]);*/

	$this->load->view('addUsers',$data);
} //END OF FUNCTION

}

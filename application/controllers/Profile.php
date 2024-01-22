<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Profile extends My_Head {
public function  __construct() 
{ 
	parent:: __construct();
	error_reporting(0);
	$this->load->model(array('geneal_model','common_model','notification_model'));
	$this->lang->load('statictext','front');
	$this->load->helper('common');
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
	$data['page'] 			=	'My Profile';
	$data['oldPassError']	=	'';
	$data['error'] 			=	'NO';

	$tbl 					=	'da_users';
	$where 					=	['users_id' => $this->session->userdata('DZL_USERID')];
	$data['profileDetails']			=	$this->geneal_model->getOnlyOneData($tbl, $where);

	$data['countryCodeData']    =   countryCodeList();

	if($this->input->post('SaveChanges')):
		$this->form_validation->set_error_delimiters('', '');
		$error						=	'NO';
		$data['error'] 				=	'YES';
		$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[8]');
		if($data['profileDetails']['password'] != md5($this->input->post('old_password'))):
			$error					=	'YES';
			$data['oldPassError']	=	lang('CHANGE_PASS_ERROR');
		endif;

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[8]|max_length[25]');			
		$this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required|min_length[8]|matches[new_password]');

		if($this->form_validation->run() && $error == 'NO'):
			$data['error'] 				=	'NO';

			$update_data = array("password" => md5($this->input->post('new_password')));
     		$update =  $this->geneal_model->editData('da_users',$update_data,'users_id',(int)$data['profileDetails']['users_id']);
	        if($update):
	            $this->session->set_flashdata('success', lang('CHANGE_PASS'));
	            redirect('my-profile');
	        else:
	            $this->session->set_flashdata('Error', lang('CHANGE_PASS_ERROR'));
	            redirect('my-profile');
	        endif;
		endif;
	endif;

	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-myprofile',$data);

	else:
		$this->load->view('myprofile',$data);
	endif;
} //END OF FUNCTION

/***********************************************************************
** Function name 	: editUsers
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for edit profile data
** Date 			: 30 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 
public function editprofile($id)
{
    $data 			        =	array();
    $data['page'] 			=	'Edit Profile';
	$tbl 					=	'da_users';
	$where 					=	['users_id' => $this->session->userdata('DZL_USERID')];
	$data['profileDetails']	=	$this->geneal_model->getOnlyOneData($tbl, $where);

	$data['countryCodeData']    =   countryCodeList();

	$wherecon = array('users_id'=> (int)$this->session->userdata('DZL_USERID'));
	$shortField = array('users_id'=>1);
	$userDetails 	=	$this->geneal_model->getdata('da_users',$wherecon,$shortField );

	
 
	if($this->input->post('SaveChanges')):

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_message('is_unique', 'The %s is already taken');
		$data['error']			=	'NO';
		$this->form_validation->set_rules('users_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		
		if($this->input->post('users_email') != $userDetails[0]['users_email']):
			$this->form_validation->set_rules('users_email', 'Email', 'trim|required|valid_email|max_length[64]|is_unique[da_users.users_email.string]');
		endif;


		if(empty($userDetails[0]['users_mobile'])):
			if($userDetails[0]['users_mobile'] != $this->input->post('users_mobile')):
				$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
				$this->form_validation->set_rules('users_mobile', 'Mobile', 'trim|required|min_length[10]|max_length[15]|numeric|is_unique[da_users.users_mobile.integer]');
			endif;
		endif;

		$this->form_validation->set_rules('sms_notification', 'SMS', 'trim');
		$this->form_validation->set_rules('email_notification', 'E-mail', 'trim');
		$this->form_validation->set_rules('notification', 'Notification', 'trim');


		if($this->form_validation->run() && $data['error'] == 'NO'):


			$update_data['users_name'] 	= 	$this->input->post('users_name');
			$update_data['last_name'] 	= 	$this->input->post('last_name');
			$update_data['country_code'] 	= 	$this->input->post('country_code');

			if($this->input->post('users_mobile')):
				$update_data['users_mobile']	= 	(int)$this->input->post('users_mobile');
			endif;

			$update_data['users_email'] 	= 	$this->input->post('users_email');
			$update_data['sms_notification'] 	= 	$this->input->post('sms_notification');
			$update_data['email_notification'] 	= 	$this->input->post('email_notification');
			$update_data['notification'] 	= 	$this->input->post('notification');
			$update_data['updated_at'] 	= 	date('Y-m-d H:i');
			$update_data['updated_ip'] 	= 	$this->input->ip_address();

			$isUpdate 	=	$this->geneal_model->editData('da_users', $update_data, 'users_id', (int)$this->session->userdata('DZL_USERID'));
			if($isUpdate):
				$this->session->set_userdata('DZL_USERNAME', $update_data['users_name']);
				$this->session->set_userdata('DZL_USEREMAIL', $update_data['users_email']);
				$this->session->set_userdata('DZL_USERMOBILE', $update_data['users_mobile']);
				$this->session->set_userdata('DZL_USERS_COUNTRY_CODE', $update_data['country_code']);
				$this->session->set_flashdata('success', lang('UPDATED'));
				redirect('my-profile');
			endif;
		endif;
	endif;

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-myprofile',$data);
	else:
		$this->load->view('editmyprofile',$data);
	endif;
}

/***********************************************************************
** Function name 	: recharge
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for cart list
** Date 			: 15 APRIL 2022
** Updated By		: 17 MAY 2022
** Updated Date 	: Afsar Ali
************************************************************************/ 	
public function recharge($page='')
{   
	$data 						=	array();
	$data['emailError']			=	'';
	$data['amountError']		=	'';
	$data['page']				=	'Topup Recharge';

	$data['countryCodeData']    =   countryCodeList();

	if($this->input->post('SaveChanges')):	 
		$this->form_validation->set_error_delimiters('', '');
		$error					=	'NO';
		
		if($this->input->post('mobile')):
			$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
			$this->form_validation->set_rules('mobile', 'mobile', 'trim|required');
		else:
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
		endif;

		if($this->input->post('email')):
			$this->form_validation->set_rules('email', 'mobile', 'trim|required');
		else:
			$this->form_validation->set_rules('mobile', 'Email', 'trim|required');
		endif;

		$this->form_validation->set_rules('recharge_amt', 'Recharge Amount', 'trim|required');

		$where 					= 	[];
		
	     //New conditions
		if(is_numeric($this->input->post('mobile'))){ 
			if(strlen($this->input->post('mobile')) >= 10){
			
				$where 				= 	['users_mobile'=>(int)$this->input->post('mobile') , 'country_code' => $this->input->post('country_code') ,'status'=>'A'];
			}else{
			
				$where 				= 	['users_mobile'=>(int)$this->input->post('mobile'), 'country_code' => $this->input->post('country_code') ,'status'=>'A'];
			} 
			
	    }else{ 
			$where 				= 	['users_email'=>$this->input->post('email'),'status'=>'A'];
	    }
		
	    $chkUser = $this->geneal_model->getOnlyOneData('da_users', $where ); 
	    if(empty($chkUser)):
	    	$error	 			=	'YES';
	    	$data['emailError'] =	lang('USER_NOT_FOUNT');
	    endif;

		if($this->input->post('recharge_amt') < 5):
			$this->session->set_flashdata('error', lang('RECHARGE_AMOUNT_ERROR'));
			redirect('top-up-recharge');
		endif;

		/* Check sales person and retailser available points */
        $whereCon 				 =	['users_id' => (int)$this->session->userdata('DZL_USERID')];
        //$availableArabianPoints  =  $this->geneal_model->getOnlyOneData('da_users', $whereCon );
		$user_data  =  $this->geneal_model->getOnlyOneData('da_users', $whereCon );
        if($user_data):

        	if($this->input->post('percentage')):
        		$rechargeAmount 			=	(int)$this->input->post('recharge_amt');
				$percent 					=	$this->input->post('percentage');
				$percentAmt					=	$rechargeAmount*$percent/100;
				$totalRechargeAmount		=	$rechargeAmount + $percentAmt;
        	else:
        		$totalRechargeAmount		=	(int)$this->input->post('recharge_amt');
        	endif;

        	if( $totalRechargeAmount > $user_data['availableArabianPoints']):
        		$error	 			=	'YES';
        		$data['amountError']=	lang('LOW_BALANCE');
        	endif;
        else:
        	$error		 			=	'YES';
        	$data['amountError']	=	lang('LOW_BALANCE');
        endif;

        if((int)$chkUser['users_id'] == (int)$this->session->userdata('DZL_USERID')){ 
			$error	 			=	'YES';
	    	$data['emailError'] =	lang('INVALID_EMAIL');
	    }

		if($this->form_validation->run() && $error == 'NO'):

			// From user update arabian points
			//$availableArabianPoints 	= 	((int)$user_data['availableArabianPoints'] - (int)$this->input->post('recharge_amt'));
			if($this->input->post('percentage')):
				$rechargeAmount 			=	(int)$this->input->post('recharge_amt');
				$percent 					=	$this->input->post('percentage');
				$percentAmt					=	$rechargeAmount*$percent/100;
				$totalRechargeAmount		=	$rechargeAmount + $percentAmt;
				$availableArabianPoints 	= 	((float)$user_data['availableArabianPoints'] - $totalRechargeAmount);
				$rechargeDetails 			=	array(
													'percentage'	=>	(float)$percent,
													'amount'		=>	(float)$percentAmt,
													);
			else:
				$availableArabianPoints 	= 	((float)$user_data['availableArabianPoints'] - (float)$this->input->post('recharge_amt'));
				$totalRechargeAmount = (float)$this->input->post('recharge_amt');
			endif;
	        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
	        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->session->userdata('DZL_USERID'));
	        $this->session->set_userdata('DZL_AVLPOINTS',$availableArabianPoints);

	        /* Load Balance Table -- from user*/
		    $fromuserparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
			$fromuserparam["user_id_cred"] 			=	(int)0;
			$fromuserparam["user_id_deb"]			=	(int)$this->session->userdata('DZL_USERID');
			$fromuserparam["user_id_to"]			=	(int)$chkUser["users_id"];
			$fromuserparam["arabian_points"] 		=	(float)$this->input->post('recharge_amt');
			$fromuserparam["sum_arabian_points"]	=	(float)$totalRechargeAmount;
			$fromuserparam["availableArabianPoints"]= 	(float)$user_data['availableArabianPoints'];
			$fromuserparam["end_balance"]			=	(float)$user_data['availableArabianPoints'] - (float)$totalRechargeAmount;

		    $fromuserparam["record_type"] 			=	'Debit';
		    $fromuserparam["arabian_points_from"]	=	'Recharge';
			if($rechargeDetails):
				$fromuserparam["rechargeDetails"]	=	$rechargeDetails;
			endif;
		    $fromuserparam["creation_ip"] 			=	$this->input->ip_address();
		    $fromuserparam["created_at"] 			=	date('Y-m-d H:i');
		    $fromuserparam["created_by"] 			=	$this->session->userdata('DZL_USERSTYPE');
			$fromuserparam["created_user_id"] 		=	(int)$this->session->userdata('DZL_USERID');
		    $fromuserparam["status"] 				=	"A";
		    
		    $this->geneal_model->addData('da_loadBalance', $fromuserparam);
		    /* End */


		     $dueparam["due_management_id"]	=	(int)$this->geneal_model->getNextSequence('da_dueManagement');
		    
		    if(!empty($this->input->post('mobile'))):
		    	$dueparam["mobile"]				=	(int)$this->input->post('mobile');
		    else:
		    	$dueparam["email"]				=	$this->input->post('email');
		    endif;
		    $dueparam["recharge_amt"]		=	(float)$this->input->post('recharge_amt');
		    $dueparam["cash_collected"]		=	(float)$this->input->post('cash_collected');
		    
			$CashCollected = (float)$this->input->post('recharge_amt') -(float)$this->input->post('cash_collected');

		    if($CashCollected > 0):
		    	$dueparam["due_amount"]			=	(float)$CashCollected;
		    else:
		    	$dueparam["due_amount"]	=	(float)0;
		    endif;

		     if($CashCollected < 0):
		    	$dueparam["advanced_amount"]	=	abs($CashCollected);
		    else:
		    	$dueparam["advanced_amount"]	=	abs(0);
		    endif;

			$dueparam["user_id_deb"]		=	(int)$this->session->userdata('DZL_USERID');
			$dueparam["user_id_to"]			=	(int)$chkUser["users_id"];
		   	$dueparam["record_type"] 		=	'Debit';
		   if($this->session->userdata('DZL_USERSTYPE') == "Sales Person"):
		    	$dueparam["recharge_type"] 	=	'Advanced Cash';
		    else:
		    	$dueparam["recharge_type"] 	=	'Direct Cash';
		    endif;
		    $dueparam['recharge_from'] 		=    'Web';
		    $dueparam["creation_ip"] 		=	$this->input->ip_address();
		    $dueparam["created_by"] 		=	$this->session->userdata('DZL_USERSTYPE');
			$dueparam["created_user_id"] 	=	(int)$this->session->userdata('DZL_USERID');
		    $dueparam["created_at"] 		=	date('Y-m-d H:i');
		    $dueparam["status"] 			=	"A";
		    
		    $this->geneal_model->addData('da_dueManagement', $dueparam);


			// Send creadited notification 
			if($chkUser["device_id"]):
				$data 		=	array(
									'arabianpoint'	=>	(int)$fromuserparam["arabian_points"],
									'name'			=>	$user_data['users_name'],
									'user_id'		=>	$chkUser["users_id"],
									'device_id'		=>	$chkUser["device_id"]
									);
				$rtn = $this->notification_model->rceivedArabianPointNotification($data);
			endif;
			//END
			// To user update arabian points
			// $updatedTAP 	= 	((int)$chkUser['totalArabianPoints'] + (int)$this->input->post('recharge_amt')); 
		    // $updateAAP 		= 	((int)$chkUser['availableArabianPoints'] + (int)$this->input->post('recharge_amt'));
			if($this->input->post('percentage')):
				$rechargeAmount2 			=	(int)$this->input->post('recharge_amt');
				$percent2 					=	$this->input->post('percentage');
				$percentAmt2				=	$rechargeAmount2*$percent2/100;
				$totalRechargeAmount2		=	$rechargeAmount2 + $percentAmt2;
				$updatedTAP 				= 	((float)$chkUser['totalArabianPoints'] + $totalRechargeAmount2); 
		    	$updateAAP 					= 	((float)$chkUser['availableArabianPoints'] + $totalRechargeAmount2);
				$rechargeDetails2 			=	array(
													'percentage'	=>	(float)$percent,
													'amount'		=>	(float)$percentAmt2,
													);
			else:
				$updatedTAP 	= 	((float)$chkUser['totalArabianPoints'] + (float)$this->input->post('recharge_amt')); 
		    	$updateAAP 		= 	((float)$chkUser['availableArabianPoints'] + (float)$this->input->post('recharge_amt'));
				$totalRechargeAmount2 = (int)$this->input->post('recharge_amt');
			endif;
			$update_data 	= 	array('totalArabianPoints' =>	(float)$updatedTAP, 'availableArabianPoints'=>	(float)$updateAAP);
			$this->geneal_model->editData('da_users', $update_data, 'users_id', (int)$chkUser['users_id']);

	        /* Load Balance Table -- to user*/
		    $touserparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
			$touserparam["user_id_cred"] 		=	(int)$chkUser["users_id"];
			$touserparam["user_id_deb"]			=	(int)0;
			$touserparam["arabian_points"] 		=	(float)$this->input->post('recharge_amt');
			$touserparam["sum_arabian_points"]	=	(float)$totalRechargeAmount2;
		    $touserparam["availableArabianPoints"]=	(float)$chkUser['availableArabianPoints'];
			$touserparam["end_balance"]			=	(float)$chkUser['availableArabianPoints'] + (float)$totalRechargeAmount2;
		    $touserparam["record_type"] 		=	'Credit';
		    $touserparam["arabian_points_from"] =	'Recharge';
			if($rechargeDetails2):
				$touserparam["rechargeDetails"]=	$rechargeDetails2;
			endif;
		    $touserparam["creation_ip"] 		=	$this->input->ip_address();
		    $touserparam["created_at"] 			=	date('Y-m-d H:i');
		    $touserparam["created_by"] 			=	$this->session->userdata('DZL_USERSTYPE');
			$touserparam["created_user_id"] 	=	(int)$this->session->userdata('DZL_USERID');
		    $touserparam["status"] 				=	"A";

			//Send debited notification 
			if($user_data["device_id"]):
				$Sdata 		=	array(
									'arabianpoint'	=>	(int)$fromuserparam["arabian_points"],
									'name'			=>	$chkUser["users_name"],
									'user_id'		=>	$user_data["users_id"],
									'device_id'		=>	$user_data["device_id"]
									);
				$rtn = $this->notification_model->sentArabianPointNotification($Sdata);
			endif;
			/* End */
		    
		    $this->geneal_model->addData('da_loadBalance', $touserparam);
		    /* End */

			$this->session->set_flashdata('success', lang('RECHARGE_SUCCESS').' '.(int)$totalRechargeAmount2);
			redirect('top-up-recharge');
		endif;
	endif;

	$this->load->library("pagination");
	//Filter Start Here
	if($this->input->get()):
		$data['todate'] 		=	$this->input->get('todate');
		$data['fromdate'] 		=	$this->input->get('fromdate');
		$data['record_type']	=	$this->input->get('record_type');
		if($data['todate'] && $data['fromdate']):
			$whereCon2['where_gte'] 			= 	array(array("created_at",$data['todate']));
			$whereCon2['where_lte'] 			= 	array(array("created_at",$data['fromdate']));
		endif;
		
		if($data['record_type'] != 'All'):
			$whereCon2['where']	= array('record_type' => $data['record_type']);
		endif;
	endif;
	// END

	$this->load->library("pagination");

	$tblName 				=	'da_loadBalance';
	$shortField 			= 	array('_id'=> -1 );
	//$whereCon2['where']		= 	array('created_user_id'=>(int)$this->session->userdata('DZL_USERID'), "created_by" => $this->session->userdata('DZL_USERSTYPE'), "arabian_points_from" => "Recharge");
	$whereCon2['where']			=	array(	'$or' => array( 
										array('user_id_cred' => (int)$this->session->userdata('DZL_USERID'), 'record_type' => 'Credit'), 
										array('user_id_deb' => (int)$this->session->userdata('DZL_USERID'),'record_type' => 'Debit'), 
										),
										'arabian_points_from' => "Recharge"
	);

	$totalPage 				=	$this->common_model->getDataByNewQuery('*','count',$tblName,$whereCon2,$shortField,'0','0');
	$config 				= 	['base_url'=>base_url('top-up-recharge'),'per_page'=>10,'total_rows'=>$totalPage];

	$this->pagination->initialize($config);
	$data['users']  		=	$this->common_model->getDataByNewQuery('*','multiple', $tblName, $whereCon2,$shortField,$config['per_page'],$this->uri->segment(2));
	
	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-recharge',$data);
	else:
	    $this->load->view('recharge',$data);
	endif;

} //END OF FUNCTION

/***********************************************************************
** Function name 	: redeemCoupon
** Developed By 	: MANOJ KUMAR
** Purpose 			: This function used for redeem Coupon
** Date 			: 07 JULY 2022
** Updated By		: Dilip halder
** Updated Date 	: 15 02 2023
************************************************************************/ 	
public function redeemCoupon()
{   
	$data 						=	array();
	$data['emailError']			=	'';
	$data['amountError']		=	'';
	$data['page']				=	'Redeem Coupon';

	if($this->input->post('SaveChanges')):
		$this->form_validation->set_error_delimiters('', '');
		$error						=	'NO';
		$this->form_validation->set_rules('couon_code', 'Coupon Code', 'trim|required');

		// $where 						= 	['coupon_code'=>$this->input->post('couon_code'),'coupon_code_statys'=>'Active'];
	    // $chkCoupon 					= 	$this->geneal_model->getOnlyOneData('da_coupon_code_only', $where );

		$where['where'] 			= 	['coupon_code'=>$this->input->post('couon_code')];
	    $chkCoupon 					= 	$this->geneal_model->getData2('single','da_coupon_code_only', $where );

	    $where['where'] 			= 	['voucher_code'=>$this->input->post('couon_code')];
	    $quickCoupon 				= 	$this->geneal_model->getData2('single','da_ticket_coupons', $where );
	    
	    if(!empty($quickCoupon)):
	    	$ticket_order_id = $quickCoupon['ticket_order_id'];
	    	$where['where'] 			= 	array('ticket_order_id'=> $ticket_order_id);
	    	$quickOrder 				= 	$this->geneal_model->getData2('single','da_ticket_orders', $where );
	    endif;
	    
	    $whereCon 				=	['users_id' => (int)$this->session->userdata('DZL_USERID')];
		$userDetails  			=  	$this->geneal_model->getOnlyOneData('da_users', $whereCon );

		// passing value in $chkCoupon variable to overlaps coupon validation.
		if($quickCoupon):
			if($quickOrder['status'] == 'CL'):
		    	$chkCoupon["coupon_code_statys"] = "Inactive";
		    else:
		    	$chkCoupon["coupon_code_statys"] = $quickCoupon['coupon_code_statys'];
		    endif;
			$chkCoupon['coupon_code_amount'] = $quickCoupon['total_price'];
		 endif;

		if($userDetails['redeem_attempt_count'] == 3):
			$chkCoupon = [];
		endif;

		if($chkCoupon && $chkCoupon["coupon_code_statys"] == "Redeem"):
			$error	 				=	'YES';
			$data['couonCodeError'] =	lang('REDDEM_COUPON');
		elseif($chkCoupon && $chkCoupon["coupon_code_statys"] == "Inactive"):
			$error	 				=	'YES';
			$data['couonCodeError'] =	lang('INVALID_COUPON');
		elseif(empty($chkCoupon) && $this->input->post('couon_code') != '' && empty($quickCoupon) && $this->input->post('couon_code') != ''):
			$error	 				=	'YES';
			if($userDetails['redeem_attempt_count'] == 3):
				$data['popup_error']	=	"YES";
				$data['error_title']  	= 	"Redemption Suspended";
				$data['error_message']	=	"Multiple incorrect coupon attempts detected. For security reasons, your ability to redeem a coupon has been temporarily suspended. If you need assistance, please contact our customer support..";
				$data['couonCodeError'] = 	'Multiple incorrect coupon attempts detected. For security reasons, your ability to redeem a coupon has been temporarily suspended. If you need assistance, please contact our customer support..';

			elseif ($userDetails['redeem_attempt_count'] == 2) :
				$updateParam['redeem_attempt_count'] 	= 3;
				$updateParam['redeem_attempt_date'] 	= date('d-m-Y H:i:s');
				$updateParam['redeem_attempt_ip'] 		= $this->input->ip_address();

				$this->geneal_model->editData('da_users', $updateParam, 'users_id', (int)$this->session->userdata('DZL_USERID'));

				$data['popup_error']	=	"YES";
				$data['error_title']  	= 	"Redemption Suspended";
				$data['error_message']	=	"Multiple incorrect coupon attempts detected. For security reasons, your ability to redeem a coupon has been temporarily suspended. If you need assistance, please contact our customer support..";
				$data['couonCodeError'] = "This is your third and last attempt. After one more wrong attempt your ability to redeem a coupon has been temporarily suspended.";

			elseif ($userDetails['redeem_attempt_count'] == 1) :
				$updateParam['redeem_attempt_count'] 	= 2;
				$updateParam['redeem_attempt_date'] 	= date('d-m-Y H:i:s');
				$updateParam['redeem_attempt_ip'] 		= $this->input->ip_address();

				$this->geneal_model->editData('da_users', $updateParam, 'users_id', (int)$this->session->userdata('DZL_USERID'));

				$data['popup_error']	=	"YES";
				$data['error_title']  	= 	"Incorrect Coupon Code";
				$data['error_message']	=	"The coupon code entered is still incorrect. Please check again.Note: After another failed attempt, your ability to redeem a coupon will be temporarily suspended.";
				$data['couonCodeError'] = "This is your second attempt. After three wrong attempt your ability to redeem a coupon has been temporarily suspended.";
			else:
				$updateParam['redeem_attempt_count'] 	= 1;
				$updateParam['redeem_attempt_date'] 	= date('d-m-Y H:i:s');
				$updateParam['redeem_attempt_ip'] 		= $this->input->ip_address();

				$this->geneal_model->editData('da_users', $updateParam, 'users_id', (int)$this->session->userdata('DZL_USERID'));

				$data['popup_error']	=	"YES";
				$data['error_title']  	= 	"Coupon Code Error";
				$data['error_message']	=	"The coupon code you entered is incorrect. Please double-check it and try again.";
				$data['couonCodeError'] = "This is your first attempt. After three wrong attempt your ability to redeem a coupon has been temporarily suspended.";	
			endif;
			// $data['couonCodeError'] =	lang('INVALID_COUPON');
		endif;


		if($this->form_validation->run() && $error == 'NO'):
			$wcon_user['where'] = array('users_id' => (int)$this->session->userdata('DZL_USERID'));
	        $userData = $this->geneal_model->getData2('single',"da_users",$wcon_user);
			/* Check sales person and retailser available points */
	        $whereCon 				 		=	['users_id' => (int)$this->session->userdata('DZL_USERID')];
	        $availableArabianPoints  		=  $this->geneal_model->getOnlyOneData('da_users', $whereCon );
	        if($availableArabianPoints):
				$availableArabianPoints 	= 	((float)$availableArabianPoints['availableArabianPoints'] + (float)$chkCoupon['coupon_code_amount']);
		        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
		    else:
		    	$availableArabianPoints 	= 	(float)$chkCoupon['coupon_code_amount'];
		        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
		    endif;
	        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->session->userdata('DZL_USERID'));
	        $this->session->set_userdata('DZL_AVLPOINTS',$availableArabianPoints);

	        

	        /* Load Balance Table -- from redeem coupon*/
		    $fromuserparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
			$fromuserparam["user_id_cred"] 		=	(int)$this->session->userdata('DZL_USERID');
			$fromuserparam["user_id_deb"]		=	(int)0;
			$fromuserparam["user_id_to"]		=	(int)$this->session->userdata('DZL_USERID');
			$fromuserparam["arabian_points"] 	=	(float)$chkCoupon['coupon_code_amount'];
		    $fromuserparam["availableArabianPoints"] =	(float)$userData['availableArabianPoints'];
			$fromuserparam["end_balance"] 		=	(float)$userData['availableArabianPoints'] + (float)$chkCoupon['coupon_code_amount'];
		    $fromuserparam["record_type"] 		=	'Credit';
		    $fromuserparam["arabian_points_from"]=	'Reddem Coupon';
		    $fromuserparam["coupon_code"]		=	$this->input->post('couon_code');
		    $fromuserparam["creation_ip"] 		=	$this->input->ip_address();
		    $fromuserparam["created_at"] 		=	date('Y-m-d H:i');
		    $fromuserparam["created_by"] 		=	$this->session->userdata('DZL_USERSTYPE');
		    $fromuserparam["status"] 			=	"A";
	        


		    $this->geneal_model->addData('da_loadBalance', $fromuserparam);
		    /* End */

		    if($quickCoupon):

		    	 /* update coupon status */
			    $update_coupon 	= 	array(
			    	'availableArabianPoints'=>	(float)$userData['availableArabianPoints'],
					'end_balance' 			=>	(float)$userData['availableArabianPoints'] + (float)$chkCoupon['coupon_code_amount'],
			    	'coupon_code_statys' => 'Redeem',
			    	'redeemed_by_whom'   =>  $this->session->userdata('DZL_USEREMAIL'),
					'redeemed_by_user_id'   =>  (int)$this->session->userdata('DZL_USERID'),
					'redeemed_by_mobile'   =>  (int)$this->session->userdata('DZL_USERMOBILE'),
			    	'redeemed_date' 	   =>  date('d-m-Y H:i')
				);

				$this->geneal_model->editData('da_ticket_coupons', $update_coupon, 'voucher_code', $this->input->post('couon_code'));

		    else:
			    /* update coupon status */
			    $update_coupon 	= 	array(
			    	'availableArabianPoints'=>	(float)$userData['availableArabianPoints'],
					'end_balance' 			=>	(float)$userData['availableArabianPoints'] + (float)$chkCoupon['coupon_code_amount'],
			    	'coupon_code_statys' => 'Redeem',
			    	'redeemed_by_whom'   =>  $this->session->userdata('DZL_USEREMAIL'),
					'redeemed_by_user_id'   =>  (int)$this->session->userdata('DZL_USERID'),
					'redeemed_by_mobile'   =>  (int)$this->session->userdata('DZL_USERMOBILE'),
			    	'redeemed_date' 	   =>  date('Y-m-d H:i')
				);

				$this->geneal_model->editData('da_coupon_code_only', $update_coupon, 'coupon_code', $this->input->post('couon_code'));

		    endif;


			$this->session->set_flashdata('success', lang('RECHARGE_SUCCESS').' '.(int)$chkCoupon['coupon_code_amount']);
			redirect('redeem-coupon');
		endif;
	endif;

	$this->load->library("pagination");

	$tblName 				=	'da_loadBalance';
	$shortField 			= 	array('_id'=> -1 );
	$whereCon2['where']		= 	array('user_id_cred'=>(int)$this->session->userdata('DZL_USERID'), "record_type" => "Credit", "arabian_points_from" => "Reddem Coupon");

	$totalPage 				=	$this->geneal_model->getData2('count',$tblName,$whereCon2,$shortField,'0','0');
	$config 				= 	['base_url'=>base_url('redeem-coupon'),'per_page'=>10,'total_rows'=>$totalPage];

	$this->pagination->initialize($config);
	$data['users']  		=	$this->geneal_model->getData2('multiple', $tblName, $whereCon2,$shortField,$this->uri->segment(2),$config['per_page']);

	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-redeemcoupon',$data);
	else:
	    $this->load->view('redeemcoupon',$data);
	endif;
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
	$data 						=	array();
	$data['error']				=	'';
	if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person'){
		$data['page']			=	'Retailer';
	}elseif ($this->session->userdata('DZL_USERSTYPE') == 'Retailer' || $this->session->userdata('DZL_USERSTYPE') == 'Promoter') {
		$data['page']			=	'Users';
	}elseif ($this->session->userdata('DZL_USERSTYPE') == 'Freelancer') {
		$data['page']			=	'Freelancer';
	}

	$data['countryCodeData']    =   countryCodeList();

	if($this->input->post('SaveChanges')):

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_message('is_unique', 'The %s is already taken');
		$data['error']			=	'NO';
		$this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
		$this->form_validation->set_rules('users_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('users_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[15]|is_unique[da_users.users_mobile.integer]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[64]|is_unique[da_users.users_email.string]');

		$this->form_validation->set_rules('arabianPoints', 'Arabian Points', 'trim|required');
		
		if($this->input->post('user_type') == 'Retailer' || $this->input->post('user_type') == 'Promoter'):
			$this->form_validation->set_rules('store', 'Store Name', 'trim|required');
		endif;
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[25]');			
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|min_length[8]|matches[password]');


		/* Check sales person and retailser available points */
        $whereCon 				 =	['users_id' => (int)$this->session->userdata('DZL_USERID')];
        $availableArabianPoints  =  $this->geneal_model->getOnlyOneData('da_users', $whereCon );
        if($availableArabianPoints):
        	if($this->input->post('arabianPoints') > $availableArabianPoints['availableArabianPoints']):
        		$data['error']	 =	'YES';
        	endif;
        else:
        	$data['error']		 =	'YES';
        endif;

		if($this->form_validation->run() && $data['error'] == 'NO'):
			if($this->input->post('page') == 'Retailer'){
				$param["users_seq_id"]			=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Retailer');
				$param["store_name"]			=	$this->input->post('store');
			}elseif($this->input->post('user_type') == 'Promoter'){
				$param["users_seq_id"]			=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Promoter');
				$param["store_name"]			=	$this->input->post('store');
			}elseif ($this->input->post('page') == 'Users') {
				$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Users');
				$param['referral_code']		=	strtoupper(uniqid(16));
			}	
			$param["users_id"]				=	(int)$this->geneal_model->getNextSequence('da_users');
			if($this->session->userdata('DZL_USERSTYPE') == 'Users'):
				$param["users_type"] 			=	$this->input->post('page');
			else:
				$param["users_type"] 			=	$this->input->post('users_type');
			endif;

			$param["users_name"]			=	$this->input->post('name');
			$param["last_name"]				=	$this->input->post('last_name');
		    $param["users_email"] 			=	$this->input->post('email');
		    $param["country_code"] 			=	$this->input->post('country_code');
		    $param["users_mobile"]			=	(int)$this->input->post('mobile');
		    $param["password"]				=	md5($this->input->post('password'));
		    $param["totalArabianPoints"]	=	(float)$this->input->post('arabianPoints');
		    $param["availableArabianPoints"]=	(float)$this->input->post('arabianPoints');
		    $param["store"]					=	(float)$this->input->post('store');
		    $param["creation_ip"] 			=	$this->input->ip_address();
		    $param["created_at"] 			=	date('Y-m-d H:i');
		    $param["created_by"] 			=	(int)$this->session->userdata('DZL_USERID');
			$param["created_by_urse_type"] 	=	$this->session->userdata('DZL_USERSTYPE');
			$param["is_verify"] 			=	"Y"; 
		    $param["status"] 				=	"A";
			$param['bind_person_id']		=	(int)$this->session->userdata('DZL_USERID');
			$param['bind_person_name']		=	$this->session->userdata('DZL_USERNAME');
			$param['bind_user_type']		=	$this->session->userdata('DZL_USERSTYPE');
			
		    $isInsert 	=	$this->geneal_model->addData('da_users', $param);
			if ($isInsert):
			    $availableArabianPoints 		= 	((int)$availableArabianPoints['availableArabianPoints'] - (int)$this->input->post('arabianPoints'));
		        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
		        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->session->userdata('DZL_USERID'));
		        $this->session->set_userdata('DZL_AVLPOINTS',$availableArabianPoints);

		        /* Load Balance Table -- from user*/
			    $fromuserparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
				$fromuserparam["user_id_cred"] 		=	(int)$this->session->userdata('DZL_USERID');
				$fromuserparam["user_id_deb"]		=	(int)0;
				$fromuserparam["user_id_to"]		=	(int)$param["users_id"];
				$fromuserparam["arabian_points"] 	=	(float)$this->input->post('arabianPoints');
			    $fromuserparam["record_type"] 		=	'Debit';
			    $fromuserparam["arabian_points_from"]=	'Recharge';
			    $fromuserparam["creation_ip"] 		=	$this->input->ip_address();
			    $fromuserparam["created_at"] 		=	date('Y-m-d H:i');
			    $fromuserparam["created_by"] 		=	$this->session->userdata('DZL_USERSTYPE');
				$fromuserparam["created_user_id"] 	=	(int)$this->session->userdata('DZL_USERID');
			    $fromuserparam["status"] 			=	"A";
			    
			    $this->geneal_model->addData('da_loadBalance', $fromuserparam);
			    /* End */

		        /* Load Balance Table -- to user*/
			    $touserparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
				$touserparam["user_id_cred"] 		=	(int)$param["users_id"];
				$touserparam["user_id_deb"]			=	(int)$this->session->userdata('DZL_USERID');
				$touserparam["arabian_points"] 		=	(float)$this->input->post('arabianPoints');
			    $touserparam["record_type"] 		=	'Credit';
			    $touserparam["arabian_points_from"] =	'Recharge';
			    $touserparam["creation_ip"] 		=	$this->input->ip_address();
			    $touserparam["created_at"] 			=	date('Y-m-d H:i');
			    $touserparam["created_by"] 			=	$this->session->userdata('DZL_USERSTYPE');
				$touserparam["created_user_id"] 	=	(int)$this->session->userdata('DZL_USERID');
			    $touserparam["status"] 				=	"A";
			    
			    $this->geneal_model->addData('da_loadBalance', $touserparam);
			    /* End */

		        $this->session->set_flashdata('success', 'New User created');
		        redirect('add-user');
		    endif;
		endif;
	endif;

	$this->load->library("pagination");

	$tblName 				=	'da_users';
	$shortField 			= 	array('users_id'=> -1 );
	$whereCon2['where']		= 	array('created_by'=>(int)$this->session->userdata('DZL_USERID'));

	$totalPage 				=	$this->geneal_model->getData2('count',$tblName,$whereCon2,$shortField,'0','0');
	$config 				= 	['base_url'=>base_url('add-user'),'per_page'=>10,'total_rows'=>$totalPage];

	$this->pagination->initialize($config);
	$data['users']  		=	$this->geneal_model->getData2('multiple', $tblName, $whereCon2,$shortField,$this->uri->segment(2),$config['per_page']);

	$useragent=$_SERVER['HTTP_USER_AGENT'];
	
	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-addUsers',$data);
	else:
	    $this->load->view('addUsers',$data);
	endif;
} //END OF FUNCTION

/***********************************************************************
** Function name 	: changepassword
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for change password
** Date 			: 30 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 
    public function changepassword($editId='')
	{	
        $date 				=	array();
        $data['page'] 		=	'Change Password';
		 $data['profileDetails']	=	$this->geneal_model->getOnlyOneData('da_users', ['users_id' => $this->session->userdata('DZL_USERID')]);
       
       if($this->input->post('savechanges')):
        $error							=	'NO';
       
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[6]|matches[old_password]');
      
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[25]');
			$this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required|min_length[6]|matches[new_password]');
		
			if($this->form_validation->run() && $error == 'NO'  && $data['profileDetails']['password'] == md5($this->input->post('old_password'))):  
          
              	$post_data   =   $this->input->post();
				$insert_data = array("password" => md5($post_data['new_password']));
      
         		$update =  $this->geneal_model->editData('da_users',$insert_data,'users_id',(int)$data['profileDetails']['users_id']);
         
       			 // print_r($update);die;
           
		         if($update)
		         {
		            // echo"working";die;
		            $this->session->set_flashdata('success', lang('CHANGE_PASS'));
		            redirect('my-profile');
		         }
		         else{
		             $this->session->set_flashdata('Error', lang('CHANGE_PASS_ERROR'));
		              redirect('my-profile');
		         }
					
			endif;
		endif;
		
		$this->layouts->set_title('Change password');
		
        $this->load->view('myprofile',$data);
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: refreshpoint
	** Developed By 	: Manoj Kumar
	** Purpose 			: This function used for refresh point
	** Date 			: 16 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 
	public function refreshpoint()
	{
	    $where 			=	['users_id' => $this->session->userdata('DZL_USERID')];
		$tblName 		=	'da_users';
		$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
		if(!empty($userDetails) && $userDetails['status'] == 'A'):
			$this->session->set_userdata('DZL_USERID', $userDetails['users_id']);
			$this->session->set_userdata('DZL_USERNAME', $userDetails['users_name']);
			$this->session->set_userdata('DZL_USEREMAIL', $userDetails['users_email']);
			//$this->session->set_userdata('DZL_SEQID', $userDetails['users_sequence_id']);
			$this->session->set_userdata('DZL_USERMOBILE', $userDetails['users_mobile']);
			$this->session->set_userdata('DZL_TOTALPOINTS', $userDetails['totalArabianPoints']);
			$this->session->set_userdata('DZL_AVLPOINTS', $userDetails['availableArabianPoints']);
			$this->session->set_userdata('DZL_USERSTYPE', $userDetails['users_type']);

			$this->session->set_userdata('DZL_USERS_REFERRAL_CODE', $userDetails['referral_code']);

			$this->session->set_userdata('DZL_USERS_IMAGE', $userDetails['users_image']);

			$this->session->set_userdata('DZL_USERS_COUNTRY_CODE', $data['country_code']);
			
			$expIN = date('Y-m-d', strtotime($userDetails['created_at']. ' +12 months'));
			$today = strtotime(date('Y-m-d'));
			$dat = strtotime($expIN) - $today;
			$Tdate =  round($dat / (60 * 60 * 24));

			$this->session->set_userdata('DZL_EXPIRINGIN', $Tdate);

			redirect($_SERVER['HTTP_REFERER']);
		endif;
	}

/***********************************************************************
** Function name    : checkEmail
** Developed By     : AFSAR ALI
** Purpose          : This function used for check user.
** Date             : 17 MAY 2022
** Updated By       :
** Updated Date     : 
************************************************************************/ 
public function checkEmail()
{
    header('Content-type: application/json');
    $request = $_GET['email'];

    if(is_numeric($request)){ 
		if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'){
			$where = [ 	'users_type'	=> 'Retailer',
						'users_mobile'	=>	(int)$request ];
		}elseif ($this->session->userdata('DZL_USERSTYPE') == 'Retailer') {
			$where = [ 	'users_type'	=> 'Users',
						'users_mobile'	=>	(int)$request ];
		}
    }else{ 
    	if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'){
			$where = [ 	'users_type'	=> 'Retailer',
						'users_email'	=>	$request,
						'status'		=>	'A' ];
		}elseif ($this->session->userdata('DZL_USERSTYPE') == 'Retailer') {
			$where = [ 	'users_type'	=> 'Users',
						'users_email'	=>	$request,
						'status'		=>	'A' ];
		}
    }
    //print_r($where); die();
    $query = $this->geneal_model->checkDuplicate('da_users',$where);
    //print_r($query); die();
    if ($query == 0){ $valid = 'false';}
    else{ $valid = 'true';  }
    echo $valid;
    exit;       
} //END OF FUNCTION


/***********************************************************************
** Function name    : checkarAbianPoints
** Developed By     : AFSAR ALI
** Purpose          : This function used for check arabian points.
** Date             : 17 MAY 2022
** Updated By       :
** Updated Date     : 
************************************************************************/ 
public function checkarAbianPoints()
{
    header('Content-type: application/json');
    $rechargeAB = $_GET['recharge_amt'];
    $where 	=	[ 'users_id' => (int)$this->session->userdata('DZL_USERID') ];
    $query = $this->geneal_model->getOnlyOneData('da_users',$where);
    if ((int)$rechargeAB > (int)$query['availableArabianPoints']){ $valid = 'false';}
    else{ $valid = 'true';  }
    echo $valid;
    exit;       
} //END OF FUNCTION

/***********************************************************************
** Function name    : couponList
** Developed By     : AFSAR ALI
** Purpose          : This function used for Coupon List
** Date             : 18 MAY 2022
** Updated By       :
** Updated Date     : 
************************************************************************/ 
public function couponList()
{
	$data = array();
	$this->load->library("pagination");

	$tblName 				=	'da_coupons';
	$shortField 			= 	array('created_at'=> -1 );
	$whereCon['where']		= 	array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'coupon_status'=>'Live');
	//$whereCon['where']		= 	array('users_id'=>(int)$this->session->userdata('DZL_USERID'));

	$totalPage 				=	$this->geneal_model->getData2('count',$tblName,$whereCon,$shortField,'0','0');
	$config 				= [ 'base_url'=>base_url('my-coupon'),'per_page'=>10,'total_rows'=>$totalPage ];
	$this->pagination->initialize($config);
	$data['coupons']  =	$this->geneal_model->getData2('multiple', $tblName, $whereCon,$shortField,$this->uri->segment(2),$config['per_page']);

	$data['page'] 			=	'Coupon List';

    $useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-coupon_list',$data);
	else:
	    $this->load->view('coupon_list',$data);
	endif;
} //END OF FUNCTION

	/***********************************************************************
	** Function name 	: mywishlist
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for my wishlist
	** Date 			: 23 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function mywishlist()
	{  
		$data 						=	array();
		$data['error']				=	'';

		$this->load->library("pagination");

		$tblName 				=	'da_wishlist';
		$shortField 			= 	array('_id'=> -1 );
		$whereCon2['where']		= 	array('users_id'=>(int)$this->session->userdata('DZL_USERID'));

		$totalPage 				=	$this->geneal_model->getData2('count',$tblName,$whereCon2,$shortField,'0','0');
		$config 				= 	['base_url'=>base_url('my-wishlist'),'per_page'=>10,'total_rows'=>$totalPage];

		$this->pagination->initialize($config);
		$data['wishlistData']  	=	$this->geneal_model->getData2('multiple', $tblName, $whereCon2,$shortField,$this->uri->segment(2),$config['per_page']);

		$this->load->view('wishlist',$data);
	} //END OF FUNCTION

	/***********************************************************************
	** Function name 	: create
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for create new dilivery address
	** Date 			: 19 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function delete($id='')
	{  
		$id  		  =	manojDecript($id);
		$deleteWhere  = array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'product_id'=>(int)$id);
		$isDelete = $this->geneal_model->deleteDataByCondition('da_wishlist', $deleteWhere);
		if ($isDelete) { 
			$this->session->set_flashdata('success', lang('delete_from_wishlist'));
			redirect('my-wishlist');
		}
		redirect('my-wishlist');
	} //END OF FUNCTION

	/***********************************************************************
	** Function name 	: uploadProfilePic
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for upload Profile Pic
	** Date 			: 14 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function uploadProfilePic()
	{  
		$json = array();

		if (!empty($_FILES['file']['name']) && is_file($_FILES['file']['tmp_name'])) {

			// Sanitize the filename
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8')));
			$filename = time().$filename;

			// Allowed file extension types
			$allowed = array('jpeg','png','jpg');
			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
				$json['error'] = "This files type not allowed";
			}

			// // Check to see if any PHP files are trying to be uploaded
			// $content = file_get_contents($_FILES['file']['tmp_name']);
			// if (preg_match('/\<\?php/i', $content)) {
			// 	$json['error'] = "This files type not allowed";
			// }

		} else {
			$json['error'] = "Error in upload. Please try again";
		}

		if (!$json) {

			// Sanitize the filename
			$uploadfilename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8')));
			$newfilename 	= time().$uploadfilename;

			$uploadtmpname	= 	$_FILES['file']['tmp_name'];

			$this->load->library("upload_crop_img");
			$uimageLink						=	$this->upload_crop_img->_upload_image($uploadfilename,$uploadtmpname,'profileimage',$newfilename,'');
			if($uimageLink == 'UPLODEERROR'):
				$json['error'] = "Error in upload. Please try again";
			else:
				$update_data = array('users_image'	=>	$uimageLink);
				$this->geneal_model->editData('da_users', $update_data, 'users_id', (int)$this->session->userdata('DZL_USERID'));
				$this->session->set_userdata('DZL_USERS_IMAGE', $uimageLink);
				$json['code'] = 200;
				$json['success'] = "Files uploaded";
			endif;
		}
		header('Content-type: application/json');
		echo json_encode($json);
	} //END OF FUNCTION

	/***********************************************************************
	** Function name    : checkShareLimit
	** Developed By     : MANOJ KUMAR
	** Purpose          : This function used for check user.
	** Date             : 27 JUNE 2022
	************************************************************************/ 
	public function checkShareLimit()
	{
		if($this->input->post('shareurl')):
			$sharedUrls    		= 	explode('product-details/',$this->input->post('shareurl'));
			$sharedUrls    		= 	explode('/',$sharedUrls[1]);
			$productId    		= 	base64_decode($sharedUrls[0]);
			$sharedData    		= 	base64_decode($sharedUrls[1]);
			$sharedData    		= 	explode('_',$sharedData);
			$userId  			=	$sharedData[0];
			$referalCode 		=	$sharedData[1];

			$prowhere['where']	=	array('products_id'=>(int)$productId);
			$prodData			=	$this->common_model->getData('single','da_products',$prowhere);

			$sharewhere['where']=	array('users_id'=>(int)$userId,'products_id'=>(int)$productId);
			$shareCount			=	$this->common_model->getData('count','da_product_share',$sharewhere,'','0','0');

			if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit']):
				$param['share_id']					=	(int)$this->common_model->getNextSequence('da_product_share');
				$param['users_id']					=	(int)$userId;
				$param['products_id']				=	(int)$productId;
				$param['creation_date']				=   date('Y-m-d H:i');
				$param['creation_ip']				=   $this->input->ip_address();
				$this->common_model->addData('da_product_share',$param);
				echo 'success'; die;
			else:
				echo 'error'; die;
			endif;
		else:
			echo 'error'; die;
		endif;  
	} //END OF FUNCTION
	/***********************************************************************
	** Function name    : collectionpoint
	** Developed By     : AFSAR ALI
	** Purpose          : This function used for Coupon List
	** Date             : 16 NOV 2022
	** Updated By       :
	** Updated Date     : 
	************************************************************************/ 
	public function pickuppoint()
	{
		$data = array();
		$this->load->library("pagination");

		$tblName 				=	'da_emirate_collection_point';
		$shortField 			= 	array('creation_date'=> -1 );
		$whereCon['where']		= 	array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'status'=>'A');

		$totalPage 				=	$this->geneal_model->getData2('count',$tblName,$whereCon,$shortField,'0','0');
		$config 				= [ 'base_url'=>base_url('collection-point'),'per_page'=>10,'total_rows'=>$totalPage ];
		$this->pagination->initialize($config);
		$data['list']  			=	$this->geneal_model->getData2('multiple', $tblName, $whereCon,$shortField,$this->uri->segment(2),$config['per_page']);

		$data['page'] 			=	'Pickup Point';

		$this->load->view('pickup_point',$data);
	} //END OF FUNCTION

	/***********************************************************************
	** Function name    : stockreport
	** Developed By     : AFSAR ALI
	** Purpose          : This function is used for stock report
	** Date             : 16 NOV 2022
	** Updated By       :
	** Updated Date     : 
	************************************************************************/ 
	public function stockreport($id='')
	{
		$data = array();
		$data['collection_point_id']	=	base64_decode($id);
		if($this->input->post()){
			$params['request_id']				=	(int)$this->geneal_model->getNextSequence('da_product_request');
			$params['collection_point_id']		=	(int)addslashes($this->input->post('collection_point_id'));
			$params['inventory_id']				=	(int)addslashes($this->input->post('inventory_id'));
			$params['product_id']				=	(int)addslashes($this->input->post('product_id'));
			$params['users_id']					=	(int)$this->session->userdata('DZL_USERID');
			$params['request_qty']				=	(int)addslashes($this->input->post('qty'));
			$params['sent_qty']					=	0;
			$params['status']					=	'P';
			$params['creation_date']			=	strtotime(date('Y-m-d h:i'));
			$params['creation_ip']				=	$this->input->ip_address();
			$params['created_by']				=	(int)$this->session->userdata('DZL_USERID');
			$isInsert 	=	$this->geneal_model->addData('da_product_request', $params);
			$this->session->set_flashdata('success','Request Successfully Submited');
			redirect('stock-report/'.base64_encode($params['collection_point_id']));
		}
		$this->load->library("pagination");

		$tblName 				=	'da_inventory';
		$shortField 			= 	array('creation_date'=> -1 );
		$whereCon['where']		= 	array(
										'collection_point_id'=>(int)$data['collection_point_id'],
										'status'=>'A'
									);
		
		$totalPage 				=	$this->common_model->getInventoryList('count',$tblName,$whereCon,$shortField,'0','0');

		$config 				= [ 'base_url'=>base_url('stock-report/'.$id),'per_page'=>5,'total_rows'=>$totalPage ];
		$this->pagination->initialize($config);
		$data['list']  			=	$this->common_model->getInventoryList('multiple', $tblName, $whereCon,$shortField,$this->uri->segment(3),5);

		$data['page'] 			=	'Stock Report';
		//echo '<pre>'; print_r($data);die();
		$this->load->view('stock_report',$data);
	} //END OF FUNCTION

	/***********************************************************************
	** Function name    : productrequest
	** Developed By     : AFSAR ALI
	** Purpose          : This function is used for product request
	** Date             : 16 NOV 2022
	** Updated By       :
	** Updated Date     : 
	************************************************************************/ 
	public function productrequest($id='',$p='')
	{
		$data = array();
		$data['collection_point_id']	=	base64_decode($id);
		$this->load->library("pagination");
	
		$whereCon['where']		= 	array('collection_point_id'=>(string)$data['collection_point_id'],'order_status'=>'Success');
		$shortField 			= 	array('collection_status'=> -1,'_id'=> -1 );

		$baseUrl 							= 	getCurrentControllerPath('product-request/'.$id);
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 				=	'da_orders';

		$totalRows 				=	$this->geneal_model->getordersList('count',$tblName,$whereCon,$shortField,'0','0');
		//echo $this->uri->segment(3);die();
		if($this->uri->segment(3)){
			$perPage							=	$this->uri->segment(3);
			$data['perpage']					=	$this->uri->segment(3);	
		}else{
			$perPage							=	0;
			$data['perpage']					=	0;
		}
		//echo $perPage;die();
		$uriSegment 						= 	getUrlSegment();
	    $data['PAGINATION']					=	adminPagination($baseUrl,$suffix,$totalRows,5,$uriSegment);
		//print_r($data['PAGINATION']);die();
		if($this->uri->segment(getUrlSegment())):
			$page = $this->uri->segment(getUrlSegment());
		else:
			$page = 0;
		endif;

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
		
		$data['list']  			=	$this->geneal_model->getordersList('multiple', $tblName, $whereCon,$shortField,$perPage,5);
		//echo '<pre>'; print_r($data['list']);die();
		$data['page'] 			=	'product report';
		$this->load->view('product_report',$data);
	} //END OF FUNCTION
	/***********************************************************************
	** Function name    : approve_order
	** Developed By     : AFSAR ALI
	** Purpose          : This function is used for product request
	** Date             : 18 NOV 2022
	** Updated By       : Afsar Ali
	** Updated Date     : 22-11-2022
	************************************************************************/ 
	public function approve_order()
	{
		if($this->input->post('collection_code') == ''):
			redirect('pickup-point');
		else:
			$id = $this->input->post('collection_point_id');
		endif;
		if($this->input->post('collection_point_id') == '' || $this->input->post('order_id') == ''):
			redirect('product-request/'.base64_encode($id));
		else:
			$order_id 			= 	$this->input->post('order_id');
			$decodedCollectionCode = (int)$this->input->post('collection_point_id');
			$collectionCode 	=	base64_encode(strtoupper($this->input->post('collection_code')));
			$where['where'] 	=	array('sequence_id'=>(int)$order_id);
			$orderData 			=	$this->geneal_model->getordersList('single', 'da_orders',$where,['sequence_id'=>-1]);
			if($orderData <> ''):		
				if($orderData['collection_code'] == $collectionCode){
					//Update Inventory Stock
					$error = 'YES';
					foreach($orderData['order_details'] as $CA): //Check inventory quantity
						$whereCon['where']		=	array(
														'collection_point_id' 	=> (int)$decodedCollectionCode,
														'products_id'			=> (int)$CA['product_id']
													);
						$inventory 	=	$this->geneal_model->getData2('single','da_inventory',$whereCon);
						if($CA['is_donated'] == 'N'):
							if($inventory['available_qty'] >= $CA['quantity']):  
								$error = 'NO';
							endif;
						endif;
					endforeach;
					if($error == 'YES'): //Redirect back when order quantity is greater than inventory quantity.
						$this->session->set_flashdata('error','Product Quantity is Not Available. Please Request Product Quantity.');
						redirect('product-request/'.base64_encode($id));
					endif;
					//Check inventory quantity and update
					foreach($orderData['order_details'] as $CA):
						$whereCon['where']		=	array(
														'collection_point_id' 	=> (int)$decodedCollectionCode,
														'products_id'			=> (int)$CA['product_id']
													);
						$inventory 	=	$this->geneal_model->getData2('single','da_inventory',$whereCon);
						if($CA['is_donated'] == 'N'):
							if($inventory['available_qty'] >= $CA['quantity']): 
								$this->geneal_model->updateInventoryStock($decodedCollectionCode,$CA['product_id'],$CA['quantity']);
							endif;
						endif;
					endforeach;
					//END
					$updateStatus				=	array('collection_status' => 'Collected');
					$this->geneal_model->editData('da_orders',$updateStatus,'sequence_id',(int)$order_id);
					$this->session->set_flashdata('success','Order Collected');
					redirect('product-request/'.base64_encode($id));
				}else{
					$this->session->set_flashdata('error','Incorrect Collection Code.');
					redirect('product-request/'.base64_encode($id));
				}
				
			else:
				redirect('product-request/'.base64_encode($id));
			endif;
		endif;
	}


	/***********************************************************************
	** Function name    : notification
	** Developed By     : Dilip Halder
	** Purpose          : This function is used to enable/disable notification.
	** Date             : 22 Feb 2023.
	** Updated By       : 
	** Updated Date     : 
	************************************************************************/ 
	public function notification()
	{
		

		$tbl 					=	'da_users';
		$where 					=	['users_id' => $this->session->userdata('DZL_USERID')];
		$profileDetails			=	$this->geneal_model->getOnlyOneData($tbl, $where);

		if(!empty($profileDetails['email_notification']) && $profileDetails['email_notification'] ==  'on' && $this->input->post('fieldName') == 'email_notification' ):
			$update_data['email_notification'] =  'off';
		elseif($this->input->post('fieldName') == 'email_notification'):
			$update_data['email_notification'] =  'on';
		endif;

		if(!empty($profileDetails['notification']) && $profileDetails['notification'] ==  'on'  && $this->input->post('fieldName') == 'notification' ):
			$update_data['notification'] = 'off';
		elseif($this->input->post('fieldName') == 'notification'):
			$update_data['notification'] =  'on';
		endif;

		if(!empty($profileDetails['sms_notification']) && $profileDetails['sms_notification'] ==  'on'  && $this->input->post('fieldName') == 'sms_notification' ):
			$update_data['sms_notification'] =  'off';
		elseif($this->input->post('fieldName') == 'sms_notification'):
			$update_data['sms_notification'] =  'on';
		endif;	

 		$update =  $this->geneal_model->editData('da_users',$update_data,'users_id',(int)$profileDetails['users_id']);
 		
 		echo "1";
	}


	/* * *********************************************************************
	 * * Function name : getNotification
	 * * Developed By : Dilip halder
	 * * Purpose  : This function used for get Notification
	 * * Date : 19 May 2023
	 * * **********************************************************************/
	public function getNotification()
	{	
		$data 					=	array();
		$data['page'] 			=	'Notification';

		$where 			=	[ 'users_id' => (int)$this->session->userdata('DZL_USERID') ];
		$tblName 		=	'da_users';
		$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

		if(!empty($userDetails)):
			if($userDetails['status'] == 'A'):
				$where1 		=	[ 'users_id' => (int)$this->session->userdata('DZL_USERID') ];
				$tblName1 		=	'da_notifications_details';
				$order1 		=	array('_id'=> -1 );
				$userAddress 	=	$this->geneal_model->getData($tblName1, $where1, $order1);

				$where2['where']	=	array('users_id' => (int)$this->session->userdata('DZL_USERID'), 'is_read' => 'N');
				$not_read_data 		=	$this->geneal_model->getData2('count',$tblName1,$where2,$order1);

				$data['not_read_userNotification_count'] = $not_read_data;

				$data['userNotification'] 	=	$userAddress;


			endif;	
		endif;	

		$this->load->view('mobile-notification',$data);
	}

	/***********************************************************************
	** Function name 	: advance_cash
	** Developed By 	:  Dilip Halder
	** Purpose 			: This function used for due management
	** Date 			: 14 APRIL 2023
	************************************************************************/ 	
	public function advanceCash()
	{
		if($this->input->post('SaveChanges')):

			$this->form_validation->set_error_delimiters('', '');
			$error						=	'NO';
			$data['error'] 				=	'YES';

		    if($this->input->post('mobile')):
		    	$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
		   	elseif($this->input->post('email')):
		   		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		    endif;

		    $this->form_validation->set_rules('advanced_amount', 'Advanced amount', 'trim|required|numeric');

		    if ($this->form_validation->run() == TRUE):

		    	   //New conditions
					if(is_numeric($this->input->post('mobile'))){ 
						if(strlen($this->input->post('mobile')) >= 10){
						
							$where 				= 	['users_mobile'=>(int)$this->input->post('mobile'),'status'=>'A'];
						}else{
						
							$where 				= 	['users_mobile'=>(float)$this->input->post('mobile'),'status'=>'A'];
						}
						
				    }else{ 
						$where 					= 	['users_email'=>$this->input->post('email'),'status'=>'A'];
				    }

				    $chkUser = $this->geneal_model->getOnlyOneData('da_users', $where ); 
				    if(empty($chkUser)):
				    	$error	 			=	'YES';
				    	$data['emailError'] =	lang('USER_NOT_FOUNT');
				    
				    else:

				    	//seller balance updating
				    	$whereCon 				 =	['users_id' => (int)$this->session->userdata('DZL_USERID')];
						$seller_data  =  $this->geneal_model->getOnlyOneData('da_users', $whereCon );
						$availableArabianPoints 	= 	((int)$seller_data['availableArabianPoints'] - (int)$this->input->post('advanced_amount'));
				        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
				        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->session->userdata('DZL_USERID'));
				        $this->session->set_userdata('DZL_AVLPOINTS',$availableArabianPoints);


				        //user balance updating
				        $totalArabianPoints1 	= 	((int)$chkUser['totalArabianPoints'] + (int)$this->input->post('advanced_amount'));
				        $availableArabianPoints1 	= 	((int)$chkUser['availableArabianPoints'] + (int)$this->input->post('advanced_amount'));
				        $updatefield1        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints1 ,'totalArabianPoints'=> $totalArabianPoints1);
				        $this->geneal_model->editData('da_users', $updatefield1, 'users_id', (int)$chkUser['users_id'] );
				        

						    $dueparam["due_management_id"]	=	(int)$this->geneal_model->getNextSequence('da_dueManagement');

						    if(!empty($this->input->post('mobile'))):
						    	$dueparam["mobile"]				=	(int)$this->input->post('mobile');
						    else:
						    	$dueparam["email"]				=	$this->input->post('email');
						    endif;
					    	
					    	$dueparam["recharge_amt"]		=	(float)$this->input->post('recharge_amt');
					    	$dueparam["cash_collected"]		=	(float)$this->input->post('cash_collected');
					    	$dueparam["due_amount"]			=	(float)$this->input->post('due_amount');
					    	$dueparam["advanced_amount"]	=	(float)$this->input->post('advanced_amount');

						    $dueparam["user_id_deb"]		=	(int)$this->session->userdata('DZL_USERID');
						    $dueparam["user_id_to"]			=	(int)$chkUser["users_id"];
						    $dueparam["record_type"] 		=	'Debit';
						    if($this->session->userdata('DZL_USERSTYPE') == "Sales Person"):
						    	$dueparam["recharge_type"] 	=	'Advanced Cash';
						    else:
						    	$dueparam["recharge_type"] 	=	'Direct Cash';
						    endif;
						    $dueparam["creation_ip"] 		=	$this->input->ip_address();
						    $dueparam["created_by"] 		=	$this->session->userdata('DZL_USERSTYPE');
						    $dueparam["created_user_id"] 	=	(int)$this->session->userdata('DZL_USERID');
						    $dueparam["created_at"] 		=	date('Y-m-d H:i');
						    $dueparam["status"] 			=	"A";
						    
						    $this->geneal_model->addData('da_dueManagement', $dueparam);

						    $this->session->set_flashdata('alert_success',lang('success'));

		    		endif;


		    		redirect('due-management');



		    else:
		    		$this->session->set_flashdata('alert_success',lang('updatesuccess'));
		    		redirect('due-management');

		    endif;

		endif;


	}

	
	/***********************************************************************
	** Function name 	: duemanagement
	** Developed By 	: Dilip Halder
	** Purpose 			: This function used for due management
	** Date 			: 13 APRIL 2022
	** Updated By 		: Dilip Halder
	** Updated At 		: 30 December 2023
	************************************************************************/ 	
	public function duemanagement()
	{	
		$data 				 =	array();
		$data['page']		 =	'Due Mangement';

		$DZL_USERID 		 =   $this->session->userdata('DZL_USERID');
		$tblName 			 =	'da_dueManagement';
		$shortField 		 = 	array('due_management_id'=> -1 );

		$fromDate =  $this->input->post('fromDate');
		$toDate   =  $this->input->post('toDate');

		if($fromDate):
			$data['fromDate'] 		= date('Y-m-d 00:01' ,strtotime($fromDate));
			// $whereCon['where_gte'] 	= array(array("created_at",$data['fromDate']));
		endif;
		if($toDate):
			$data['toDate'] 		= date('Y-m-d 23:59' ,strtotime($toDate));
			$whereCon['where_lte'] 	= array(array("created_at",$data['toDate']));
		else:
			$data['toDate'] 		= date('Y-m-d 23:59');
			// $whereCon['where_lte'] 	= array(array("created_at",$data['toDate']));
		endif;

		$whereCon['where']	 	    = array('user_id_deb' => (int)$DZL_USERID,'bind_person_id' => (string)$DZL_USERID );

		// Search by product/retailer details....
		$salesperson = $this->input->post('salesperson');

		if(empty($salesperson)):

			$tbl 			 =  'da_dueManagement';
		 	$shortField 	 =  array('due_management_id' => -1 );
			$DueManagement 	 =  $this->geneal_model->duemanagementweb('multiple',$tbl,$whereCon ,$shortField);
			// $data['DueManagement']   = $DueManagement?$DueManagement:'';
		else:
			$data['salesperson'] = $salesperson;
			if(is_numeric($salesperson)):
				$sValue = (int)$salesperson;
				$whereCondition['where']	 = 	array( 'users_mobile' => (int)$sValue ,'status' => 'A');
				// $whereCon['where']	 = 	array( 'sender_users_mobile' => (int)$sValue ,'record_type' => 'Debit','user_type' => 'Promoter');
			else:
				$sValue = $salesperson;
				$whereCondition['where']	 = 	array( 'users_email' => $sValue ,'status' => 'A');
				// $whereCon['where']	 = 	array( 'sender_users_email' => $sValue ,'record_type' => 'Debit','user_type' => 'Promoter');
			endif;

			$Salesperson	   =	$this->common_model->getParticularFieldByMultipleCondition(array('users_id'),'da_users',$whereCondition);
			$DZL_USERID        =  $Salesperson['users_id']; 
			$whereCon['where'] = array('user_id_deb' => (int)$DZL_USERID,'bind_person_id' => (string)$DZL_USERID );

			$tblName 			 = 	'da_dueManagement';
			$shortField 		 = 	array('due_management_id'=> -1 );
			$Salesperson_Due  	 =	$this->geneal_model->duemanagementweb('multiple',$tblName,$whereCon,$shortField);
			// $data['Salesperson_Due']    = $Salesperson_Due?$Salesperson_Due:'';
		endif;

		if($this->session->userdata('DZL_USERSTYPE') == "Super Salesperson" || $this->session->userdata('DZL_USERSTYPE') == "Super Retailer"):
			$tblName 				= 	'da_users';
			$shortField 			= 	'';
			$whereConsales['where']		=	array('users_type' => 'Sales Person','status' => "A" );
			
			if($this->session->userdata('DZL_USERSTYPE') == "Super Salesperson"):
				$whereConsales['where_in']	=   array("0" => "users_email" ,"1"=>array("manawalanwaseem@gmail.com","shafimak25@gmail.com","ismailkk0520@gmail.com","jaseer26@gmail.com","jaleel.dmi@gmail.com","ajmal2nasar@gmail.com","asrafk.ae@gmail.com"));
			elseif($this->session->userdata('DZL_USERSTYPE') == "Super Retailer"):
				$whereConsales['where_in']	=   array("0" => "users_email" ,"1"=>array("dealzfaisal@gmail.com","shabeer0606@gmail.com","ashiqpcpalam@gmail.com"));
			endif;
			
			$salesPersonList 		    = 	$this->common_model->getData('multiple',$tblName,$whereConsales,$shortField);
			$data['salespersonList']    = $salesPersonList;

		endif;


		if($Salesperson_Due|| $DueManagement ):
			$dueData        = $DueManagement?$DueManagement : $Salesperson_Due;
			$TotalRecharge  =   0;
			foreach($dueData as $key => $items):
			 	$TotalRecharge = $TotalRecharge + $items['recharge_amt'];

			 	$UserIdTo = $items['user_id_to'];
				$tblName 					=	'da_ticket_orders';
				$shortField 				= 	array('sequence_id'=> -1 );
				
				$whereCona  				=	array(
												'user_id' => (int)$UserIdTo  , 'status' => array('$ne'=> 'CL'),
												'created_at' => array(  '$gte' => $data['fromDate']?$data['fromDate']:date('Y-m-d 00:01') , '$lte' => $data['toDate']?$data['toDate']:date('Y-m-d 23:59'))
											 );

				// if($this->session->userdata('DZL_USERID') ==100000000001983 ):
				// 	echo "<pre>";
				// 	print_r($whereCona);
				// 	die();
				// endif;
				
				
				$todaysales					= $this->geneal_model->todaysales($tblName,$whereCona,$shortField);
				if($DueManagement):
					$DueManagement[$key]['todaySales'] = $todaysales;
					$data['DueManagement']   = $DueManagement?$DueManagement:'';
				else:
					$Salesperson_Due[$key]['todaySales'] = $todaysales;





					$data['Salesperson_Due']   = $Salesperson_Due?$Salesperson_Due:'';
				endif;

			endforeach;	
			$data['TotalRecharge']   	  = $TotalRecharge;


			// Todays total sales details.
			$whereCon['where_gte'] 		= 	array(array("created_at",date('Y-m-d 00:01')));
			$whereCon['where_lte'] 		= 	array(array("created_at",date('Y-m-d 23:59')));
			$tblName 			 		= 	'da_dueManagement';
			$shortField  				= 	array('due_management_id'=> -1 );
			$todayDueManagement 		=	$this->geneal_model->duemanagementweb('multiple',$tblName,$whereCon,$shortField);

			$todayTotalRecharge = 0;
			if($todayDueManagement):
				foreach ($todayDueManagement as $key => $item):
				 $todayTotalRecharge  = $todayTotalRecharge  + $item['recharge_amt'];
				 $totalCash_collected = $totalCash_collected + $item['cash_collected'];
				endforeach;
			endif;
			$data['todayTotalRecharge']   = $todayTotalRecharge;

			// Todaystotal sales count
			$todaystotalSales = 0;
			if($DueManagement):
				// $todaystotalSales = 0;
				foreach($DueManagement as $items):
					$todaystotalSales = $todaystotalSales + $items['todaySales'];
				endforeach;

			elseif($Salesperson_Due):
				// $todaystotalSales = 0;
				foreach($Salesperson_Due as $items):
					$todaystotalSales = $todaystotalSales + $items['todaySales'];
				endforeach;
			endif;
			$data['todaystotalSales'] = $todaystotalSales; 

		endif;

		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-duemanagement',$data);
		else:
			$this->load->view('duemanagement',$data);
		endif;
	}
	
	/***********************************************************************
	** Function name 	: duemanagement
	** Developed By 	: Dilip Halder
	** Purpose 			: This function used for due management
	** Date 			: 13 APRIL 2022
	************************************************************************/ 	
	public function viewduemanagement($id)
	{	

		$id =  manojDecript($id);

		$data 						=	array();
		$data['emailError']			=	'';
		$data['amountError']		=	'';
		$data['page']				=	'Due Mangement';

		$tblName 					=	'da_dueManagement';
		$shortField 				= 	array('due_management_id'=> -1 );
		$whereCon['where']			=	array('user_id_to' => (int)$id, 'user_id_deb' => (int)$this->session->userdata('DZL_USERID'));

		$data['DueManagement'] 		=	$this->geneal_model->getData2('multiple',$tblName,$whereCon,$shortField);

		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			
			$this->form_validation->set_rules('CurrentDataID', 'CurrentDataID', 'trim|required');
			// $this->form_validation->set_rules('collect_cash', 'Collect Cash', 'trim|required');
			// $this->form_validation->set_rules('recharge_amt', 'Recharge Cash', 'trim|required');

			if($this->form_validation->run() == TRUE && $error == 'NO'): 

				$due_management_id = manojDecript($this->input->post('CurrentDataID'));

				// Getting current Due Details.
				$tblName   			=  "da_dueManagement";
				$whereCon1['where'] =   array('due_management_id'  => (int)$due_management_id );
				$shortField 		= 	array('due_management_id'=> -1 );
				$dueData 			= 	$this->geneal_model->getData2('single',$tblName,$whereCon1,$shortField);
			   
			   $param['recharge_amt']				= 	(float)$dueData['recharge_amt'] + (float)$this->input->post('recharge_amt');
			   $param['due_amount']					= 	(float)$dueData['due_amount'] - (float)$this->input->post('collect_cash') ;
			   
			   if($this->input->post('recharge_amt')):
			    $param['cash_collected']			= 	(float)$dueData['cash_collected'] + (float)$this->input->post('recharge_amt') ;
			   	$param['advanced_amount']			= 	(float)$dueData['advanced_amount'] - (float)$this->input->post('recharge_amt') ;
			   else:
			    $param['cash_collected']			= 	(float)$dueData['cash_collected'] + (float)$this->input->post('collect_cash') ;
			   	$param['advanced_amount']			= 	(float)$dueData['advanced_amount'];

			   endif;

				if($this->input->post('CurrentDataID') !=''):
					$categoryId					=	manojDecript($this->input->post('CurrentDataID'));
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->userdata('HCAP_ADMIN_ID');

					$this->geneal_model->editData('da_dueManagement',$param,'due_management_id',(int)$categoryId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;

				redirect('view-due-management/'.manojEncript($dueData['user_id_to']));
			endif;
		endif;




		$this->load->view('view-duemanagement',$data);
	}
	
	/***********************************************************************
	** Function name 	: clearCoupons
	** Developed By 	: Dilip Halder
	** Purpose 			: This function used for clear expired Coupons
	** Date 			: 18-07-2023
	************************************************************************/ 
	
	public function clearCoupons()
	{
		
		$tblName 				=	'da_coupons';
		$shortField 			= 	array('created_at'=> -1 );
		$whereCon['where']		= 	array('coupon_status'=>'Live');
		//$whereCon['where']		= 	array('users_id'=>(int)$this->session->userdata('DZL_USERID'));
		
		$couponsList   = $this->geneal_model->getData2('multiple', $tblName,$whereCon);

		$productID = array();
		foreach($couponsList as $coupons):
			$productID[] = $coupons['product_id'];
		endforeach;

		$filteredProductID = array_values(array_unique($productID));

		$tblName 				=	'da_products';
		$productwhereCon['where_in']		 			= 	array('0' => 'products_id'  ,'1'=> $filteredProductID);

		$productdetails = $this->geneal_model->getData2('multiple', $tblName,$productwhereCon); 

		if($productdetails):
			 
			foreach($productdetails  as $productsitems):
				// $draw_date  				=	 $productsitems['draw_date'];
				// $draw_time 				=	 $productsitems['draw_time'];

				$products_id = $productsitems['products_id'];
				$draw_date 	 = $productsitems['draw_date'] . " ".$productsitems['draw_time'];
				
				 $Drawdateleft = getDateDifference($draw_date);

				 if($Drawdateleft == "Draw date is finished"):
				 	 
				 	$tblName 				=	'da_coupons';
					$shortField 			= 	array('created_at'=> -1 );
					$whereCon['where']		= 	array('coupon_status'=>'Live');

					$tableName 				=	'da_coupons';
					$shortField 			= 	array('created_at'=> -1 );
					$param['coupon_status'] = 	'Expired';
					$param['remark'] 		= 	'Expired by cron.';
					$whereCon['where']		= 	array('coupon_status'=>'Expired');
					$couponsList   			= $this->geneal_model->editData($tableName,$param,'product_id',(int)$products_id);
				 endif;

			endforeach;

		endif;
	}


}
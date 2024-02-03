<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
public function  __construct() 
{ 
	parent:: __construct();
	error_reporting(0);
	$this->load->model(array('geneal_model','emailtemplate_model','common_model','emailsendgrid_model','sms_model'));
	$this->lang->load('statictext','front');
	
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
	$data['page']			=	'Login';

	if($this->session->userdata('DZL_USERID')):
		redirect('my-profile');
	endif;
	if($this->input->get('referenceUrl')):
		$this->session->set_userdata('referenceUrl',$this->input->get('referenceUrl'));
		redirect('login');
	endif;

	/*--------------------Start Login--------------------*/
	if($this->input->post($_POST)):



		if($this->input->post('mobile')):
			$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
			$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[6]');
		endif;

		if($this->input->post('email')):
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[64]');
		endif;

		$this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[25]');
		
		$this->form_validation->set_error_delimiters("<div class='text-danger'>","</div>");

		if($this->form_validation->run()):

			$country_code 		=	$this->input->post('country_code');
			$password 			=	md5($this->input->post('password'));
			$userDetails = array();

			if($this->input->post('mobile')):
				$loginID 			=	$this->input->post('mobile');

				$where 			=	[ 'users_mobile' => (int)$loginID ,'country_code' => $country_code ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
				if(!empty($userDetails)): $data = $userDetails; endif;
			else:
				$loginID 			=	$this->input->post('email');

				$where 			=	[ 'users_email' => $loginID ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
				if(!empty($userDetails)): $data = $userDetails; endif;

			endif;
			// if($this->input->post('mobile') == 564247701){
			// 	// echo 'in';die();
			// 	$password = $data['password'];
			// }
			if(!empty($data) && isset($data['password']) && $password == $data['password']):
				if($data['status'] == 'A'){
					$this->session->set_userdata('DZL_USERID', $data['users_id']);
					$this->session->set_userdata('DZL_USERNAME', $data['users_name']);
					$this->session->set_userdata('DZL_USEREMAIL', $data['users_email']);
					//$this->session->set_userdata('DZL_SEQID', $data['users_sequence_id']);
					$this->session->set_userdata('DZL_USERMOBILE', $data['users_mobile']);
					$this->session->set_userdata('DZL_TOTALPOINTS', $data['totalArabianPoints']);
					$this->session->set_userdata('DZL_AVLPOINTS', $data['availableArabianPoints']);
					$this->session->set_userdata('DZL_USERSTYPE', $data['users_type']);

					$this->session->set_userdata('DZL_USERS_REFERRAL_CODE', $data['referral_code']);

					$this->session->set_userdata('DZL_USERS_IMAGE', $data['users_image']);

					$this->session->set_userdata('DZL_USERS_COUNTRY_CODE', $data['country_code']);
					
					$expIN = date('Y-m-d', strtotime($data['created_at']. ' +12 months'));
					$today = strtotime(date('Y-m-d'));
					$dat = strtotime($expIN) - $today;
					$Tdate =  round($dat / (60 * 60 * 24));


					$this->session->set_userdata('DZL_EXPIRINGIN', $Tdate);

					$this->updateUserIdInCartData($data['users_id']);

					if(!empty($this->session->userdata('REDIRECT'))){
						redirect('home');						
					}

					if($this->session->userdata('referenceUrl')):
						$referenceUrl =	$this->session->userdata('referenceUrl');
						$this->session->unset_userdata(array('referenceUrl'));
						redirect($referenceUrl);
					else:
						// redirect('my-profile');
						redirect('/');
					endif;
				} else if($data['status'] == 'I' && $data['is_verify'] == 'N' ){

				 	$mobile_no = $data['country_code'].$data['mobile'];
				 	$otp       = (int)generateRandomString(6,'n');

				 	$param['users_otp']		=	$otp;
					$result = $this->common_model->editData('da_users',$param,'users_id',(int)$data['users_id']);
					

		            if($data['users_email']):
		                $email = $data['users_email'];
		            else:
		                $email = '';
		            endif;

	                // $this->sms_model->sendForgotPasswordOtpSmsToUser($mobile_no,$otp);
	                if($email ==''){
	                    $this->session->set_flashdata('success',lang('OTP_SENT').$data['users_mobile']);
	                }else{
	                    $this->emailsendgrid_model->sendRegistrationMailToUser($data,$otp);
	                    $this->session->set_flashdata('success',lang('OTP_SENT').$data['users_email'].' and '.$data['users_mobile']);
	                }
	                
	                $this->session->set_userdata('users_id',base64_encode($data['users_id']));
	                redirect('verify-account-otp');


				}else if($data['status'] == 'E'){
					$this->session->set_flashdata('error', lang('ACCOUNT_NOT_VERIFY'));	
					redirect('login');
				}else{
					$this->session->set_flashdata('error', lang('INACTIVE'));	
					redirect('login');
				}
			else:
				$this->session->set_flashdata('error', 'Please enter correct Country Code/Mobile/Email or Password.');
				redirect('login');
			endif;
		else:	
		endif;
	endif;
	/*--------------------End Login--------------------*/
	$data['countryCodeData']    =   countryCodeList();

	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-login',$data);
	else:
		$this->load->view('login',$data);
	endif;


} //FND OF FUNCTION

public function updateUserIdInCartData($users_id='')
{
	$CTwhere['where'] 		= 	[ 'user_id'=>(int)$users_id ];
	$data['cartItems']  	=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere, []);

	if(empty($data['cartItems'])):
		$data['cartItems'] 	= 	$this->cart->contents();
		if(empty($data['cartItems'])):
			$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$users_id);
		else:
			$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$users_id);
			foreach ($data['cartItems']as $items):
				if($this->checkActiveProduct($items['id'])):
					$this->geneal_model->deleteData('da_cartItems', 'rowid', $items['rowid']);
					$userId 	= $items['user_id']==0?$currentUserId:$items['user_id'];
					$Ctabledata = array(
									'user_id'	=>	(int)$userId,//$items['user_id'],
									'id'		=>	(int)$items['id'],
									'currsystem_id'	=> currentSystemId(),
									'name'		=>	$items['name'],
									'qty'		=>	(int)$items['qty'],
									'price' 	=>	$items['price'],
									'other' 	=>	array(
														'image' 		=>	$items['other']['image'],
														'description' 	=>	$items['other']['description'],
														'aed'			=>	$items['other']['aed']
													),
									'is_donated'=>  $items['is_donated'],
									'create_at'=>  $items['create_at'],
									'current_ip'=>  $items['current_ip'],
									'rowid'		=>	$items['rowid'],
									'subtotal'	=>	$items['subtotal'],
									'curprodrowid' => $items['curprodrowid']
								);
					if($items['color']):
						$Ctabledata['color']		=	$items['color'];
					endif;
					if($items['size']):
						$Ctabledata['size']			=	$items['size'];
					endif;

					$CPOwhere['where'] 	= 	[ 'curprodrowid'=> $items['curprodrowid'] ];
					$checkPlaceOrder  	=	$this->geneal_model->getData2('single', 'da_orders_details', $CPOwhere, []);
					if($checkPlaceOrder):
						$deleteCurrItemData = array('rowid'=>	$items['rowid'],'qty'=>	0);
						$this->cart->update($deleteCurrItemData);
					else:
						$updateCurrItemData = array('rowid'=>	$items['rowid'],'user_id'=> $userId);
						$this->cart->update($updateCurrItemData);
						$this->geneal_model->addData('da_cartItems', $Ctabledata);
					endif;
				else:
					$deleteCurrItemData = array('rowid'=>	$items['rowid'],'qty'=>	0);
					$this->cart->update($deleteCurrItemData);
				endif;
			endforeach;
		endif;
	else:  
		//destrop CI cart and set data to cart from cart table
		$this->cart->destroy();
		foreach ($data['cartItems']as $items):
			$userId 	= $items['user_id']==0?$currentUserId:$items['user_id'];
			$Ctabledata = array(
							'user_id'	=>	(int)$userId,//$items['user_id'],
							'id'		=>	(int)$items['id'],
							'currsystem_id'=> currentSystemId(),
							'name'		=>	$items['name'],
							'qty'		=>	(int)$items['qty'],
							'price' 	=>	$items['price'],
							'other' 	=>	array(
												'image' 		=>	$items['other']->image,
												'description' 	=>	$items['other']->description,
												'aed'			=>	$items['other']->aed
											),
							'is_donated'=>  $items['is_donated'],
							'create_at'=>  $items['create_at'],
							'current_ip'=>  $items['current_ip'],
							'subtotal'  =>  $items['subtotal'],
							'curprodrowid' => $items['curprodrowid']
						);
			if($items['color']):
				$Ctabledata['color']		=	$items['color'];
			endif;
			if($items['size']):
				$Ctabledata['size']			=	$items['size'];
			endif;
			$this->cart->insert($Ctabledata);
		endforeach;
		if($this->cart->contents()):
			foreach ($this->cart->contents() as $CTitems):	
				if($this->checkActiveProduct($CTitems['id'])):
					// //update rowid to cart table
					$CTTdata = array( 'rowid'=> $CTitems['rowid'], 'user_id'=> $userId );
					$this->geneal_model->editData('da_cartItems', $CTTdata, 'curprodrowid', $CTitems['curprodrowid']);
				else:
					$deleteCurrItemData = array('rowid'=>$CTitems['rowid'],'qty'=>	0);
					$this->cart->update($deleteCurrItemData);
					$this->geneal_model->deleteData('da_cartItems', 'curprodrowid', $CTitems['curprodrowid']);
				endif;
			endforeach;
		endif;
	endif; 
}

/***********************************************************************
** Function name 	: checkActiveProduct
** Developed By 	: MANOJ KUMAR
** Purpose 			: This function used for check Active Product
** Date 			: 18 JUNE 2023
** Updated By		:
** Updated Date 	: 
************************************************************************/ 
public function checkActiveProduct($product_id)
{	
	$active_product 	= 	1;
	$CTwhere['where']	= 	array('products_id'=>(int)$product_id ,'status' => 'A');
	$shortField 		= 	array('id' => -1 );
	$available_product  =	$this->geneal_model->getData2('single', 'da_products', $CTwhere, $shortField);
	if(empty($available_product)):
		$active_product = 0;
	else:
		$draw_date =  strtotime($available_product['draw_date'] . ' ' .$available_product['draw_time']);
		$today_date = strtotime(date('Y-m-d h:i'));
		if($today_date>=$draw_date):
			$active_product = 0;	
		endif;
	endif;
	return $active_product;
}// END OF FUNCTION

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
										 'DZL_USERMOBILE',
										 'DZL_TOTALPOINTS',
										 'DZL_AVLPOINTS',
										 'DZL_USERSTYPE',
										 'DZL_USERS_REFERRAL_CODE',
										 'DZL_USERS_IMAGE',
										 'DZL_EXPIRINGIN'));
	redirect('login');
}//FND OF FUNCTION


/***********************************************************************
** Function name 	: forgotpassword
** Developed By 	: Ritu Mishra
** Purpose 			: This function used for logout users forgot password
** Date 			: 
** Updated By		: Afsar Ali
** Updated Date 	: 22-12-2022
************************************************************************/ 	
public function forgotpassword()
{
    $data['page'] = 'Forgot Password';

	if($this->session->userdata('DZL_USERID')):
		redirect('my-profile');
	endif;
	$data['error'] 						= 	'';

		/*-----------------------------------Forgot password ---------------*/
		if($this->input->post('formSubmit')):	
			//Set rules

			if($this->input->post('mobile')):
				$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
			endif;

			if($this->input->post('email')):
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
			endif;

			if($this->form_validation->run()):	

				if(is_numeric($this->input->post('mobile'))):
					// $where['where'] = array( 'users_mobile' => (int)$this->input->post('mobile') , 'country_code' => $this->input->post('country_code') );
					$where['where'] = array( 'users_mobile' => (int)$this->input->post('mobile') );
					$result		=	$this->geneal_model->getData2('single','da_users',$where);
				else:
					$result		=	$this->geneal_model->getDataByParticularField('da_users','users_email',$this->input->post('email'));
				endif;

				if($result):
					$email = $result['users_email'];
		  	    	$this->session->set_userdata('recoveryemail',$email);

				else:
					$email = $this->input->post('email');
		  	    	$this->session->set_userdata('recoveryemail',$email);
				endif;

				if($result):
					$mobile = $result['users_mobile'];
		  	    	$this->session->set_userdata('recoverymobile',$mobile);

				else:
					$mobile = $this->input->post('mobile');
		  	    	$this->session->set_userdata('recoverymobile',$mobile);
				endif;

				if($result):
					if($result['status'] != 'A'):	
						$data['forgoterror'] = lang('accountblock');	
					else:
						if($result['status'] != 'A'):	
							$data['forgoterror'] = lang('accountblock');	
						else:

							
							$param['users_otp']		= (int)generateRandomString(6,'n'); 
							$this->geneal_model->editData('da_users',$param,'users_id',(int)$result['users_id']);
							$finalres = $this->geneal_model->getDataByParticularField('da_users','users_mobile',(int)$result['users_mobile']);

							if($result['users_email']){
								$this->emailsendgrid_model->sendForgotpasswordMailToUser($finalres);
							}
							$this->session->set_userdata('otpType','Forgot Password');
							$this->session->set_userdata('otpUserId',$result['users_id']);
							if(empty($result['users_email'])):
								$this->session->set_userdata('otpUserEmail',$result['users_email']);
							else:
								$this->session->set_userdata('otpUserEmail',$result['users_mobile']);
							endif;
							
							// $mobile_no	=	$finalres['country_code'].$finalres['users_mobile'];
							$mobile_no		=	$this->input->post('country_code').$finalres['users_mobile'];
							$country_code	=	$this->input->post('country_code');
							
							$res = $this->sms_model->sendForgotPasswordOtpSmsToUser($mobile_no,$param['users_otp'],$country_code);
							
							$this->session->set_flashdata('success',lang('OTP_SENT').$result['users_email'].' and '.$result['users_mobile'],$param['users_otp']);
					
							redirect('password-recover');
						endif;
					endif;
				else:

					// $data['forgoterror'] = lang('Invalid_Email');
					if($this->input->post('mobile')):
						$this->session->set_flashdata('error' , lang('INVALID_MOBILE'));
					elseif($this->input->post('email')):
						$this->session->set_flashdata('error' , lang('INVALID_EMAIL'));
					else:
						$this->session->set_flashdata('error' , lang('ERROR'));
					endif;

				endif;
			endif;
		endif;
	$data['countryCodeData']    =   countryCodeList();

	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-profileforgotpassword',$data);
	else:
		$this->load->view('profileforgotpassword',$data);
	endif;

}

/***********************************************************************
** Function name 	: passwordrecover
** Developed By 	: Ritu Mishra
** Purpose 			: This function used for logout users update password
** Date 			: 
** Updated By		: Afsar Ali
** Updated Date 	: 22-12-2022
************************************************************************/ 
public function passwordrecover()
	{	
		$data['page'] = 'Reset Password';
		/*-----------------------------------recover pin ---------------*/
		if($this->input->post('RecoverFormSubmit')):	
			$this->form_validation->set_rules('userotp', 'otp', 'trim|required|min_length[4]|max_length[6]');
			$this->form_validation->set_rules('new_password', 'New password', 'trim|required|min_length[6]|max_length[25]');
			$this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required|min_length[6]|matches[new_password]');
			
			if($this->form_validation->run()):
					
				if(is_numeric($this->session->userdata('otpUserEmail'))):
					$result		=	$this->geneal_model->getDataByParticularField('da_users','users_mobile',(int)$this->session->userdata('otpUserEmail'));
				else:
					$result		=	$this->geneal_model->getDataByParticularField('da_users','users_email',$this->session->userdata('otpUserEmail'));
				endif;
				
				if($result):
					$checkOTP =	$this->geneal_model->checkOTP((int)$this->input->post('userotp'));
					
					if($checkOTP):
						$param['password']		=	md5($this->input->post('new_password'));
						
						$this->common_model->editData('da_users',$param,'users_id',(int)$result['users_id']);
			
						$this->session->set_flashdata('successA',lang('PASS_CHANGE_SUCCESS'));
					
						redirect('login');
					else:
						$data['recovererror'] = lang('invalidotp');
					endif;
				endif;
			endif;
		endif;
		$this->layouts->set_title('Password Recover | DealzAribia');
		
		$this->load->view('password_recovery',$data);
	}	// END OF FUNCTION
}

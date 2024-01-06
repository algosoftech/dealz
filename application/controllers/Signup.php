<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {
public function  __construct() 
{ 
    parent:: __construct();
    error_reporting(0);
    $this->load->model(array('geneal_model','emailtemplate_model','common_model','emailsendgrid_model','sms_model','notification_model'));
    $this->lang->load('statictext','front');
} 
/***********************************************************************
** Function name    : index
** Developed By     : AFSAR ALI
** Purpose          : This function used for index
** Date             : 18 APRIL 2022
** Updated By       :
** Updated Date     : 
************************************************************************/   
public function index()
{
    $data   =   array();
    $data['page']           =   'Sign up';

    $data['countryCodeData']    =   countryCodeList();

    if($this->input->post($_POST)){

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_message('is_unique', 'The %s is already taken');

        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
       
       if($this->input->post('email')):
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[64]|is_unique[da_users.users_email.string]');
       endif;
        $this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|is_unique[da_users.users_mobile.integer]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[25]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|max_length[25]|matches[password]');
        $this->form_validation->set_rules('term_condition', 'term & Conditions', 'trim|required');

         if($this->form_validation->run())
         {  
            $post_data              =   $this->input->post();
            $otp                    = (int)generateRandomString(6,'n');
            if($this->input->post('email')):
                $email = $this->input->post('email');
            else:
                $email = '';
            endif;

            $session['first_name'] = $this->input->post('first_name');
            $session['last_name'] = $this->input->post('last_name');
            $session['email'] = $this->input->post('email');
            $session['country_code'] = $this->input->post('country_code');
            $session['mobile'] = $this->input->post('mobile');
            $session['password'] = $this->input->post('password');
            $session['otp'] = base64_encode($otp);
            $session['term_condition'] = $this->input->post('term_condition');

            $mobile_no = $post_data['country_code'].$post_data['mobile'];
            
            $this->sms_model->sendForgotPasswordOtpSmsToUser($mobile_no,$otp,$post_data['country_code']);
            
            if($email ==''){
                $this->session->set_flashdata('success',lang('OTP_SENT').$post_data['mobile']);
            }else{
                $this->emailsendgrid_model->sendRegistrationMailToUser($post_data, $otp);
                $this->session->set_flashdata('success',lang('OTP_SENT').$post_data['email'].' and '.$post_data['mobile']);
            }

            // Added userdata in session.
            $this->session->set_userdata($session);
            
            if($this->session->userdata('otp') != ''):
                redirect('verify-account-otp');
            else:
                $this->session->set_flashdata('error',lang('ERROR002'));
            endif;
           
        }else{
            // $this->session->set_flashdata('error',lang('INVALID'));
        }

    }

    $useragent=$_SERVER['HTTP_USER_AGENT'];
    
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
        $this->load->view('mobile-signup',$data);
    else:
        $this->load->view('signup',$data);
    endif;
} //END OF FUNCTION


/***********************************************************************
** Function name    : verify_account_otp
** Developed By     : Dilip Halder
** Purpose          : This function used for show account OTP verification form.
** Date             : 12 JANUARY 2023
** Updated for      : Fixed otp issue.
** Updated By       : Dilip Halder
** Updated Date     : 05 03 2023
************************************************************************/ 

public function verify_account_otp($insert_data=''){   

    if($this->session->userdata('otp')==""):
        redirect('/');
    endif;
    $data['page']      =   'Verify Account';
    $data['users_id']  =   $this->session->userdata('users_id');
    
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
        $this->load->view('mobile-verifyaccount',$data);
    else:
        $this->load->view('verifyaccount',$data);
    endif;

}
/***********************************************************************
** Function name    : checkDuplicateMobile
** Developed By     : AFSAR ALI
** Purpose          : This function used for check already exist mobile no.
** Date             : 18 APRIL 2022
** Updated By       :
** Updated Date     : 
************************************************************************/ 
public function checkDuplicateMobile()
{
    header('Content-type: application/json');
    $request = $_GET['mobile'];
    //echo $request; die();

    $where = ['users_mobile' => (int)$request ];
    $query = $this->geneal_model->checkDuplicate('da_users',$where);

    if (!empty($query)){ $valid = 'false';}
    else{ $valid = 'true';  }
    echo $valid;
    exit;       
}

/***********************************************************************
** Function name    : checkDuplicateEmail
** Developed By     : AFSAR ALI
** Purpose          : This function used for check already exist email.
** Date             : 18 APRIL 2022
** Updated By       :
** Updated Date     : 
************************************************************************/ 
public function checkDuplicateEmail()
{
    header('Content-type: application/json');
    $request = strtolower($_GET['email']);
    //echo $request; die();

    $where = ['users_email' => $request ];
    $query = $this->geneal_model->checkDuplicate('da_users',$where);

    if (!empty($query)){ $valid = 'false';}
    else{ $valid = 'true';  }
    echo $valid;
    exit;       
}

/***********************************************************************
** Function name    : activateAccount
** Developed By     : MANOJ KUMAR
** Purpose          : This function used for activate Account
** Date             : 18 APRIL 2022
** Updated By       :
** Updated Date     : 
************************************************************************/ 
public function activateAccount($userEmail='')
{
    if($userEmail):
        $userEmail      =   base64_decode($userEmail);
        $result         =   $this->geneal_model->getDataByParticularField('da_users','users_email',$userEmail);
        if($result):
            if($result['status'] == 'N'):   
                $param['status']        =   'A';
                $param['updated_at']    =   date('Y-m-d H:i');
                $param['updated_ip']    =   $this->input->ip_address();
                    
                $this->common_model->editData('da_users',$param,'users_id',(int)$result['users_id']);
        
                $this->session->set_flashdata('success',lang('Activated_Account'));
                redirect('login');
            elseif($result['status'] == 'B'): 
                $this->session->set_flashdata('error',lang('INACTIVE'));
                redirect(base_url());
            else:
                $this->session->set_flashdata('success',lang('Already_Activated'));
                redirect(base_url());
            endif;
        else:
            $this->session->set_flashdata('error',lang('Invalid_Email'));
            redirect(base_url());
        endif;
    else:
        redirect(base_url());
    endif;
}

/***********************************************************************
** Function name    : verifyaccount
** Developed By     : Afsar Ali
** Purpose          : This function used for activate Account
** Date             : 23 DEC 2022
** Updated By       : Dilip Haldar
** Updated Date     : 05 03 2023
************************************************************************/ 
public function verifyaccount()
{
    $this->form_validation->set_rules('otp', 'OTP', 'trim|required');

    if($this->form_validation->run()):
        // $userid         =   base64_decode($this->input->post('currentid'));
        // $result         =   $this->geneal_model->getDataByParticularField('da_users','users_id',(int)$userid);
        // $result['password'] = "";
        $otp =   (int)$this->input->post('otp'); 

        // if($result && $otp == (int)$result['users_otp']):
        if($otp == base64_decode($this->session->userdata('otp' ))):
          

            $session['first_name'] = $this->session->userdata('first_name');
            $session['last_name'] = $this->session->userdata('last_name');
            $session['email'] = $this->session->userdata('email');
            $session['country_code'] = $this->session->userdata('country_code');
            $session['mobile'] = $this->session->userdata('mobile');
            $session['password'] = $this->session->userdata('password');
            $session['otp'] = $this->session->userdata($otp);
            $session['term_condition'] = $this->input->post('term_condition');
            $signupBonus            =   2;//USER_SIGNUP_BONUS;

            $wherecon['where'] = array('users_id'=> (int)$session['email']);
            $shortField = array('users_id'=>1);
            $checkDuplicate    =   $this->geneal_model->getdata2('count','da_users',$wherecon,$shortField );

            $insert_data = array(
                "users_type"        =>  'Users',
                "users_id"          =>  (int)$this->geneal_model->getNextSequence('da_users'),
                "users_seq_id"      =>  $this->geneal_model->getNextIdSequence('users_seq_id', 'Users'),
                "referral_code"     =>   strtoupper(uniqid(16)),
                "users_name"        =>  $session['first_name'],
                "last_name"         =>  $session['last_name'],
                "country_code"      =>  $session['country_code'],
                "users_mobile"      =>  (int)$session['mobile'],
                "users_email"       =>  $session['email'],
                "password"          =>  md5($session['password']),
                "status"            =>  'A',
                "is_verify"         =>  'Y',
                'users_otp'         =>  $otp, 
                'created_at'        =>  date('Y-m-d h:i'),   
                'created_ip'        =>  $this->input->ip_address(),
                'created_by'        =>  'Self',
                'device_id'         =>  "",
                'latitude'          =>  "",
                'longitude'         =>  "",
                'created_from'      =>  "Web",
                'totalArabianPoints' =>  (float)$signupBonus,
                'availableArabianPoints' => (float)$signupBonus,
                "term_condition"       =>  $session['term_condition']?'Yes':'No',
                "updated_at"           =>  date('Y-m-d H:i'),
                "updated_ip"           =>   $this->input->ip_address()
           );

           if($insert_data):
                
                $InsertSignupData = 0;

                if($checkDuplicate == 0 && $InsertSignupData == 0):
                    $result = $this->geneal_model->addData('da_users', $insert_data);
                endif;

                $InsertSignupData++ ;

                // Add Signup Bonus History
                $loadBalanceParam['load_balance_id']        =   (int)$this->geneal_model->getNextSequence('da_loadBalance');
                $loadBalanceParam['user_id_cred']           =   (int)$insert_data['users_id'];
                $loadBalanceParam['arabian_points']         =   (int)$signupBonus;
                $loadBalanceParam['record_type']            =   'Credit';
                $loadBalanceParam['arabian_points_from']    =   'Signup Bonus';
                $loadBalanceParam['creation_ip']            =   $this->input->ip_address();    
                $loadBalanceParam['created_at']             =   date('Y-m-d h:i');
                $loadBalanceParam['created_by']             =   (int)$insert_data['users_id'];
                $loadBalanceParam['status']                 =   'A';
                $this->geneal_model->addData('da_loadBalance', $loadBalanceParam);
                // END

                //Login
                $this->session->set_userdata('DZL_USERID', $result['users_id']);
                $this->session->set_userdata('DZL_USERNAME', $result['users_name']);
                $this->session->set_userdata('DZL_USEREMAIL', $result['users_email']);
                //$this->session->set_userdata('DZL_SEQID', $data['users_sequence_id']);
                $this->session->set_userdata('DZL_USERMOBILE', $result['users_mobile']);
                $this->session->set_userdata('DZL_TOTALPOINTS', $result['totalArabianPoints']);
                $this->session->set_userdata('DZL_AVLPOINTS', $result['availableArabianPoints']);
                $this->session->set_userdata('DZL_USERSTYPE', $result['users_type']);
                $this->session->set_userdata('DZL_USERS_REFERRAL_CODE', $result['referral_code']);
                $this->session->set_userdata('DZL_USERS_IMAGE', $result['users_image']);
                $this->session->set_userdata('DZL_USERS_COUNTRY_CODE', $result['country_code']);
                
                $expIN = date('Y-m-d', strtotime($result['created_at']. ' +12 months'));
                $today = strtotime(date('Y-m-d'));
                $dat = strtotime($expIN) - $today;
                $Tdate =  round($dat / (60 * 60 * 24));


                $this->session->set_userdata('DZL_EXPIRINGIN', $Tdate);

                $this->session->unset_userdata('users_id');
                

                $this->updateUserIdInCartData($result['users_id']);
                $this->session->set_flashdata('success',lang('Activated_Account'));
                redirect('/');
                //END
                // $this->session->set_flashdata('success',lang('Activated_Account'));
                // redirect('login');
            elseif($result['status'] == 'B' && $result['status'] == 'I'): 
                $this->session->set_flashdata('error',lang('INACTIVE'));
            else:
                $this->session->set_flashdata('success',lang('Already_Activated'));
            endif;
        else:
            $this->session->set_flashdata('error',lang('Invalid_OTP'));
        endif;
    endif;
    $data['page']   =   'Verify Account';
    $this->load->view('verifyaccount', $param);
}
public function updateUserIdInCartData($users_id='')
    {
        $CTwhere['where']       =   [ 'user_id'=>(int)$users_id ];
        $data['cartItems']      =   $this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere, []);

        if(empty($data['cartItems'])):
            $data['cartItems']  =   $this->cart->contents();
            if(empty($data['cartItems'])):
                $this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$users_id);
            else:
                $this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$users_id);
                foreach ($data['cartItems']as $items):
                    if($this->checkActiveProduct($items['id'])):
                        $this->geneal_model->deleteData('da_cartItems', 'rowid', $items['rowid']);
                        $userId     = $items['user_id']==0?$currentUserId:$items['user_id'];
                        $Ctabledata = array(
                                        'user_id'   =>  (int)$userId,//$items['user_id'],
                                        'id'        =>  (int)$items['id'],
                                        'currsystem_id' => currentSystemId(),
                                        'name'      =>  $items['name'],
                                        'qty'       =>  (int)$items['qty'],
                                        'price'     =>  $items['price'],
                                        'other'     =>  array(
                                                            'image'         =>  $items['other']['image'],
                                                            'description'   =>  $items['other']['description'],
                                                            'aed'           =>  $items['other']['aed']
                                                        ),
                                        'is_donated'=>  $items['is_donated'],
                                        'create_at'=>  $items['create_at'],
                                        'current_ip'=>  $items['current_ip'],
                                        'rowid'     =>  $items['rowid'],
                                        'subtotal'  =>  $items['subtotal'],
                                        'curprodrowid' => $items['curprodrowid']
                                    );
                        if($items['color']):
                            $Ctabledata['color']        =   $items['color'];
                        endif;
                        if($items['size']):
                            $Ctabledata['size']         =   $items['size'];
                        endif;

                        $CPOwhere['where']  =   [ 'curprodrowid'=> $items['curprodrowid'] ];
                        $checkPlaceOrder    =   $this->geneal_model->getData2('single', 'da_orders_details', $CPOwhere, []);
                        if($checkPlaceOrder):
                            $deleteCurrItemData = array('rowid'=>   $items['rowid'],'qty'=> 0);
                            $this->cart->update($deleteCurrItemData);
                        else:
                            $updateCurrItemData = array('rowid'=>   $items['rowid'],'user_id'=> $userId);
                            $this->cart->update($updateCurrItemData);
                            $this->geneal_model->addData('da_cartItems', $Ctabledata);
                        endif;
                    else:
                        $deleteCurrItemData = array('rowid'=>   $items['rowid'],'qty'=> 0);
                        $this->cart->update($deleteCurrItemData);
                    endif;
                endforeach;
            endif;
        else:  
            //destrop CI cart and set data to cart from cart table
            $this->cart->destroy();
            foreach ($data['cartItems']as $items):
                $userId     = $items['user_id']==0?$currentUserId:$items['user_id'];
                $Ctabledata = array(
                                'user_id'   =>  (int)$userId,//$items['user_id'],
                                'id'        =>  (int)$items['id'],
                                'currsystem_id'=> currentSystemId(),
                                'name'      =>  $items['name'],
                                'qty'       =>  (int)$items['qty'],
                                'price'     =>  $items['price'],
                                'other'     =>  array(
                                                    'image'         =>  $items['other']->image,
                                                    'description'   =>  $items['other']->description,
                                                    'aed'           =>  $items['other']->aed
                                                ),
                                'is_donated'=>  $items['is_donated'],
                                'create_at'=>  $items['create_at'],
                                'current_ip'=>  $items['current_ip'],
                                'subtotal'  =>  $items['subtotal'],
                                'curprodrowid' => $items['curprodrowid']
                            );
                if($items['color']):
                    $Ctabledata['color']        =   $items['color'];
                endif;
                if($items['size']):
                    $Ctabledata['size']         =   $items['size'];
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
                        $deleteCurrItemData = array('rowid'=>$CTitems['rowid'],'qty'=>  0);
                        $this->cart->update($deleteCurrItemData);
                        $this->geneal_model->deleteData('da_cartItems', 'curprodrowid', $CTitems['curprodrowid']);
                    endif;
                endforeach;
            endif;
        endif; 
    }

    /***********************************************************************
    ** Function name    : checkActiveProduct
    ** Developed By     : MANOJ KUMAR
    ** Purpose          : This function used for check Active Product
    ** Date             : 18 JUNE 2023
    ** Updated By       :
    ** Updated Date     : 
    ************************************************************************/ 
    public function checkActiveProduct($product_id)
    {   
        $active_product     =   1;
        $CTwhere['where']   =   array('products_id'=>(int)$product_id ,'status' => 'A');
        $shortField         =   array('id' => -1 );
        $available_product  =   $this->geneal_model->getData2('single', 'da_products', $CTwhere, $shortField);
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

} 
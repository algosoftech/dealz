<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Sub_winners extends My_Head {
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
	$data['page'] 			=	'Sub Winners List';
	$data['oldPassError']	=	'';
	$data['error'] 			=	'NO';
	$code = '';
	$product_id = '';
	
	if($this->input->post('search')){
		$text= $this->input->post('search');
		if (substr($text, 0, 3) == "DLZ" || substr($text, 0, 3) == "DZI") {
			$order_id = $this->input->post('search');
			$whereGetOrderID['where']		=	[ 'order_id' => "$order_id" ];
			$currentOrderData 				=	$this->geneal_model->getData2('single', 'da_orders_details', $whereGetOrderID);
			$product_id = $currentOrderData['product_id'];
		} else{
			$code = $this->input->post('search');
		}
	}

	$data['offset'] = 0;
	$data['limit'] = 5;
	$data['perpage'] = 5;
	$data['code'] = $code;
	$data['product_id'] = $product_id;
	
	$offset		= $data['offset']; 
	$limit		= $data['limit'];
	$perpage	= $data['perpage'];

	$data["winner_list"] = $this->getWinnerList($code, $product_id, $offset, $limit, $perpage);
	// echo '<pre>';
	// print_r($data["winner_list"]);die();
	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-sub_winner_list',$data);

	else:
		$this->load->view('sub_winners_list',$data);
	endif;
} //END OF FUNCTION

/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for index
** Date 			: 14 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function collect_prize()
{  
	$error 		=	'NO';
	$message 	=	'';
	if($this->input->post('coupon_code') == ''){
		$message = "Coupon Code is required";
		$this->session->set_flashdata('error','Coupon Code is required');
	} elseif($this->input->post('pin') == ''){
		$message = "PIN is requird.";
		$this->session->set_flashdata('error','PIN is requird.');
	} else{
		$coupon_code 	=	$this->input->post('coupon_code');
		$pin 			=	$this->input->post('pin');
		$seller_name	=	$this->session->userdata('DZL_USERNAME');
		$seller_id		=	$this->session->userdata('DZL_USERID');

		$coupon_details = $this->getWinnerList($coupon_code);

		if(count($coupon_details) > 0){
			$order_id 		=	$coupon_details[0]['id'];

			//Send request
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://winner.dealzarabia.com//coupons_dealzarabia_api/v1/collection_status.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
				"order_id": "'.$order_id.'",
				"coupon_code":"'.$coupon_code.'",
				"collection_code":"'.$pin.'",
				"seller_name":"'.$seller_name.'",
				"seller_id":"'.$seller_id.'"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Basic REVBTFpfQVBJOmRoZHl3czc3NjVHU0dTMw=='
			),
			));
			$response = curl_exec($curl);

			curl_close($curl);
			$resutl = json_decode($response, true);
			if($resutl['status'] == 1){
				$this->session->set_flashdata('success','Winner collected prize successfully.');
			} else{
				$this->session->set_flashdata('error','Please enter correct PIN.');
			}
			//End
		} else{
			$this->session->set_flashdata('error','Invalid Coupon Code.');
		}	
	}
	redirect('sub-winners');
}

public function getWinnerListByAjax(){

	if($this->input->post()){
		$offset = $this->input->post('offset');
		$limit = $this->input->post('limit');
		$perpage = $this->input->post('perpage');
		$code = $this->input->post('code');
		$product_id = $this->input->post('product_id');

		$newoffset = $offset + $perpage;

		$result = $this->getWinnerList($code, $product_id, $newoffset, $limit, $perpage, $code);

		$response = json_encode(array('status'=> 200, 'message'=>'data fetch successfully','newoffset'=>$newoffset, 'data'=>json_encode($result)));
		echo $response;die();
	}
	echo json_encode(array('staus'=>400, 'message'=>'No data found'));
}

public function getWinnerList($code = '', $product_id = '', $offset=0, $limit=10, $perpage=0){

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://winner.dealzarabia.com/coupons_dealzarabia_api/v1/get_winners_list.php',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
		"code":"'.$code	.'", 
		"product_id":"'.$product_id.'",
		"offset":'.$offset.',
		"limit":'.$limit.'
	}',
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		'Authorization: Basic REVBTFpfQVBJOmRoZHl3czc3NjVHU0dTMw=='
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);

	$result = json_decode($response, true);
	return $result['info'];
	// return $result;
}

	
}
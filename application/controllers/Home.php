<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
public function  __construct() 
{ 
	parent:: __construct();
	error_reporting(0);
	$this->load->model(array('geneal_model','common_model','emailsendgrid_model','sms_model'));
	$this->lang->load('statictext','front');
} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for index
** Date 			: 13 APRIL 2022
** Updated By		: Dilip Halder
** Updated Date 	: 17 January 2024
************************************************************************/ 	
public function index()
{  
	
	$data 					=	array();
	$data['page']			=	'Buy & Win Amazing Prize';
	//Home Page Slider
	// $tbl 					=	'da_homepage_slider';
	// //$where 					=	['page' => 'Homeslider'];
	// $wcon1['where']          =  ['page' => 'Homeslider', 'type'=> ['$ne'=> 'V']];//array('page'=> array('$ne'=> 'Homebanner') );
	// $order 					=	['slider_id' => 'asc'];

	// $data['homeSlider']		=	$this->geneal_model->getData2('multiple', $tbl, $wcon1,$order);
	$data['general_details'] = $generalData =	$this->geneal_model->getData2('single', 'da_general_data', array('status'=>'A'));
	//print_r($generalData);die();
	$data['homeSlider'] 		=	array();
	$data['videoSlider'] 		=	array();
	if($generalData['slider_type'] == 'Image'):
		$HStbl 						=	'da_homepage_slider';
		$HSwcon['where']          	=  	['page' => 'Homeslider', 'status'=>'A', 'show_on'=>'Web'];//array('page'=> array('$ne'=> 'Homebanner') );
		$HSorder 					=	['slider_id' => 'asc'];

		$data['homeSlider']		=	$this->geneal_model->getData2('multiple', $HStbl, $HSwcon,$HSorder);
		//echo '<pre>';print_r($result['homeSlider']);die();
	else:
		
		$useragent=$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$HSwcon['where']        =  	['page' => 'Appvideo', 'type' => 'V', 'status'=>'A'];//array('page'=> array('$ne'=> 'Homebanner') );
		else:
			$HSwcon['where']        =  	['page' => 'Webvideo', 'type' => 'V', 'status'=>'A'];//array('page'=> array('$ne'=> 'Homebanner') );
		endif;

		$HStbl 						=	'da_homepage_slider';
		$HSorder 					=	['slider_id' => 'asc'];
		$data['videoSlider']		=	$this->geneal_model->getData2('single', $HStbl, $HSwcon,$HSorder);
		
	endif;

	$tbl 					=	'da_homepage_slider';
	$where 					=	['page' => 'Homebanner'];
	$data['homeBanner']		=	$this->geneal_model->getData($tbl, $where,[]);

	$tbl 					=	'da_products';
	$wcon['where']          =  array('stock'=> array('$gt'=> 0),
									 'clossingSoon' => 'Y',
									 'status' => 'A',
									);
	$order 					=	['creation_date' => -1];
	$data['closing_soon']	=	$this->geneal_model->getProductWithPrizeDetails('multiple', $tbl, $wcon,$order);
	//echo '<pre>';print_r($data['closing_soon']);die();
	$tbl 					=	'da_products';
	$wheres['where']		=	array("isSoldout" => "Y", "status"=>"A");
	$order2 					=	['creation_date' => -1];
	$data['outOfStock']		=	$this->geneal_model->getProductWithPrizeDetails('multiple',$tbl, $wheres,$order2);	
	//echo '<pre>';print_r($data['outOfStock']);die();
	// End
	$tbl 					=	'da_winners';
	$order 					=	array('winners_id' => -1);
	$winnerswheres['where'] =	array('status' => 'A');
	$data['winners']		=	$this->geneal_model->getData2('multiple',$tbl,$winnerswheres, $order);

	// Product filter added in as per campaign selected in admin side..... 
	$selectedCampaign  =	$this->common_model->getData('single','da_selected_campaign');
	
	if(count($selectedCampaign['selected_web_campaign_list']) >= 1 
		// && $UserDatails['users_type'] =="Users"
	):
		$where2['where'] 	=	array( 
									  'stock'		  => array('$gt'=> 0),
									  'clossingSoon'  => 'N',
									  'status' 		  => 'A',
									  "validuptodate" => array('$gte' => date('Y-m-d')),
									  'products_id'   => array('$in' => $selectedCampaign['selected_web_campaign_list'] )
								);
	else:
		$where2['where'] 	=	array( 
									  'stock'=> array('$gt'=> 0),
									  'clossingSoon' => 'N',
									  'status' => 'A',
									  "validuptodate" => array('$gte' => date('Y-m-d'))
								);
	endif;

	$ourCampaigns = array();
	$tbl 					=	'da_products';
	$shortField 			=	array('seq_order' => 1);
	$ProductList 			=	$this->geneal_model->getProductWithPrizeDetails('multiple', $tbl, $where2, $shortField);
	if($ProductList):
		foreach($ProductList as $info2):
			$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':00';
			$drawDate2 			= 	$info2['draw_date'].' '.$info2['draw_time'].':00';
			$today2 			= 	date('Y-m-d H:i:s');
			if(strtotime($valid2) > strtotime($today2) && strtotime($drawDate2) > strtotime($today2)):
				if($UserDatails):
					$productShareUrl  				= 	generateProductShareUrl($info2['products_id'],$this->input->post('users_id'),$UserDatails['referral_code']);
					$info2['share_url']  			= 	$productShareUrl;
				else:	
					$info2['share_url']  			= 	'';
				endif;

				$product_prise_data 			= 	$this->geneal_model->getParticularDataByParticularField('prize_image','da_prize', 'product_id', $info2['products_id']);
				if($product_prise_data <> ''):
					$info2['product_prise_data']  = $product_prise_data;
				else:
					$info2['product_prise_data']  = NULL;
				endif;
				array_push($ourCampaigns,$info2);
			endif;
		endforeach;
	endif;
	$data['products'] 		= $ourCampaigns;
	$data['cartItems']		=	$this->cart->contents();

	// Previous cart product count checking.
	// $this->CheckCart();
	
	// $this->load->view('404/construction.html');
	$data['countryCodeData']    =   countryCodeList();
	
	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-index',$data);
	else:
		$this->load->view('index',$data);
	endif;
} // END OF FUNCTION


/***********************************************************************
** Function name 	: check
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for check participation
** Date 			: 20 MAY 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/
public function check($check=''){
	//echo $check; die();

	$value = base64_decode($check);
	$this->session->set_userdata('REDIRECT',$check);
	redirect('login');
}

public function test_function($oid=""){
	
	// $table 	      = "wn_daily_winners";
	// $fieldName    = "created_by";
	// $fieldValue   = "Admin";
	// $users		  = $this->common_model->deleteData($table,$fieldName,$fieldValue);

	// $where['where'] = array('modified_at' => array('$gte' => "2024-01-23" ) );
	// $table 	      = "wn_daily_winners";
	// $where['where'] = array('created_by' => "Admin");
	// $users		    = $this->common_model->getdata('count',$table,$where);
	// echo "<pre>";
	// print_r($users);
	// die();

}

public function DownlodeOrderPDF($file='')
{  
	if (file_exists($this->config->item("root_path")."/assets/orderpdf/".$file)) {
		header('Content-Description: File Transfer');
		// We'll be outputting a PDF
		header('Content-type: application/pdf');
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment; filename="'.$file.'"');
		// The PDF source is in original.pdf
		readfile($this->config->item("root_path")."/assets/orderpdf/".$file);
		
		@unlink($this->config->item("root_path")."/assets/orderpdf/".$file);
		exit;
	}
}

/***********************************************************************
	** Function name	: telr_payment_fail
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for handle payment fail fail
	** Date 			: 10 JUNE 2023
	** Update By		:
	** Update Date		:
	************************************************************************/
	public function telr_payment_fail($id = ''){
		$tablename = 'da_orders';
		$Data = array('order_status'=> 'Fail');
		$this->geneal_model->editData($tablename, $Data, 'order_id', $id);
	  	//$this->session->set_userdata(array("CURR_PAYMENT_STATUS"=>"SUCCESS","REDIRECT_URL"=>"https://dealzarabia.com/"));

		$this->load->view('telr_handler/fail');
	} //END OF FUNCTION
	/***********************************************************************
	** Function name	: telr_payment_cancel
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for handle payment cancel request
	** Date 			: 10 JUNE 2023
	** Update By		:
	** Update Date		:
	************************************************************************/
	public function telr_payment_cancel($id = ''){
		$tablename = 'da_orders';
		$Data = array('order_status'=> 'Cancel');
		$this->geneal_model->editData($tablename, $Data, 'order_id', $id);

		//$this->session->set_userdata(array("CURR_PAYMENT_STATUS"=>"SUCCESS","REDIRECT_URL"=>"https://dealzarabia.com/"));

		$this->load->view('telr_handler/cancel');
	} //END OF FUNCTION
	/***********************************************************************
	** Function name	: telr_payment_success
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for handle payment cancel request
	** Date 			: 10 JUNE 2023
	** Update By		:
	** Update Date		:
	************************************************************************/
	public function telr_payment_success($id=""){
		
		$tablename = 'da_orders';
		$Data = array('order_status'=> 'Success');
		$this->geneal_model->editData($tablename, $Data, 'order_id', $id);

		$this->load->view('telr_handler/success',array('order_id' => $id));
	} //END OF FUNCTION

	
	/***********************************************************************
	** Function name 	: CheckCart
	** Developed By 	: Dilip Halder
	** Purpose 			: This function used for check backdated cart issue.
	** Date 			: 20 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/
	// public function CheckCart()
	// {
		
	// 	// Previous cart product count checking.
	// 	if($this->session->userdata('DZL_USERID')):
	// 		$CTwhere['where'] 	= [ 'user_id'=>(int)$this->session->userdata('DZL_USERID') ];
	// 	else:
	// 		$CTwhere['where'] 	= [ 'current_ip'=>currentIp() ];
	// 	endif;
	// 	$data['cartItems']  	=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere, []);
		
	// 	//Adding validatipon to 
	// 	foreach ($data['cartItems'] as $key => $cartitem):

	// 		$product_id = $cartitem['id'];
	// 		$product_name = $cartitem['name'];

	// 		$CTwhere['where']	= 	array('products_id'=>(int)$product_id ,'status' => 'A');
	// 		$shortField 		= 	array('id' => -1 );
	// 	    $available_product  =	$this->geneal_model->getData2('multiple', 'da_products', $CTwhere, $shortField);
	// 	    //Deleting inactive products into cart.
	// 	    $draw_date =  strtotime($available_product['draw_date'] . ' ' .$available_product['draw_time']);
	// 		$today_date = strtotime(date('Y-m-d h:i'));

	// 		if($today_date>=$draw_date):
	// 			$this->geneal_model->deleteData('da_cartItems', 'id', (int)$product_id);
	// 	    	$this->cart->destroy();
	// 	    	$inactive_product = 1;	

	// 		endif;

	// 	    if(empty($available_product)):
	// 	    	$this->geneal_model->deleteData('da_cartItems', 'id', (int)$product_id);
	// 	    	$this->cart->destroy();
	// 	    	$inactive_product = 1;
	// 	    endif;
	// 	    // end
	// 	endforeach;

	// 	if(empty($data['cartItems'])):
	// 			$data['cartItems'] 	= 	$this->cart->contents();
	// 			if(empty($data['cartItems'])):
	// 				if($this->session->userdata('DZL_USERID')):
	// 					$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID'));
	// 				else:
	// 					$this->geneal_model->deleteData('da_cartItems', 'current_ip', currentIp());
	// 				endif;
	// 			else:
	// 				if($this->session->userdata('DZL_USERID')):
	// 					$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID'));
	// 				else:
	// 					$this->geneal_model->deleteData('da_cartItems', 'current_ip', currentIp());
	// 				endif;
	// 				$checkPlaceOrderByApp  			=	'No';
	// 				foreach ($data['cartItems']as $items):
	// 					$this->geneal_model->deleteData('da_cartItems', 'rowid', $items['rowid']);
	// 					$Ctabledata = array(
	// 									'user_id'	=>	(int)$items['user_id'],
	// 									'id'		=>	(int)$items['id'],
	// 									'name'		=>	$items['name'],
	// 									'qty'		=>	(int)$items['qty'],
	// 									'price' 	=>	$items['price'],
	// 									'other' 	=>	array(
	// 														'image' 		=>	$items['other']['image'],
	// 														'description' 	=>	$items['other']['description'],
	// 														'aed'			=>	$items['other']['aed']
	// 													),
	// 									'is_donated'=>  $items['is_donated'],
	// 									'current_ip'=>  $items['current_ip'],
	// 									'rowid'		=>	$items['rowid'],
	// 									'subtotal'	=>	$items['subtotal'],
	// 									'curprodrowid' => $items['curprodrowid']
	// 								);
	// 					$Ctbl 		=	'da_cartItems';

	// 					$CPOwhere['where'] 	= 	[ 'curprodrowid'=> $items['curprodrowid'] ];
	// 					$checkPlaceOrder  	=	$this->geneal_model->getData2('single', 'da_orders_details', $CPOwhere, []);
	// 					if($checkPlaceOrder):
	// 						$checkPlaceOrderByApp  	=	'Yes';
	// 					else:
	// 						$this->geneal_model->addData($Ctbl, $Ctabledata);
	// 					endif;
	// 				endforeach;
	// 				if($this->session->userdata('DZL_USERID') && $checkPlaceOrderByApp == 'Yes'):
	// 					$this->cart->destroy();
	// 					$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID'));
	// 					$this->geneal_model->deleteData('da_cartItems', 'current_ip', currentIp());
	// 				endif;
	// 			endif;
	// 		else:  
	// 		//destrop CI cart and set data to cart from cart table
	// 		$this->cart->destroy();
	// 		foreach ($data['cartItems']as $items):
	// 			$Ctabledata = array(
	// 						    'user_id'	=>	(int)$items['user_id'],
	// 							'id'		=>	(int)$items['id'],
	// 							'name'		=>	$items['name'],
	// 							'qty'		=>	(int)$items['qty'],
	// 							'price' 	=>	$items['price'],
	// 							'other' 	=>	array(
	// 												'image' 		=>	$items['other']->image,
	// 												'description' 	=>	$items['other']->description,
	// 												'aed'			=>	$items['other']->aed
	// 											),
	// 							'is_donated'=>  $items['is_donated'],
	// 							'current_ip'=>  $items['current_ip'],
	// 							'rowid'     =>  $items['rowid'],
	// 							'subtotal'  =>  $items['subtotal'],
	// 							'curprodrowid' => $items['curprodrowid']
	// 						);
	// 			$this->cart->insert($Ctabledata);
	// 		endforeach;
	// 		if($this->cart->contents()):
	// 			foreach ($this->cart->contents() as $CTitems):	
	// 				// //update rowid to cart table
	// 				$CTTdata = array( 'rowid'=> $CTitems['rowid'] );
	// 				$this->geneal_model->editData('da_cartItems', $CTTdata, 'curprodrowid', $CTitems['curprodrowid']);
	// 			endforeach;
	// 		endif;
	// 	endif; 
	// }

}	

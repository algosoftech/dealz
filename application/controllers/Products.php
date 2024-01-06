<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
public function  __construct() 
{ 
	parent:: __construct();
	error_reporting(0);
	$this->load->model(array('geneal_model','common_model'));
	$this->lang->load('statictext','front');
} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for index
** Date 			: 13 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function index()
{  
	$data 					=	array();

	/*$tbl 					=	'da_homepage_slider';
	$where 					=	['page' => 'Homeslider'];
	$data['homeSlider']		=	$this->geneal_model->getData($tbl, $where,[]);

	$tbl 					=	'da_homepage_slider';
	$where 					=	['page' => 'Homebanner'];
	$data['homeBanner']		=	$this->geneal_model->getData($tbl, $where,[]);

	$tbl 					=	'da_products';
	$where 					=	['status' => 'A'];
	$order 					=	['creation_date' => 'desc'];
	$data['closing_soon']	=	$this->geneal_model->getData($tbl, $where, $order);

	$tbl 					=	'da_products';
	$where 					=	['status' => 'A'];
	$order 					=	['creation_date' => 'desc'];
	$data['closing_soon']	=	$this->geneal_model->getData($tbl, $where, $order);

	$tbl 					=	'db_campaigns';
	$where 					=	['status' => 'A'];
	$order 					=	['creation_date' => 'desc'];
	$data['campaigns']		=	$this->geneal_model->getData($tbl, $where, $order);

	$tbl 					=	'da_products';
	$where 					=	['status' => 'A', 'stock' => '0' ];
	$order 					=	['creation_date' => 'desc'];

	$data['outOfStock']	=	$this->geneal_model->getData($tbl, $where, $order);*/

	$this->load->view('productDetails',$data);
} // END OF FUNCTION

/***********************************************************************
** Function name 	: productDetails
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for index
** Date 			: 13 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function productDetails($id='',$sharedDetails='')
{  
	if($sharedDetails):
		$sharedDatas    	= 	base64_decode($sharedDetails);
		$sharedData    		= 	explode('_',$sharedDatas);

		$datatime 			=	strtotime(date('Y-m-d  H:i:s'));
		$sharedate			=	date('Y-m-d H:i:s',$sharedData[2]);
		$expairon			=	strtotime(date('Y-m-d H:i:s',strtotime($sharedate.'+5 hour')));

		//echo $sharedDatas;die();	
		if($sharedDetails <> '' && $datatime < $expairon):

			$param['seq_id']					=	(int)$this->common_model->getNextSequence('da_deep_link');
			$param['SHARED_USER_ID']			=	(int)$sharedData[0];
			$param['SHARED_USER_REFERRAL_CODE']	=	$sharedData[1];
			$param['SHARED_PRODUCT_ID']			=	(int)manojDecript($id);
			$param['BUY_USER_ID']				=	(int)$this->session->userdata('DZL_USERID');
			$param['created_at']				=	date('Y-m-d H:i');

			$prowhere['where']	=	array('products_id'=>(int)$param['SHARED_PRODUCT_ID']);
			$prodData			=	$this->common_model->getData('single','da_products',$prowhere);

			$sharewhere['where']=	array('users_id'=>(int)$param['SHARED_USER_ID'],'products_id'=>(int)$param['SHARED_PRODUCT_ID']);
			$shareCount			=	$this->common_model->getData('count','da_product_share',$sharewhere,'','0','0');

			$checkDuplicatewhere['where']	=	array('BUY_USER_ID'=>(int)$param['BUY_USER_ID'],'SHARED_PRODUCT_ID'=>(int)$param['SHARED_PRODUCT_ID']);
			$checkDuplicate					=	$this->common_model->getData('count','da_deep_link',$checkDuplicatewhere,'0','0');

			if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit'] && $checkDuplicate == 0):
				$this->geneal_model->addData('da_deep_link',$param);

				$this->session->set_userdata('SHARED_USER_ID', $sharedData[0]);
				$this->session->set_userdata('SHARED_USER_REFERRAL_CODE', $sharedData[1]);
				$this->session->set_userdata('SHARED_PRODUCT_ID', manojDecript($id));
			endif;
		endif;
		//echo base_url('product-details/'.(int)manojDecript($id));die();
		redirect(base_url('product-details/'.(int)manojDecript($id)));
	endif;

	$data 					=	array();

	$tbl 					=	'da_products';
	//$where 					=	['products_id' => (int)manojDecript($id) ];
	if(is_numeric($id)):
		$where1 					=	['products_id' => (int)$id, 'stock' => [ '$gt' => 0], 'status' => 'A' ];
	else:
		$where1 					=	['title_slug' => $id, 'stock' => [ '$gt' => 0], 'status' => 'A' ];
	endif;
	$data['products']		=	$this->geneal_model->getOnlyOneData($tbl, $where1);

	if(!$data['products']):
		redirect(base_url());
	endif;

	// $data['page']			=	"Buy & Win Amazing Prize"; //$data['products']['title'];
	$data['page']			=	'Product | '.$data['products']['title'];
	

	$tbl 					=	'da_prize';
	//$where 					=	['product_id' => (int)manojDecript($id) ];
	$where 					=	['product_id' => (int)$data['products']['products_id'] ];
	//$where 					=	['title_slug' => $id ];
	$data['prize']			=	$this->geneal_model->getOnlyOneData($tbl, $where);

	//$prowhere['where']		=	array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'product_id'=>(int)manojDecript($id));
	$prowhere['where']		=	array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'product_id'=>(int)$data['products']['products_id']);
	$data['prodData']		=	$this->common_model->getData('single','da_wishlist',$prowhere);

	$data['countryCodeData']    =   countryCodeList();

	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		$this->load->view('mobile-productDetails',$data);
	else:
		$this->load->view('productDetails',$data);
	endif;
	
} // END OF FUNCTION

/* * *********************************************************************
	 * * Function name : addtowishlist
	 * * Developed By : Ravi Negi
	 * * Purpose  : This function use for wishlist adding
	 * * Date : 08 SEP 2021
	 * * **********************************************************************/
	public function addtowishlist()
	{	
		$users_id								=	$this->session->userdata('DZL_USERID');
		$product_id                				=   $this->input->post('product_id');

		$prowhere['where']						=	array('users_id'=>(int)$users_id,'product_id'=>(int)$product_id);
		$prodData								=	$this->common_model->getData('single','da_wishlist',$prowhere);
		//echo '<pre>';print_r($product_id);die;
		$param['users_id']						=	(int)$users_id;
		$param['product_id']					=	(int)$product_id;
		$param['creation_date']					=   date('Y-m-d H:i');
		$param['creation_ip']					=   $this->input->ip_address();

		if($prodData == ""):
			$param['wishlist_id']				=	(int)$this->common_model->getNextSequence('da_wishlist');
			$param['wishlist_product']        	=   'Y';
			$result['wishlistData']				=	$param;
			$this->common_model->addData('da_wishlist',$param);
			echo "addedtowishlist";die;
		else:
			$this->common_model->deleteData('da_wishlist','product_id',(int)$product_id);
			echo "removedfromwishlist";die;
		endif;
	}

}
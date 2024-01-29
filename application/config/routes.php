<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//echo $_SERVER['SERVER_NAME'];
//$route['default_controller'] 					= 	'welcome/index';
$route['default_controller'] 					= 	'home/index';
$route['404_override'] 							= 	'';
$route['translate_uri_dashes'] 					= 	FALSE;

$route['about-us'] 								= 	'about/index';
$route['contact-us'] 							= 	'contact/index';

$route['login/(:any)'] 							= 	'home/check/$1';

$route['login'] 								= 	'login/index';
$route['logout'] 								= 	'login/logout';
$route['sign-up'] 								= 	'signup/index';
$route['verify-account-otp'] 				    = 	'signup/verify_account_otp';
$route['verify-account'] 				        = 	'signup/verifyaccount';
$route['activate-account/(:any)'] 				= 	'signup/activateAccount/$1';

$route['my-profile'] 							= 	'profile/index';
$route['edit-profile/(:any)'] 					= 	'profile/editprofile/$1';		//DONE
$route['my-profile/notification'] 				= 	'profile/notification';		//DONE
$route['notification'] 							= 	'profile/getnotification';		//DONE

$route['my-cart'] 								= 	'profile/cart';

$route['add-retailer/(:any)'] 					= 	'profile/addUsers/$1';			//NOT USE
$route['add-user'] 								= 	'profile/addUsers';
$route['add-user/(:any)'] 						= 	'profile/addUsers/$1';			//NOT USE

$route['top-up-recharge'] 						= 	'profile/recharge';

$route['due-management'] 						= 	'profile/duemanagement';
$route['due-management/(:any)'] 				= 	'profile/duemanagement/$1';
$route['view-due-management/(:any)'] 			= 	'profile/viewduemanagement/$1';
$route['collect-due-management/(:any)'] 		= 	'profile/collectduemanagement/$1';
$route['advance-cash/(:any)'] 					= 	'profile/advanceCash/$1';

$route['top-up-recharge/(:any)'] 				= 	'profile/recharge/$1';
$route['redeem-coupon'] 						= 	'profile/redeemCoupon';

$route['refresh-point'] 						= 	'profile/refreshpoint';
$route['my-coupon'] 							= 	'profile/couponList';
$route['my-coupon/(:any)'] 						= 	'profile/couponList/$1';		//NOT USE

$route['clear-coupons'] 						= 	'profile/clearCoupons';

$route['shopping-cart'] 						= 	'shopping_cart/index';
$route['user-cart'] 							= 	'shopping_cart/index';

$route['remove-item/(:any)'] 					= 	'shopping_cart/remove_items/$1';

$route['place-order'] 							= 	'order/placeOrder';					//not in used
$route['checkout'] 							    = 	'order/index';
$route['checkout-step-data'] 					= 	'order/checkoutStep';

$route['payment'] 							    = 	'order/payment';
//for stripe payments only.
$route['order-success/(any)'] 					= 	'order/success/$1';
$route['order-success'] 						= 	'order/success';


$route['order-details/download-invoice/(:any)'] = 	'order/download_invoice/$1'; 
$route['order/download-invoice/(:any)'] = 	'order/download_invoice/$1';



$route['order-list'] 							= 	'order/orderList';
$route['order-list/(:any)'] 					= 	'order/orderList/$1';
$route['order-details/(:any)'] 					= 	'order/orderDetails/$1';


$route['my-wishlist'] 							= 	'profile/mywishlist';
$route['my-wishlist/(:any)'] 					= 	'profile/mywishlist/$1';			//not in used		
$route['delete-product-from-wishlist'] 			= 	'profile/delete';
$route['delete-product-from-wishlist/(:any)'] 	= 	'profile/delete/$1';				//DONE

$route['pickup-point'] 					    	= 	'profile/pickuppoint';
$route['stock-report/(:any)'] 					= 	'profile/stockreport/$1';
$route['stock-report/(:any)/(:any)'] 			= 	'profile/stockreport/$1/$1';
$route['product-request/(:any)'] 		        = 	'profile/productrequest/$1';
$route['product-request/(:any)/(:any)'] 		= 	'profile/productrequest/$1/$1';

$route['approved-order'] 		                = 	'profile/approve_order';

$route['dilivery-address'] 						= 	'diliveryAddress/index';
$route['add-dilivery-address'] 					= 	'diliveryAddress/create';
$route['edit-dilivery-address'] 				= 	'diliveryAddress/edit';
$route['edit-dilivery-address/(:any)'] 			= 	'diliveryAddress/edit/$1';			//DONE
$route['delete-dilivery-address'] 				= 	'diliveryAddress/delete';
$route['delete-dilivery-address/(:any)'] 		= 	'diliveryAddress/delete/$1';		//DONE

$route['productDetails'] 						= 	'product/index';
$route['product-details/(:any)'] 				= 	'products/productDetails/$1';		//DONE
$route['product-details/(:any)/(:any)'] 		= 	'products/productDetails/$1/$2';	//DONE
$route['our-products'] 							= 	'product_list/index';
$route['add-to-wishlist'] 						= 	'products/addtowishlist';

$route['winners-list'] 							= 	'winners/index';

$route['earning'] 								= 	'earning/index';
$route['help'] 									= 	'help/index';

$route['terms-condition'] 						= 	'termconditions/index';
$route['charities'] 						    = 	'charities/index';
$route['how-it-works'] 						    = 	'howitworks/index';
$route['contestrule'] 						    = 	'contestrule/index';


$route['faqs'] 					            	= 	'faqs/index';


$route['user-agreement'] 						= 	'user_agreement/index';
$route['privacy-policy'] 						= 	'privacypolicy/index';
$route['check-mobile'] 							= 	'signup/checkDuplicateMobile';
$route['check-email'] 							= 	'signup/checkDuplicateEmail';

$route['forgot-password']                       =   'login/forgotpassword';

$route['password-recover']                      =   'login/passwordrecover';


$route['delivery-policy'] 						= 	'deliverypolicy/index';
$route['cancellation-policy'] 					= 	'cancellationpolicy/index';
$route['refund-policy'] 						= 	'refundpolicy/index';


$route['check-share-limit'] 					= 	'profile/checkShareLimit';

$route['test/(:any)'] 					        = 	'home/test_function/$1';
$route['DownlodeOrderPDF/(:any)'] 			    = 	'home/DownlodeOrderPDF/$1';

$route['pick-up-point'] 					    =	'order/pickup_point';

$route['wallet-statement'] 					    =	'walletstatement';

$route['sub-winners'] 					        =	'sub_winners/index';
$route['collect-prize'] 					    =	'sub_winners/collect_prize';
$route['get-winners-by-ajax'] 					=	'sub_winners/getWinnerListByAjax';

/*API SECTION FOR APP */

/////////////////////////////		COMMON 		/////////////////////////////////////
$route['api/getCountryCode'] 									= 	'api/common/getCountryCode';
$route['api/getCountryList'] 									= 	'api/common/getCountryList';
$route['api/contact'] 											= 	'api/common/contact';
$route['api/contestrules'] 							            = 	'api/common/contestrules';
$route['api/termsconditions'] 							        = 	'api/common/termsconditions';
$route['api/privacypolicy'] 							        = 	'api/common/privacypolicy';
$route['api/usersagreement'] 							        = 	'api/common/usersagreement';


/////////////////////////////		USERS 				/////////////////////////////////////
$route['api/checkEmail'] 										= 	'api/users/checkEmail';
$route['api/checkMobile'] 										= 	'api/users/checkMobile';
$route['api/signup'] 											= 	'api/users/signup';
$route['api/login'] 											= 	'api/users/login';
$route['api/usersLogin'] 										= 	'api/users/login';
$route['api/forgotPassword'] 									= 	'api/users/forgotPassword';
$route['api/resetPassword'] 									= 	'api/users/resetPassword';
$route['api/getProfileData'] 									= 	'api/users/getProfileData';
$route['api/updateProfile'] 									= 	'api/users/updateProfile';
$route['api/changePassword'] 									= 	'api/users/changePassword';
$route['api/refreshPoint'] 										= 	'api/users/refreshPoint';
$route['api/getAddress'] 										= 	'api/users/getAddress';
$route['api/addAddress'] 										= 	'api/users/addAddress';
$route['api/editAddress'] 										= 	'api/users/editAddress';
$route['api/deleteAddress'] 									= 	'api/users/deleteAddress';

$route['api/getNotification'] 									= 	'api/users/getNotification';
$route['api/getMembershipDetails'] 								= 	'api/users/getMembershipDetails';
$route['api/read_all_notifications'] 						    = 	'api/users/read_all_notifications';

$route['api/deleteAccount'] 									= 	'api/users/deleteAccount';
$route['api/verifyaccount'] 									= 	'api/users/verifyaccount';
$route['api/rsendotp'] 									        = 	'api/users/rsendotp';

$route['api/updateNotification'] 								= 	'api/users/updateNotification';


/////////////////////////////		USERS DATA 			/////////////////////////////////////
$route['api/getCoupons'] 										= 	'api/users/getCoupons';
$route['api/redeemCoupon'] 										= 	'api/users/redeemCoupon';

$route['api/getWishlist'] 										= 	'api/users/getWishlist';
$route['api/addToWishlist'] 									= 	'api/users/addToWishlist';
$route['api/deleteFromWishlist'] 								= 	'api/users/deleteFromWishlist';
$route['api/getEarnings'] 										= 	'api/users/getEarnings';
$route['api/getOrders'] 										= 	'api/users/getOrders';
$route['api/getOrdersDetails'] 									= 	'api/users/getOrdersDetails';
$route['api/getVoucherHistory'] 								= 	'api/users/getVoucherHistory';

/////////////////////////////		USERS RECHARGE 		/////////////////////////////////////
$route['api/getRechargeHistory'] 								= 	'api/usertopup/getRechargeHistory';
$route['api/bindedPersonList'] 									= 	'api/usertopup/bindedPersonList';
$route['api/rechargeToUser'] 									= 	'api/usertopup/rechargeToUser';
$route['api/getUsers'] 											= 	'api/usertopup/getUsers';
$route['api/addUsers'] 											= 	'api/usertopup/addUsers';
$route['api/getRechargeHistoryByCondition'] 					= 	'api/usertopup/getRechargeHistoryByCondition';

/////////////////////////////		PRODUCT AND CART 	/////////////////////////////////////
$route['api/getHomePageData'] 									= 	'api/products/getHomePageData';
$route['api/getProductListPageData'] 							= 	'api/products/getProductListPageData';
$route['api/getProductListPageData_two'] 							= 	'api/products/getProductListPageData_two';
$route['api/getProductDetails'] 								= 	'api/products/getProductDetails';

/////////////////////////////		Lotto PRODUCT /////////////////////////////////////
$route['api/getLottoProductListPageData'] 						= 	'api/products/getLottoProductListPageData';


$route['api/getWinnerList'] 									= 	'api/products/getWinnerList';
$route['api/searchProduct'] 									= 	'api/products/searchProduct';

$route['api/addToCart'] 										= 	'api/products/addToCart';
$route['api/getCartData'] 										= 	'api/products/getCartData';
$route['api/removeFromCart'] 									= 	'api/products/removeFromCart';
$route['api/quantityIncreaseToCart'] 							= 	'api/products/quantityIncreaseToCart';
$route['api/addRemoveToDonate'] 								= 	'api/products/addRemoveToDonate';

$route['api/getCollectionPointsData'] 							= 	'api/products/getCollectionPointsData';

$route['api/purchageByArabianPoint'] 							= 	'api/products/purchageByArabianPoint';

$route['api/stripePaymentIntent'] 								= 	'api/products/stripePaymentIntent';
$route['api/stripePaymentFinal'] 								= 	'api/products/stripePaymentFinal';

$route['api/changeCollectionStatus'] 							= 	'api/products/changeCollectionStatus';
$route['api/productRequest'] 							        = 	'api/products/productRequest';
$route['api/stockReport'] 							            = 	'api/products/stockReport';

$route['api/addDeepLink'] 							            = 	'api/products/addDeepLink';

$route['api/requestProductToAdmin'] 							= 	'api/products/requestProductToAdmin';

$route['api/donateallproducts'] 							    = 	'api/products/donateallproducts';

$route['api/addMobileShearedLink'] 							    = 	'api/products/addMobileShearedLink';

$route['download-invoice/(:any)'] 							    = 	'api/common/download_invoice/$1';

$route['api/get-sub-winner-list'] 							    = 	'api/common/getSubWinnerist';
$route['api/get-daily-sub-winner-list'] 						= 	'api/common/getDailySubWinnerist';


$route['api/sub-winner/draw-winner'] 							= 	'api/common/draw_winner';
$route['api/sub-winner/cancel-draw-winner'] 					= 	'api/common/cancel_draw_winner';


$route['api/sub-winner/redeem-by-cash-draw'] 					= 	'api/common/redeem_by_cash_draw';
//echo '<pre>';  print_r($route); die;

/////////////////////////////		POS DATA 			/////////////////////////////////////

$route['api/QuickTicket'] 										= 	'api/pos/QuickTicket';
$route['api/GetQuickUser'] 										= 	'api/pos/GetQuickUser';
$route['api/GetQuickTicketHistory'] 							= 	'api/pos/GetQuickTicketHistory';
$route['downloadQuickInvoice/(:any)'] 							= 	'api/pos/download_quick_invoice/$1';
$route['api/SummaryReportSearch'] 								= 	'api/pos/SummaryReportSearch';
$route['api/newSummaryReportSearch'] 							= 	'api/pos/newSummaryReportSearch';

$route['api/getAvailableTickets'] 								= 	'api/pos/getAvailableTickets';
$route['api/getSelectedField'] 									= 	'api/pos/getSelectedField';

$route['api/Walletstatement'] 									= 	'api/walletstatement/index';
$route['api/duemanagement'] 									= 	'api/DueManagement/index';
$route['api/viewduemanagement'] 								= 	'api/DueManagement/ViewDueManagement';
$route['api/newduemanagement'] 									= 	'api/DueManagement/newDueManagement';

$route['api/collectduecash'] 									= 	'api/DueManagement/CollectDueCash';

// Telr payment gateway.
$route['telrpayment'] 											= 	'order/telrpayment';
$route['telr-fail/(:any)'] 									    = 	'home/telr_payment_fail/$1';
$route['telr-cancel/(:any)'] 									= 	'home/telr_payment_cancel/$1';
$route['telr-success/(:any)'] 									= 	'home/telr_payment_success/$1';
$route['check-telr-payment-status']      						=   'order/check_telr_payment_status';
$route['api/telrGenerateOrderId'] 								= 	'api/telr/generateOrderId';
$route['api/telrOrderSuccess'] 									= 	'api/telr/telrOrderSuccess';
$route['api/telrOrderFailed'] 									= 	'api/telr/telrOrderFailed';


$route['api/selectaddress'] 									= 	'api/telr/selectaddress';

$route['noonpayment'] 											= 	'order/noonpayment';
$route['ngenius'] 												= 	'order/ngenius';
$route['ngenius-order-status'] 									= 	'test/ngenius_order_status';
$route['order-status'] 											= 	'order/orderStatus';

$route['api/enablepayment'] 									= 	'api/telr/enablepayment';

$route['telr-fail'] 									        = 	'home/telr_payment_fail';
$route['telr-cancel'] 									        = 	'home/telr_payment_cancel';
$route['telr-success/(:any)'] 									= 	'home/telr_payment_success/$1';

$route['api/pos-test'] 								            = 	'api/pos/test';

$route['api/getgeneralinfo'] 									= 	'api/common/getGeneralInfo';

$route['api/test-header'] 										= 	'api/products/testAPI';

$route['api/resendSMS']											= 	'api/pos/resendPOSsms';

$route['api/generateOrderId'] 									= 	'api/common/generateOrderId';
$route['api/orderSuccess'] 										= 	'api/common/orderSuccess';

$route['api/testSummaryReportSearch'] 							= 	'api/pos/testSummaryReportSearch';

$route['api/posQuickBuy'] 										= 	'api/pos/posQuickBuy';
$route['api/show-default-company'] 								= 	'api/common/showDefaltCompany';
$route['api/pos-cancelled-orders'] 								= 	'api/pos/posCancelledOrders';


// quick buy web routes start
$route['quick-buy'] 											= 	'quickbuy/quickcampaign';
$route['quick-orders'] 											= 	'quickbuy/quickorders';
$route['quick-orders/(:any)'] 									= 	'quickbuy/quickorders/$1';
$route['quick/resend-sms/(:any)'] 								= 	'quickbuy/quicksms/$1';
$route['quick/download-invoice/(:any)'] 						= 	'quickbuy/download_invoice/$1';
$route['quick-summery-report'] 									= 	'quickbuy/quicksummeryreport';
// quick buy web routes end

$route['quick-buy/verifyUser'] 									= 	'quickbuy/verifyUser';
$route['quick-buy/verifyUserOTP'] 								= 	'quickbuy/verifyUserOTP';
$route['quick-buy/checkout'] 									= 	'quickbuy/checkout';
$route['quick-buy/purchase'] 									= 	'quickbuy/purchase';
$route['quick-buy-ticket'] 										= 	'quickbuy/quickbuyticket';
$route['quick-buy/(:any)'] 										= 	'quickbuy/checkValiduser/$1';


// Quick Buy
$route['api/quickbuy/verifyUser'] 								= 	'api/pos/verifyUser';
$route['api/quickbuy/verifyUserOTP'] 							= 	'api/pos/verifyUserOTP';
$route['api/quickbuy/resendUserOTP'] 							= 	'api/pos/resendUserOTP';

$route['api/quickbuy/generateOrderId'] 							= 	'api/pos/generateOrderId';
$route['api/quickbuy/paymentCapture'] 							= 	'api/pos/paymentCapture';

//lotto api
$route['api/lotto/paymentCapture'] 								= 	'api/lotto/paymentCapture';
$route['api/lotto/orderHistory'] 								= 	'api/lotto/orderHistory';
$route['api/lotto/SummaryReportSearch'] 						= 	'api/lotto/SummaryReportSearch';
$route['api/lotto/getWinner'] 									= 	'api/lotto/getWinner';
$route['api/lotto/product-settings'] 							= 	'api/lotto/productSettings';

//lotto web
$route['lotto/order/(:any)'] 									= 	'order/getlottoOrder/$1';

$route['api/user/addedit_appname'] 							    = 	'api/users/addeditAppname';


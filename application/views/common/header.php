<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<?php
$url_save = $this->uri->segment(1);
//echo $url_save; die();
$myprofile 				= '';
$mycart 					= '';
$diliveryaddress	=	'';
$earning 					=	'';
$coupon 					=	'';
$myorder					=	'';
$mywishlist				=	'';
$help 						=	'';
$recharge 				=	'';
$redeemcoupon 		=	'';
$adduser					=	'';
$pickuppoint			=	'';
$pickup_point  		= '';
$wallet    				= '';
$quickbuy    			= '';

if($url_save == 'my-profile' || $url_save == 'edit-profile'){
	$myprofile 	= 'active';
}elseif ($url_save == 'user-cart') {
	$mycart = 'active';
}elseif($url_save == 'dilivery-address'){
	$diliveryaddress = 'active';
}elseif ($url_save == 'earning') {
	$earning = 'active';
}elseif ($url_save == 'my-coupon') {
	$coupon = 'active';
}elseif ($url_save == 'order-list' || $url_save == 'order-details') {
	$myorder = 'active';
}elseif ($url_save == 'my-wishlist') {
	$mywishlist = 'active';
}elseif ($url_save == 'help') {
	$help = 'active';
}elseif($url_save == 'top-up-recharge'){
	$recharge = 'active';
}elseif($url_save == 'pick-up-point'){
  $pickup_point = 'active';
}elseif($url_save == 'redeem-coupon'){
	$redeemcoupon = 'active';
}elseif($url_save == 'add-user'){
	$adduser = 'active';
}elseif($url_save == 'pickup-point' || $url_save == 'stock-report'){
	$pickuppoint = 'active';
}elseif($url_save == 'wallet-statement'){
	$wallet = 'active';
}elseif($url_save == 'quick-buy'){
	$quickbuy = 'active';
}elseif($url_save == 'due-management'){
	$due_management = 'active';
}




//$url_save = $this->uri->segment(1);
//echo $url_save; die();

?>
<style>



</style>
<div class="top-header" id="sticker">
	<div class="container">
		<header>
			<div class="head-nav-third">
				<div class="row">
					<div class="col-lg-12 col-sm-12 col-md-12 col-12">
						<nav class="navbar navbar-expand-lg">
							<a class="navbar-brand" href="<?=base_url()?>"><img src="<?=base_url('assets')?>/img/white-logo.gif" class="img-fluid" alt="websites_logo"/></a>
							
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
								<!-- <i class="fal fa-bars"></i> -->
								<div id="nav-icon1">
								<span></span>
								<span></span>
								<span></span>
								</div>
							</button>
							<div class="collapse navbar-collapse" id="collapsibleNavbar">
								<ul class="navbar-nav mr-auto">
									<li class="nav-item"> <a class="nav-link" href="<?=base_url('our-products')?>">Our Products</a> </li>
									<li class="nav-item"> <a class="nav-link" href="<?=base_url('winners-list')?>"> Winners</a> </li>
									<li class="nav-item"> <a class="nav-link" href="<?=base_url('about-us')?>">About Us</a></li>
									<li class="nav-item"> <a class="nav-link" href="<?=base_url('contact-us')?>">Contact Us</a> </li>
									<li class="nav-item"> <a class="nav-link" href="<?=base_url('faqs')?>">FAQs</a> </li>
								</ul>
								<ul class="navbar-nav ml-auto">
									<!--<li class="nav-item headerneed-help">
										<a class="nav-link no_need" href="<?=base_url('contact-us')?>">Need Help? Contact us: <span>Call 0800-IDEALZ </span></a>
									</li>-->
									
									<li class="nav-item">
											<a class="nav-link shoping_cart" id="cartA" href="<?=base_url('shopping-cart')?>"><b><i class="fas fa-shopping-cart"></i>(<?=@count($this->cart->contents())?>)</b></a>
									</li> 

									<?php if($this->session->userdata('DZL_USERID')){ ?>
									<li class="nav-item dropdown">
										<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
											<?=@$this->session->userdata('DZL_USERNAME')?> <i class="fas fa-user-circle"></i>
										</a>
									  <div class="dropdown-menu">
										<!-- <a class="dropdown-item" href="<?=base_url('my-profile')?>">My Profile</a> -->
										<!-- <a class="dropdown-item" href="<?=base_url('my-profile')?>">Account</a> -->
										<a class="dropdown-item <?=$myprofile?>" href="<?=base_url('my-profile')?>" >
											<span style="padding-right: 10px;">
												<img src="<?=base_url()?>assets/img/My-Profile.png" class="icon_img" alt="my_profile">
											</span>My Profile
										</a>
										<a class="dropdown-item <?=$coupon?>" href="<?=base_url('my-coupon')?>">
											<span style="padding-right: 10px;">
												<img src="<?=base_url()?>assets/img/coupon.png" class="icon_img" alt="coupen">
											</span>Active Coupon<?php /* ?>My Coupons<?php */ ?>
										</a>
										<a class="dropdown-item <?=$redeemcoupon?>"  href="<?=base_url('redeem-coupon')?>">
											<span style="padding-right: 10px;">
												<img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="top_up_recharge">
											</span>Redeem Arabian Points
										</a>
											<a class="dropdown-item <?=$earning?>"  href="<?=base_url('earning')?>">
												<span style="padding-right: 10px;">
													<img src="<?=base_url()?>assets/img/Earnings.png" class="icon_img" alt="earning">
												</span>My Arabian Points
											</a>
											<a class="dropdown-item <?=$recharge?>"  href="<?=base_url('top-up-recharge')?>">
												<span style="padding-right: 10px;">
													<img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="recharge_icon">
												</span>Transfer Arabian Points
											</a>

											<a class="dropdown-item <?=$wallet?>"  href="<?=base_url('wallet-statement')?>">
													<span style="padding-right: 10px;">
														<img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="recharge_icon">
													</span>Wallet Statement
											</a>
											<a class="dropdown-item <?=$due_management?>"  href="<?=base_url('due-management')?>">
													<span style="padding-right: 10px;">
														<img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="recharge_icon">
													</span>Due Management
											</a>

											<?php if($this->session->userdata('DZL_USERID') == 100000000001983 || $this->session->userdata('DZL_USERID') == 100000000013118 ): ?>
												<a class="dropdown-item <?=$quickbuy?>"  href="<?=base_url('quick-buy')?>">
													<span style="padding-right: 10px;">
														<img src="<?=base_url()?>assets/img//Help.png" class="icon_img" alt="quick_buy">
													</span> Quick Buy
												</a>
											<?php endif; ?>

                      <a class="dropdown-item <?=$pickup_point?>"  href="<?=base_url('pick-up-point')?>">
                        <span style="padding-right: 10px;">
                          <img src="<?=base_url()?>assets/img/pin.png" class="icon_img" alt="pick_up_points">
                        </span>Pick Up Points
                      </a>

											<a class="dropdown-item <?=$myorder?>" href="<?=base_url('order-list')?>">
												<span style="padding-right: 10px;">
													<img src="<?=base_url()?>assets/img/order.png" class="icon_img" alt="header_order">
												</span>My Orders
											</a>
											<?php if($this->session->userdata('DZL_USERSTYPE') == 'Retailer'): ?>
													<a class="dropdown-item <?=$pickuppoint?>" href="<?=base_url('pickup-point')?>">
														<span style="padding-right: 10px;">
															<img src="<?=base_url()?>assets/img/order.png" class="icon_img" alt="mypickpoints">
														</span>My Pickup Points
													</a>
											<?php endif; ?>

											<?php if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'){ ?>
													<a class="dropdown-item <?=$adduser?>" href="<?=base_url('add-user')?>">
														<span style="padding-right: 10px;">
															<img src="<?=base_url()?>assets/img/Add-retailer.png" class="icon_img" alt="add_retailer">
														</span>Add User
													</a>
											<?php } ?>
											<?php
											if($this->session->userdata('DZL_USERSTYPE') == 'Retailer'){ ?>
													<a class="dropdown-item <?=$adduser?>" href="<?=base_url('add-user')?>">
														<span style="padding-right: 10px;">
															<img src="<?=base_url()?>assets/img/Add-retailer.png" class="icon_img" alt="add-retialer">
														</span>Add User
													</a>
											<?php }	?>	

											<a class="dropdown-item <?=$help?>" href="<?=base_url('help')?>">
												<span style="padding-right: 10px;">
													<img src="<?=base_url()?>assets/img/Help.png" class="icon_img" alt="help">
												</span>Help
											</a>


										<a class="dropdown-item" href="<?=base_url('logout')?>" onClick="return confirm('Want to Logout!');" ><span style="padding-right: 10px;">
													<img src="<?=base_url()?>assets/img/power-off.png" class="icon_img" alt="logout">
												</span>Logout</a>
									  </div>
									</li>  
									<?php }else{ ?> 
										<a class="nav-link linkes" href="<?=base_url('login')?>">
											Login </a>
									<?php } ?>
								</ul>
							</div>  
						</nav>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<script>
	$(document).ready(function(){
	$('#nav-icon1').click(function(){
		$(this).toggleClass('open');
	});
});
</script>
<script>
  //header mobile left menu open
  $("header .navbar-toggler").click(function(){$("header .navbar-collapse").toggleClass("active");});
</script>
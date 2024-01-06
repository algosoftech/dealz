<?php
$url_save = $this->uri->segment(1);
//echo $url_save; die();
$myprofile 			= 	'';
$mycart 			= 	'';
$diliveryaddress	=	'';
$earning 			=	'';
$coupon 			=	'';
$myorder			=	'';
$mywishlist			=	'';
$help 				=	'';
$recharge 			=	'';
$redeemcoupon 		=	'';
$adduser			=	'';
$pickuppoint		=	'';
$duemanagement    	=   '';
$wallet    			=   '';
$quickbuy 			=   '';
$due_management 	=   '';


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
}elseif($url_save == 'due-management'){
	$duemanagement = 'active';
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
}



?>

<style>
.user_content	p {
    margin: 0px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    font-size: 13px !important;
    color: black;
    font-style: normal !important;
}
    .avatar-upload {
	 position: relative;
	
}
 .avatar-upload .avatar-edit {
	 position: absolute;
	 right: 12px;
	 z-index: 1;
	 top: 10px;
}
 .avatar-upload .avatar-edit input {
	 display: none;
}
 .avatar-upload .avatar-edit input + label {
    display: inline-block;
    width: 22px;
    height: 21px;
    margin-bottom: 0;
    border-radius: 100%;
    background: #FFFFFF;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgb(0 0 0 / 12%);
    cursor: pointer;
    font-weight: normal;
    transition: all .2s ease-in-out;
    position: relative;
    left: 29px;
    top: 8px;
}
 .avatar-upload .avatar-edit input + label:hover {
	 background: #f1f1f1;
	 border-color: #d6d6d6;
}
 .avatar-upload .avatar-edit input + label:after {
content: "+";
    color: #bb2627;
    position: absolute;
    top: -9px;
    left: 0;
    right: 0;
    text-align: center;
    margin: auto;
    font-size: 22px;
}
/*
 .avatar-upload .avatar-preview {
	 width: 192px;
	 height: 192px;
	 position: relative;
	 border-radius: 100%;
	 box-shadow: 0px 2px 4px 0px rgba(0,0,0,0.1);
}
*/
 .avatar-upload .avatar-preview > .abhs{
	 width: 100%;
	 height: 100%;
	 border-radius: 100%;
	 background-size: cover;
	 background-repeat: no-repeat;
	 background-position: center;
}
.profile-pic{
    position: absolute;
    height:120px;
    width:120px;
    left: 50%;
    transform: translateX(-50%);
    top: 0px;
    z-index: 1001;
    padding: 10px;
}
.profile-pic img{
   
    border-radius: 50%;
    box-shadow: 0px 0px 5px 0px #c1c1c1;
    cursor: pointer;
    width: 100px;
    height: 100px;
}  
.icon_img{
    width:22px;
}
.avatar-upload .avatar-preview {
  width: 50px;
  height: 50px;
  position: relative;
  border-radius: 100%;
  border: 6px solid #F8F8F8;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}

.avatar-upload .avatar-edit label {
    display: inline-block;
    width: 22px;
    height: 21px;
    margin-bottom: 0;
    border-radius: 100%;
    background: #FFFFFF;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgb(0 0 0 / 12%);
    cursor: pointer;
    font-weight: normal;
    transition: all .2s ease-in-out;
    position: relative;
    left: 29px;
    top: 8px;
}
 .avatar-upload .avatar-edit label:hover {
	 background: #f1f1f1;
	 border-color: #d6d6d6;
}
 .avatar-upload .avatar-edit label:after {
content: "+";
    color: #bb2627;
    position: absolute;
    top: -9px;
    left: 0;
    right: 0;
    text-align: center;
    margin: auto;
    font-size: 22px;
}
.card{display:none;}

.dropdown-menu a.dropdown-item.active{
	background-color: #fee9e9;
	color: #000000;
}


.card-header a{
	font-size: 15px;
    color: black;
    font-family: 'Open Sans', sans-serif;
}
.sidebar_menu a{
	font-size: 15px;
    color: black;
    font-family: 'Open Sans', sans-serif;
}
.sidebar_menu {
	padding: 0px;
}
.sidebar_menu li{
	list-style: none;
    padding: 10px 21px;
    border-bottom: 1px solid rgb(209 209 209);
    color: black;

}
.btn-header-link:after {
    content: "\f107";
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    float: right;
}
.btn-header-link.collapsed:after {
    content: "\f106";
}
.mobile_view{
	display:none;
}
@media (min-width: 360px) and (max-width: 600px) {
	.sidebar_menu .active {
    background-color: #fee9e9;
    font-weight: 700;
	}
.accordion{
	display: block !important;
    width: 92%;
    margin: auto;
    margin-top: 15px;
    margin-bottom: 0px;
}
	.sidenav{
		display:none;
	}
	.user_1 {
    display: flex;
    flex-direction: column;
}
.refersh {
    background-color: #ffffff;
    border: none;
    padding: 1px 11px !important;
    border-radius: 8px;
    color: #e72d2e;
    margin: 0px;
    font-size: 11px !important;
    font-weight: 500 !important;
    border: 1px solid #e72d2e;
    /* margin-bottom: 21px; */
    margin-left: 27px !important;
	position: relative;
    left: 0px;
    top: 3px;
}
.user_content{
	display:none;
}
.my-profile .user_profile {
    display: flex;
    padding: 27px;
    justify-content: center;
    background: #f5fcff00;
    padding: 5px 10px !important;
    position: relative;
    box-shadow: rgb(99 99 99 / 20%) 0px 2px 8px 0px;
    border-radius: 8px;
    flex-wrap: wrap;
    align-items: center;
    height: 100px !important;
}
}

</style>
<div class="col-md-3">
	<div class="user_profile">
				<div class="avatar-upload">
        <div class="avatar-edit">
            <label id="profileImageUpload"></label>
        </div>
       <div class="avatar-preview">
       		<?php if($this->session->userdata('DZL_USERS_IMAGE')): ?>
       			<div id="imagePreview" class="abhs" style="background-image:url(<?php echo base_url().$this->session->userdata('DZL_USERS_IMAGE'); ?>)"></div>   
       		<?php else: ?>
	            <div id="imagePreview" class="abhs" style="background-image:url(<?php echo base_url()?>assets/img/user.png)"></div>       
	        <?php endif; ?>
        </div>
        </div>
		<div class="user">
		<h2><?=@$this->session->userdata('DZL_USERNAME')?></h2>
		<?php if($this->session->userdata('DZL_USERSTYPE') == "Retailer"): ?>
			<p>Reseller</p>
		<?php else: ?>
			<p><?=@$this->session->userdata('DZL_USERSTYPE')?></p>
		<?php endif; ?>
		<p class="email mobile_view"><?=@$this->session->userdata('DZL_USEREMAIL')?></p>
		<p class="mobile_view"><?=@$this->session->userdata('DZL_USERMOBILE')?></p>

		
		</div>
		<div class="user_content">
		<p class="email"><?=@$this->session->userdata('DZL_USEREMAIL')?></p>
		<p><?=@$this->session->userdata('DZL_USERMOBILE')?></p>
		</div>
	</div>

	<div class="sidenav">
      <div class="profiles">
					<h4 class="information" >Account Information</h4>
			</div>
		
		<!-- Updated on 23-11-2022 -->
		<ul>
			<li class="<?=$myprofile?>"><a href="<?=base_url('my-profile')?>" ><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/My-Profile.png" class="icon_img" alt="myprofile"></span>My Profile</a></li>
			<!-- <li class="<?=$mywishlist?>"><a href="<?=base_url('my-wishlist')?>"><span style="padding-right: 10px;"><i class="far fa-heart heart_icon" style="font-size:20px;" title="Mark Favourite" aria-hidden="true"></i></span>My Wishlist</a></li> -->
			<li class="<?=$coupon?>"><a href="<?=base_url('my-coupon')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/coupon.png" class="icon_img" alt="activecoupen"></span>Active Coupon<?php /* ?>My Coupons<?php */ ?></a></li>
			<li class="<?=$redeemcoupon?>"><a href="<?=base_url('redeem-coupon')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="toprecharge"></span>Redeem Arabian Points</li>
			<li class="<?=$earning?>"><a href="<?=base_url('earning')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Earnings.png" class="icon_img" alt="arbian_points"></span>My Arabian Points</a></li>
			<li class="<?=$recharge?>"><a href="<?=base_url('top-up-recharge')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="top_up_recharge"></span>Transfer Arabian Points</a></li>
			<li class="<?=$wallet?>"><a href="<?=base_url('wallet-statement')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Help.png" class="icon_img" alt="help"></span>Wallet Statement</a></li>
			
			<?php if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'  || $this->session->userdata('DZL_USERSTYPE') == 'Super Salesperson' || $this->session->userdata('DZL_USERSTYPE') == 'Super Retailer'): ?>
			<li class="<?=$duemanagement?>"><a href="<?=base_url('due-management')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img"></span>Due Management</a></li>
			<?php endif; ?>
			<li class="<?=$myorder?>"><a href="<?=base_url('order-list')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/order.png" class="icon_img" alt="order_img"></span>My Orders</a></li>
			
			
			<?php if($this->session->userdata('DZL_USERSTYPE') == 'Retailer'): ?>
				<li class="<?=$pickuppoint?>"><a href="<?=base_url('pickup-point')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Delivery-Address.png" class="icon_img" alt="delivery_address"></span>My Pickup Points</a></li>
			<?php endif; ?>
			
			<?php if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'){ ?>
				<li class="<?=$adduser?>"><a href="<?=base_url('add-user')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Add-retailer.png" class="icon_img" alt="Add User"></span>Add User</a></li>
			<?php } ?>
			<?php
			if($this->session->userdata('DZL_USERSTYPE') == 'Retailer'){ ?>
				<li class="<?=$adduser?>"><a href="<?=base_url('add-user')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Add-retailer.png" class="icon_img" alt="add_users"></span>Add User</a></li>
			<?php }	?>	
			<li class="<?=$help?>"><a href="<?=base_url('help')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Help.png" class="icon_img" alt="help"></span>Help</a></li>
			
			<li><a href="<?=base_url('logout')?>" onClick="return confirm('Want to Logout!');" ><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/logout.png" class="icon_img" alt="logout"></span>Logout</a></li>
		</ul>
	</div>
			</div>
	<div class="accordion" id="faq">
				
	<div class="card">
			
	<div class="card-header" id="1">
		<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#2" aria-expanded="false" aria-controls="faq1">Account Information</a>
	</div>

	<div id="2" class="showcollapse collapse" aria-labelledby="faqhead1" data-parent="#2" style="">
	<ul class="sidebar_menu">
			<li class="<?=$myprofile?>"><a href="<?=base_url('my-profile')?>" ><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/My-Profile.png" class="icon_img" alt="my_profile"></span>My Profile</a></li>
			<!-- <li class="<?=$mywishlist?>"><a href="<?=base_url('my-wishlist')?>"><span style="padding-right: 10px;"><i class="far fa-heart heart_icon" style="font-size:20px;" title="Mark Favourite" aria-hidden="true"></i></span>My Wishlist</a></li> -->
			<li class="<?=$coupon?>"><a href="<?=base_url('my-coupon')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/coupon.png" class="icon_img" alt="coupon"></span>Active Coupon<?php /* ?>My Coupons<?php */ ?></a></li>
			<li class="<?=$redeemcoupon?>"><a href="<?=base_url('redeem-coupon')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="Redeem Arabian Points"></span>Redeem Arabian Points</li>
			<li class="<?=$earning?>"><a href="<?=base_url('earning')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Earnings.png" class="icon_img" alt="My Arabian Points"></span>My Arabian Points</a></li>
			<li class="<?=$recharge?>"><a href="<?=base_url('top-up-recharge')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Top-up-recharge.png" class="icon_img" alt="top-up-recharge"></span>Transfer Arabian Points</a></li>
			<li class="<?=$myorder?>"><a href="<?=base_url('order-list')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/order.png" class="icon_img" alt="My Orders"></span>My Orders</a></li>
			
			
			<?php if($this->session->userdata('DZL_USERSTYPE') == 'Retailer'): ?>
				<li class="<?=$pickuppoint?>"><a href="<?=base_url('pickup-point')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Delivery-Address.png" class="icon_img" alt="My Pickup Points"></span>My Pickup Points</a></li>
			<?php endif; ?>
			
			<?php if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'){ ?>
				<li class="<?=$adduser?>"><a href="<?=base_url('add-user')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Add-retailer.png" class="icon_img" alt="Add User"></span>Add User</a></li>
			<?php } ?>
			<?php
			if($this->session->userdata('DZL_USERSTYPE') == 'Retailer'){ ?>
				<li class="<?=$adduser?>"><a href="<?=base_url('add-user')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Add-retailer.png" class="icon_img" alt="Add User"></span>Add User</a></li>
			<?php }	?>	
			<li class="<?=$help?>"><a href="<?=base_url('help')?>"><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/Help.png" class="icon_img" alt="Help"></span>Help</a></li>
			
			<li><a href="<?=base_url('logout')?>" onClick="return confirm('Want to Logout!');" ><span style="padding-right: 10px;"><img src="<?=base_url()?>assets/img/logout.png" class="icon_img" alt="Logout"></span>Logout</a></li>
		</ul>
	</div>
		</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript"><!--
$('label[id^=\'profileImageUpload\']').on('click', function() {
	var node = this;
	$('#form-upload').remove();
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
	$('#form-upload input[name=\'file\']').trigger('click');
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			$.ajax({
				url: '<?php echo base_url('profile/uploadProfilePic'); ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).html('...');
					$('.text-danger').remove();
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();
					if (json['error']) {
						alert(json['error']);
					}
					if (json['success']) {
						location.reload();
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script> 

<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
		 .error{
            color:red;
        }
        .my-profile .confirm_password .change_passworded {
    text-align: end!important;
    padding: 28px 28px 17px 36px !important;
}
.my-profile .inputWithIcon input[type="text"] {
    padding-left: 40px;
    color: rgb(209 209 209) !important;
}
.my-profile input[type="password"] {
    width: 100%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: 8px 0;
    outline: none;
    padding: 5px 28px 5px;
    box-sizing: border-box;
    transition: 0.3s;
     color: rgb(209 209 209) !important;
}
.my-profile .btn:hover{
background: #e72d2e;
    color: #ffffff;
}
.my-profile .btn {
    background: #ffffff;
    border: 1px solid #e72d2e;
    padding: 4px 13px !important;
    border-radius: 8px;
    color: #e72d2e;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
    height: 37px;
}
.password {
    color: #252525;
    font-size: 13px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    border-bottom: 0;
}
.my-profile input[type="password"] {
    width: 100%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: 8px 0;
    outline: none;
    padding: 5px 28px 5px 10px;
    box-sizing: border-box;
    transition: 0.3s;
    color: rgb(209 209 209) !important;
}
.user-cart{
 background: #f5fcff00;
    border-radius: 8px;
    position: relative;
    margin-top: 32px;
    border: 2px solid #ebebeb;
    text-align: left;
    margin-bottom: 36px;   
}
input[type=text]::placeholder { 
color: rgb(209 209 209) !important;
	opacity: 1; 
	font-family: 'Open Sans', sans-serif;
}
.my-profile .user_password {
    color: #d12a2b;
    padding-top: 12px;
    font-style: normal;
    text-decoration: revert;
    padding-top: 12px;
    font-family: 'Open Sans';
    line-height: 28px;
}
.my-profile .form-inline {
    padding: 17px 10px 17px 14px;
}
.my-profile .user_password {
    color: #d12a2b;
    padding-top: 12px;
    font-style: normal;
    text-decoration: revert;
    padding-top: 12px;
    font-family: 'Open Sans';
    line-height: 28px;
}

.switch {
	position: relative;
	display: inline-block;
	width: 70px;
	height: 27px;
}

.switch input {display:none;}

.slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #ca2222;
	-webkit-transition: .4s;
	transition: .4s;
	border-radius: 34px;
}

.slider:before {
	position: absolute;
	content: "";
	height: 17px;
	width: 17px;
	left: 7px;
	bottom: 5px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
	border-radius: 50%;
}

input:checked + .slider {
	background-color: #2ab934;
}

input:focus + .slider {
	box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
	-webkit-transform: translateX(26px);
	-ms-transform: translateX(26px);
	transform: translateX(55px);
}

.form-inline label{
	justify-content: left !important;
}
.slider:after
{
	content:'OFF';
	color: white;
	display: block;
	position: absolute;
	transform: translate(-50%,-50%);
	top: 50%;
	left: 64%;
	font-size: 13px;
	font-family: Verdana, sans-serif;
}

input:checked + .slider:after
{  
	content:'ON';
	left: 40%;
}

input:checked + .slider:before
{  
	left: -12px;
}

.form_user label{
	font-family: 'Open Sans', sans-serif;
	font-weight: 700 !important;
	font-size: 14px !important;
	color: black !important;
	margin: 6px 0px;
}
.icon_img{
	width: 19px !important;
}

@media (min-width: 769px) {

	.showpassword {
	    position: relative !important;
	    top: -42px !important;
	    left: 80% !important;
	    cursor: pointer !important;
	}
}


@media (min-width: 546px) and (max-width: 768px) {

	.showpassword {
	    position: relative !important;
	    top: -42px !important;
	    left: 90% !important;
	    cursor: pointer !important;
	}
}

@media (min-width: 360px) and (max-width: 545px) {

	.showpassword {
	    position: relative !important;
	    top: -42px !important;
	    left: 85% !important;
	    cursor: pointer !important;
	}
}

@media (min-width: 360px) and (max-width: 600px) {

	 

	.switch {
		position: relative;
		display: inline-block;
		width: 70px;
		height: 25px;
	}
	.form_user .user_label{
		display: flex;
		justify-content: space-between;
		margin: 0px 0px 5px;
	}
	.slider:before {
		position: absolute;
		content: "";
		height: 15px;
		width: 16px;
		left: 9px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 50%;
	}
}


    </style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="my-profile">
	<div class="container">
		<div class="row">
		<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<div class="users_form">
					<div class="profiles">
						<h4 class="information" >My Profile</h4>
					</div>
					<?php
					   if ($this->session->flashdata('error')) { ?>
						<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
						<?php } ?>
						<?php if ($this->session->flashdata('success')) { ?>
						<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>
					<div class="row form_user">
					<form class="form-inline">

						<div class="col-sm-4 col-md-4 col-lg-4 px-0 user_label">
							<label for='sms'>SMS</label>
							<label class="switch">
								<input type="checkbox"  class="notification" name="sms_notification" data-type="sms_notification" id="sms" <?php if(set_value('sms_notification')): echo "checked";  elseif($profileDetails['sms_notification'] == "on"): echo "checked"; endif; ?> >
								<div class="slider round"></div>
							</label>
						</div>

						<div class="col-sm-4 col-md-4 col-lg-4 px-0 user_label">
							<label for="e-mail">E-mail</label>
							<label class="switch">
								<input type="checkbox" class="notification" name="email_notification" data-type="email_notification" id="e-mail" <?php if(set_value('email_notification')): echo "checked";  elseif($profileDetails['email_notification'] == "on"): echo "checked"; endif; ?>>
								<div class="slider round"></div>
							</label>
						</div>

						<div class="col-sm-4 col-md-4 col-lg-4 px-0 user_label">
							<label for="notification">Notification</label>
							<label class="switch">
								<input type="checkbox" class="notification" name="notification" data-type="notification" id="notification" <?php if(set_value('notification')): echo "checked";  elseif($profileDetails['notification'] == "on"): echo "checked"; endif; ?> >
								<div class="slider round"></div>
							</label>
						</div>

						<div class="col-md-4 px-0">
						<div class="inputWithIcon">
							  <input type="text" name="name" value="<?=@$profileDetails['users_name']?>" placeholder="Name" readonly>
							  <i class="far fa-user " aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-md-4 px-0">
							<div class="inputWithIcon">
								<input type="text" name="last_name" value="<?=@$profileDetails['last_name']?>" placeholder="Last Name" readonly>
								<i class="far fa-user " aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-md-4 px-0">
						<div class="inputWithIcon">
								  <input type="text" value="<?=@$profileDetails['country_code']?> <?=@$profileDetails['users_mobile']?>" placeholder="Mobile Number" readonly>
								 <i class="fas fa-mobile" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-md-4 px-0">
						<div class="inputWithIcon" style="margin-right:0px;">
								  <input type="text" value="<?=@$profileDetails['users_email']?>" placeholder="Email" readonly>
								 <i class="far fa-envelope" aria-hidden="true"></i>
							</div>
						</div>
						<!--<div class="col-md-4 px-0">
						<div class="inputWithIcon">
							  <select name="Gender" id="Gender" p>
							<option value="Men" <?php if($profileDetails['Gender']): if($profileDetails['Gender'] == 'Men'): echo 'selected';endif;endif;?>>Men</option>
							<option value="Women" <?php if($profileDetails['Gender']): if($profileDetails['Gender'] == 'Women'): echo 'selected';endif;endif;?>>Women</option>
						  </select>
							</div>
						</div>-->
						<!-- <div class="col-md-4 px-0">
						<div class="inputWithIcon">
							  <input type="date" value="<?=@$profileDetails['dob']?>" placeholder="Date of Birth" readonly>
							</div>
						</div> -->
					 </form>
					</div>
					<div class="change_password">
						 <a href="javascript:void(0);" class="user_password" onclick="ConfirmForm();">Change Password</a>
						 <a class="btn" href="<?php echo base_url()?>edit-profile/<?=manojEncript($profileDetails['users_id'])?>">Update Profile</a>
					</div>
				</div>
				<div class="confirm_password" id="BlockUIConfirm">
					<div class="col-md-12 px-0" >
								
						<div class="users_form">
							<div class="row form_user">
							<!--<form class="form-inline" id="form" action="<?=base_url('profile/changepassword/'.@$profileDetails['users_id'])?>" method="post">-->
							<form class="form-inline" id="form" action="" method="post">
								<div class="col-md-4 px-0">
								<div class="inputWithIcon form-group">
								<label class="password">Old Password</label>
									  <input type="password" name="old_password" id="old_password" class="password2" value="<?php if(set_value('old_password')): echo set_value('old_password'); endif; ?>" placeholder="Old Password" class=" <?php if(form_error('old_password')): ?>error<?php endif; ?>">
									  	<i class="fas showpassword fa-eye"></i>
									  	<?php if(form_error('old_password')): ?>
				                          <label id="old_password-error" class="error" for="old_password"><?php echo form_error('old_password'); ?></label>
				                        <?php elseif($oldPassError): ?>
				                          <label id="old_password-error" class="error" for="old_password"><?php echo $oldPassError; ?></label>
				                        <?php endif; ?>
									</div>
								</div>
								<div class="col-md-4 px-0">
								<div class="inputWithIcon form-group">
								<label class="password">New Password</label>
									  <input type="password" name="new_password" id="new_password" class="password2" value="<?php if(set_value('new_password')): echo set_value('new_password'); endif; ?>" placeholder="New Password" class=" <?php if(form_error('new_password')): ?>error<?php endif; ?>">
									  	<i class="fas showpassword fa-eye"></i>
									  	<?php if(form_error('new_password')): ?>
				                          <label id="new_password-error" class="error" for="new_password"><?php echo form_error('new_password'); ?></label>
				                        <?php endif; ?>
									</div>
								</div>
								<div class="col-md-4 px-0">
								<div class="inputWithIcon form-group" style="margin-right:0px;">
									<label class="password">Confirm Password</label>
									<input type="password" name="conf_password" id="conf_password" class="password2" value="<?php if(set_value('conf_password')): echo set_value('conf_password'); endif; ?>" placeholder="Confirm Password" class=" <?php if(form_error('conf_password')): ?>error<?php endif; ?>">
									<i class="fas showpassword fa-eye"></i>
									<?php if(form_error('conf_password')): ?>
			                          <label id="conf_password-error" class="error" for="conf_password"><?php echo form_error('conf_password'); ?></label>
			                        <?php endif; ?>
								</div>
								</div>
									<div class="col-md-9 px-0">
									    </div>
								<div class="col-md-2 px-0">
								<div class="change_passworded">
									<input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
									 <button  class="btn">Update Password</button>
								</div>
								</div>
							
							 </form>
							</div>
							
						</div>
					</div>
			   </div>
			</div>
		</div>
	</div>
</div>

<?php include('common/footer.php') ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
<script src="<?=base_url('assets/')?>js/bootstrap.min.js"></script>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" ></script>
<script>
	AOS.init({
	  duration: 1200,
	})
</script>
<script>
	var s = $("#sticker");
	var pos = s.position();					   
	$(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		if (windowpos > pos.top) {
		s.addClass("stick");
		} else {
			s.removeClass("stick");	
		}
	});
</script>
<script>
	function ConfirmForm() {
		$("#BlockUIConfirm").show();
	}
	$(document).ready(function(){
		<?php if($error == "YES"): ?>
			$("#BlockUIConfirm").show();
		<?php endif; ?>
	});
</script>
<script>
	jQuery("#main-carousel").owlCarousel({
	  autoplay: true,
	  loop: true,
	  margin: 0,
	  transitionStyle : "goDown",
	  responsiveClass: true,
	  autoHeight: true,
	  autoplayTimeout: 7000,
	  smartSpeed: 800,
	  lazyLoad: false,
	  nav: false,
	  dots:true,
	  responsive: {
		0: {
		  items: 1
		},

		600: {
		  items: 1
		},

		1024: {
		  items: 1
		},

		1366: {
		  items: 1
		}
	  }
	});
</script>
<script>
	jQuery("#closing-soon").owlCarousel({
	  autoplay: true,
	  lazyLoad: true,
	  loop: true,
	  margin: 20,
	  responsiveClass: true,
	  autoHeight: true,
	  autoplayTimeout: 7000,
	  smartSpeed: 800,
	  dots: false,
	  nav: true,
	  responsive: {
		0: {
		  items: 1
		},

		600: {
		  items: 3
		},

		1024: {
		  items: 4
		},

		1366: {
		  items: 4
		}
	  }
	});
</script>
<script>
	jQuery("#sold-out").owlCarousel({
	  autoplay: true,
	  lazyLoad: true,
	  loop: true,
	  margin: 20,
	  responsiveClass: true,
	  autoHeight: true,
	  autoplayTimeout: 7000,
	  smartSpeed: 800,
	  dots: false,
	  nav: true,
	  responsive: {
		0: {
		  items: 1
		},

		600: {
		  items: 3
		},

		1024: {
		  items: 4
		},

		1366: {
		  items: 4
		}
	  }
	});
</script>
<script>
	var $owl = $('#testimonial');

		$owl.children().each( function( index ) {
		  $(this).attr( 'data-position', index ); 
		});

		$owl.owlCarousel({
		  center: true,
		  loop: true,
		  dots:true,
		  nav:true,
		  responsive: {
			0: {
			  items: 1
			},

			600: {
			  items: 1
			},

			800: {
			  items: 3
			},

			1366: {
			  items: 3
			}
		  }
		});

		$(document).on('click', '.owl-item>div', function() {
		 
		  var $speed = 300;  
		  $owl.trigger('to.owl.carousel', [$(this).data( 'position' ), $speed] );
		});
</script>

<script>
	 $('.dropdown > .caption').on('click', function() {
		$(this).parent().toggleClass('open');
	});

    $('.dropdown > .list > .item').on('click', function() {
		$('.dropdown > .list > .item').removeClass('selected');
		$(this).addClass('selected').parent().parent().removeClass('open').children('.caption').html($(this).html());

		if ($(this).data("item") == "RUB") {
			console.log('RUB');
		} else if ($(this).data("item") == "UAH") {
			console.log('UAH');
		} else {
			console.log('USD');
		}
	});

	$(document).on('keyup', function(evt) {
		if ((evt.keyCode || evt.which) === 27) {
			$('.dropdown').removeClass('open');
		}
	});

	$(document).on('click', function(evt) {
		if ($(evt.target).closest(".dropdown > .caption").length === 0) {
			$('.dropdown').removeClass('open');
		}
	});
</script>

<script type="text/javascript">

$('.notification').on('click' ,function(){

	var fieldName = $(this).attr('data-type');
	var ur = '<?=base_url()?>';
	$.ajax({
		url : ur+ "my-profile/notification",
		method: "POST", 
		data: { fieldName: fieldName },
		success: function(data){
			if(data == 1 ){
				location.reload();
			}
		}
	});
});

$("#form").validate({
rules: {
old_password: {
required: true,
},
new_password: {
required: true,
},
conf_password: {
required: true,
},
},
});
</script>
</body>
</html>

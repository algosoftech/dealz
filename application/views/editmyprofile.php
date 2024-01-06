
<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
	    .my-profile .confirm_password .change_passworded {
		    text-align: end!important;
		    padding: 28px 28px 17px 36px;
		}
		#country_code:focus-visible{
	    	border:1px solid grey;
	    
		}
		#country_code {
		    margin: 0;
		    font-family: inherit;
		    font-size: inherit;
		    line-height: inherit;
		    color: #495057d9;
		    width: 82px;
		    padding: 5px 8px;
		    border: none;
		}
		.custom_code{
			width: 100%;
    display: flex;
    align-items: center;
		}
		#country_code {
    margin: 0;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
    color: #495057d9;
    width: 82px;
    padding: 5px 1px;
    border: none;
    border: 2px solid #f1f1f1;
    height: 38px;
    border-radius: 4px;
    border-top-right-radius: 0px;
    border-bottom-right-radius: 0px;
    border-right: 0px;
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
				    <form class="form-inline" method="post">
					<div class="row form_user">
					
					
					<div class="col-sm-4 col-md-4 col-lg-4 px-0 user_label">
						<label for='sms'>SMS</label>
						<label class="switch">
							<input type="checkbox"  name="sms_notification" id="sms" <?php if(set_value('sms_notification')): echo "checked";  elseif($profileDetails['sms_notification'] == "on"): echo "checked"; endif; ?> >
							<div class="slider round"></div>
						</label>
					</div>

					<div class="col-sm-4 col-md-4 col-lg-4 px-0 user_label">
						<label for="e-mail">E-mail</label>
						<label class="switch">
							<input type="checkbox" name="email_notification" id="e-mail" <?php if(set_value('email_notification')): echo "checked";  elseif($profileDetails['email_notification'] == "on"): echo "checked"; endif; ?>>
							<div class="slider round"></div>
						</label>
					</div>

					<div class="col-sm-4 col-md-4 col-lg-4 px-0 user_label">
						<label for="notification">Notification</label>
						<label class="switch">
							<input type="checkbox" name="notification" id="notification" <?php if(set_value('notification')): echo "checked";  elseif($profileDetails['notification'] == "on"): echo "checked"; endif; ?> >
							<div class="slider round"></div>
						</label>
					</div>
					<div class="col-md-4 px-0">
						<input type="hidden" name="current_user_id" id="current_user_id" value="<?php echo $profileDetails['users_id']; ?>">
					<div class="inputWithIcon">
                    <input type="text" value="<?php if(set_value('users_name')): echo set_value('users_name'); else: echo stripslashes($profileDetails['users_name']); endif; ?>" placeholder="Name" name="users_name" class=" <?php if(form_error('users_name')): ?>error<?php endif; ?>">
						  <i class="far fa-user " aria-hidden="true"></i>
						  <?php if(form_error('users_name')): ?>
	                          <label id="users_name-error" class="error" for="users_name"><?php echo form_error('users_name'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-4 px-0">
						<div class="inputWithIcon">
							<input type="text" value="<?php if(set_value('last_name')): echo set_value('last_name'); else: echo stripslashes($profileDetails['last_name']); endif; ?>" placeholder="Last Name" name="last_name" class=" <?php if(form_error('last_name')): ?>error<?php endif; ?>">
							<i class="far fa-user " aria-hidden="true"></i>
							<?php if(form_error('last_name')): ?>
								<label id="last-name-error" class="error" for="last_name"><?php echo form_error('last_name'); ?></label>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-md-4 px-0">
						<div class="custom_code" style="width:100%">
							<?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>
							<select name="country_code" id="country_code" <?php if($profileDetails['country_code']): echo 'disabled'; endif; ?>>
								<?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
									<option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeKey; ?></option>
								<?php endforeach; endif; ?>
							</select>
							<div class="inputWithIcon" style="width:100%">
						  <input type="text" value="<?php if(set_value('users_mobile')): echo set_value('users_mobile'); else: echo stripslashes($profileDetails['users_mobile']); endif; ?>" placeholder="Mobile Number" name="users_mobile" class=" <?php if(form_error('user_mobile')): ?>error<?php endif; ?>" <?php if($profileDetails['users_mobile']): echo 'disabled'; endif; ?> >
						 <i class="fas fa-mobile" aria-hidden="true"></i>
						 <?php if(form_error('users_mobile')): ?>
	                          <label id="users_mobile-error" class="error" for="users_mobile"><?php echo form_error('users_mobile'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
						</div>
					
					<div class="col-md-4 px-0">
					<div class="inputWithIcon">
						  <input type="text" value="<?php if(set_value('users_email')): echo set_value('users_email'); else: echo stripslashes($profileDetails['users_email']); endif; ?>" placeholder="Email" name="users_email" class=" <?php if(form_error('users_email')): ?>error<?php endif; ?>">
						 <i class="far fa-envelope" aria-hidden="true"></i>
						 <?php if(form_error('users_email')): ?>
	                          <label id="users_email-error" class="error" for="users_email"><?php echo form_error('users_email'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>

					<!-- <div class="col-md-4 px-0">
					<div class="inputWithIcon">
						  <select name="gender" id="gender">
						<option value="Men" <?php if($profileDetails['gender']): if($profileDetails['gender'] == 'Men'): echo 'selected';endif;endif;?>>Men</option>
						<option value="Women" <?php if($profileDetails['gender']): if($profileDetails['gender'] == 'Women'): echo 'selected';endif;endif;?>>Women</option>
					  </select>
						</div>
					</div> -->
					
					</div>
					<div class="change_password">
						 <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
						 <button class="btn" type="submit" name="submit">Update Profile</button>
						 <!--<a class="btn" href="<?=base_url('profile/editusers/'.@$profileDetails['users_id']) ?>">Update Profile</a>-->
						 </div>
						 </form>
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
</body>
</html>
<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
	.tacbox {
	  display: flex;
	    padding: 0px;
	    margin: 0px;
	    max-width: 100%;
	}
	.form-control_45{
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    padding: 8px 98px !important;
    padding-left: 91px;
    color: #000;
  }
	.condition{
	  color: grey;
	  font-size: 14px;
	  font-weight: 500;
	}
	.points{
	   display:flex; 
	}
	.forms_1{
	border-top-left-radius:0px !important;
	  border-bottom-left-radius:0px !important;
	}
	.custom_code label{
	position: relative;
	  top: 9px;
	    
	}
	.custom_code label{
		position: relative;
	   top: 9px;
	}
	.custom_code span {
	    position: relative;
	    top: 5px;
	    font-size: 12px;
	    left: 5px;
	}
	.custom_code{
	    border: 1px solid #e0e0e0;
	    color: rgb(108 117 125);
	    font-size: 15px;
	    /* border-radius: 2px; */
	    border-top-right-radius: 1px;
	    border-top-left-radius: 7px;
	    border-bottom-right-radius: 0px;
	    border-bottom-left-radius: 9px;
	    line-height: 4px;
	    position: relative;
	    padding: 0px 15px;
	}
	.signup-block .login-box .color-default-btn {
	    background: #e22c2d;
	    padding: 21px 20px !important;
	    border-radius: 7px;
	    display: inline-block;
	    color: #fff;
	    font-family: 'Open Sans', sans-serif;
	    font-size: 15px !important;
	    font-weight: 400;
	    border: 1px solid #e22c2d;
	    width: 100%;
	    line-height: 0px !important;
	}
	#checkbox{
	  height: 1em;
	  width: 2em;
	  vertical-align: middle;
	}

	.error{
		color:red;
	}
	.signup-block .login-box h4 {
	    font-family: 'Open Sans', sans-serif;
	    font-size: 21px;
	    color: #020202;
	    font-weight: 700;
	    line-height: 32px;
	    margin: 15px 0 15px;
	    position: relative;
	    left: -21px;
	}
	.signup-block .login-box .forgot-link {
	    text-align: end !important;
	    display: block;
	    color: #be1a29;
	    font-family: 'Open Sans', sans-serif;
	    font-size: 17px !important;
	    font-weight: 500 !important;
	    }
	.color-default-btn {
	    background: #e22c2d;
	    padding: 9px 20px !important;
	    border-radius: 7px;
	    display: inline-block;
	    color: #fff;
	    font-family: 'Open Sans', sans-serif;
	    font-size: 15px;
	    font-weight: 400;
	    border: 1px solid #e22c2d;
	    width: 100%;
	}
	.signup-block .login-box {
	    background: #fff;
	    padding: 20px 40px;
	    border-radius: 8px;
	    text-align: center;
	    position: relative;
	    margin: 109px 20px 25px;
	    -webkit-box-shadow: 1px 1px 5px 0px rgb(189 189 189);
	    -moz-box-shadow: 1px 1px 5px 0px rgba(189,189,189,1);
	    box-shadow: 1px 1px 5px 0px rgb(189 189 189);
	}
	.login_img {
	    width: 77%;
	    padding-top: 88px;
	    /* height: 612px; */
	    position: relative;
	    left: 50px;
	    top: 32px;
	}
	@media (min-width: 360px) and (max-width: 600px) {
.signup-block .login-box {
    background: #fff;
    padding: 20px 18px;
    border-radius: 8px;
    text-align: center;
    position: relative;
    margin: 109px 20px 25px;
    -webkit-box-shadow: 1px 1px 5px 0px rgb(189 189 189);
    -moz-box-shadow: 1px 1px 5px 0px rgba(189,189,189,1);
    box-shadow: 1px 1px 5px 0px rgb(189 189 189);
}
	    .login_img {
    width: 77%;
    padding-top: 88px;
    /* height: 612px; */
    position: relative;
    left: 50px;
    top: 32px;
    display: none;
}
		.form-control_45 {
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    padding: 8px 0px !important;
    padding-left: 82px !important;
    color: #000;
}
	.page-ending ul {
     display:flex !important;
	 flex-direction: column !important;

	}
}



.error {
    display: none;
}

</style>
	
</head>
<body>
<?php include('common/header.php') ?>
<div class="signup-block">
	<div class="container">
		<div class="row">
		    <div class="col-lg-7 col-md-12 col-12">
		        <img src="assets/img/sign-up1-image.png" class="login_img" alt="signup_banner">
		        </div>
			<div class="col-lg-5  col-md-12 col-12">
				<div class="login-box">
					 <h4 style="margin-bottom:5px;">BUY &amp; <span>WIN</span></h4>
                      <p style="margin-bottom:10px;">We Make it affordable</p>
                      <p><small>Enter the details below to avail this offers!</small></p>
				   <?php
				   if ($this->session->flashdata('error')) { ?>
					<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					<?php } ?>
					<?php if ($this->session->flashdata('success')) { ?>
					<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>
					<form action="<?=base_url('sign-up')?>" method="post" id="form">
					<div class="form-group">
						<input type="text" name="first_name" id="name" value="<?php if(set_value('first_name')): echo set_value('first_name'); endif; ?>" class="form-control" placeholder="First Name">
                         <label id="first_name-error" class="error" for="first_name"><?php echo form_error('first_name'); ?></label>
					</div>
					<div class="form-group">
						<input type="text" name="last_name" id="last_name" value="<?php if(set_value('last_name')): echo set_value('last_name'); endif; ?>" class="form-control" placeholder="Last Name">
                        <label id="last_name-error" class="error" for="last_name"><?php echo form_error('last_name'); ?></label>
					</div>
				 	<div class="form-group">
                        <?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>
                        <select  name="country_code" id="country_coded"   class="form-control select-search " >
                            <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeValue; ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
					<div class="form-group">
                        <!-- <input type="text" class="form-control"  name="country_code" id="country_code" value="+971" > -->
						<input type="tel" name="mobile" id ="mobile" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" class="form-control form-control_45" placeholder="Mobile Number">
                         <label id="mobile-error" class="error" for="mobile"> <?php echo form_error('mobile'); ?></label>
						<?php if(form_error('mobile')): ?>
                        <?php endif; ?>
					</div>

					<div class="form-group">
						<input type="email" name="email" id="email" value="<?php if(set_value('email')): echo set_value('email'); endif; ?>" class="form-control" placeholder="Email Address(Optional)">
                        <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
					</div>
					
					<div class="form-group">
                    </div>
					
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control password2" placeholder="Enter Password">
						<i class="fas fa-eye showpassword"></i> 
                      	<label id="password-error" class="error" for="password"><?php echo form_error('password'); ?></label>
					</div>

					<div class="form-group">
						<input type="password" name="confirm_password" id="confirm_password" class="form-control password2" placeholder="Enter confirm password">
						<i class="fas fa-eye showpassword"></i> 
                      	<label id="confirm_password-error" class="error" for="confirm_password"><?php echo form_error('confirm_password'); ?></label>
					</div>

					<div class="tacbox">
	                  <input name="term_condition" class="termsConditions" id="checkbox" type="checkbox" value="Yes" />
	                  <label for="checkbox" style="font-size: 13px;color: grey;">  I agree to <a href="<?php echo base_url('terms-condition'); ?>" class="condition">Usage and Terms</a>
	                    and <a href="<?php echo base_url('privacy-policy'); ?>" class="condition">Privacy Policy</a></label>
                	</div>
                	<div class="tacbox">
	                  <label id="term_condition-error" class="error" for="term_condition"></label>
                	</div>
					<div class="row">
						<div class="col-lg-5 col-md-5 col-12">
						</div>
							<div class="col-lg-7 col-md-7 col-12">
							<div class="form-group">
								<a href="<?=base_url('login')?>" class="forgot-link" style="font-weight: 500 !important;">Sign in instead </a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="form-group">
								<input type="submit" class="color-default-btn float-right submit-button" value="Signup">
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<link href="<?=base_url('/assets/css/fSelect.css');?>" rel="stylesheet">
<script src="<?=base_url('/assets/js/fSelect.js');?>"></script> 
<script type="text/javascript">
  $(document).ready(function(){  
    $('.select-search').fSelect();
  });
</script>


    <!-- Country code popup Ui end -->
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

<script type="text/javascript">
	$('#form').submit(function(event) {

		let error;
		let name 	   		  = $('#name').val();
		let last_name  		  = $('#last_name').val();
		let password   		  = $('#password').val();
		let confirm_password  = $('#confirm_password').val();
		let termsConditions = $(".termsConditions").is(":checked")


		if(name == ''){
			error = 'First name is required';
			$('#first_name-error').text(error);
			$('#first_name-error').show();
			event.preventDefault();
		}else if($.isNumeric(name)){
			error = 'Please enter valid First Name.';
			$('#first_name-error').text(error);
			$('#first_name-error').show();
			event.preventDefault();
		}else if(name.length < 2 ){
			error = 'Please Enter valid First Name';
			$('#first_name-error').text(error);
			$('#first_name-error').show();
			event.preventDefault();
		}else{
			error = '';
			$('#first_name-error').hide();
			$('#first_name-error').empty();
		}

		if(last_name == ''){
			error = 'Last Name is required';
			$('#last_name-error').text(error);
			$('#last_name-error').show();
			event.preventDefault();
		}else if($.isNumeric(last_name)){
			error = 'Please enter valid Last Name.';
			$('#last_name-error').text(error);
			$('#last_name-error').show();
			event.preventDefault();
		}else if(last_name.length < 2 ){
			error = 'Please Enter valid Last Name';
			$('#last_name-error').text(error);
			$('#last_name-error').show();
			event.preventDefault();
		}else{
			error = '';
			$('#last_name-error').empty();
			$('#last_name-error').hide();
		}

		if(password == ''){
			error = 'Password is required';
			$('#password-error').text(error);
			$('#password-error').show();
			event.preventDefault();
		}else if(password.length < 8 ){
			error = 'Password should be altest 8 digits';
			$('#password-error').text(error);
			$('#password-error').show();
			event.preventDefault();
		}else if(password.length > 25 ){
			error = 'Password should be within 25 digits';
			$('#password-error').text(error);
			$('#password-error').show();
			event.preventDefault();
		}else{
			error = '';
			$('#password-error').empty();
			$('#password-error').hide();
		}

		if(confirm_password == ''){
			error = 'Confirm password is required';
			$('#confirm_password-error').text(error);
			$('#confirm_password-error').show();
			event.preventDefault();
		}else if(confirm_password.length < 8 ){
			error = 'Password should be altest 8 digits';
			$('#confirm_password-error').text(error);
			$('#confirm_password-error').show();
			event.preventDefault();
		}else if(confirm_password.length > 25 ){
			error = 'Password should be within 25 digits';
			$('#confirm_password-error').text(error);
			$('#confirm_password-error').show();
			event.preventDefault();
		}else if (confirm_password != password){
			error = 'The Confirm Password does not match the Password.';
			$('#confirm_password-error').text(error);
			$('#confirm_password-error').show();
			event.preventDefault();
		}else{
			error = '';
			$('#confirm_password-error').empty();
			$('#confirm_password-error').hide();
		}


		

		

	    if(termsConditions == false){
	    	error = 'Please accept term & Conditions';
			$('#term_condition-error').text(error);
			$('#term_condition-error').show();
			event.preventDefault();
	    }else{
	    	error = '';
			$('#term_condition-error').empty();
			$('#term_condition-error').hide();
	    }
	});

$('#email').on('keyup', function(){
			let email = $('#email').val();
			// console.log(email);
			if(email != ''){
		        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		        if (!emailPattern.test(email)) {
		            error = "Please enter a valid email address.";
		            $("#email-error").empty('');
					$("#email-error").text(error);
					$('#email-error').show();
					$('.submit-button').attr('disabled',true);
		        }else{
		        	// Email ID Validation.
		 			$.ajax({
						 type:"GET",  
				         url: "<?=base_url('signup/checkDuplicateEmail');?>",  
				         data:"email="+email,
				         success:function(data){  
				         	if(data == false ){
								error = "This email is already exist! Try another.";
								$("#email-error").empty('');
								$("#email-error").text(error);
								$('#email-error').show();
								$('.submit-button').attr('disabled',true);
				         	}else{
			         			error = "";
								$("#email-error").empty('');
								$('#email-error').hide();
								$('.submit-button').attr('disabled',false);
				         	}
				         }  
					});
		     	}
			}
		});

		$('#mobile').on('keyup' , function(){
			let error;
			let CountryCode 	 = $("#country_code").val();
			let mobileNumber	 = $(this).val();

			// non numaric keys validation.
			if(!$.isNumeric(mobileNumber)){
			  error = 'Please enter valid mobile number';
			}else if( mobileNumber.charAt(0) === '0'   ){
			  error =  'First number should not be zero';
			}
			else if(CountryCode == '+971' &&  ( mobileNumber.length <9 || mobileNumber.length  >9 ) ){
			  error =  'Please enter valid 9 digits mobile number';
			}else if(CountryCode == '+974' &&  ( mobileNumber.length <8 || mobileNumber.length  >8 ) ){
			  error =  'Please enter valid 8 digits mobile number';
			}else if(CountryCode == '+62' &&  ( mobileNumber.length <11 || mobileNumber.length  >11 ) ){
			  error =  'Please enter valid 11 digits mobile number';

			}else if(  CountryCode == '+91'  &&  ( mobileNumber.length <10 || mobileNumber.length  >10 ) ||  
					   CountryCode == '+92'  &&  ( mobileNumber.length <10 || mobileNumber.length  >10 ) ||   
					   CountryCode == '+880' &&  ( mobileNumber.length <10 || mobileNumber.length  >10 ) || 
					   CountryCode == '+977' &&  ( mobileNumber.length <10 || mobileNumber.length  >10 ) || 
					   CountryCode == '+63'  &&  ( mobileNumber.length <10 || mobileNumber.length  >10 )   
			){

			  error =  'Please enter valid 10 digits mobile number';
			}else if(
					 CountryCode == '+971'  &&  mobileNumber.length == 9   || 
					 CountryCode == '+974'  &&  mobileNumber.length == 8   ||
					 CountryCode == '+62' 	&&  mobileNumber.length == 11  ||
					 CountryCode == '+91'   &&  mobileNumber.length == 10  ||
					 CountryCode == '+92'   &&  mobileNumber.length == 10  ||
					 CountryCode == '+880'  &&  mobileNumber.length == 10  ||
					 CountryCode == '+977'  &&  mobileNumber.length == 10  ||
					 CountryCode == '+63'   &&  mobileNumber.length == 10  
					
					){

					$.ajax({
						 type:"GET",  
				         url: "<?=base_url('signup/checkDuplicateMobile');?>",  
				         data:"mobile="+mobileNumber,
				         success:function(data){  
				         	if(data == false ){
			  					error = "This mobile no. is already exist! Try another.";
			  					$("#mobile-error").empty('');
								$("#mobile-error").text(error);
								$('#mobile-error').show();
								$('.submit-button').attr('disabled',true);
				         	}else{
				         		error = "";
			  					$("#mobile-error").empty('');
								$('#mobile-error').hide();
								$('.submit-button').attr('disabled',false);
				         	}
				         }  
					});

			}else{
			    error = "";
			}

			if(error){
				$("#mobile-error").empty('');
				$("#mobile-error").text(error);
				$('#mobile-error').show();
				$('.submit-button').attr('disabled',true);
			}else{
				$("#mobile-error").text(error);
				$('#mobile-error').hide();
				$('.submit-button').attr('disabled',false);
			}
		});
</script>
</body>
</html>


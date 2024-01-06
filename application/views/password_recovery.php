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
	.login_button {
    background: #e22c2d;
    padding: 9px 20px;
    border-radius: 7px;
    display: inline-block;
    color: #fff;
    font-family: 'Open Sans', sans-serif;
    font-size: 16px;
    font-weight: 500;
    border: 1px solid #e22c2d;
    width: 100%;
}
.login-block .account_content a {
    margin-left: 10px;
    color: #e22c2d;
    text-align: center;
    display: block;
    color: #be1a29;
    font-family: 'Open Sans', sans-serif;
    font-size: 17px;
    font-weight: 600;
}

	.login-block .login-box .forgot-link {
   text-align: end;
    display: block;
    color: #be1a29;
    font-family: 'Open Sans', sans-serif;
    font-size: 17px;
    font-weight: 500;
}
	.color-default-btn {
    background: #e22c2d;
    padding: 9px 20px;
    border-radius: 7px;
    display: inline-block;
    color: #fff;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    border: 1px solid #e22c2d;
    width: 100%;
}
	
</style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="login-block">
	<div class="container">
		<div class="row">
			<div class="offset-md-7 col-md-5 col-12">
				<div class="login-box">
					<?php if ($this->session->flashdata('error')) { ?>
					<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					<?php } ?>
					<?php if ($this->session->flashdata('success')) { ?>
					<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>

					<h4>Your First Purchase <br> CAN BE <span>FREE</span>.</h4>
					<form id="login" action="<?=base_url('password-recover')?>" method="post">
					
					<div class="form-group">
						<input type="text" name="userotp" id="userotp" class="form-control" value="<?php if(set_value('userotp')): echo set_value('userotp'); endif; ?>" placeholder="Enter OTP">
						<span class="icon"><i class="far fa-user"></i></span>
						<?php if(form_error('userotp')): ?>
							<span for="userotp" id="userotp-error" generated="true" class="error"><?php echo form_error('userotp'); ?></span>
						<?php endif; ?>
					</div>
					<div class="form-group my-0">
						<input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter New Password">
						<span class="icon"><i class="fas fa-key"></i></span>
						<?php if(form_error('new_password')): ?>
							<span for="new_password" id="new_password-error" generated="true" class="error"><?php echo form_error('new_password'); ?></span>
						<?php endif; ?>
					</div>
                    <div class="form-group my-0">
						<input type="password" name="conf_password" id="conf_password" class="form-control" placeholder="Enter Confirm Password">
						<span class="icon"><i class="fas fa-key"></i></span>
						<?php if(form_error('conf_password')): ?>
							<span for="conf_password" id="conf_password-error" generated="true" class="error"><?php echo form_error('conf_password'); ?></span>
						<?php endif; ?>
					</div>
					
					<div class="row">
						<div class="col-lg-5 col-md-5 col-12">
							
						</div>
						<div class="col-lg-7 col-md-7 col-12">
							<div class="form-group " >
								<!--<a href="<?=base_url('forgot-password')?>"  name="RecoverFormSubmit" class="forgot-link">Forgot Password?</a>-->
							</div>

						</div>
						
					</div>
					
					
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="form-group">
                                <input type="hidden" name="RecoverFormSubmit" id="RecoverFormSubmit" value="Yes">
								<input type="submit" class="login_button" value="Login">
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="form-group account_content">
								<!--<h5><a href="<?=base_url('sign-up')?>">Signup</a></h5>-->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
<script src="<?=base_url('assets/')?>js/bootstrap.min.js"></script>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" ></script>
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
<script type="text/javascript">
$("#login").validate({
rules: {
userotp: { required: true },
new_password: { required: true, },
conf_password: { required: true, equalTo : "#new_password"},
},
messages:{
userotp: { required: 'Please enter 4 digit otp', },
new_password: { required: 'Please enter New password.',},
conf_password: { required: 'Please enter confirm password',equalTo: 'Confirm password must be same as password'},
},

});
</script>

</body>
</html>

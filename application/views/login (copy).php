<!Doctype html>
<html lang="eng">
<head>
	<!-- Basic page needs -->	
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>

    <style>
    .error{	color: red; }
   
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
.login-block .login-box {
    background: #fff;
    padding: 20px 40px;
    border-radius: 8px;
    text-align: center;
    position: relative;
    margin: 110px 20px 30px;
    -webkit-box-shadow: 1px 1px 5px 0px rgb(189 189 189);
    -moz-box-shadow: 1px 1px 5px 0px rgba(189,189,189,1);
    box-shadow: 1px 1px 5px 0px rgb(189 189 189);
}
.login-block .login-box h4 {
    font-family: 'Open Sans', sans-serif;
    font-size: 21px;
    color: #020202;
    font-weight: 700;
    line-height: 32px;
    margin: 15px 0 30px;
    position: relative;
    left: 6px !important;
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
	/*
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
		.error{
		color: red;
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
*/
.login_img{
 width: 90%;
    padding-top: 88px;
    /* height: 612px; */
    position: relative;
    left: 39px;
    top: 52px;   
}
</style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="login-block">
	<div class="container">
		<div class="row">
		    <div class="col-md-7 col-md-7 col-12">
		        <img src="assets/img/login-banner.png" class="login_img">
		        </div>
			<div class=" col-md-5 col-12">
				<div class="login-box">
					<?php if ($this->session->flashdata('error')) { ?>
					<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					<?php } ?>
					<?php if ($this->session->flashdata('success')) { ?>
					<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>

					<?php if ($this->session->flashdata('successA')) { ?>
					<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('successA')?></label>
					<?php  } ?>

					<h4>Your First Purchase <br> CAN BE <span>FREE</span>.</h4>
					<form id="login" action="<?=base_url('login')?>" method="post">
					<div class="form-group">
						<input type="text" name="userid" id="userid" class="form-control" placeholder="Enter Email/Mobile No.">
						<span class="icon"><i class="far fa-user"></i></span>
					</div>
					<div class="form-group my-0">
						<input type="password" name="password" class="form-control" placeholder="Enter Password">
						<span class="icon"><i class="fas fa-key"></i></span>
					</div>
					
					<div class="row">
						<div class="col-lg-5 col-md-5 col-12">
							
						</div>
						<div class="col-lg-7 col-md-7 col-12">
							<div class="form-group " >
								<a href="<?=base_url('forgot-password')?>" class="forgot-link">Forgot Password?</a>
							</div>

						</div>
						
					</div>
					
					
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="form-group">
								<input type="submit" class="login_button" value="Login">
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="form-group account_content">
								<h5><a href="<?=base_url('sign-up')?>">Signup</a></h5>
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

<!-- Animation Js -->
<script>
	AOS.init({
	  duration: 1200,
	})
</script>
<script>
	/* TOP Menu Stick
	--------------------- */
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
userid: { required: true },
password: { required: true, },
},
messages:{
name: { required: 'Please enter your email or mobile no.', },
mobile: { required: 'Please enter mobile no.',},
},
/*submitHandler: function(form) {
form.submit();
}*/
});
</script>

</body>
</html>

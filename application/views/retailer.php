<?php //echo"workig"; ?>
<!Doctype html>
<html lang="eng">
<style>
	.error{
		color:red;
	}
</style>
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php include('common/head.php') ?>
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
						<h4 class="information" >Add Retailer</h4>
					</div>
					<div class="row form_user">
					<form class="form-inline" action="<?=base_url('add-retailer')?>" method="post" id="form">
					<div class="col-md-12 px-0">
						<div class="inputWithIcon">
							<input type="text" name="name" id="name" placeholder="Full Name">
						  	<i class="fa fa-address-book" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
							<input type="email" name="email" id="email" placeholder="Email">
						  	<i class="fa fa-address-book" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
							  <input type="text" name="mobile" placeholder="Mobile No.">
							 <i class="far fa-envelope" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
							  <input type="text" name="arabianPoints" id="arabianPoints" placeholder="Arabian Points" >
							 <i class="far fa-envelope" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
						  <input type="text" name="store" id="store" placeholder="Store Name" >
						  <i class="far fa-envelope" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
						  <input type="text" name="password" id="password" placeholder="Password" >
						  <i class="far fa-envelope" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
						  <input type="text" name="cpassword" id="cpassword" placeholder="Confirm Password" >
						  <i class="fa fa-globe" aria-hidden="true"></i>
						</div>
					</div>
					</div>
						<div class="change_password">
							 <button class="btn">Add</button>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<script>
var s = $("#sticker");
var pos = s.position();
$(window).scroll(function() {
	var windowpos = $(window).scrollTop();
	if(windowpos > pos.top) {
		s.addClass("stick");
	} else {
		s.removeClass("stick");
	}
});
</script>
<script>
	$('.minus').click(function () {
		var $input = $(this).parent().find('input');
		var count = parseInt($input.val()) - 1;
		count = count < 1 ? 1 : count;
		$input.val(count);
		$input.change();
		return false;
	});
	$('.plus').click(function () {
		var $input = $(this).parent().find('input');
		$input.val(parseInt($input.val()) + 1);
		$input.change();
		return false;
	});
</script>
<script>
$('.dropdown > .caption').on('click', function() {
	$(this).parent().toggleClass('open');
});
$('.dropdown > .list > .item').on('click', function() {
	$('.dropdown > .list > .item').removeClass('selected');
	$(this).addClass('selected').parent().parent().removeClass('open').children('.caption').html($(this).html());
	if($(this).data("item") == "RUB") {
		console.log('RUB');
	} else if($(this).data("item") == "UAH") {
		console.log('UAH');
	} else {
		console.log('USD');
	}
});
$(document).on('keyup', function(evt) {
	if((evt.keyCode || evt.which) === 27) {
		$('.dropdown').removeClass('open');
	}
});
$(document).on('click', function(evt) {
	if($(evt.target).closest(".dropdown > .caption").length === 0) {
		$('.dropdown').removeClass('open');
	}
});
</script>
<script type="text/javascript">
$("#form").validate({
rules: {
name: { required: true },
mobile: { required: true, maxlength: 10, remote:'signup/checkDuplicateMobile'},
email: { required: true, remote: "signup/checkDuplicateEmail" },
arabianPoints: { required: true },
store: { required: true },
password: { required: true, maxlength: 12, minlength: 7 },
cpassword : {required: true, equalTo: password}
},
messages:{
name: { required: 'Please enter your name.' },
mobile: { required: 'Please enter mobile number.', remote:"This mobile no. is already exist! Try another."},
email: { required: 'Please enter email.', remote:"This email is already exist! Try another."},
arabianPoints: { required: 'Please enter arabian points.'},
store: { required: 'Please enter store name.'},
password: {required : 'Please enter your password'},
cpassword : {required : 'Please enter your conform password', equalTo: 'Both password does not match.'}
},

});
</script>
</body>

</html>

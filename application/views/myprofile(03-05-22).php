<!Doctype html>
<html lang="eng">
<head>
	<!-- Basic page needs -->	
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dealz Arabia || Myprofile</title>
    <meta name="description" content="">
	<!-- Mobile specific metas -->		
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<!-- Favicon -->
	<link rel="icon" href="<?=base_url('assets/')?>img/h-1.png" type="image/x-icon"/>
	<!--All Fonts  Here -->
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
	<!-- style CSS -->			
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/style.css">
	<!-- responsive CSS -->			
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/responsive.css">
    <style>
        .my-profile .confirm_password .change_passworded {
    text-align: end!important;
    padding: 28px 28px 17px 17px;
}
.my-profile .user_profile p {
    margin: 0px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    font-size: 12px;
    color: black !important;
}
    </style>
</head>
<body>
<?php include('common/header.php') ?>
<!-- profile -->
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
					<div class="row form_user">
					<form class="form-inline">
					<div class="col-md-4 px-0">
					<div class="inputWithIcon">
						  <input type="text" name="name" value="<?=@$profileDetails['users_name']?>" placeholder="Name" readonly>
						  <i class="far fa-user " aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-4 px-0">
					<div class="inputWithIcon">
							  <input type="text" value="<?=@$profileDetails['users_mobile']?>" placeholder="Mobile Number" readonly>
							 <i class="fas fa-mobile" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-4 px-0">
					<div class="inputWithIcon">
							  <input type="text" value="<?=@$profileDetails['users_email']?>" placeholder="Email" readonly>
							 <i class="far fa-envelope" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-md-4 px-0">
					<div class="inputWithIcon">
						  <select name="Gender" id="Gender" p>
						<option value="Men">Men</option>
						<option value="Women">Women</option>
					  </select>
						</div>
					</div>
					<!-- <div class="col-md-4 px-0">
					<div class="inputWithIcon">
						  <input type="date" value="<?=@$profileDetails['dob']?>" placeholder="Date of Birth" readonly>
						</div>
					</div> -->
					 </form>
					</div>
					<div class="change_password">
						 <a href="#" class="user_password" onclick="ConfirmForm();">Change your password</a>
						  <a class="btn" href="<?=base_url('profile/editusers/'.@$profileDetails['users_id']) ?>">Update Profile</a>
						 </div>
				</div>
				<div class="confirm_password" id="BlockUIConfirm">
					<div class="col-md-12 px-0" >
								
						<div class="users_form">
							<div class="row form_user">
							<form class="form-inline" action="<?=base_url('profile/changepassword/'.@$profileDetails['users_id'])?>" method="post">
							    
							    <div class="col-md-5 px-0">
							<div class="inputWithIcon">
							<label class="password">OLD Password</label>
								  <input type="text" name="old_password" id="old_password" placeholder="Change Password">
								</div>
							</div>
							<div class="col-md-5 px-0">
							<div class="inputWithIcon">
							<label class="password">Change password</label>
								  <input type="text" placeholder="Change Password">
								</div>
							</div>
							<div class="col-md-5 px-0">
							<div class="inputWithIcon">
							<label class="password">Confirm password</label>
									  <input type="text" placeholder="Confirm Password">
								</div>
							</div>
							<div class="col-md-2 px-0">
							<div class="change_passworded">
							    <input type="hidden" name="savechanges" id="savechanges" value="Yes">
								 <button class="btn">Update password</button>
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
<!-- Main Slider Js -->
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
<!-- Home Closing Soon Slider -->
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
<!-- Home Sold Out Slider -->
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
<!-- Testimonial Slider -->
<script>
	var $owl = $('#testimonial');

		$owl.children().each( function( index ) {
		  $(this).attr( 'data-position', index ); // NB: .attr() instead of .data()
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
		  // see https://owlcarousel2.github.io/OwlCarousel2/docs/api-events.html#to-owl-carousel
		  var $speed = 300;  // in ms
		  $owl.trigger('to.owl.carousel', [$(this).data( 'position' ), $speed] );
		});
</script>
<!-- Header Dropdown -->
<script>
	 $('.dropdown > .caption').on('click', function() {
		$(this).parent().toggleClass('open');
	});

	// $('.price').attr('data-currency', 'RUB');

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
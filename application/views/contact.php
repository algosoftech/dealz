<!Doctype html>
<html lang="eng">
<head>
<meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dealz Arabia | Contact Us</title>
    <?php include('common/head.php') ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>	
<style>

	.error{
	color: red;
}
.color-default-btn {
    background: #ffffff !important;
    padding: 4px 20px !important;
    border-radius: 7px;
    display: inline-block;
    color: #e22c2d !important;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    border: 1px solid #e22c2d !important;
}
</style>
    
</head>
<body>
<?php include('common/header.php') ?>
<div class="contact-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-7 col-md-7 col-sm-7 col-12">
				<div class="feedback-block">
					<h3>Contact<span>Dealz - Arabia</span></h3>
					<p>Please fill in the form below and a dedicated member of <br>our team will be in touch within 24 hrs.</p>
				</div>


				<div class="tab-box mb-5">
					<ul class="nav nav-tabs justify-content-center">
						<li><a data-toggle="tab" class="active"  href="#mailustab">Mail us</a></li>
						<li><a data-toggle="tab" href="#chatustab">Chat with us</a></li>
					</ul>
				</div>


				<div class="tab-content">
					
					<div id="mailustab" class="tab-pane fade show active">
						
						<?php if ($this->session->flashdata('error')) { ?>
						<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
						<?php } ?>
						<?php if ($this->session->flashdata('success')) { ?>
							<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
						<?php  } ?>
						<div class="contact-big-box">
							<form  id="form" action="<?=base_url('contact/contact_detail')?>" method="post">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile Number">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<select class="form-control" name="subject" id="subject"> 
												<option value="Recharge Related"> Recharge Related </option>
												<option value="Sales Related"> Sales Related </option>
												<option value="Support Related"> Support Related </option>
												<option value="General"> General </option>
											</select>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<textarea rows="4" cols="5" class="form-control" name="message" id="message" placeholder="Message"></textarea>
										</div>
									</div>
									

									<div class="col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<div class="g-recaptcha" data-sitekey="<?=GOOGLE_KEY?>"></div>
											<input type="submit" class="color-default-btn float-right g-recaptcha" value="Submit" style="text-transform:uppercase;">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div id="chatustab" class="tab-pane fade">
						<div class="contact-big-box">

							<div class="row">
								<div class="col-6">
									<span class="conatct-us mr-5">
										<a href="tel:+971 45541927">
											<i class="fas fa-phone-alt"> </i> +971 45541927
										</a>
										
									</span>
								</div>
								<div class="col-6">
									<span class="whatsApp">
										<a href="https://api.whatsapp.com/send?phone=<?=$general_details[0]['whatsapp_no']?>">
											<i class="fab fa-whatsapp fa-lg" aria-hidden="true"> </i> WhatsApp
										</a>
									</span>
								</div>
							</div>
							
								
								
								
							
						</div>
					</div>
				</div>





				
			</div>
			<div class="col-lg-5 col-md-5 col-sm-5 col-12">
				<div class="map-box">
					<?php foreach($general_details as $key => $item){ ?>
					<div class="row">
						<div class="col-lg-12 col-12">
							<p>Dealz Arabia Headquarters</p>
							<h4><?=$item['address']?></h4>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-12">
							<p>Call us now</p>
							<h4><?=$item['contact_no']?></h4>
						</div>
						<div class="col-lg-6 col-12">
							<p>Write us an email</p>
							<h4><?=$item['email_id']?></h4>
						</div>
					</div>
					<div id="googleMap" style="width:100%;height:320px;"></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>
<script>
$(document).ready(function() {
$("#mobile").inputFilter(function(value) {
return /^\d*$/.test(value);    
});

$("#name").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);    
});
$("#message").inputFilter(function(value) {
return /^[a-zA-Z-'.@,()/^\d ]*$/.test(value);    
});
});
</script>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd0U-dY13CLZW2EB_By2_dIgqCJFyPMJ8&callback=initMap"></script>
<script>
	function initialize() {
	var mapOptions = {
	zoom: 17,
	scrollwheel: true,	
	center: new google.maps.LatLng(25.257943471030828, 55.333791500212726),
	styles: [{
	"featureType": "landscape",
	"elementType": "geometry.fill",
	"stylers": [{
	"color": "#f6f6f6"
	}]
	}, {
	"featureType": "poi",
	"elementType": "geometry",
	"stylers": [{
	"color": "#efefef"
	}]
	}, {
	"featureType": "water",
	"stylers": [{
	"visibility": "off"
	}]
	}, {
	"featureType": "road",
	"elementType": "geometry.stroke",
	"stylers": [{
	"visibility": "on"
	}, {
	"color": "#dedddd"
	}]
	}, {
	"featureType": "road",
	"elementType": "geometry.fill",
	"stylers": [{
	"visibility": "on"
	}, {
	"color": "#efefef"
	}]
	}, {
	"featureType": "poi",
	"elementType": "labels.icon",
	"stylers": [{
	"visibility": "on"
	}]
	}]
	};

	var map = new google.maps.Map(document.getElementById('googleMap'),
	mapOptions);

	var marker = new google.maps.Marker({
	position: map.getCenter(),
	animation:google.maps.Animation.BOUNCE,
	icon: '<?=base_url('assets/')?>img/map-marker1.png',
	map: map
	});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script type="text/javascript">
$("#form").validate({
rules: {
name: { required: true, },
mobile: { required: true, maxlength: 10 },
email: { required: true, },
message: { required: true, },
subject: { required: true, },
},
messages:{
name: { required: 'Please enter your name', },
email: { required: 'Please enter valid email',},
subject: { required: 'Please enter subject', },
mobile: { required: 'Please enter mobile number',},
message: { required: 'Please enter message', },
},
submitHandler: function(form) {
form.submit();
}
});


function onSubmit(token) {
     document.getElementById("form").submit();
}


<script>
      function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('<?=GOOGLE_KEY?>', {action: 'submit'}).then(function(token) {
             
          });
        });
      }
  </script>


</script>
</body>
</html>

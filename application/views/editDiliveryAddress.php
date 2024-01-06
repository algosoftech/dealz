<?php //echo"workig"; ?>
<!Doctype html>
<html lang="eng">
<style> 
	.error{
		color:red;
	}
	.my-profile .inputWithIcon input[type="text"] {
    padding-left: 40px;
    color: rgb(209 209 209) !important;
}
input[type=text]::placeholder { 
color: rgb(209 209 209) !important;
	opacity: 1; 
	font-family: 'Open Sans', sans-serif;
}
</style>
<head>

<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php include('common/head.php') ?>
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
						<h4 class="information" >Edit Address</h4>
					</div>
					<div class="row form_user">
					<form class="form-inline" action="<?=base_url('edit-dilivery-address/'.manojEncript($address['id']))?>" method="post" id="form">
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
							<?php if(set_value('address_type')): $address_type = set_value('address_type'); elseif($address['address_type']): $address_type = stripslashes($address['address_type']); else: $address_type = ''; endif; ?>
							<select name="address_type" id="address_type"class=" <?php if(form_error('address_type')): ?>error<?php endif; ?>">
								<option value="">Address Type</option>
								<option value="Office" <?php if($address_type == 'Office'): echo ' selected="selected"'; endif; ?>>Office</option>
								<option value="Home" <?php if($address_type == 'Home'): echo ' selected="selected"'; endif; ?>>Home</option>
								<option value="Others" <?php if($address_type == 'Others'): echo ' selected="selected"'; endif; ?>>Others</option>
							</select>
						  
						  	<?php if(form_error('address_type')): ?>
						  		<label id="address_type-error" class="error" for="address_type"><?php echo form_error('address_type'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon" style="margin-right:0px;">
							<input type="text" name="name" id="name" value="<?php if(set_value('name')): echo set_value('name'); else: echo stripslashes($address['name']); endif; ?>" placeholder="Name" class=" <?php if(form_error('name')): ?>error<?php endif; ?>">
						  	<i class="fa fa-address-book" aria-hidden="true"></i>
						  	<?php if(form_error('name')): ?>
	                          <label id="name-error" class="error" for="name"><?php echo form_error('name'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
							  <input type="text" name="village" id="village" value="<?php if(set_value('village')): echo set_value('village'); else: echo stripslashes($address['village']); endif; ?>" placeholder="Village/Town Name" class=" <?php if(form_error('village')): ?>error<?php endif; ?>">
							 <i class="fal fa-city"></i>
							 <?php if(form_error('village')): ?>
	                          <label id="village-error" class="error" for="village"><?php echo form_error('village'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon" style="margin-right:0px;">
							  <input type="text" name="street" id="street" value="<?php if(set_value('street')): echo set_value('street'); else: echo stripslashes($address['street']); endif; ?>" placeholder="Street Name or No." class=" <?php if(form_error('street')): ?>error<?php endif; ?>">
						 	 <i class="fal fa-city"></i>
						 	 <?php if(form_error('street')): ?>
	                          <label id="street-error" class="error" for="street"><?php echo form_error('street'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php /* ?>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
						  <input type="text" name="area" id="area" value="<?php if(set_value('area')): echo set_value('area'); else: echo stripslashes($address['area']); endif; ?>" placeholder="Area" class=" <?php if(form_error('area')): ?>error<?php endif; ?>">
						  <i class="fal fa-city"></i>
						  <?php if(form_error('area')): ?>
	                          <label id="area-error" class="error" for="area"><?php echo form_error('area'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php */ ?>
					<div class="col-md-6 px-0" >
						<div class="inputWithIcon" style="margin-right:0px;">
						  <input type="text" name="city" id="city" value="<?php if(set_value('city')): echo set_value('city'); else: echo stripslashes($address['city']); endif; ?>" placeholder="City" class=" <?php if(form_error('city')): ?>error<?php endif; ?>">
						 <i class="fal fa-city"></i>
						 <?php if(form_error('city')): ?>
	                          <label id="city-error" class="error" for="city"><?php echo form_error('city'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php /* ?>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
						  <input type="text" name="pincode" id="pincode" value="<?php if(set_value('pincode')): echo set_value('pincode'); else: echo stripslashes($address['pincode']); endif; ?>" placeholder="Pincode" class=" <?php if(form_error('pincode')): ?>error<?php endif; ?>">
						  <i class="fas fa-map-pin" aria-hidden="true"></i>
						  <?php if(form_error('pincode')): ?>
	                          <label id="pincode-error" class="error" for="pincode"><?php echo form_error('pincode'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php */ ?>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
							<?php /* ?>
						  <input type="text" name="country" id="country" value="<?php if(set_value('country')): echo set_value('country'); else: echo stripslashes($address['country']); endif; ?>" placeholder="Country" class=" <?php if(form_error('country')): ?>error<?php endif; ?>">
						  <i class="fa fa-globe" aria-hidden="true"></i>
						  <?php */ ?>
						  <?php if(set_value('country')): $country = set_value('country'); elseif($address['country']): $country = stripslashes($address['country']); else: $country = 'United Arab Emirates'; endif; ?>
						  <select name="country" id="country"class=" <?php if(form_error('country')): ?>error<?php endif; ?>">
								<option value="">Select Country</option>
								<?php if($countryData): foreach($countryData as $countryInfo): ?>
									<option value="<?php echo $countryInfo['country_name']; ?>" <?php if($country == $countryInfo['country_name']): echo ' selected="selected"'; endif; ?>><?php echo $countryInfo['country_name']; ?></option>
								<?php endforeach; endif; ?>
							</select>
						  <?php if(form_error('country')): ?>
	                          <label id="country-error" class="error" for="country"><?php echo form_error('country'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon"  style="margin-right:0px;">
						  <input type="text" name="mobile" id="mobile" value="<?php if(set_value('mobile')): echo set_value('mobile'); else: echo stripslashes($address['mobile']); endif; ?>" placeholder="Mobile No." class=" <?php if(form_error('mobile')): ?>error<?php endif; ?>">
						  <i class="fas fa-mobile" aria-hidden="true"></i>
						  <?php if(form_error('mobile')): ?>
	                          <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>

					</div>
						<div class="change_password">
							<input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
							<button class="btn">Save</button>
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
address_type: { required: true },
name: { required: true },
village: { required: true },
mobile: { required: true, maxlength: 10},
street: { required: true },
area: { required: true },
city: { required: true },
pincode: { required: true },
country: { required: true },
},
messages:{
address_type: { required: 'Please enter your address type.' },
name: { required: 'Please enter your name.' },
village: { required: 'Please enter your village/town.'},
street: { required: 'Please enter your street name or no.' },
mobile: { required: 'Please enter mobile number.'},
area: { required: 'Please enter area.'},
city: { required: 'Please enter city.'},
pincode: { required: 'Please enter pincode.'},
country: { required: 'Please enter country.'},
},

});
</script>

<script>
$(document).ready(function() {
$("#name").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);    
});

$("#area").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);    
});

$("#city").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);   
});

$("#country").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);    
});

$("#village").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);    
});

});
</script>

</body>

</html>
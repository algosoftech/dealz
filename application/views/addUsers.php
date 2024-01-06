<?php //echo"workig"; ?>
<!Doctype html>
<html lang="eng">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php include('common/head.php') ?>
<style>
	.input_user_type{
		width: 100%;
		display: flex;
		align-items: center;
	}
	.input_user_type i{
		position: absolute;
		left: 0;
		top: 10px !important;
		padding: 9px 8px;
		color: #45454563;
		transition: 0.3s;
		left: 90px !important;
	}
	.input_mobile{
		width: 100%;
		display: flex;
		align-items: center;
	}
	.error{
		color:red;
	}
	.input_mobile i{
		position: absolute;
		left: 0;
		top: 10px !important;
		padding: 9px 8px;
		color: #45454563;
		transition: 0.3s;
		left: 90px !important;
	}
	.my-profile .users_form {
		background: #f5fcff00;
		border-radius: 8px;
		position: relative;
		margin-top: 32px;
		border: 1px solid #ebebeb;
		text-align: left;
		margin-bottom: 36px;
		/* height: 105px; */
		padding: 0px 0px 23px;
	}
	#country_code {
		margin: 0;
		font-family: inherit;
		font-size: inherit;
		line-height: inherit;
		color: #495057d9;
		width: 66px;
		padding: 5px 1px;
		border: none;
		border: 2px solid #f1f1f1;
		height: 38px;
		border-radius: 4px;
		/* border-right: snow; */
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		border-right: 0px;
	}
	#user_type {
		width: 100%;
		border: 2px solid #f1f1f1;
		border-radius: 4px;
		margin: 8px 0;
		outline: none;
		padding: 5px 28px 5px;
		box-sizing: border-box;
		transition: 0.3s;
	}
.pagination {
    margin: 0;
    padding: 0;
    text-align: center;
    margin-bottom: 18px !important;
    margin-top: 12px;
    margin-left: 10px !important;
}
.add_user {
    
    padding: 8px 16px;
    border-radius: 12px;
    color: #e72d2e;
}

.pagination .active {
    display: inline;
    background-color: #eeeeee9e !important;
    border-radius: 7px;
    margin-right: 10px !important;
}

.pagination li a {
    display: inline-block;
    text-decoration: none;
    padding: 5px 10px;
    color: #000;
}
.pagination {
    margin: 0;
    padding: 0;
    text-align: center;
    margin-bottom: 18px !important;
    margin-top: 12px;
}
.pagination li span {
    display: inline-block;
    text-decoration: none;
    padding: 5px 10px;
    color: #000;
}
.pagination .active {
    display: inline;
    background-color: #eeeeee9e !important;
    border-radius: 7px;
}
    tr{
    border: 1px solid #ddd;
}
table td {
    border: 1px solid #eee;
    border-top: 0;
    font-weight: 400;
    text-align:center;
    padding: 0.75rem;
    vertical-align: top;
    color: #6c757d;
    font-size: 14px;
    line-height: 22px;
  
    text-align: center;
}
.my-profile .btn:hover{
background: #e72d2e;
    color: #ffffff;
}
.my-profile .btn {
    background: #ffffff;
    border: 1px solid #e72d2e;
    padding: 8px 13px;
    border-radius: 8px;
    color: #e72d2e;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
}
 table th {
    padding: 0.75rem;
    vertical-align: top;
    border: 1px solid #dee2e6;
    font-size: 14px;
    line-height: 22px;
    font-weight: 600;
    text-align: center;
    color: #6c757d;
}
table {
    border-collapse: collapse;
}
.user_list{
   padding-top: 19px; 
}
.my-profile input[type="password"] {
    width: 98%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: 8px 0;
    outline: none;
    padding: 5px 28px 5px;
    box-sizing: border-box;
    transition: 0.3s;
}
.my-profile .change_password {
    display: flex;
    justify-content: space-between;
    display: flex;
    justify-content: space-between;
    padding: 20px 0px;
}
.my-profile .inputWithIcon input::placeholder {
    color: rgb(209 209 209) !important;
    font-size: 15px;
}
.hidden{
	display : none;
}
.show{
	display : block;
}
</style>
</head>


<body>
<?php include('common/header.php') ?>
<div class="my-profile add_user">
	<div class="container">
		<div class="row">
			<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<div class="users_form">
					<div class="profiles">
						<h4 class="information" >Add User</h4>
					</div>
					<div class="row">
					<div class="col-md-9">
						<?php  if ($this->session->flashdata('error')) { ?>
						<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
						<?php } ?>
						<?php if ($this->session->flashdata('success')) { ?>
						<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
						<?php  } ?>
					</div>
					<div class="col-md-3">
					 <a href="javascript:void(0);" class="add_user" onclick="ConfirmForm();">Add User</a>
					</div>
					</div>
					<div class="row form_user" id="BlockUIConfirm">
					<form class="form-inline" action="<?=base_url('add-user')?>" method="post" id="xyz">
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
							<input type="text" name="name" id="name" value="<?php if(set_value('name')): echo set_value('name'); endif; ?>" placeholder="Full Name" class=" <?php if(form_error('name')): ?>error<?php endif; ?>">
						  	<i class="fa fa-address-book" aria-hidden="true"></i>
						  	<?php if(form_error('name')): ?>
	                          <label id="name-error" class="error" for="name"><?php echo form_error('name'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon" style="margin-right:0px;">
							<input type="email" name="email" id="email" value="<?php if(set_value('email')): echo set_value('email'); endif; ?>" placeholder="Email" class=" <?php if(form_error('email')): ?>error<?php endif; ?>">
						   <i class="far fa-envelope" aria-hidden="true"></i>
						   <?php if(form_error('email')): ?>
	                          <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon input_mobile" style="width:100%">
						<div class="user_codee">
						<?php if(set_value('country_code')): $countryCode = set_value('country_code'); else: $countryCode = '+971'; endif; ?>
							<select name="country_code" id="country_code">
								<?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
									<option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeKey; ?></option>
								<?php endforeach; endif; ?>
							</select>
								</div>
						    <div class="" style="width: 80%;">	
						  	<input type="text" name="mobile" id="mobile" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" placeholder="Mobile No." class=" <?php if(form_error('mobile')): ?>error<?php endif; ?>">
							<i class="fa fa-mobile" aria-hidden="true"></i>
							</div>
							<?php if(form_error('mobile')): ?>
	                          <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php /*  ?>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon" >
							  <input type="text" name="mobile" id="mobile" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" placeholder="Mobile No." class=" <?php if(form_error('mobile')): ?>error<?php endif; ?>">
							  <i class="fa fa-mobile" aria-hidden="true"></i>
							  <?php if(form_error('mobile')): ?>
	                          <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php */ ?>
					<div class="col-md-6 px-0" >
						<div class="inputWithIcon" style="margin-right:0px;">
							  <input type="text" name="arabianPoints" id="arabianPoints" value="<?php if(set_value('arabianPoints')): echo set_value('arabianPoints'); endif; ?>" placeholder="Arabian Points" class=" <?php if(form_error('arabianPoints')): ?>error<?php endif; ?>">
							<?php if(form_error('arabianPoints')): ?>
	                          <label id="arabianPoints-error" class="error" for="arabianPoints"><?php echo form_error('arabianPoints'); ?></label>
	                        <?php elseif($error): ?>
	                          <label id="arabianPoints-error" class="error" for="arabianPoints"><?php echo lang('LOW_BALANCE'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					
					<div class="col-md-6 px-0" >
						<div class="inputWithIcon" >
						  <input type="password" name="password" id="password" value="<?php if(set_value('password')): echo set_value('password'); endif; ?>" placeholder="Password" class=" <?php if(form_error('password')): ?>error<?php endif; ?>">
						 <i class="fa fa-lock" aria-hidden="true"></i>
						 <?php if(form_error('password')): ?>
	                          <label id="password-error" class="error" for="password"><?php echo form_error('password'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon" style="margin-right:0px;">
						  <input type="password" name="cpassword" id="cpassword" value="<?php if(set_value('cpassword')): echo set_value('cpassword'); endif; ?>" placeholder="Confirm Password" class=" <?php if(form_error('cpassword')): ?>error<?php endif; ?>">
						  <i class="fa fa-lock" aria-hidden="true"></i>
						  <?php if(form_error('cpassword')): ?>
	                          <label id="cpassword-error" class="error" for="cpassword"><?php echo form_error('cpassword'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<!-- USER TYPE -->
					<?php if($this->session->userdata('DZL_USERSTYPE') == "Freelancer"): ?>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon input_user_type" style="width:100%">
							<select name="users_type" id="user_type">
								<!-- <option>Freelancer</option> -->
								<option value="Retailer">Reseller</option>
								<option value="Users">User</option>
							</select>
							<?php if(form_error('mobile')): ?>
	                          <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php elseif($this->session->userdata('DZL_USERSTYPE') == "Sales Person"): ?> 
					<div class="col-md-6 px-0">
						<div class="inputWithIcon input_user_type" style="width:100%">
							<select name="users_type" id="user_type">
								<option>Freelancer</option>
								<option value="Retailer">Reseller</option>
								<option value="Users">User</option>
							</select>
							<?php if(form_error('mobile')): ?>
	                          <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>
					<?php endif; ?>
						<div class="col-md-6 px-0 hidden" id="store_section">
						<div class="inputWithIcon" >
						  <input type="text" name="store" id="store" value="<?php if(set_value('store')): echo set_value('store'); endif; ?>" placeholder="Store Name" class=" <?php if(form_error('store')): ?>error<?php endif; ?>">
						<i class="fas fa-store"></i>
						<?php if(form_error('store')): ?>
	                          <label id="store-error" class="error" for="store"><?php echo form_error('store'); ?></label>
	                        <?php endif; ?>
						</div>
					</div>

					<div class="col-md-9 px-0"></div>
						<div class="col-md-3 px-0" style="text-align:right;">
							<div class="change_password" style="display: block;">
								<input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
								<input type="hidden" name="page" value="<?=$page?>">
								<button class="btn">Save</button>
							</div>
						</div>
					</div>

				</form>
				
				<?php if(!empty($users)){ ?>
				<div class="user_list" style="overflow-x: auto;">
				  <table>
				    <tr>
				      <th style="text-align:center;">S.No</th>
				      <th style="text-align:center;">Name</th>
				      <th style="text-align:center;">Email</th>
				      <th style="text-align:center;">Phone</th>
				      <th style="text-align:center;">Points</th>
				      <th style="text-align:center;">Recharge Date</th>
				    </tr>
				    <?php $i=1; foreach ($users as $key => $items) { ?>
				    <tr>
				      <td width="10%"><?=$i?></td>
				      <td width="15%"><?=$items['users_name']?></td>
				      <td width="15%"><?=$items['users_email']?></td>
				      <td width="20%"><?=$items['users_mobile']?></td>
				      <td width="5%"><?=$items['availableArabianPoints']?></td>
				      <td width="20%"><?=date('d M Y', strtotime($items['created_at']))?></td>
				    </tr>
				    <?php $i++; } ?>
				  </table>
				  <?= $this->pagination->create_links(); ?>
				</div>
				<?php }?>				
			</div>
		</div>
	</div>
</div>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>

<script>
	$(document).ready(function(){
		var val = $('#user_type').val();
		if(val =="Users"){
			$('#store_section').addClass('hidden');
			$('#store_section').removeClass('show');
		}
		if(val =="Retailer"){
			$('#store_section').addClass('show');
			$('#store_section').removeClass('hidden');
		}
		if(val =="Freelancer"){
			$('#store_section').addClass('hidden');
			$('#store_section').removeClass('show');
		}

		$('#user_type').change(function(){
			var val = $(this).val();
			if(val =="Users"){
				$('#store_section').addClass('hidden');
				$('#store_section').removeClass('show');
			}
			if(val =="Retailer"){
				$('#store_section').addClass('show');
				$('#store_section').removeClass('hidden');
			}
			if(val =="Freelancer"){
				$('#store_section').addClass('hidden');
				$('#store_section').removeClass('show');
			}
		});
	});
	
</script>

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
$("#xyz").validate({
rules: {
name: { required: true },
mobile: { required: true, maxlength: 10, remote:'signup/checkDuplicateMobile'},
email: { required: true, remote: "signup/checkDuplicateEmail" },
arabianPoints: { required: true },

password: { required: true, maxlength: 12, minlength: 7 },
cpassword : {required: true, equalTo: password}
},
messages:{
name: { required: 'Please enter user name.' },
mobile: { required: 'Please enter mobile number.', remote:"This mobile no. is already exist! Try another."},
email: { required: 'Please enter email.', remote:"This email is already exist! Try another."},
arabianPoints: { required: 'Please enter arabian points.'},
password: {required : 'Please enter user password'},
cpassword : {required : 'Please enter user conform password', equalTo: 'Both password does not match.'}
},

});

</script>
<script>
function ConfirmForm() {
$("#BlockUIConfirm").show();
}
$(document).ready(function(){
<?php if($error == 'YES'): ?>
$("#BlockUIConfirm").show();
<?php endif; ?>
});
</script>

<script>
$(document).ready(function(){
$(document).ready(function() {
$("#mobile").inputFilter(function(value) {
return /^\d*$/.test(value);   
});

$("#arabianPoints").inputFilter(function(value) {
return /^\d*$/.test(value);   
});

$("#name").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);    

$("#store").inputFilter(function(value) {
return /^[a-zA-Z-'. ]*$/.test(value);    
});

});
});
</script>

</body>

</html>

<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
		.my-profile input[type="date"] {
    width: 96%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: 8px 0px;
    outline: none;
    padding: 5px 28px 5px;
    box-sizing: border-box;
    transition: 0.3s;
}
.deopdown {
    width: 67%;
    border: 2px solid #f1f1f1;
    border-radius: 4px;
    margin: -6px 0;
    outline: none;
    padding: 5px 3px 5px;
    box-sizing: border-box;
    transition: 0.3s;
    position: absolute;
    top: 0px;
}
		.my-profile .users_form {
    background: #f5fcff00;
    border-radius: 8px;
    position: relative;
    margin-top: 32px;
    border: 1px solid #ebebeb;
    text-align: left;
    margin-bottom: 10px;
}
		.my-profile .change_password {
		
		display: flex;
		justify-content: space-between;
		padding: 20px 0px;
		}
			.error{
				font-family: 'Open Sans', sans-serif;
			font-size: 14px;
			line-height: 22px;
			font-weight: 400;
			text-align: left;
			color: #e22c2d;
			margin-bottom: 0;
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
		}      
		.my-profile .confirm_password .change_passworded {
			text-align: end!important;
			padding: 28px 28px 17px 36px;
		}
		.recharge .users_form {
			width: 100%;
		}
		.my-profile .form-inline {
			padding: 0px; 
		}
		.my-profile input[type="number"] {
			width: 100%;
			border: 2px solid #f1f1f1;
			border-radius: 4px;
			margin: 8px 0;
			outline: none;
			padding: 5px 35px 5px;
			box-sizing: border-box;
			transition: 0.3s;
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
		.my-profile .inputWithIcon input::placeholder {
			color: rgb(209 209 209) !important;
			font-size: 15px;
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
		.form_user {
			padding: 0px 35px;
		}
		table {
			border-collapse: collapse;
		}
		.user_list{
		padding-top: 19px; 
		}
		.show_record{
			position: absolute;
    left: -35px;
    top: -6px;
    padding: 6px 20px!important;
		}
		.percentace_active{
			background  : #e22c2d !important;
			color : #fff !important;
		}
		.hidden{
			display : none;
		}
@media (min-width: 360px) and (max-width: 600px) {
.show_record {
    position: absolute;
    left: 266px;
    top: -31px;
    padding: 6px 20px!important;
}
.user_list {
    padding-top: 19px;
    padding-bottom: 40px;
}
.label{
   text-align:left !important;
}
.my-profile .form-inline {
    padding: 0px;
    margin-bottom: 20px;
}
}

.fade {
    opacity: 0;
     transition: unset !important; 
}
    </style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="my-profile recharge">
	<div class="container">
		<div class="row">
		<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<form class="form-inline" id="rechargeForm" method="post" action="<?=base_url('profile/recharge')?>">
					<?php if ($this->session->flashdata('error')) { ?>
					<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					<?php } ?>
					<?php if ($this->session->flashdata('success')) { ?>
					<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>
				<div class="users_form">
					<div class="profiles">
						<h4 class="information" >Top Up Recharge</h4>
					</div>
					<div class="row form_user">

					<div class="col-md-12 px-0 mb-3">
						<div class="tab-box">
							<ul class="nav nav-tabs justify-content-center">
								<li><a data-toggle="tab" class="active" href="#mobiletab">Mobile</a></li>
								<li><a data-toggle="tab" href="#emailtab">Email</a></li>
							</ul>
						</div>
					</div>
					
					<div class="col-md-6 px-0">

						<div class="tab-content">
							<div id="mobiletab" class="tab-pane fade show active">
								
								<div class="inputWithIcon">
								   <?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>
                                     <select  name="country_code" id="country_code"   class="form-control" >
                                        <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                            <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeValue; ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
								</div>
								
								<div class="inputWithIcon">
								  <input type="text" autocomplete="off" name="mobile" id="mobile" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" placeholder="Mobile">
								  <i class="fa fa-address-card" aria-hidden="true"></i>
								  <?php if(form_error('email')): ?>
			                          <label id="email-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
			                       <?php elseif($emailError): ?>
			                          <label id="email-error" class="error" for="recharge_amt"><?php echo $emailError; ?></label>
			                        <?php endif; ?>
								</div>
							</div>
							<div id="emailtab" class="tab-pane fade">
								
								<div class="inputWithIcon">
								  <input type="text" autocomplete="off" name="email" id="email" value="<?php if(set_value('email')): echo set_value('email'); endif; ?>" placeholder="Email">
								  <i class="fa fa-address-card" aria-hidden="true"></i>
								  <?php if(form_error('email')): ?>
			                          <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
			                       <?php elseif($emailError): ?>
			                          <label id="email-error" class="error" for="recharge_amt"><?php echo $emailError; ?></label>
			                        <?php endif; ?>
								</div>
							</div>
							
						</div>

						
					</div>	
						<div class="col-md-6 px-0">
					<div class="inputWithIcon" style="margin-right:0px;">
						  <input type="number" min="5" autocomplete="off" name="recharge_amt" id="recharge_amt" value="<?php if(set_value('recharge_amt')): echo set_value('recharge_amt'); endif; ?>" placeholder="Recharge amount">
						  <i class="fa fa-address-card" aria-hidden="true"></i>
						  <?php if(form_error('recharge_amt')): ?>
	                          <label id="recharge_amt-error" class="error" for="recharge_amt"><?php echo form_error('recharge_amt'); ?></label>
	                      <?php elseif($amountError): ?>
	                          <label id="recharge_amt-error" class="error" for="recharge_amt"><?php echo $amountError; ?></label>
	                        <?php endif; ?>
						</div>
					
					</div>
					
					<?php if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer'): ?>
							<div class="col-md-6 px-0">
								<lable>Add : </lable>
								<a class="btn" id="perc_5">5%</a>
								<input type="radio" name="set_perc" class="perc_5 hidden" value="5"/>
								<a class="btn" id="perc_10">10%</a>
								<input type="radio" name="set_perc" class="perc_10 hidden" value="10"/>
								<a class="btn" id="perc_15">15%</a>
								<input type="radio" name="set_perc" class="perc_15 hidden" value="15"/>
								<a class="btn" id="perc_20">20%</a>
								<input type="radio" name="set_perc" class="perc_20 hidden" value="20"/>
							</div>
							<div class="col-md-6 px-0">
								<div class="inputWithIcon" style="margin-right:0px;">
								<input type="number" min="1" autocomplete="off" name="percentage" id="percentage" value="<?php if(set_value('percentage')): echo set_value('percentage'); endif; ?>" placeholder="Enter %">
								<i class="fa fa-percent" aria-hidden="true"></i>
								<?php if(form_error('percentage')): ?>
									<label id="percentage-error" class="error" for="percentage"><?php echo form_error('percentage'); ?></label>
								<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					
					 <div class="col-md-9 px-0">
					    </div>
					    	<div class="col-md-3 px-0" style="text-align:right;">
					    	    	<div class="change_password" style="display:block;">
					    	 <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
						 <a href="javascript:void{0}" onclick="recharge()" class="btn">Recharge</a>
					</div>
					</div>
					
					    </div>
				
				</div>
				<div class="col-md-4 px-0"> 
				<lable class="label">To </lable>
					<input type="date" name="todate" id="todate" value="<?php if($todate) : echo $todate; endif; ?>">
				</div>
			
				<div class="col-md-4 px-0">
				<lable class="label">From</lable> 
					<input type="date" name="fromdate" id="fromdate" value="<?php if($fromdate) : echo $fromdate; endif; ?>">
				</div>
				&nbsp;
				<div class="col-md-2 px-0"> 
					<select class="deopdown" name="record_type" id="record_type">
						<option value="All" <?php if($record_type == 'All'): echo 'selected'; endif; ?> >All</option>
						<option value="Debit" <?php if($record_type == 'Debit'): echo 'selected'; endif; ?> >Sent</option>
						<option value="Credit" <?php if($record_type == 'Credit'): echo 'selected'; endif; ?> >Received</option>
					</select>
				</div>
				&nbsp;
				<div class="col-md-1 px-0"> 
					<a heref="javaScript:void{0}" class="btn show_record" style="padding: 6px 20px!important;"> Show </a>
				</div>
				</form>
					<?php if(!empty($users)){ ?>
					<div class="user_list" style="overflow-x: auto;">
					  <table>
					    <tr>
					      <th style="text-align:center;">S.No</th>
					      
						  <th style="text-align:center;">User's Details</th>
						  <th style="text-align:center;">Record Type</th>
					      <th style="text-align:center;">Recharge Date</th>
						  <th style="text-align:center;">Margin Percentage</th>
					      <th style="text-align:center;">Recharge Amount</th>
					      <th style="text-align:center;">AVAILABLE ARABIANPOINTS</th>
					      <th style="text-align:center;">END BALANCE</th>
					    </tr>
					    <?php $i=1; foreach ($users as $key => $items) { 
							if($items['created_by'] == 'ADMIN'){
								$where 			= 	['users_id'=>(int)$items['user_id_cred']];
							}else{
								if($items['record_type'] == 'Credit') {
									$where 			= 	['users_id'=>(int)$items['created_user_id']];
								}else{
									$where 			= 	['users_id'=>(int)$items['user_id_to']];
								}
							}
					    	$userData = $this->geneal_model->getOnlyOneData('da_users', $where );
					    ?>
					    <tr>
					      <td width="10%"><?=$i?></td>
					      <!-- <td width="15%"><?=$userData['users_name']?></td>
					      <td width="15%"><?=$userData['users_email']?></td>
					      <td width="20%"><?=$userData['users_mobile']?></td>

					      <td width="5%"><?=$items['arabian_points_from']?></td> -->
						  <td width="15%">
							Name : <?=$userData['users_name']?> <br>
							<?php if($userData['users_email']){ ?>
								Email : <?=$userData['users_email']?> <br>
							<?php } ?>
							Phone : <?=$userData['users_mobile']?>
							</td>
					      	<td width="5%">
								<?php if($items['record_type'] == 'Credit'): 
									echo 'Received';
								else: 
									echo 'Sent';
								endif; ?>
							</td>

					      <td width="20%"><?=date('d M, Y h:i A', strtotime($items['created_at']))?></td>
						  <td width="5%"><?php 
						  if($items['rechargeDetails']){
							echo $items['arabian_points'].' + '.$items['rechargeDetails']['percentage'].'%'.' = '.$items['sum_arabian_points'];
						  }else{
							echo '-';
						  }
						  	
						  ?></td>
					      <td width="5%"><?php
						  if($items['sum_arabian_points']){
							echo $items['sum_arabian_points'];
						  }else{
							echo $items['arabian_points'];
						  }
						  ?></td>
						  <td> 
	                        <?php 

	                        if($items['availableArabianPoints']):
	                        echo 'AED ' .number_format($items['availableArabianPoints'],2);

	                        else:
	                         echo  '-';
	                        endif; ?>

	                      </td>
	                      
	                      <td>

	                        <?php 

	                        if($items['end_balance']):
	                        echo 'AED' .number_format($items['end_balance'],2);

	                        else:
	                         echo  '-';
	                        endif; ?>

	                      </td>
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
		$('#perc_5').click(function(){
			$('.perc_5').prop('checked', true);
			if ($(".perc_5").prop("checked")) {
				$('#perc_5').addClass('percentace_active');
				$('#percentage').empty().val(5);
			} 
			$('#perc_10').removeClass('percentace_active');
			$('#perc_15').removeClass('percentace_active');
			$('#perc_20').removeClass('percentace_active');
		});
		$('#perc_10').click(function(){
			$('.perc_10').prop('checked', true);
			if ($(".perc_10").prop("checked")) {
				$('#perc_10').addClass('percentace_active');
				$('#percentage').empty().val(10);
			} 
			$('#perc_5').removeClass('percentace_active');
			$('#perc_15').removeClass('percentace_active');
			$('#perc_20').removeClass('percentace_active');
			
		});
		$('#perc_15').click(function(){
			$('.perc_15').prop('checked', true);
			if ($(".perc_15").prop("checked")) {
				$('#perc_15').addClass('percentace_active');
				$('#percentage').empty().val(15);
			} 
			$('#perc_5').removeClass('percentace_active');
			$('#perc_10').removeClass('percentace_active');
			$('#perc_20').removeClass('percentace_active');
		});
		$('#perc_20').click(function(){
			$('.perc_20').prop('checked', true);
			if ($(".perc_20").prop("checked")) {
				$('#perc_20').addClass('percentace_active');
				$('#percentage').empty().val(20);
			} 
			$('#perc_5').removeClass('percentace_active');
			$('#perc_10').removeClass('percentace_active');
			$('#perc_15').removeClass('percentace_active');
		});
		$('#percentage').change(function(){
			var perc = $('#percentage').val();
			var match = 0;
			if(perc == 5){
				$('.perc_5').prop('checked', true);
				$('#perc_5').addClass('percentace_active');

				$('#perc_10').removeClass('percentace_active');
				$('#perc_15').removeClass('percentace_active');
				$('#perc_20').removeClass('percentace_active');
				match =1;
			}

			if(perc == 10){
				$('.perc_10').prop('checked', true);
				$('#perc_10').addClass('percentace_active');

				$('#perc_5').removeClass('percentace_active');
				$('#perc_15').removeClass('percentace_active');
				$('#perc_20').removeClass('percentace_active');
				match =1;
			}
			if(perc == 15){
				$('.perc_15').prop('checked', true);
				$('#perc_15').addClass('percentace_active');

				$('#perc_5').removeClass('percentace_active');
				$('#perc_10').removeClass('percentace_active');
				$('#perc_20').removeClass('percentace_active');
				match =1;
			}
			if(perc == 20){
				$('.perc_20').prop('checked', true);
				$('#perc_20').addClass('percentace_active');

				$('#perc_5').removeClass('percentace_active');
				$('#perc_10').removeClass('percentace_active');
				$('#perc_15').removeClass('percentace_active');
				match =1;
			}
			if(match == 0){
				$('#perc_5').removeClass('percentace_active');
				$('#perc_10').removeClass('percentace_active');
				$('#perc_15').removeClass('percentace_active');
				$('#perc_20').removeClass('percentace_active');
			}
		});
	});
</script>
<script>
	$(document).ready(function(){
		$('.show_record').click(function(){
			$('#filter_error').empty();
			var todate = $('#todate').val();
			var fromdate = $('#fromdate').val();
			var record_type = $('#record_type').val();
			var url  = '<?=base_url('profile/recharge')?>' + '?todate='+todate+'&fromdate='+fromdate+'&record_type='+record_type;
			window.location.href = url;
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
	function recharge(){
		
		var email = $('#email').val();
		var arabianpoints = $('#recharge_amt').val();
		Swal.fire({
			title: 'Are you sure?',
			text: "Are you sure to transfer AED "+arabianpoints+" to "+email+"!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Transfer it!'
			}).then((result) => {
			if (result.isConfirmed) {
				$('#rechargeForm').submit();
				
			}
			})

	}
	/*
$("#rechargeForm").validate({
rules: {
email: { required: true, remote: "<?=base_url('profile/checkEmail')?>" },
recharge_amt: { required: true, remote: "<?=base_url('profile/checkarAbianPoints')?>" },
},
messages:{
email: { 	required: 'Please enter Email ID / Mobile No.', 
			remote: 'Invalid Email ID / Mobile No.' },
recharge_amt: { required: 'Please enter arabian points.',
				remote: '<?php echo lang('LOW_BALANCE'); ?>'},
},
});
*/
</script>
</body>
</html>

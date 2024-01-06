<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
        .user_list {
    padding-top: 0px !important;
    border: 1px solid #8080801c;
    border-radius: 5px;
    margin-top: 20px;
}
    .my-profile .change_password {
    
    display: flex;
    justify-content: space-between;
    padding: 20px 0px;
}
.users_form{
    margin-bottom:0px !important;
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
				<form class="form-inline" id="rechargeForm" method="post" action="<?=base_url('redeem-coupon')?>">
					<?php if ($this->session->flashdata('error')) { ?>
					<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					<?php } ?>
					<?php if ($this->session->flashdata('success')) { ?>
					<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>
				<div class="users_form">
					<div class="profiles">
						<h4 class="information" >Redeem</h4>
					</div>
					<div class="row form_user">
					<div class="col-md-12 px-0">
                        <?php if(form_error('couon_code')): ?>
                            <label id="couon_code-error" class="error" for="couon_code"><?php echo form_error('couon_code'); ?></label>
                        <?php elseif($couonCodeError): ?>
                            <label id="couon_code-error" class="error" for="couon_code"><?php echo $couonCodeError; ?></label>
                        <?php endif; ?>
                    </div>
					<div class="col-md-6 px-0">
						<div class="inputWithIcon">
						  <input type="text" autocomplete="off" name="couon_code" id="couon_code" value="<?php if(set_value('couon_code')): echo set_value('couon_code'); endif; ?>" placeholder="Coupon Code">
						  <i class="fa fa-address-card" aria-hidden="true"></i>
						</div>
					</div>	
					<div class="col-md-6 px-0">&nbsp;</div>
					    	<div class="col-md-3 px-0" style="text-align:left;">
					    	    	<div class="change_password" style="display:block;">
					    	 <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
						 <button class="btn">Redeem</button>
					</div>
					</div>
					
					    </div>
				
				</div>
				</form>
					<?php if(!empty($users)){ ?>
					<div class="user_list" style="overflow-x: auto;">
					  <table>
					    <tr>
					      <th style="text-align:center;">S.No</th>
					      <th style="text-align:center;">Voucher Code</th>
					      <th style="text-align:center;">Amount</th>
					      <th style="text-align:center;">AVAILABLE ARABIANPOINTS</th>
					      <th style="text-align:center;">END BALANCE</th>
					      <th style="text-align:center;">Date</th>
					    </tr>
					    <?php $i=1; foreach ($users as $key => $items) {  ?>
					    <tr>
					      <td width="10%"><?=$i?></td>
					      <td width="15%"><?=$items['coupon_code']?></td>
					      <td width="15%"><?=$items['arabian_points']?></td>
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
					      <td width="20%"><?=date('d M, Y h:i A', strtotime($items['created_at']))?></td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if($popup_error == "YES"): ?>
    <script>
        Swal.fire(
            '<?=$error_title?>',
            '<?=$error_message?>',
            'error'
        )
    </script>
<?php endif; ?>
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
<script type="text/javascript">
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

<?php //echo"workig"; ?>
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
.coupon .about-coupen div {
	margin: 0 15px;
	padding: 12px 0px;
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
.pagination {
    margin: 0;
    padding: 0;
    text-align: center;
    margin-bottom: 18px !important;
    margin-top: 12px;
    margin-left: 10px !important;
}
.add_user {
    background-color: #d12a2b;
    border: none;
    padding: 8px 16px;
    border-radius: 12px;
    color: white;
}
.pagination .active {
    display: inline;
    background-color: #eeeeee9e !important;
    border-radius: 7px;
    margin-right: 10px !important;
}
.add_user:hiver {
    background-color: #d12a2b;
    border: none;
    padding: 8px 16px;
    border-radius: 12px;
    color: white;
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
    color:#00000087;
}

 table th {
    padding: 0.75rem;
    vertical-align: top;
    border: 1px solid #dee2e6;
    text-align: center;
    font-weight: 500;
}
table {
    border-collapse: collapse;
}
.user_list{
   padding-top: 19px; 
}
.ordered_list {
    display: flex;
    justify-content: space-around;
    margin: 0px;
    padding: 0px;
}
.ordered_list li{
 padding:0px 5px;   
}
.ordered_list a{
 color: #d12a2b;  
}
.coupon .coupen-footer {
    background: #b12021;
    color: #fff;
    padding: 4px;
}
.coupon .about-coupen p span {
    color: #6c757d;
    font-weight: 400;
}
.coupen_list {
    padding-top: 15px;
}
</style>
</head>


<body>
<?php include('common/header.php') ?>
<div class="my-profile">
	<div class="container">
		<div class="row">
			<?php include ('common/profile/menu.php') ?>
			<div class="col-md-9">
				<?php include ('common/profile/head.php') ?>
				<div class="row">
				    <div class="col-md-12 coupen_list">
				    	<div class="coupon">
							<div class="about-coupen">
								<div class="coupen-info" style="width:100%">
								     <ul class="ordered_list">
								         <li style="width:15%"><a href="javascript:void(0);">Order id:</a></li>
								         <li style="width:25%"><a href="javascript:void(0);">Product</a></li>
								         <?php /* ?><li style="width:15%"><a href="javascript:void(0);">Draw Date</a></li><?php */ ?>
								         <li style="width:20%"><a href="javascript:void(0);">Coupon code</a></li>
								         <!-- <li style="width:25%"><a href="javascript:void(0);">Winner Name</a></li> -->
								     </ul>
								</div>
							</div>
						
						</div>
				    	<?php $i=1; foreach ($coupons as $key => $items) { 
				    		if($fdsfdsf['coupon_status'] == 'Expired'):
				    			$name 	=	@$items['winners_name'];
								$nameData = explode(' ',$name);
								$count = count($nameData);
								$winnerName = $nameData[0].' '.$nameData[$count -1];
				    		else:
				    			$winnerName 	=	'Not Yet';
				    		endif;
				    	?>
				        <div class="coupon">
							<div class="about-coupen">
								<div class="coupen-info" style="width:100%">
								    <ul class="ordered_list">
										<li style="width:15%">
											<p><span><?=@$items['order_id']?></span></p>
										</li>
										<li style="width:25%">
											<p><span><?=@$items['product_title']?></span></p>
										</li>
										<?php /* ?><li style="width:15%">
											<p><span><?=@date('d-M-Y', strtotime($items['created_at']))?></span></p>
										</li><?php */ ?>
										<li style="width:20%">
											<p><span><?=@$items['coupon_code']?></span></p>
										</li>
										<!-- <li style="width:25%">
											<p><span><?=@$winnerName?></span></p>
										</li> -->
									</ul>
								</div>
							</div>
						
						</div>
						<?php $i++; } ?>
				    </div>
				    <?= $this->pagination->create_links(); ?>
				</div>
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
name: { required: 'Please enter your name.' },
mobile: { required: 'Please enter mobile number.', remote:"This mobile no. is already exist! Try another."},
email: { required: 'Please enter email.', remote:"This email is already exist! Try another."},
arabianPoints: { required: 'Please enter arabian points.'},
password: {required : 'Please enter your password'},
cpassword : {required : 'Please enter your conform password', equalTo: 'Both password does not match.'}
},

});
</script>
<script>
	function ConfirmForm() {
	$("#BlockUIConfirm").show();
}
	</script>
</body>

</html>
<?php //echo"workig"; ?>
<!Doctype html>
<html lang="eng">
<style>
	.error{
		color:red;
	}
</style>
<head>
<!-- Basic page needs -->
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Dealz Arabia || Add <?=$page?></title>
<meta name="description" content="">
<!-- Mobile specific metas -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="keywords" content="">
<!-- Favicon -->
<link rel="icon" href="<?=base_url('assets/')?>img/h-1.png" type="image/x-icon" />
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
/* TOP Menu Stick
		--------------------- */
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
<!-- Header Dropdown -->
<script>
$('.dropdown > .caption').on('click', function() {
	$(this).parent().toggleClass('open');
});
// $('.price').attr('data-currency', 'RUB');
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
//store: { required: true },
password: { required: true, maxlength: 12, minlength: 7 },
cpassword : {required: true, equalTo: password}
},
messages:{
name: { required: 'Please enter your name.' },
mobile: { required: 'Please enter mobile number.', remote:"This mobile no. is already exist! Try another."},
email: { required: 'Please enter email.', remote:"This email is already exist! Try another."},
arabianPoints: { required: 'Please enter arabian points.'},
//store: { required: 'Please enter store name.'},
password: {required : 'Please enter your password'},
cpassword : {required : 'Please enter your conform password', equalTo: 'Both password does not match.'}
},
/*submitHandler: function(form) {
form.submit();
}*/
});
</script>
<script>
	function ConfirmForm() {
	$("#BlockUIConfirm").show();
}
	</script>
</body>

</html>
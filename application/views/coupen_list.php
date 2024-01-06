<?php //echo"workig"; ?>
<!Doctype html>
<html lang="eng">
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php include('common/head.php') ?>	
<head>
<style>
.error{
		color:red;
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
}
.ordered_list li{
 padding:0px 17px;   
}
.ordered_list a{
 color: #d12a2b;  
}
.coupon .coupen-footer {
    background: #b12021;
    color: #fff;
    padding: 4px;
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
				    <div class="col-md-12">
				        <div class="coupon">
									<div class="about-coupen">
									
										<div class="coupen-info">
										    <ul class="ordered_list">
														<li>
															<a href="#">Order Number:
															<p><span>1</span></p>
															</a>
														</li>
														<li>
														<a href="#">Product title</a>
														<p><span>Rasso Set</span></p>
														</li>
														<li>
															<a href="#">Status
															<p><span>active</span></p>
															</a>
														</li>
														<li>
															<a href="#">Date of purchase
															<p><span>06.04.2022</span></p></a>
														</li>
														<li>
															<a href="#">Coupon code
															<p><span>DA28641654652615</span></p></a>
														</li>
													</ul>
											
										</div>
									</div>
									<div class="coupen-footer">
									
									</div>
								</div>
								  <div class="coupon">
									<div class="about-coupen">
									
										<div class="coupen-info">
										    <ul class="ordered_list">
														<li>
															<a href="#">Order Number:
															<p><span>2</span></p>
															</a>
														</li>
														<li>
														<a href="#">Product title</a>
														<p><span>Rasso Set</span></p>
														</li>
														<li>
															<a href="#">Status
															<p><span>active</span></p>
															</a>
														</li>
														<li>
															<a href="#">Date of purchase
															<p><span>06.04.2022</span></p></a>
														</li>
														<li>
															<a href="#">Coupon code
															<p><span>DA28641654652615</span></p></a>
														</li>
													</ul>
											
										</div>
									</div>
									<div class="coupen-footer">
									
									</div>
								</div>
				    </div>
				    
				</div>
				<!--
				<div class="user_list" style="overflow-x: auto;">
				  <table>
				    <tr>
				      <th style="text-align:center;">Order.No</th>
				      <th style="text-align:center;">Product title</th>
				      <th style="text-align:center;">Status </th>
				      <th style="text-align:center;">Date of purchase</th>
				      <th style="text-align:center;">Coupon code</th>
				      
				    </tr>
				  
				    <tr>
				      <td width="10%">1</td>
				      <td width="15%">Rasso Set</td>
				      <td width="15%">Active</td>
				     
				      <td width="20%">06.04.2022</td>
				      <td width="20%">DA28641654652615</td>
				    </tr>
				   
				  </table>
				  <?= $this->pagination->create_links(); ?>
				</div>
						-->	
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
<!Doctype html>
<html lang="eng">

<head>
	<!-- Basic page needs -->
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<?php include('common/head.php') ?> 
</head>
	<style>
	.fa-map-marker:before {
    content: "\f041";
    color: #031b26;
    padding-right: 5px;
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
.box {
    width: 8%;
    position: relative;
    top: -30px;
}

 .box h2 {
  display: block;
  text-align: center;
  color: black;
  position: relative;
    top: -23px;
    left: 13px;

}
.pagination {
    margin: 0;
    padding: 0;
    text-align: center;
    margin-bottom: 18px !important;
    padding-top: 15px;
}
.pagination li{
    margin: 0;
    padding: 0;
    text-align: center;
    margin-bottom: 18px !important;
    padding-right: 5px;
}
.pagination .active {
    display: inline;
    background-color: #eeeeee9e !important;
    padding-right: 5px;
    margin-right: 5px;
    border-radius: 7px;
}
.pagination li a {
    display: inline-block;
    text-decoration: none;
    padding: 5px 10px;
    color: #000;
}
.textes {
    font-size: 11px;
    position: relative;
    top: -21px;
    left: -13px;
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 400;
}
 .box .chart {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    font-size: 12px;
    line-height: 108px;
    height: 160px;
    color: #300808;
}
.user-earningblock .cart-box {
    margin-top: 18px;
    border: 1px solid #eaeaea;
    border-radius: 8px;
}
.user-cart .cart-box table h5 {
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
   color: #6c757d;
    margin-bottom: 20px;
    line-height: 20px;
    
}
.box .chart1 {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    font-size: 12px;
    line-height: 108px;
    height: 160px;
    color: #300808;
    top: 18px;
}

.box canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  width: 100%;
}
	.my-profile .user_profile p {
    margin: 0px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    font-size: 12px;
    color: black;
}
	.my-profile .user_profile h2 {
    font-size: 14px !important;
    /* padding-bottom: 6px; */
    color: black;
    color: black !important;
    margin: 0px;
    font-weight: 700;
}
	.ordered_list a {
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    color: #d12a2b;
    margin-bottom: 20px;
    line-height: 20px;
    font-weight: 400;
}
.user-cart .cart-box table p {
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 400;
    margin: 0;
    margin-bottom: 5px;
    color: #6c757d;
}
  .ordered_list {
    display: flex;
    justify-content: space-around;
}
.user-earningblock{
    padding-top: 14px;
}
	</style>

<body>
<?php include('common/header.php') ?>
	<!-- profile -->
	<div class="my-profile">
		<div class="container">
			<div class="row">
				<?php include ('common/profile/menu.php') ?>
				<div class="col-md-9">
					<?php include ('common/profile/head.php') ?>
					<div class="user-earningblock">

						<div class="user-cart">
							<?php foreach ($orderData as $key => $value) { ?>							
							<div class="cart-box">
								<div class="table-responsive cart-table">
									<table class="table">
										<tbody>
											<tr>
												<td width="20%">
													<div class="cart-product">
														<img src="<?=base_url().$value['product_image'][0]?>" class="img-fluid" alt="">
													</div>
												</td>
												<td width="100%">
													<div class="table-hd">
														<h3><?=$value['product_name'][0]?></h3>
														<h5><?=strip_tags($value['product_desc'][0])?></h5>
													</div>
													<ul class="ordered_list">
														<li>
															<a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span><?=strip_tags($value['dilivertAddress'])?></span></a>
														</li>
														<li>
															<a href="#">Order Number:
															<p><span>#<?=$value['order_id']?></span></p>
															</a>
														</li>
														<li>
															<a href="#">Date of Order :
															<p><span><?=date('d M Y', strtotime($value['created_at']))?></span></p>
															</a>
														</li>
														<li>
															<a href="#">Quantity :
															<p><span><?=$value['quantity']?> Nos.</span></p></a>
														</li>
													</ul>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<?php } ?>
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
		//         if ($(this).data("item") == "RUB") {
		//             $('.price').attr('data-currency', 'RUB');
		//             $('.currency').text('руб.');
		//         } else if ($(this).data("item") == "UAH") {
		//             $('.price').attr('data-currency', 'UAH');
		//             $('.currency').text('грн.');
		//         } else {
		//             $('.price').attr('data-currency', 'USD');
		//             $('.currency').text('долл.');
		//         }
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
	<script>
	    $(function() {
  $('.chart1').easyPieChart({
    size: 70,
    barColor: "#dc3545b0",
    
    lineWidth: 6,
    trackColor: "#98fb987d",
    lineCap: "circle",
    animate: 2000,
  });
});
	</script>
</body>

</html>

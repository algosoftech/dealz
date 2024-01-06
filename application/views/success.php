<!Doctype html>
<html lang="eng">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<?php include('common/head.php') ?> </head>
	<style>

@media (min-width: 768px) and (max-width: 820px){
	.coupen-box {
		margin: auto;
	}
}
		
@media (min-width: 360px) and (max-width: 600px){
	.coupen-img{
display:none;	
}

.coupon .coupen-footer p {
    font-size: 12px;
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    color: #fff;
}
.coupen-box {
    background: #fff;
    padding: 20px 0px;
    margin: 0px !important;
    border-radius: 8px;
    -webkit-box-shadow: 1px 1px 5px 0px rgb(184 184 184);
    -moz-box-shadow: 1px 1px 5px 0px rgba(184,184,184,1);
    box-shadow: 1px 1px 5px 0px rgb(184 184 184);
}
}

.text-heading{
	font-weight: 700 !important;
}

.order-date-section{
    margin: 20px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 10px;
}

.table-responsive tr{
	color:#000000;
	font-weight: 100;
}



</style>
<body>
	<?php include('common/header.php') ?>
	
	<div class="coupen-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-12 col-12 pd-zero"></div>
				<div class="col-lg-6 col-md-12 col-12 pd-zero">
					<div class="coupen-box">
						<img src="<?=base_url('assets/img/right.png');?>" class="img-fluid d-block mx-auto" alt="right_img">
						<h3 class="default-heading">Thank You</h3>
						<p>Please find below your Invoice and order details.</p>
						<ul class="nav nav-tabs justify-content-center">
							<li><a data-toggle="tab" class="active" href="#Invoice">Invoice</a></li>
							<li><a data-toggle="tab" href="#Coupons">Coupons</a></li>
						</ul>
						<div class="tab-content">
							<div id="Invoice" class="tab-pane fade show active">
								<div class="invoice-details">
									<p class="text-heading">Tax Invoice</p>
									
									<div class="order-date-section">
										<p>Order Id <span><?php echo $order_id;?></span></p>
										<?php if($stripe_token): ?>
											<p>Transaction Id <span><?php echo $stripe_token;?></span></p>
										<?php endif; ?>
										<p>Purchased on : <span><?php echo date('d M, Y h:i A' ,strtotime($orderData['created_at']))?></span></p>
									</div>



									<div class="table-responsive">
										<table class="table">
											<tr>
												<th>Product</th>	
												<th>Quantity</th>	
												<th>Subtotal</th>	
											</tr>
											<?php foreach($orderDetails as $OD):
												$CPwcon['where']					=	[ 'product_id' => $OD["product_id"] ];									
												$CPData								=	$this->geneal_model->getData2('single', 'da_prize', $CPwcon);			
											?>
											<tr>
												<td><?php echo stripslashes($OD['product_name']) //$OD['product_name'];?></td>	
												<td> AED  <?= $OD['price'].'x'. $OD['quantity']?></td>	
												<td>AED <?php echo number_format($OD['subtotal'],2);?></td>
											</tr>

											<tr >
												<td colspan="2">Including VAT</td>	
												<td>AED <?=@number_format($orderData['total_price'],2)?></td>	
											</tr>
											<?php endforeach;?>
										</table>
									</div>
								
									<div class="table-responsive">
										<table class="table">
											<tr>
												<td>Discount</td>	
												<td class="text-right"><?=@$cashback?></td>	
											</tr>
											<?php if($orderData['payment_mode'] == 'arabianpoint' || $orderData['payment_mode'] == 'Arabian Points'): ?>
												<tr>
													<td>Paid Using Arabian Points</td>	
													<td class="text-right">AED <?=@number_format($orderData['total_price'],2)?></td>	
												</tr>
												
											<?php else: ?>
												<tr>
													<td>Paid Using Card</td>	
													<td class="text-right">AED <?=@number_format($orderData['total_price'],2)?></td>	
												</tr>
											<?php endif; ?>
										</table>
									</div>
								</div>
							</div>
							<div id="Coupons" class="tab-pane fade">
								<?php foreach ($couponDetails as $coupon) { ?>
								<div class="coupon">
									<div class="about-coupen">

										<?php 
											$SPwhereCon['where']	=	array('products_id' => (int)$coupon['product_id'] );
											$ProductData			=	$this->geneal_model->getData2('single','da_products',$SPwhereCon);
										?>
										<div class="coupen-img">

											<?php if(file_get_contents(base_url($ProductData['product_image'])) ):  ?>
												<img src="<?=base_url($ProductData['product_image']);?>" class="img-fluid" alt="<?=$ProductData['product_image_alt'];?>">
											<?php else: ?>
												<img src="<?=base_url('assets/img/NO_IMAGE.jpg');?>" class="img-fluid" alt="<?=$ProductData['product_image_alt'];?>">
											<?php endif;?>
										</div>
										<div class="coupen-info">
											
											<p>Products: <span><?=$coupon['product_title']?></span></p>
											<p>Purchased on: <span><?=date('d M, Y h:i A' ,strtotime($coupon['created_at']))?></span></p>
											
											<p>Draw Date : <span><?=date('d M, Y' ,strtotime($ProductData['draw_date'].' '.$ProductData['draw_time']))?></span></p>
										</div>
									</div>
									<div class="coupen-footer">
										<p>Coupon No: <?=$coupon['coupon_code']?></p>
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="promo-code">
								<a href="<?=base_url()?>" class="color-default-btn float-right">Shop More</a>
								<a href="<?=base_url().'order/download-invoice/'.$order_id ;?>" class="color-default-btn float-left">Download Invoice</a>
							</div>
						</div>
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
	
</body>

</html>

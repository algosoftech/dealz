<?php echo""; ?>
<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dealz Arabia || Cart-Items</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<link rel="icon" href="<?=base_url('assets/')?>img/h-1.png" type="image/x-icon"/>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">			
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/style.css">		
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/responsive.css">
    <style>
.error{
	color: red; 
}
.activess{
background-color:green !important;
}
    .outside-cart-block .toggle {
    position: relative;
    height: 14px;
    width: 50px;
    border-radius: 15px;
    background: #ddd;
    margin: 8px 0;
    float: right;
}
.outside-cart-block p {
    margin: 0;
    color: #fff;
}
.outside-cart-block .toggle input:nth-child(2):checked {
    z-index: 1;
}
.fa-trash:before {
    content: "\f1f8";
    color: #a62323;
    font-size: 19px;
}
.outside-cart-block  .toggle input:nth-child(2):checked + .toggle__pointer {
  left: 22px;
  background-color: #fff;
}
.outside-cart-block .toggle input:nth-child(2):checked + .toggle__pointer {
    left: 22px;
    background-color: #fff;
}
.outside-cart-block .toggle__pointer {
    position: absolute;
    top: -7px;
    left: 0;
    width: 28px;
    height: 28px;
    border-radius: 15px;
    background-color: #ffffff;
    -webkit-transition: left .15s ease-out;
    transition: left .15s ease-out;
}
.outside-cart-block .toggle input {
    opacity: 0;
    width: 100%;
    height: 200%;
    position: absolute;
    top: -7px;
    left: 0;
    z-index: 2;
    margin: 0;
}
.outside-cart-block .toggle {
    position: relative;
    height: 14px;
    width: 50px;
    border-radius: 15px;
    background: #ddd;
    margin: 8px 0;
    float: right;
}
.outside-cart-block .donate-footer {
    background: #e72d2e;
    padding: 5px 30px;
    border-radius: 0 0 8px 8px;
    margin-top: 0px;
    z-index: 2;
    position: relative;
}
</style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="outside-cart-block">
	<div class="container">
		<div class="row">
			<div class="col-lg-9 col-md-9 col-12">
				<?php $totalAED=0; foreach ($this->cart->contents() as $items) { 
					$totalAED = $totalAED + (int)$items['qty'] * (int)$items['other']['aed'];
					?>
				<div class="user-cart">
					<div class="cart-box">
						<div class="table-responsive cart-table">
							<table class="table">
								<tbody>
									<tr>
										<td width="20%">
											<div class="cart-product">
												<img src="<?=base_url().$items['other']['image']?>" class="img-fluid" alt="cart_product">
											</div>
										</td>
										<td width="50%">
											<div class="table-hd">
												<h3><?=$items['name']?></h3>
												<h5><?=strip_tags($items['other']['description'])?></h5>
											</div>
											<ul>
												<li>
													<div class="number">
														<span data-id='<?=$items['other']['aed']?>___<?=$items['rowid']?>___D' class="minus qtyA">-</span>
														<input type="number" min="1" id="qty" name="qty" value="<?=$items['qty']?>" readonly>
														<span data-id='<?=$items['other']['aed']?>___<?=$items['rowid']?>___I' class="plus qtyA">+</span>
													</div>
												</li>
												<li>
													<a href="<?=base_url('remove-item/').$items['rowid']?>" class="remove-item"> Remove </a>
												</li>
											</ul>
										</td>
										<td>
											<div class="inquiry-and-buy">
												<?php $total = $items['other']['aed'] * $items['qty']	?>
												<p id="<?=$items['rowid']?>">AED<?=$total?>.00</p>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="donate-footer <?php if($items['is_donated'] == 'Y'){?> activess <?php } ?>" id="d<?=$items['rowid']?>">
							<div class="row">
								<div class="col-lg-9 col-md-9 col-12">
									<p>Donate these product(s) to double ticket(s)</p>
								</div>
								<div class="col-lg-3 col-md-3 col-12">
									<div class="toggle" id="toggle" data-id="<?=$items['rowid']?>">
										<input type="radio" id="radio" value="on" id="r<?=$items['rowid']?>"  name="r<?=$items['rowid']?>">
										<input type="radio" value="off" id="r<?=$items['rowid']?>"  name="r<?=$items['rowid']?>" <?php if($items['is_donated'] == 'Y'){?> checked="checked" <?php } ?> >
										<div class="toggle__pointer"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="related-product">
					<h3 class="default-heading">People have also bought this together</h3>
					<div class="row">
						<?php foreach ($products as $key => $value) { ?>
						<div class="col-lg-4 col-md-4 col-12">
							<a href="<?=base_url('product-details/').base64_encode($value['products_id'])?>">
							<div class="slide-product-box">
								<?php if(isset($value['soldout_status']) && $value['soldout_status'] == 'Y'){ ?> 
								<div class="coupen-img">
									<div class="">
									<?php
								    $avlstock = $value['stock'] * 100 / $value['totalStock'];
								    $remStick = 100 - $avlstock;
									?>
								    <div class="box">
                                        <div class="chart1" data-percent="<?=number_format($remStick,0)?>" ><?=number_format($remStick,0)?>%</div>
                                    </div>
								</div>
								<h2 class="textes">Sold</h2>
								</div>
								<?php }else{ ?>
									<div class="coupen-img">
										<div class="">
											<div class="box">
	                                       	<div class="chartdemo easyPieChart" style="width: 70px; height: 70px; line-height: 70px;"></div>
	                                    	</div>
										</div>
										<h2 class="textes">&nbsp;</h2>
									</div>
								<?php } ?>
								<div class="product-img">
									<img src="<?=base_url().$value['product_image']?>" class="img-fluid" alt="product_img">
								</div>
								<div class="product-des">
									<p>Get a chance to win:</p>
									<h4><?=$value['title']?></h4>
									<h5>AED<?=number_format($value['adepoints'], 2)?></h5>
									
								</div>
							</div>
							</a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-12">
				<div class="coupen-box">
					<div class="table-responsive">
						<?php
							$inclusiveOfVat = 	$totalAED;
							$totalVat		=	($totalAED * 5/100);
							$subTotal 		=	($inclusiveOfVat-$totalVat);
						?>
						<table class="table">
							<tbody><tr>
								<td>Total Inclusice of VAT</td>	
								<td class="text-right" id="subTotal">AED<?php echo number_format($inclusiveOfVat,2); ?></td>
							</tr>
							<tr>
								<td>Subtotal</td>	
								<td class="text-right" id="subTotalAmt">AED<?php echo number_format($subTotal,2); ?></td>		
							</tr>
							<tr>
								<td>VAT</td>	
								<td class="text-right" id="vat">AED<?php echo number_format($totalVat,2); ?></td>
							</tr>
						</tbody></table>
					</div>
				</div>
				<div class="promo-code">
					<a href="<?=base_url('login')?>?referenceUrl=user-cart" class="color-default-btn float-right">Pay Now</a>
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
<script>
	var boxes = document.querySelectorAll('input[type="radio"]');
	boxes = Array.prototype.slice.call(boxes);

	boxes.forEach(function(box) {
	  box.addEventListener('change', function(e) {
	  });
	});
</script>
<script>
	$(document).ready(function(){
		$('.qtyA').click(function(){
			var data 	= $(this).data('id');
			var ur = '<?=base_url()?>';
			var totalID = data.split("___");
			$.ajax({
				url : ur+ "shopping_cart/addqty",
				method: "POST", 
				data: {data: data},
				success: function(data1){
					
					var totalVAL = data1.split("__");
					var A = 'AED'+totalVAL[0]
					var B = 'AED'+totalVAL[1]
					var C = 'AED'+totalVAL[2]
					var D = 'AED'+totalVAL[3]
					
					$('#'+totalID[1]).empty().append(A);
					$('#subTotal').empty().append(B);
					$('#vat').empty().append(C);
					$('#subTotalAmt').empty().append(D);
		
				}
			});
		});
	});
</script>
<script>
	
	$('.toggle').on('click', function() {
	  	var rowid = $(this).data('id');
	  	var id = '#r'+rowid;
	  	var divID = '#d'+rowid;
	  	var ur = "<?=base_url()?>";
	  	if ($(id).prop("checked")) {
	   		var donate = 'Y';
		}else{
			var donate = 'N';
		}
		$.ajax({
			url : ur+ "shopping_cart/donate",
			method: "POST", 
			data: {rowid: rowid, donate:donate},
			success: function(resdata){
				if(resdata === 'Y'){
					$(divID).addClass("activess");
				}
				if(resdata === 'N'){
					$(divID).removeClass("activess");
				}
			}
		}); 	 
	});
</script>
</body>
</html>

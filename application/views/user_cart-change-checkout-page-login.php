<?php //echo "<pre>"; print_r($cartItems); die(); ?>
<!Doctype html>
<html lang="eng">

<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php include('common/head.php') ?> 
    <style>
    .my-profile .toggle {
    position: relative;
    height: 14px;
    width: 50px;
    border-radius: 15px;
    background: #ddd;
    margin: 8px 0;
    float: right;
}
.user-cart .cart-box table h5 {
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    line-height: 22px;
    font-weight: 400;
    text-align: left;
    color: #6c757d;
    margin-bottom: 0;
}
.my-profile p {
    margin: 0;
    color: #fff;
}
.my-profile .toggle input:nth-child(2):checked {
    z-index: 1;
}
.fa-trash:before {
    content: "\f1f8";
    color: #a62323;
    font-size: 19px;
}
.my-profile  .toggle input:nth-child(2):checked + .toggle__pointer {
  left: 22px;
  background-color: #fff;
}
.my-profile .toggle input:nth-child(2):checked + .toggle__pointer {
    left: 22px;
    background-color: #fff;
}
.my-profile .toggle__pointer {
    position: absolute;
    top: -6px;
    left: 0;
    width: 25px;
    height: 24px;
    border-radius: 15px;
    background-color: #ffffff;
    -webkit-transition: left .15s ease-out;
    transition: left .15s ease-out;
}
.my-profile .toggle input {
    opacity: 0;
    width: 100%;
    height: 200%;
    position: absolute;
    top: -7px;
    left: 0;
    z-index: 2;
    margin: 0;
}
.my-profile .toggle {
    position: relative;
    height: 14px;
    width: 50px;
    border-radius: 15px;
    background: #ddd;
    margin: 8px 0;
    float: right;
}
.my-profile .donate-footer {
    background: #e72d2e;
    padding: 5px 30px;
    border-radius: 0 0 8px 8px;
    margin-top: 0px;
    z-index: 2;
    position: relative;
}
        .change_passworded{
         text-align: end;
         padding: 20px;   
        }
        .activess{
background-color:green !important;
}
.user-cart .cart-box table p {
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    margin: 0;
    margin-bottom: 5px;
    color: #ebebeb;
    margin: 0px;
    padding-top: 4px;
}
.change_passworded {
    text-align: end;
    padding: 10px 0px 20px;
}
.my-profile .btn:hover{
background: #e72d2e;
    color: #ffffff;
}
.my-profile .btn{
    background: #ffffff;
    border: 1px solid #e72d2e;
    padding: 4px 25px !important;
    border-radius: 8px;
    color: #e72d2e;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin-top: 0px;
}
.coupen-box {
	background: #fff;
	padding: 20px;
	margin: 0px;
	border-radius: 8px;
	-webkit-box-shadow: 1px 1px 5px 0px rgba(184,184,184,1);
	-moz-box-shadow: 1px 1px 5px 0px rgba(184,184,184,1);
	box-shadow: none;
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
				<div class="users_form user-cart">
					<?php if ($this->session->flashdata('error')) { ?>
					<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					<?php } ?>
					<?php if ($this->session->flashdata('success')) { ?>
					<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php  } ?>
					<div class="profiles">
						<h4 class="information">My Cart</h4> 
					</div>
					<div class="row">
						<?php if($this->cart->contents()): ?>
							<div class="col-lg-8 col-md-8 col-12">
								<div class="cart-box">
									<div class="table-responsive cart-table">
										<table class="table">
											<tbody>
												<?php $totalAED=0; foreach ($this->cart->contents() as $items) { 
													$totalAED = $totalAED + (int)$items['qty'] * (int)$items['other']['aed'];
												?>
													<tr>
														<td width="50%">
															<div class="cart-product">
																<img src="<?=base_url().$items['other']['image']?>" class="img-fluid" alt="cart_product_img">
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
																	<a href="<?=base_url('remove-item/').$items['rowid']?>"><i class="fa fa-trash" aria-hidden="true" title="Delete"></i></a>
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
													<tr>
													   <td colspan="4" style="padding:10px 0px !important">	
														   	<div class="donate-footer <?php if($items['is_donated'] == 'Y'){?> activess <?php } ?>" id="d<?=$items['rowid']?>">
																<div class="row">
																	<div class="col-lg-9 col-md-9 col-12">
																		<p>Donate these product(s) to double ticket(s)</p>
																	</div>
																	<div class="col-lg-3 col-md-3 col-12">
																		<div class="toggle" data-id="<?=$items['rowid']?>">
																			<input type="radio" id="radio" value="on" id="r<?=$items['rowid']?>"  name="r<?=$items['rowid']?>">
																			<input type="radio" value="off" id="r<?=$items['rowid']?>"  name="r<?=$items['rowid']?>" <?php if($items['is_donated'] == 'Y'){?> checked="checked" <?php } ?> >
																			<div class="toggle__pointer"></div>
																		</div>
																	</div>
																</div>
															</div>
														</td>
													</tr>
											
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>	
							<div class="col-lg-4 col-md-4 col-12">
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
							</div>
							<div class="col-md-12">
							<div class="change_passworded">
							 	<a class="btn" href="<?php echo base_url();?>checkout">Pay now</a>
							</div>
						</div>
						<?php else: ?>
							<div class="col-lg-12 col-md-12 col-12">
								<div class="coupen-box">
									<div class="table-responsive" style="border:none">
										<table class="table">
											<tbody><tr>
												<td style="border:none">Your cart is empty</td>	
											</tr>
										</tbody></table>
									</div>
								</div>
							</div>
						<?php endif; ?>
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

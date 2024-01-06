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


	.green {
		background: #0d9e51;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		color: #ffffff !important;
		font-weight: 900 !important;
	}
	.red{
	background: #c9261f;
	padding: 6px 10px;
	border-radius: 5px;
	font-size: 12px;
	color: #ffffff !important;
	font-weight: 900 !important;
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

	/* Chrome, Safari, Edge, Opera */
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
	}

	/* Firefox */
	input[type=number] {
	-moz-appearance: textfield;
	}

	.loading {
		align-items: center;
		display: none;
		justify-content: center;
		height: 100%;
		/* position: fixed; */
		width: 100%;
		}

		.loading__dot {
		animation: dot ease-in-out 1s infinite;
		background-color: grey;
		display: inline-block;
		height: 20px;
		margin: 10px;
		width: 20px;
		}

		.loading__dot:nth-of-type(2) {
		animation-delay: 0.2s;
		}

		.loading__dot:nth-of-type(3) {
		animation-delay: 0.3s;
		}

		@keyframes dot {
		0% { background-color: grey; transform: scale(1); }
		50% { background-color: #e72d2e; transform: scale(1.3); }
		100% { background-color: grey; transform: scale(1); }
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
				<div class="users_form" style="margin-bottom: 0;">
				<?php if($this->session->userdata('DZL_USERSTYPE') != 'Users'){ ?> 
					<div class="profiles">
						<h4 class="information" >Verify your coupon code</h4>
					</div>
					<?php if($this->session->flashdata('success')): ?>
						<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
					<?php endif; ?>

					<?php if($this->session->flashdata('error')): ?>
						<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
					<?php endif; } ?>

					<div class="row form_user">
						<?php if($this->session->userdata('DZL_USERSTYPE') != 'Users'){ ?> 
							<form class="form-inline" id="couponForm" method="post" action="<?=base_url('collect-prize')?>">
								<div class="col-md-6 px-0">
									<div class="inputWithIcon">
										<input type="text" style="text-transform:uppercase" autocomplete="off" name="coupon_code" id="coupon_code" value="<?php if(set_value('coupon_code')): echo set_value('coupon_code'); endif; ?>" placeholder="Enter your Coupon Code">
										<i class="fa fa-address-card" aria-hidden="true"></i>
										<label id="coupon_code-error" class="error" for="coupon_code">&nbsp;</label>
									</div>
								</div>
								
								<div class="col-md-6 px-0">
									<div class="inputWithIcon">
										<input type="number" autocomplete="off" name="pin" id="pin" value="<?php if(set_value('pin')): echo set_value('pin'); endif; ?>" placeholder="Enter your collection PIN">
										<i class="fa fa-key" aria-hidden="true"></i>
											<label id="pin-error" class="error" for="pin">&nbsp;</label>
									</div>
								</div>
								
								<div class="col-md-9 px-0">
									</div>
										<div class="col-md-3 px-0" style="text-align:right;">
											<div class="change_password" style="display:block;">
												<input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
												<a href="javascript:void{0}" id="approve-submit-form" class="btn">Approve</a>
											</div>
										</div>
							
									</div>
						
								</div>
							</form>
						<?php } ?>
						<div class="row">
							<div class="col-md-12 coupen_list">
								
								<form class="form-inline" id="rechargeForm" method="post">
									<div class="col-md-10 px-0"> 
										<input type="text" name="search" id="search	" class="form-control"  autocomplete="off" placeholder="Search your coupon number / order id" value="<?=set_value('search');?>">
									</div>
									&nbsp;

									<div class="col-md-1 px-0"> 
										<input type="submit" class="btn show_record" style="padding: 6px 20px!important;" value="Show">
									</div>
								</form>		
								


								<!-- Winnner's List -->
								<div class="user_list" id="winner-list" style="overflow-x: auto;">
									<input type="hidden" id="offset" value="<?=$offset?>"/>
									<input type="hidden" id="limit" value="<?=$limit?>"/>
									<input type="hidden" id="perpage" value="<?=$perpage?>"/>
									<input type="hidden" id="code" value="<?=$code?>"/>
									<input type="hidden" id="product_id" value="<?=$product_id?>"/>
									<input type="hidden" id="data_count" value="<?=count($winner_list)?>"/>
									<table id="winner-table">
										<tr>
											<th style="text-align:center;">S.No</th>
											<th style="text-align:center;">Order ID</th>
											<th style="text-align:center;">Draw Date</th>
											<th style="text-align:center;">Name</th>
											<th style="text-align:center;">Coupon Number</th>
											<th style="text-align:center;">Winning Amount</th>
											<?php if($this->session->userdata('DZL_USERSTYPE') != "Users"){ ?>
												<th style="text-align:center;">Collection Status</th>
												<th style="text-align:center;">Action</th>
											<?php } ?>
										</tr>
										<?php foreach ($winner_list as $key => $value) { ?>
											<tr class="purchase-section">
												<td width="10%"><?=++$key?></td>
												<td width="100%"><?=$value['id']?></td>
												<td width="100%"><?=$value['draw_date']?></td>
												<td width="100%"><?=$value['name']?></td>
												<td width="100%"><?=$value['coupon_code']?></td>
												<td width="100%"><?=$value['amount']?></td>
												<?php if($this->session->userdata('DZL_USERSTYPE') != "Users"){ ?>
													<td width="100%"><?=$value['collection_status']?></td>
													<?php if($value['collection_status'] == "Pending"){ ?>
														<td width="10%"> 
															<!-- <a href="javaScript:void{}" class="btn btn-primary approved-coupon" data-coupon="<?=$value['coupon_code']?>">Approval</a>  -->
															<a href="javaScript:void{}" class="btn btn-primary" onclick="active_coupon('<?=$value['coupon_code']?>')" >Approval</a>
														</td>

													<?php }else{ ?>
														<td width="10%"> <span class="green">  Approved </span> </td>
													<?php } ?>
												<?php } ?>
											</tr>
										<?php } ?>
									</table>
									<div class="loading">
										<span class="loading__dot"></span>
										<span class="loading__dot"></span>
										<span class="loading__dot"></span>
									</div>
								</div>
								<!-- Winner's list end -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#approve-submit-form').click(function(){
			let coupon = $('#coupon_code').val();
			let pin = $('#pin').val();
			if(!coupon){
				$('#coupon_code-error').html("Please enter copon.");
			}else if(!pin){
				$('#pin-error').html("Please enter PIN.");
			} else{
				$('#couponForm').submit();
			}
		});
	});

	function active_coupon(code =''){
		let coupon = code
		$('#coupon_code').val(coupon);
		$('#pin').focus();
	}

	let request_sent = 0;
	let offset		= $('#offset').val();
	let limit 		= $('#limit').val();
	let perpage 	= $('#perpage').val();
	let code 		= $('#code').val();
	let product_id 	= $('#product_id').val();
	let data_count 	= $('#data_count').val();
	let last 		= '';
	$(window).scroll(function() {
		var windowHeight = $(window).height();
		var documentHeight = $(document).height();
		var scrollPosition = $(window).scrollTop();
		var lastSectionHeight = $('#winner-list').height();
		var lastSectionOffset = $('#winner-list').offset().top;

		if (scrollPosition + windowHeight >= lastSectionOffset + lastSectionHeight && request_sent === 0) {
			$('.loading').css('display','flex');
			var ur = '<?=base_url()?>';
			request_sent = 1
            $.ajax({				
                url: ur + 'get-winners-by-ajax',
                type: "POST",
                data: {
                    'offset': offset,
					'limit': limit,
					'perpage': perpage,
					'code' : code,
					'product_id' : product_id
                },
                cache: false,
                success: function (result) {
					$('.loading').css('display','none');
					let res = JSON.parse(result)
					if(res.status === 200){
						offset = res.newoffset; 
						$('#offset').val(offset);
						// offset = parseInt(offset) + parseInt(perpage);
						let winnerData = JSON.parse(res.data);
						let html = '';
						let link = '';
						if(winnerData  !== null){
							winnerData.forEach(element => {
							 	html +=	'<tr class="purchase-section">';
								html +=		'<td width="10%">'+(++data_count)+'</td>';
								html += 	'<td width="100%">'+element.id+'</td>';
								html += 	'<td width="100%">'+element.draw_date+'</td>';
								html += 	'<td width="100%">'+element.name+'</td>';
								html += 	'<td width="100%">'+element.coupon_code+'</td>';
								html += 	'<td width="100%">'+element.amount+'</td>';
								
								<?php if($this->session->userdata('DZL_USERSTYPE') != "Users"){ ?>
									html += 	'<td width="100%">'+element.collection_status+'</td>';
									if(element.collection_status === "Pending"){
										link = "active_coupon('"+element.coupon_code+"')";
										html += '<td width="10%"> <a href="javaScript:void{}" class="btn btn-primary" onclick="'+link+'">Approval</a> </td>';
									} else{
										html +=	'<td width="10%"> <span class="green">  Approved </span> </td>';
									}	
								<?php } ?>
								html += '</tr>';
								
								if(element.total === data_count){
									last = 'END';
								}
							});	
							if(last === 'END'){
								request_sent = 1;
							} else{
								request_sent = 0;
							}
						}
						
						$('#winner-table').append(html);
						$('#data_count').val('')
					} else{
						request_sent = 1;
					}
                }
            });			
		}
	});
</script>
<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>

<script>
	function ConfirmForm() {
	$("#BlockUIConfirm").show();
	}

	 $(document).ready(function(){
      	
      	$('select[name="filters"]').on('change', function(){
        
         filter = $(this).val();


         if(filter == "all"){
          $('.purchase-section').show();
          $('.cashback-section').show();
          $('.quickbuy-section').show();
          $('.refund-section').show();
          $('.coupon-section').show();
          $('.recharge-sent-section').show();
          $('.recharge-received-section').show();


         }else if(filter == "sent"){
          
          $('.purchase-section').hide();
          $('.cashback-section').hide();
          $('.quickbuy-section').show();
          $('.refund-section').hide();
          $('.coupon-section').hide();
          $('.recharge-sent-section').show();
          $('.recharge-received-section').hide();


         }else if(filter == "received"){
          
          $('.purchase-section').hide();
          $('.cashback-section').hide();
          $('.quickbuy-section').hide();
          $('.refund-section').hide();
          $('.coupon-section').hide();
          $('.recharge-sent-section').hide();
          $('.recharge-received-section').show();


         }else if(filter == "order"){

          $('.purchase-section').show();
          $('.cashback-section').hide();
          $('.quickbuy-section').show();
          $('.refund-section').hide();
          $('.coupon-section').hide();
          $('.recharge-sent-section').hide();
          $('.recharge-received-section').hide();

         }

      });


    });


</script>
</body>

</html>
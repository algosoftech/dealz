<!Doctype html>
<html lang="eng">
<head>
	<!-- Basic page needs -->	
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
		/* .deopdown {
			width: 100%;
			border: 2px solid #f1f1f1;
			border-radius: 4px;
			margin: 8px 0;
			outline: none;
			padding: 5px 28px 5px;
			box-sizing: border-box;
			transition: 0.3s;
		} */

		.adress-edit-dropdown .dropdown-menu {
		  top: 30px !important;
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
	</style>
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<!-- Boostrap JS -->
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>


</head>
<body>
	<?php include('common/header.php') ?>
	<!-- profile -->
	<div class="my-profile recharge">
		<div class="container">
			<div class="row">
				<?php include ('common/profile/menu.php') ?>
				<div class="col-md-9">
					<?php include ('common/profile/head.php') ?>
						<?php if ($this->session->flashdata('error')) { ?>
							<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
						<?php } ?>
						<?php if ($this->session->flashdata('success')) { ?>
							<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
						<?php  } ?>
						<div class="users_form">
							<div class="profiles">
								<h4 class="information" >View Due Management</h4>
							</div>
							<div class="row form_user">

							  <table>
							    <tr>
							      <th style="text-align:center;">S.No</th>
						  		  <th style="text-align:center;">Recharge Amount</th>
						  		  <th style="text-align:center;">Cash Collected Amount</th>
						  		  <th style="text-align:center;">Due Amount</th>
						  		  <th style="text-align:center;">Advance Amount</th>
						  		  <th style="text-align:center;">Created On</th>
						  		  <th style="text-align:center;">Updated On</th>
						  		  <th style="text-align:center;">Action</th>
						  		</tr>

									<?php if($DueManagement): ?>
										<?php foreach ($DueManagement as $key => $item): ?>
						  				<tr>
											<td><?php echo $key+1;  ?> </td>
											
											
											<td><?= $item['recharge_amt'];  ?> </td>
											<td><?= $item['cash_collected'];  ?> </td>
											
											<td><?= $retVal = ($item['advanced_amount'] >0) ?  '0': $item['due_amount'];  ?> </td>

											<td><?= $item['advanced_amount'];  ?> </td>
											<td><?= date("Y-m-d h:i" ,strtotime($item['created_at']) );      ?> </td>
											<td><?php if($item['update_date']): echo  date("Y-m-d h:i" , $item['update_date']);   endif;   ?> </td>
											<td>

											<?php if($item['due_amount']> 0 || $item['advanced_amount'] >0 ): ?>
												<div class="adress-edit-dropdown">
				                                    <button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				                                    <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
				                                    </button>
				                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(488px, 26px, 0px);">
				                                    
				                                        <div class="container mb-2">
				                                        		
		                                        		    <form id="currentPageForm" name="currentPageForm" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
											                   
				                                        		<div class="row">
												                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=manojEncript($item['due_management_id'])?>"/>
												                    <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes"/>
												                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
				                                        			<div class="col-sm-12 col-md-12 col-lg-12">
				                                        			
				                                        			<?php 
				                                        				 $advance_cash = $item['advanced_amount'];
				                                        				 $due_amount   = $item['due_amount'];
				                                        				 $recharge_amt   = $item['recharge_amt'];
				                                        			?>

				                                        			<?php if($advance_cash >=1): ?>
			                                        					<input type="number" name="recharge_amt" id="recharge_amt" class="from-control" placeholder="Recharge Amount" min="1" max="<?=$item['advanced_amount']?>">
				                                        			<?php elseif($due_amount >=1 && $advance_cash == 0 ): ?>
				                                        				<input type="number" name="collect_cash" id="collect_cash" class="from-control" placeholder="Collect Cash" min="1" max="<?=$item['due_amount']?>">
				                                        			<?php endif; ?>

				                                        				<input type="submit" class="collect_cash btn btn-success" id="collect_cash" value="submit">
				                                        			
				                                        			</div>
				                                        		</div>
				                                        	</form>
				                                        </div>

				                                    </div>
				                                </div>
			                                <?php endif; ?>
											</td>
						  				</tr>

										<?php endforeach; ?>

									<?php else: ?>
										<tr><td> Data not found. </td></tr>
									<?php endif; ?>
						  				
						  	  </table>


							</div>
						</div>

					</div>

				</div>
		
</div>
</div>
</div>
</div>

<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>


<!-- Animation Js -->
<script>
	AOS.init({
		duration: 1200,
	})
</script>

<script>
/* TOP Menu Stick
--------------------- */
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
<!-- Main Slider Js -->





<!-- Header Dropdown -->

</body>
</html>

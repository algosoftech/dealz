<!Doctype html>
<html lang="eng">

<head>
	<!-- Basic page needs -->
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<?php include('common/head.php') ?> 
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<!-- Boostrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<style>
        .fa-map-marker:before {
        content: "\f041";
        color: #031b26;
        padding-right: 5px;
        }
        .user_list{ overflow-x: auto;
            padding-top: 21px;}
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
        table {
            border-collapse: collapse;
        }
        .user-earningblock{
            padding-top: 14px;
        }
        tr{
            border: 1px solid #ddd;
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
            </style>
            <style>
        .users_form .add-newaddress {
            border: 2px solid #d12a2b;
            padding: 4px 20px;
            border-radius: 8px;
        }
        .users_form .add-newaddress a {
            font-family: 'Open Sans', sans-serif;
            font-size: 15px;
            font-weight: 400;
            color: #000;
        }
        
        .fa-ellipsis-v:before {
            content: "\f142";
            color: rgb(209 209 209);
            
        }
        .adress-edit-dropdown .dropdown-menu {
        top: 30px !important;
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
					<div class="user-earningblock">

						<?php if(!empty($list)){ ?>
						<div class="user_list" style="overflow-x: auto;">
						  <table>
						    <tr>
						      <th style="text-align:Left;">Sl. No.</th>
						      <th style="text-align:Left;">Order ID</th>
						      <th style="text-align:Left;">Product Name</th>
						      <th style="text-align:Left;">Buyer's Details</th>
                              <th style="text-align:Left;">Purchase Date</th>
						      <th style="text-align:Left;">Total Amount</th>
                              <th style="text-align:Left;">Status</th>
                              <th style="text-align:Left;">Expairy Date</th>
						      <!-- <th style="text-align:Left;">Action</th> -->
						    </tr>
						    <?php $i=1; foreach ($list as $key => $items) { 
						    	//$ordDetails = $this->geneal_model->getDataByParticularField('da_orders_details', 'order_id', $items['order_id']);
						    	?>
						    <tr>
						      <td width="7%"><?=$i?></td>
						      <td width="20%"><?=$items['order_id']?></td>
						      <td width="20%"><?=$items['product_name']?></td>
						      <td width="25%">
                                <?php if($items['user_email'] && $items['user_phone']): ?>
                                    Email : <?=$items['user_email']?><br>
                                    Phone : <?=$items['user_phone']?>
                                <?php elseif($items['user_email']): ?>
                                    Email : <?=$items['user_email']?>
                                <?php else: ?>
                                    Phone : <?=$items['user_phone']?>
                                <?php endif; ?>
                                </td>
						      <td width="25%"><?=date('d M Y', strtotime($items['created_at']))?></td>
                              <td width="25%">ADE <?=number_format($items['subtotal'],2)?></td>
                              <td width="20%"><?=$items['collection_status']?></td>
                              <td width="25%"><?=date('d M Y', strtotime($items['expairy_data']))?></td>
						      <!-- <td>
						      	<div class="adress-edit-dropdown">
									<button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-ellipsis-v" aria-hidden="true"></i>
									</button>
							      	<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(488px, 26px, 0px);">
										<a href="<?=base_url('order-details/'.manojEncript($items['collection_point_id']))?>" class="dropdown-item active"><i class="far fa-eye" aria-hidden="true"></i> View All Orders</a>
									</div>
								</div>
						      </td> -->
						    </tr>
						    <?php $i++; } ?>
						  </table>
                          <?php echo $PAGINATION; ?>
						  
						</div>
						<?php }?>	
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
$('.dropdown').on('click', function () {
$(this).toggleClass('open')
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

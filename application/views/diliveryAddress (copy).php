<?php //echo"workig"; ?>
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
				<div class="users_form">
					<div class="profiles">
						<h4 class="information">Address</h4> 
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-12">
							    <div class="row">
									<div class="col-lg-9 col-md-9 col-sm-9 col-12">
										<?php  if ($this->session->flashdata('error')) { ?>
										<label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
										<?php } ?>
										<?php if ($this->session->flashdata('success')) { ?>
										<label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
										<?php  } ?>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-12">
									<div class="add-newaddress">
								
									<div >
										<a href="<?=base_url('add-dilivery-address')?>">Add New Address</a>
									</div>
								
									</div>
									</div>
								</div> 
								<div class="row">
									<?php foreach ($address as $key => $add) { ?>
										<div class="col-lg-6 col-md-6 col-sm-6 col-12 pr-0">
											<div class="user-address-box">
												<span class="home-tag"><?php echo $add['address_type']?></span>
												<div class="adress-edit-dropdown">
													<button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fas fa-ellipsis-v" aria-hidden="true"></i>
													</button>
													<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(488px, 26px, 0px);">
														<a href="<?=base_url('edit-dilivery-address/'.manojEncript($add['id']))?>" class="dropdown-item active"><i class="far fa-edit" aria-hidden="true"></i> Edit</a>
														<a href="<?=base_url('delete-dilivery-address/'.manojEncript($add['id']))?>" class="dropdown-item" onClick="return confirm('Want to delete!');"><i class="fas fa-trash" aria-hidden="true"></i> Delete</a>
													</div>
												</div>
												<h4><?php echo $add['name']?></h4>
												<p><?php echo $add['mobile']?></p>
												<p><?php echo $add['village'].', '.$add['street'].', '.$add['area'].', '.$add['city'].', '.$add['country'].' - '.$add['pincode']?></p>
												<p>Mobile No. : <?php echo $add['mobile']?></p>
											</div>
										</div>
									<?php } ?>
								</div>
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

</body>

</html>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
$( function() {
  $("#start_date").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
  $("#end_date").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
} );
</script>

<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper quick-order-summery">
        <div class="quick-order mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/quick-buy');?>"><i class="icofont-rounded-left"></i></a>
                <h1> Quick Orders Summery </h1>
            </section>
            <div class="inner_pagedata">
                <section class="deals_homesec">
                   <div class="myorder_table">

                        <div class="inner_forms login_tab">
                         <h3><span>Search</span></h3>
                            <form method="post" method="post">
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 form-group">
                                    <input type="text" id="product_title" class="form-control btn-search" name="product_title" placeholder="Search by product name" value="<?=$product_title;?>">
                                </div>
                                <div class="col-sm-5 col-md-5 col-lg-5 form-group">
                                    <input type="text" class="form-control" name="start_date" id="start_date" value="<?=$start_date;?>" placeholder="From Date" autocomplete='off'>
                                </div>
                                 <div class="col-sm-5 col-md-5 col-lg-5 form-group">
                                    <input type="text" class="form-control" name="end_date"  id="end_date" value="<?=$end_date;?>" placeholder="To Date" autocomplete='off'>
                                </div>
                                 <div class="col-sm-2 col-md-2 col-lg-2 form-group text-end">
                                     <button class="btn btn-danger w-75 btn-submit"> <i class="icofont-search"></i> </button>
                                </div>


                              </div>
                            </form>
                        </div>


                        <div class="inner_forms login_tab">
                         <h3><span>User Details</span></h3>

                            <div class="detail-section">
                                <span> <?=$items['_id'];?> </span>
                                <p class="detail-text">Opening Balance: <span> AED <?=$totalArabianPoints;?> </span></p>
                                <p class="detail-text">Total Sales : <span> AED <?=$total_sales;?> </span></p>
                                <p class="detail-text">Closing Balance: <span> AED <?=$availableArabianPoints;?> </span></p>
                            </div>

                        </div>

                        <div class="summery-container">
                            <?php if($products): ?>
                                <div class="row">
                                    <?php foreach($products as $key => $items ) :  ?>
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="summery-section active_couponbox">
                                                <div class="image-section">
                                                    <img class="product-img" src="<?=base_url($items['product_image'][0]);?>" >
                                                </div>
                                                <div class="detail-section">
                                                    <span> <?=$items['_id'];?> </span>
                                                    <p>Price: <span> AED <?=$items['price'];?> </span></p>
                                                    <p>Sales: <span> AED <?=$items['sales'];?> </span></p>
                                                    <p>Sales Count : <span><?=$items['sales_count'];?> </span></p>
                                                    <p>Donated Count: <span><?=count($items['product_is_donate']);?> </span></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                         <!-- pagination start  -->
                        <div class="pagination-container">
                            <?php 
                                foreach($pagination as $item):
                                    echo $item;
                                endforeach;
                            ?>
                        </div>
                        <!-- pagination End -->

                   </div>
                </section>
            </div>



    <?php include('common/mobile/footer.php'); ?>
    <?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php //include('common/mobile/footer_script.php'); ?>

 
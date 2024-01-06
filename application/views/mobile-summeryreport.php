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
                                 <div class="col-sm-6 col-md-6 col-lg-6 form-group text-end">
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

 <!--Script Start Here-->
    <!-- <script src="<?=base_url('assets/');?>mobile/js/jquery-1.11.0.min.js"></script> -->
    <script src="<?=base_url('assets/');?>mobile/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="<?=base_url('assets/');?>mobile/js/dealzscript.js"></script>

    <script type="text/javascript" src="<?=base_url('assets/');?>/countdownTimer/multi-countdown.js"></script>


<?php $useragent=$_SERVER['HTTP_USER_AGENT'];

    if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
     ?>
     <!-- Start of REVE Chat Script-->
      <script type='text/javascript'>
      window.$_REVECHAT_API || (function(d, w) { var r = $_REVECHAT_API = function(c) {r._.push(c);}; w.__revechat_account='1567774';w.__revechat_version=2;
        r._= []; var rc = d.createElement('script'); rc.type = 'text/javascript'; rc.async = true; rc.setAttribute('charset', 'utf-8');
        rc.src = ('https:' == document.location.protocol ? 'https://' : 'http://')+'static.revechat.com'+'/widget/scripts/new-livechat.js?'+new Date().getTime();
        var s = d.getElementsByTagName('script')[0]; s.parentNode.insertBefore(rc, s);
      })(document, window);
     </script>
     <!-- End of REVE Chat Script -->
     

   <?php
    endif; ?>


</body>
</html>

 
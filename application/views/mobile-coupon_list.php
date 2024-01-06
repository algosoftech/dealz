<?php include('common/mobile/header.php') ?>

    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Active Coupon
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="active_couponlist">
                    
                    <?php $i=1; foreach ($coupons as $key => $items) { 
                            if($fdsfdsf['coupon_status'] == 'Expired'):
                                $name   =   @$items['winners_name'];
                                $nameData = explode(' ',$name);
                                $count = count($nameData);
                                $winnerName = $nameData[0].' '.$nameData[$count -1];
                            else:
                                $winnerName     =   'Not Yet';
                            endif;
                        ?>
                    <div class="active_couponbox">
                        <p><span><?=@$items['product_title']?></span></p>
                        <p>Order Id: <span><?=@$items['order_id']?></span></p>
                        <p>Purchased On: <span><?= date('d M, Y, h:m A', strtotime($items['created_at']) ); ?> </span></p>

                        <?php 
                            $where['where'] = array('product_id', $items['product_id'] );
                            $tblName                =   'da_products';
                            $shortField             =   array('products_id'=> -1 );
                            $whereCon['where']      =   array('products_id'=>(int)$items['product_id']);
                            $productDetails         = $this->geneal_model->getData2('single', $tblName, $whereCon,$shortField,$this->uri->segment(2),$config['per_page']);

                            $draw_date = $productDetails['draw_date'].' '.$productDetails['draw_time'];
                            $DrawDate  =  date('d M, Y', strtotime($draw_date) );
                         ?>

                        <p>Draw Date: <span><?=$DrawDate;?></span></p>
                        <span>Coupon No.: <?=@$items['coupon_code']?></span>
                    </div>
                    <?php $i++; } ?>
               </section>
            </div>

<?php include('common/mobile/footer.php'); ?>
<?php include('common/mobile/menu.php'); ?>
        </div>
    </div>

<?php include('common/mobile/footer_script.php'); ?>
   
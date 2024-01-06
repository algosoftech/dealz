<?php include('common/mobile/header.php') ?>

    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Notification
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="active_couponlist">
                    
                    <?php if($userNotification): ?>
                        <?php foreach($userNotification as $items): ?>
                            <div class="active_couponbox">
                                <p><span><?=$items['notific_title']; ?></span></p>
                                <p><?=$items['notific_message'];?></p>
                                <p> <?=date('d M, Y, h:m A',$items['creation_date']);?> </p>
                            </div>
                        <?php endforeach; ?> 
                    <?php endif; ?>

               </section>
            </div>

<?php include('common/mobile/footer.php'); ?>
<?php include('common/mobile/menu.php'); ?>
        </div>
    </div>

<?php include('common/mobile/footer_script.php'); ?>
   
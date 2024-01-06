<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Terms & Conditions
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="info_pages">
                    <h2>Terms & Conditions</h2>
                    <div class="info_pagecontent faqp">
                        <div class="row">
                            <?php foreach ($termconditions as $key => $item) { ?>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <h3 class="default-heading"><?=$item['title']?></h3>
                                    <p><?=$item['description']?></p>
                                    
                                </div>
                            <?php } ?>
                        </div>
                    </div>
               </section>
            </div>

<?php include('common/mobile/footer.php'); ?>
<?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
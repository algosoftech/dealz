<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Contest Rule
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="info_pages">
                    <h2>Contest Rule</h2>
                    <div class="info_pagecontent faqp">
                        <div class="row">
                            
                                <div class="col-lg-12 col-md-12 col-12">
                                    <h3 class="default-heading"><?=$contestrule['title']?></h3>
                                    <p><?=$contestrule['description']?></p>
                                    
                                </div>
                            
                        </div>
                    </div>
               </section>
            </div>

    <?php include('common/mobile/footer.php'); ?>
    <?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
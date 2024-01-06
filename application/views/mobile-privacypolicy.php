<?php include('common/mobile/header.php') ?>
    

    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href=""><i class="icofont-rounded-left"></i></a>
                <h1>
                    Privacy Policy
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="info_pages">
                    <h2>Privacy Policy</h2>
                    <div class="info_pagecontent">
                      <?php foreach ($Privacy as $key => $item) { ?>
                            <h3 class="default-heading"><?=$item['title']?> </h3>
                            <p><?=$item['description']?></p>
                        </div>
                      <?php } ?>
               </section>
            </div>
 
            <?php include('common/mobile/footer.php'); ?>
            <?php include('common/mobile/menu.php'); ?>

        </div>
    </div>
    




    <?php include('common/mobile/footer_script.php'); ?>
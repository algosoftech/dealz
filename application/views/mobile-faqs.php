<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    FAQs
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="info_pages">
                    <h2>FAQs</h2>
                    <div class="info_pagecontent faqp">
                        <?php if($faqs):  ?>
                            <?php foreach($faqs['faq_list'] as $key => $items): ?>
                              <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading<?=$key?>">
                                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?=$key?>" aria-expanded="false" aria-controls="flush-collapse<?=$key?>">
                                     <div class="heading">
                                        <h3><?=$items->heading;?></h3>
                                     </div>
                                  </button>
                                </h2>
                                <div id="flush-collapse<?=$key?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?=$key?>" data-bs-parent="#accordionFlushExample">
                                  <div class="accordion-body text-left">
                                    <p class="description"><?=$items->description;?></p>
                                  </div>
                                </div>
                              </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
               </section>
            </div>

<?php include('common/mobile/footer.php'); ?>
<?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
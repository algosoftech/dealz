<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Winners
                </h1>
            </section>
            <div class="inner_pagedata">
                <section class="winner_cards">
                   <?php $i=1; foreach ($winners as $key => $items) { ?>
                    <div class="winnercard">
                        <div class="winner_img">
                            
                            <?php if($items['winners_image']):  ?>
                                <img src="<?=base_url().$items['winners_image']?>" class="img-responsive" alt="winner"/>
                            <?php else: ?>
                                <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="img-responsive" alt="winner"/>
                            <?php endif ?>

                        </div>
                        <div class="winner_txt">
                            <?php 
                                $string = $items['title'];
                                // Extract the number from the string
                                $number = preg_replace('/[^0-9]/', '', $string);

                                // Convert the number to a formatted string with commas
                                $formattedNumber = number_format($number);

                                // Replace the original number in the string with the formatted number
                                $itemName = str_replace($number, $formattedNumber, $string);

                            ?>

                            <h3>Congratulations</h3>
                            <b><?=$items['name']?></b>
                            <h5>Winner of <?=$itemName;?></h5>
                            <span>
                                <?php if($items['country']): ?>
                                    Country : <?=$items['country']?><br>
                                <?php endif; ?>
                                Coupon no: <?=$items['coupon']?> <br>
                                Announced: <?=@date('d M, Y', strtotime($items['announcedDate'].$items['announcedTime']))?>
                            </span>
                        </div>
                    </div>
                    <?php $i++; } ?>
                     
                </section>
            </div>

    <?php include('common/mobile/footer.php'); ?>
    <?php include('common/mobile/menu.php'); ?>
    <?php include('common/mobile/cart.php'); ?>
     
        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
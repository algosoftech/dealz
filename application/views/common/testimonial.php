
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
<link rel="stylesheet" href="style.css">

<?php 
    $tblName        = "da_cms";
    $where['where'] = array('page_name' => 'testimonials', 'status' => 'A' ,'image'=> array('$ne' => '' ));
    $testimonials   = $this->common_model->getData('multiple',$tblName,$where ); 
?>
<?php if(!empty($testimonials)): ?>
<!-- TESTIMONIALS -->
<section class="testimonials">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="container-fluid px-3 px-sm-5 my-5 text-center">
                    <h2>Testimonials</h2>
                </div>
                <div
                id="customers-testimonials" class="owl-carousel">
                <?php foreach ($testimonials as $key => $items) : ?>
                    <!--TESTIMONIAL 1 -->
                    <div class="item">
                                <?php if(base_url($items['image'])):  ?>
                                    <img src="<?=base_url().$items['image'];?>" class="person-image" alt="<?=$items['image']?>"/>
                                <?php else: ?>
                                    <img src="<?=base_url().'assets/img/NO_IMAGE.jpg'?>" class="person-image" alt="<?=$items['image']?>" />
                                <?php endif ?>
                    </div>
                    <!--END OF TESTIMONIAL 1 -->
                <?php endforeach;  ?>

            </div>
        </div>
    </div>
</div>
</section>
<!-- END OF TESTIMONIALS -->
<script>
    jQuery(document).ready(function ($) {
        "use strict";
                // TESTIMONIALS CAROUSEL HOOK
        $('#customers-testimonials').owlCarousel({
            loop: true,
            center: true,
            items: 5,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 50000,
            dots: true,
            smartSpeed: 450,
            responsive: {
                0: {
                    items: 1,
                    autoplay: true,  // Autoplay
                    autoplayTimeout: 5000
                },
                768: {
                    items: 2,
                    autoplay: true,  // Autoplay
                    autoplayTimeout: 5000
                },
                1170: {
                    items: 3,
                    autoplay: true,  // Autoplay
                    autoplayTimeout: 5000
                }
            }
        });
    });
</script>

<?php endif; ?>

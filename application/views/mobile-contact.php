<?php include('common/mobile/header.php') ?>
<div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?=base_url('/')?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Help
                </h1>
            </section>
            <div class="inner_pagedata">
               <section class="inner_forms login_tab">
                    <div class="cardbox mb-4">
                        <form  id="form" action="<?=base_url('contact/contact_detail')?>" method="post">

                            <?php if ($this->session->flashdata('error')) : ?>
                                <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('success')) : ?>
                                <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                            <?php endif; ?>

                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" autocomplete="off">
                                    <?php if(form_error('name')): ?>
                                        <label id="name-error" class="error" for="name"><?=form_error('name');?> </label>
                                    <?php endif;  ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" autocomplete="off">
                                    <?php if(form_error('email')): ?>
                                        <label id="email-error" class="error" for="email"><?=form_error('email');?> </label>
                                    <?php endif;  ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter Mobile Number" autocomplete="off">
                                    <?php if(form_error('mobile')): ?>
                                        <label id="mobile-error" class="error" for="mobile"><?=form_error('mobile');?> </label>
                                    <?php endif;  ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" autocomplete="off">
                                    <?php if(form_error('subject')): ?>
                                        <label id="subject-error" class="error" for="subject"><?=form_error('subject');?> </label>
                                    <?php endif;  ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col p-0">
                                    <textarea  id="message" class="form-control" placeholder="Message" rows="5"></textarea>
                                    <?php if(form_error('message')): ?>
                                        <label id="message-error" class="error" for="message"><?=form_error('message');?> </label>
                                    <?php endif;  ?>

                                </div>
                            </div>
                            <div class="form-group row  text-center m-0">
                                <div class="col-12 p-0">
                                    <div class="g-recaptcha" data-sitekey="<?=GOOGLE_KEY?>"></div>
                                    <input type="submit" class="login_button" id="login_button2" value="Submit">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cardbox mb-4">
                        <div class="helpchatnum">
                           <a href="tel:+971 45541927" target="_blank">
                            <img src="<?=base_url('/assets/mobile/');?>img/call.png" /> +971 45541927
                           </a>
                           <a href="https://api.whatsapp.com/send?phone=<?=$general_details[0]['whatsapp_no']?>&text=Hello, I have a question about <?=base_url('/');?>" target="_blank">
                            <img src="<?=base_url('/assets/mobile/');?>img/whatsapp.png" /> WhatsApp
                           </a>
                        </div>
                    </div>
               </section>
            </div>

            <?php include('common/mobile/footer.php'); ?>
            <?php include('common/mobile/menu.php'); ?>

        </div>
    </div>

    <?php include('common/mobile/footer_script.php'); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> 
    <script>
        function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('<?=GOOGLE_KEY?>', {action: 'submit'}).then(function(token) {
             
          });
        });
      }
    </script>
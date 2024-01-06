<?php include('common/mobile/header.php') ?>

    <div class="main_wrapper">
        <div class="mob_wrapper">
            <section class="login_sec signup_sec">
                <img src="<?=base_url('assets/');?>mobile/img/white-logo.png" class="img-responsive" />
                <h1>BUY & <span>WIN</span></h1>
                <p>We Make it affordable</p>
                <small>Enter the details below to avail this offer!</small>
                <form action="<?=base_url('verify-account')?>" method="post" id="form">
                
                <div class="login_tab">
                    <?php if ($this->session->flashdata('error')) { ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                    <?php } ?>
                    <?php if ($this->session->flashdata('success')) { ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                    <?php  } ?>
                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="text" name="otp" id="otp" value="<?php if(set_value('otp')): echo set_value('otp'); endif; ?>" class="form-control" placeholder="OTP(e.g. 502387)" autocomplete="off">
                                <?php if(form_error('otp')): ?>
                                  <label id="otp-error" class="error" for="otp"><?php echo form_error('otp'); ?></label>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 p-0">
                                <input type="submit" class="login_button" id="login_button2" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <div class="signin_botomstripe">
                Existing? <a href="<?=base_url('login');?>">Login</a>
            </div>
        </div>
    </div>

<?php include('common/mobile/footer_script.php'); ?>
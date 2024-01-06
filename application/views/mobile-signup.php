<?php include('common/mobile/header.php') ?>

    <div class="main_wrapper">
        <div class="mob_wrapper">
            <section class="login_sec signup_sec">
                <img src="<?=base_url('assets/');?>mobile/img/white-logo.png" class="img-responsive" />
                <h1>BUY & <span>WIN</span></h1>
                <p>We Make it affordable</p>
                <small>Enter the details below to avail this offer!</small>
                <form action="<?=base_url('sign-up')?>" method="post" id="form">
                
                <div class="login_tab">
                    <?php if ($this->session->flashdata('error')) { ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                    <?php } ?>
                    <?php if ($this->session->flashdata('success')) { ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                    <?php  } ?>


                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="text" name="first_name" id="first_name" value="<?php if(set_value('first_name')): echo set_value('first_name'); endif; ?>" class="form-control" placeholder="First Name" autocomplete="off">
                                <label id="first_name-error" class="error" for="first_name"><?php echo form_error('first_name'); ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="text" name="last_name" id="last_name"  value="<?php if(set_value('last_name')): echo set_value('last_name'); endif; ?>" class="form-control" placeholder="Last Name" autocomplete="off">
                                <label id="last_name-error" class="error" for="last_name"><?php echo form_error('last_name'); ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="text" name="email" id="email" value="<?php if(set_value('email')): echo set_value('email'); endif; ?>" class="form-control" placeholder="Email Address(Optional)" autocomplete="off">
                                <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3 ps-0">
                                <?php if(set_value('country_code')): $countryCode = set_value('country_code'); else: $countryCode = '+971'; endif; ?>
                                <select class="form-control" name="country_code" id="country_code">
                                    <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                        <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeKey; ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="col p-0">
                                <input type="tel" name="mobile" id="mobile" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" class="form-control" placeholder="Mobile Number" autocomplete="off">
                                <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="password" name="password" id="password" class="form-control password2" placeholder="Password" autocomplete="off" value="<?php if(set_value('password')): echo set_value('password'); endif; ?>" >
                                <i class="icofont-eye showpassword"></i>
                                <label id="password-error" class="error" for="password"><?php echo form_error('password'); ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control password2" placeholder="Confirm Password" autocomplete="off" value="<?php if(set_value('confirm_password')): echo set_value('confirm_password'); endif; ?>" >
                                <i class="icofont-eye showpassword"></i>
                                <label id="confirm_password-error" class="error" for="confirm_password"><?php echo form_error('confirm_password'); ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 p-0 text-start">
                                <label for="terms" class="terms">
                                    <input type="checkbox" name="term_condition" id="checkbox" class="termsConditions"  value="Yes" <?php if(set_value('term_condition')): echo 'checked'; endif; ?> /> I agree to <a href="<?php echo base_url('terms-condition'); ?>">Usage Terms</a> and <a href="<?php echo base_url('privacy-policy'); ?>">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                         <div class="form-group row">
                            <div class="col-12 p-0 text-start">
                                <label id="term_condition-error" class="error" for="term_condition"><?php echo form_error('term_condition'); ?></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 p-0">
                                <input type="submit" class="login_button" id="login_button2" value="Register">
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
<!-- Country code popup Ui start -->
    <?php include('common/mobile/countrycode-list.php') ?>
<!-- Country code popup Ui end -->
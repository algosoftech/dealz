<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper">
            <section class="login_sec">
                <img src="<?=base_url('assets/');?>img/white-logo.png" class="img-responsive" />
                <h1>BUY & <span>WIN</span></h1>
                <p>We Make it affordable</p>
                <div class="sign_inicon">
                    <i class="icofont-ui-user"></i>
                    Sign In
                </div>

                <?php if($this->session->flashdata('error')): ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                <?php endif; ?>
                <?php if($this->session->flashdata('success')): ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                <?php endif; ?>
                <?php if($this->session->flashdata('successA')): ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('successA')?></label>
                <?php endif; ?>

                <div class="login_tab">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#mobnumber">With Mobile Number</a>
                          </li>
                        <li class="nav-item">
                          <a class="nav-link" data-bs-toggle="tab" href="#withemailaccount">With Email Account</a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content">
                        <div class="tab-pane active" id="mobnumber">
                            <form action="<?=base_url('login')?>" method="post"  autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-3 ps-0">
                                        <?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>
                                        <select class="form-control" name="country_code" id="country_code">
                                             <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                                <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeKey; ?></option>
                                             <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                    <div class="col p-0">
                                        <input type="tel" name="mobile" id="mobile" class="form-control" placeholder="Mobile No. (e.g. 502345678)" autocomplete="off">
                                        <?php if(form_error('mobile')): ?>  
                                            <label id="mobile-error" class="error" for="mobile"><?=form_error('mobile');?></label>
                                        <?php endif; ?> 

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col p-0">
                                        <input type="password" name="password" id="password" class="form-control password2" placeholder="Password" autocomplete="off">
                                        <i class="icofont-eye showpassword"></i>
                                        <?php if(form_error('password')): ?>  
                                            <label id="password-error" class="error" for="password"><?=form_error('password');?></label>
                                        <?php endif; ?> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 p-0 text-end">
                                       <a href="<?=base_url('forgot-password')?>">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 p-0">
                                        <input type="submit" class="login_button" id="login_button2" value="Sign In">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="withemailaccount">
                            <form action="<?=base_url('login')?>" method="post"  autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-12 p-0">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email Id" autocomplete="off">
                                        <?php if(form_error('email')): ?>  
                                            <label id="email-error" class="error" for="email"><?=form_error('email');?></label>
                                        <?php endif; ?> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col p-0">
                                        <input type="password" name="password" id="password" class="form-control password2" placeholder="Password" autocomplete="off">
                                        <i class="icofont-eye showpassword"></i>
                                        <?php if(form_error('password')): ?>  
                                            <label id="password-error" class="error" for="password"><?=form_error('password');?></label>
                                        <?php endif; ?> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 p-0 text-end">
                                       <a href="<?=base_url('forgot-password')?>">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 p-0">
                                        <input type="submit" class="login_button" id="login_button2" value="Sign In">
                                    </div>
                                </div>
                            </form>
                        </div>
                      </div>
                </div>
            </section>
            <div class="signin_botomstripe">
                Don't have an account? <a href="<?=base_url('sign-up');?>">Sign Up</a>
            </div>
        </div>
    </div>
<?php include('common/mobile/footer_script.php'); ?>
<!-- Country code popup Ui start -->
    <?php include('common/mobile/countrycode-list.php') ?>
<!-- Country code popup Ui end -->
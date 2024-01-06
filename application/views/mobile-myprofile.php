<?php include('common/mobile/header.php') ?>
    <div class="main_wrapper">
        <div class="mob_wrapper inner_bodyarea">
            <section class="inner_head">
                <a href="<?= base_url('/');?>"><i class="icofont-rounded-left"></i></a>
                <h1>
                    Profile
                </h1>
            </section>
            <div class="inner_pagedata">
                <?php include('common/mobile/membership-details.php') ?>

                <?php if ($this->session->flashdata('error')) : ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('success')) : ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                <?php endif; ?>

                <?php
                    $id =  base64_encode($profileDetails['users_id']);  
                ?>

                <form class="form-inline" action="<?=base_url('edit-profile/'. manojEncript($profileDetails['users_id']));?>" method="post">
                    <div class="profile_notification">
                        <div class="notific_togglelist">
                            <span>SMS</span>
                            <label class="smallswitch">
                                <input type="checkbox" class="notification" name="sms_notification" data-type="sms_notification" id="sms" <?php if(set_value('sms_notification')): echo "checked";  elseif($profileDetails['sms_notification'] == "on"): echo "checked"; endif; ?> >
                                <div class="slider round">
                                    <span class="on">ON</span>
                                    <span class="off">OFF</span>
                                </div>
                            </label>
                        </div>
                        <div class="notific_togglelist">
                            <span>E-Mail</span>
                            <label class="smallswitch">
                                <input type="checkbox"  class="notification" name="email_notification" data-type="email_notification" id="e-mail" <?php if(set_value('email_notification')): echo "checked";  elseif($profileDetails['email_notification'] == "on"): echo "checked"; endif; ?> >
                                <div class="slider round">
                                    <span class="on">ON</span>
                                    <span class="off">OFF</span>
                                </div>
                            </label>
                        </div>
                        <div class="notific_togglelist">
                            <span>Notification</span>
                            <label class="smallswitch">
                                <input type="checkbox" class="notification" name="notification" data-type="notification" id="notification" <?php if(set_value('notification')): echo "checked";  elseif($profileDetails['notification'] == "on"): echo "checked"; endif; ?> >
                                <div class="slider round">
                                    <span class="on">ON</span>
                                    <span class="off">OFF</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="inner_forms login_tab">
                        <h3><span>Personal Details</span></h3>

                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="users_name" id="users_name" class="form-control" placeholder="First Name" autocomplete="off" value="<?php if(set_value('users_name')): echo set_value('users_name'); else: echo stripslashes($profileDetails['users_name']); endif; ?>" >
                                    <?php if(form_error('users_name')): ?>
                                      <label id="users_name-error" class="error" for="users_name"><?php echo form_error('users_name'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" autocomplete="off" value="<?php if(set_value('last_name')): echo set_value('last_name'); else: echo stripslashes($profileDetails['last_name']); endif; ?>">
                                    <?php if(form_error('last_name')): ?>
                                        <label id="last-name-error" class="error" for="last_name"><?php echo form_error('last_name'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 ps-0">
                                <?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>

                                    <select class="form-control" name="country_code" id="country_code" <?php if($profileDetails['country_code']): echo 'disabled'; endif; ?> >
                                        <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                            <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeKey; ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <div class="col p-0">
                                    <input type="text" name="users_mobile" id="users_mobile" class="form-control" placeholder="Mobile" autocomplete="off" value="<?php if(set_value('users_mobile')): echo set_value('users_mobile'); else: echo stripslashes($profileDetails['users_mobile']); endif; ?>" placeholder="Mobile Number" name="users_mobile" class=" <?php if(form_error('user_mobile')): ?>error<?php endif; ?>" <?php if($profileDetails['users_mobile']): echo 'disabled'; endif; ?> >
                                    <?php if(form_error('users_mobile')): ?>
                                      <label id="users_mobile-error" class="error" for="users_mobile"><?php echo form_error('users_mobile'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="text" name="users_email" id="users_email" class="form-control" placeholder="Email Address(Optional)" autocomplete="off" value="<?php if(set_value('users_email')): echo set_value('users_email'); else: echo stripslashes($profileDetails['users_email']); endif;?>">
                                    <?php if(form_error('users_email')): ?>
                                      <label id="users_email-error" class="error" for="users_email"><?php echo form_error('users_email'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row  text-center">
                                <div class="col-12 p-0">
                                    <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                    <input type="submit" class="login_button" id="login_button2" value="Update Profile">
                                </div>
                            </div>
                </form>

                        <h3><span>Change Password</span></h3>
                        <form method="post" class="password-form">
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="password" name="old_password" id="old_password" class="form-control password2" placeholder="Old Password" autocomplete="off" value="<?php if(set_value('old_password')): echo set_value('old_password'); endif; ?>">
                                    <i class="icofont-eye showpassword"></i>
                                    <?php if(form_error('old_password')): ?>
                                      <label id="old_password-error" class="error" for="old_password"><?php echo form_error('old_password'); ?></label>
                                    <?php elseif($oldPassError): ?>
                                      <label id="old_password-error" class="error" for="old_password"><?php echo $oldPassError; ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="password" name="new_password" id="new_password" class="form-control password2" placeholder="New Password" autocomplete="off" value="<?php if(set_value('new_password')): echo set_value('new_password'); endif; ?>">
                                    <i class="icofont-eye showpassword"></i>
                                    <?php if(form_error('new_password')): ?>
                                      <label id="new_password-error" class="error" for="new_password"><?php echo form_error('new_password'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col p-0">
                                    <input type="password" name="conf_password" id="conf_password" class="form-control password2" placeholder="Confirm Password" autocomplete="off" value="<?php if(set_value('conf_password')): echo set_value('conf_password'); endif; ?>">
                                    <i class="icofont-eye showpassword"></i>
                                    <?php if(form_error('conf_password')): ?>
                                      <label id="conf_password-error" class="error" for="conf_password"><?php echo form_error('conf_password'); ?></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 p-0 text-center">
                                    <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                    <input type="submit" class="login_button" id="login_button2" value="Update Password">
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
   
        <?php include('common/mobile/footer.php'); ?>
        <?php include('common/mobile/menu.php'); ?>
        </div>
    </div>

<?php include('common/mobile/footer_script.php'); ?>


<script type="text/javascript">
    
    $('.notification').on('click' ,function(){

        var fieldName = $(this).attr('data-type');
        var ur = '<?=base_url()?>';
        $.ajax({
            url : ur+ "my-profile/notification",
            method: "POST", 
            data: { fieldName: fieldName },
            success: function(data){
                if(data == 1 ){
                    location.reload();
                }
            }
        });
    });

</script>
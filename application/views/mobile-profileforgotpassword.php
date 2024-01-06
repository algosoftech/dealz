<?php include('common/mobile/header.php') ?>

    <div class="main_wrapper">
        <div class="mob_wrapper">
            <section class="login_sec h-auto">
                <img src="<?=base_url('assets/');?>mobile/img/white-logo.png" class="img-responsive" />
                <h1>Forgot Password</h1>
                <p><small>Enter your register mobile or email address to receive OTP</small></p>
                <div class="login_tab">

                    <?php if($this->session->flashdata('error')): ?>
                        <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('success')): ?>
                        <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('successA')): ?>
                        <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('successA')?></label>
                    <?php endif; ?>


                    <form id="login2" action="<?=base_url('forgot-password')?>" method="post" >
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
                                <input type="text" name="mobile" id="userid2" class="form-control" placeholder="Mobile No. (e.g. 502345678)" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" autocomplete="off">
                                <?php if(form_error('mobile')): ?>
                                  <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
                                <?php endif; ?>

                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <h5 class="m-1">OR</h2>
                        </div>
                        <div class="form-group row">
                            <div class="col p-0">
                                <input type="text" name="email" id="useremail" class="form-control" placeholder="Email Address" autocomplete="off">
                                <?php if(form_error('email')): ?>
                                  <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
                                <?php endif; ?>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <div class="col-12 p-0">
                                 <input type="hidden" name="formSubmit" id="formSubmit" value="yes">
                                <input type="submit" class="login_button" id="login_button2" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            
        </div>
    </div>

<?php include('common/mobile/footer.php'); ?>
<?php include('common/mobile/menu.php'); ?>
<?php include('common/mobile/footer_script.php'); ?>
<!-- Country code popup Ui start -->
    <?php include('common/mobile/countrycode-list.php') ?>
<!-- Country code popup Ui end -->
<script>
    var s = $("#sticker");
    var pos = s.position();                    
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        if (windowpos > pos.top) {
        s.addClass("stick");
        } else {
            s.removeClass("stick"); 
        }
    });


     $("#login1").validate({
        rules: {
            useremail: { 
                required: true ,
                email : true
            }
        },
        messages:{
            useremail: { 
                    required: 'Please enter your email',
                    email: 'Please enter a valid email',
                 }

        },
        submitHandler: function(form) {
            form.submit();
        }
    });
        $('#login_button2').on("click", function() {

             var userData =  $("#userid2").val();
            if(userData == ""){
                $('#error_userid2').empty().append('Please enter your mobile no.');
                return false;

            }else if(parseInt(userData.slice(0,1)) == 0){
                $('#error_userid2').empty().append('First number should not be zero.');
                return false;


            }else if($.isNumeric( userData ) == false){
                $('#error_userid2').empty().append('Mobile no. should be in number.');
                return false;

            }else if( userData.length >15 ){
                $('#error_userid2').empty().append('Enter valid mobile number');
                return false;

            }
            return true;

        });
</script>
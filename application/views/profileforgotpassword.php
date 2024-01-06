<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php include('common/head.php') ?>
    <style>
    .login_button {
    background: #e22c2d;
    padding: 9px 20px;
    border-radius: 7px;
    display: inline-block;
    color: #fff;
    font-family: 'Open Sans', sans-serif;
    font-size: 16px;
    font-weight: 500;
    border: 1px solid #e22c2d;
    width: 100%;x`
}
.login-block .account_content a {
    margin-left: 10px;
    color: #e22c2d;
    text-align: center;
    display: block;
    color: #be1a29;
    font-family: 'Open Sans', sans-serif;
    font-size: 17px;
    font-weight: 600;
}

    .login-block .login-box .forgot-link {
   text-align: end;
    display: block;
    color: #be1a29;
    font-family: 'Open Sans', sans-serif;
    font-size: 17px;
    font-weight: 500;
}
    .color-default-btn {
    background: #e22c2d;
    padding: 9px 20px;
    border-radius: 7px;
    display: inline-block;
    color: #fff;
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    font-weight: 400;
    border: 1px solid #e22c2d;
    width: 100%;
}
 .nav-tabs p {
        padding: 8px 32px;
        border-radius: 25px;
        background: #f4f3f3;
        color: #2f2f2f;
        font-family: 'Open Sans', sans-serif;
        font-size: 13px;
        font-weight: 400;
    }

.nav-tabs .active {
        padding: 8px 32px;
        border-radius: 25px;
        background: #e72d2e !important;
        color: #fff !important ;
        font-family: 'Open Sans', sans-serif;
        font-size: 13px;
        font-weight: 400;
        position: relative;
        z-index: 2;
    }


    

    .nav-tabs {
        border-bottom: 1px solid #dee2e600;
        margin: 10px 0px;
    }

    .nav-tabs li {
        margin: 0 -13px;
    }

    .error,.error_class{
        text-align: left;
        color: red;
        margin-top: 5px;
    }
    @media (min-width: 360px) and (max-width: 600px) {
        .login-block .login-box {
    background: #fff;
    padding: 20px 17px;
    border-radius: 8px;
    text-align: center;
    position: relative;
    margin: 124px 20px;
    -webkit-box-shadow: 1px 1px 5px 0px rgba(189,189,189,1);
    -moz-box-shadow: 1px 1px 5px 0px rgba(189,189,189,1);
    box-shadow: 1px 1px 5px 0px rgba(189,189,189,1);
}
     .nav-tabs li {
    margin: 0 -5px;
}
.nav-tabs .active {
    padding: 8px 18px;
    border-radius: 25px;
    background: #e72d2e !important;
    color: #fff;
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 400;
    position: relative;
    z-index: 2;
}
.nav-tabs p {
    padding: 8px 13px;
    border-radius: 25px;
    background: #f4f3f3;
    color: #2f2f2f;
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    font-weight: 400;
}
 }
 .nav-tabs {
    border-bottom: transparent !important;
}
</style>
</head>
<body>
<?php include('common/header.php') ?>
<div class="login-block">
    <div class="container">
        <div class="row">
            <div class="offset-md-7 col-md-5 col-12">
                <div class="login-box">
                    <?php if ($this->session->flashdata('error')) { ?>
                    <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                    <?php } ?>
                    <?php if ($this->session->flashdata('success')) { ?>
                    <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                    <?php  } ?>

                    <?php if ($forgoterror) { ?>
                    <label class="alert alert-success" style="width:100%;"><?=$forgoterror;?></label>
                    <?php  } ?>


                    <h4>BUY & <span>WIN</span>.</h4>
                    <ul class="nav nav-tabs justify-content-center">
                        <li><p data-toggle="tab" href="#numtab" class="active">With Mobile Number</p></li>
                        <li><p data-toggle="tab" href="#emailtab"  >With User Name</p></li>
                    </ul>

                     <div class="tab-content">
                            <div class="tab-pane fade " id="emailtab">

                                <form id="login1" action="<?=base_url('forgot-password')?>" method="post">
                                    <div class="form-group">
                                        <input type="text" name="email" id="useremail" class="form-control" placeholder="Enter Email Address">
                                        <span class="icon"><i class="fas fa-user"></i></span>
                                        <?php if(form_error('email')): ?>
                                          <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <input type="hidden" name="formSubmit" id="formSubmit" value="yes">
                                                <input type="submit"  class="login_button" value="Submit">

                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group account_content">
                                                <!--<h5><a href="<?=base_url('forgot-password')?>">Signup</a></h5>-->
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade show active" id="numtab">
                                <form id="login2" action="<?=base_url('forgot-password')?>" method="post">
                                    
                                    <div class="form-group">
                                        <?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>
                                        <select  name="country_code" id="country_code"   class="form-control select-search " >
                                            <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                                <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeValue; ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>




                                    <div class="form-group">
                                        <input type="text" name="mobile" id="userid2" class="form-control" placeholder="Mobile No. (e.g. 502345678)">
                                        <span class="icon"><i class="fas fa-user"></i></span>
                                        <div class="error_class" id="error_userid2"></div>
                                        <?php if(form_error('mobile')): ?>
                                          <label id="mobile-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <input type="hidden" name="formSubmit" id="formSubmit" value="yes">
                                                <input type="submit"  class="login_button" id="login_button2" value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group account_content">
                                                <!--<h5><a href="<?=base_url('forgot-password')?>">Signup</a></h5>-->
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('common/footer.php') ?> 
<!-- Country code popup Ui start -->
<?php include('common/mobile/countrycode-list.php') ?>

  });
</script>
<!-- Country code popup Ui end -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
<script src="<?=base_url('assets/')?>js/bootstrap.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" ></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<link href="<?=base_url('/assets/css/fSelect.css');?>" rel="stylesheet">
<script src="<?=base_url('/assets/js/fSelect.js');?>"></script> 
<script type="text/javascript">
  $(document).ready(function(){  
    $('.select-search').fSelect();
  });
</script>

<script>
    AOS.init({
      duration: 1200,
    })
</script>
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


</body>
</html>


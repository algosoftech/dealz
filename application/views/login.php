<!Doctype html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dealz Arabia | Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="icon" href="<?=base_url('assets/')?>img/logo.png" type="image/x-icon"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.1/css/all.css">
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">     
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/style.css">     
    <link rel="stylesheet" href="<?=base_url('assets/')?>css/responsive.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@icon/icofont@1.0.1-alpha.1/icofont.min.css">
</head>
<body class="pages ">
    <?php include('common/header.php') ?>
    <div class="loginess user_login " >
        <div class="container-fluid pl-0">

            <div class="row">
                <div class="col-lg-3 col-md-2 col-12">
                    <div class="side_bar"></div>

                </div>
                <div class="col-lg-4 col-md-2 col-12">
                    <img src="https://dealzarabia.com/assets/img/login-banner.png" class="banner" alt="login_banner">
                </div>
                <div class="col-lg-5 col-md-8 col-12">
                    <div class="login-box">
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
                        <?php endif; ?>
                        <?php if($this->session->flashdata('success')): ?>
                            <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
                        <?php endif; ?>
                        <?php if($this->session->flashdata('successA')): ?>
                            <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('successA')?></label>
                        <?php endif; ?>

                        <h4 style="margin-bottom:5px;">BUY &amp; <span>WIN</span></h4>
                        <p>We Make it affordable</p>
                        <ul class="nav nav-tabs justify-content-center">
                            <li><p data-toggle="tab" href="#numtab" class="active" >With Mobile Number</p></li>
                            <li><p data-toggle="tab" href="#emailtab" >With User Name</p></li>
                        </ul>

                        <div class="tab-content">

                             <div  class="tab-pane fade show active" id="numtab">
                                <form id="login2" action="<?=base_url('login')?>" method="post" autocomplete="off">
                                   
                                    <div class="form-group">
                                        <?php if(set_value('country_code')): $countryCode = set_value('country_code'); elseif(isset($profileDetails['country_code'])): $countryCode = stripslashes($profileDetails['country_code']); else: $countryCode = '+971'; endif; ?>
                                        <select  name="country_code" id="country_coded"   class="form-control select-search " >
                                            <?php if($countryCodeData): foreach($countryCodeData as $countryCodeKey=>$countryCodeValue): ?>
                                                <option value="<?php echo $countryCodeKey; ?>" <?php if($countryCode == $countryCodeKey): echo 'selected="selected"'; endif; ?>><?php echo $countryCodeValue; ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                       <span class="icon"><i class="fas fa-globe"></i></span>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No. (e.g. 502345678)">
                                        <span class="icon"><i class="fas fa-user"></i></span>
                                        <?php if(form_error('mobile')): ?>  
                                            <label id="mobile-error" class="error" for="mobile"><?=form_error('mobile');?></label>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group my-0">
                                        <input type="password" name="password" id="password2" class="form-control password2" placeholder="Password">
                                        <span class="icon"><i class="fas fa-key"></i></span>
                                         <i class="fas fa-eye showpassword"></i> 
                                        <?php if(form_error('password')): ?>  
                                            <label id="password-error" class="error" for="password"><?=form_error('password');?></label>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-12">

                                        </div>
                                        <div class="col-lg-7 col-md-7 col-12">
                                            <div class="form-group " >
                                                <a href="<?=base_url('forgot-password')?>" class="forgot-link">Forgot Password?</a>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <input type="submit" class="login_button" id="login_button2" value="Login">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group account_content">
                                                <h5><a href="<?=base_url('sign-up')?>">Signup</a></h5>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div  class="tab-pane fade " id="emailtab">
                                <form id="login1" action="<?=base_url('login')?>" method="post"  autocomplete="off">
                                    <div class="form-group">
                                        <input type="text" name="email" id="userid" class="form-control" placeholder="Email" autocomplete="off">
                                        <span class="icon"><i class="fas fa-user"></i></span>
                                        <?php if(form_error('email')): ?>  
                                            <label id="email-error" class="error" for="email"><?=form_error('email');?></label>
                                        <?php endif; ?> 
                                    </div>
                                    <div class="form-group my-0">
                                        <input type="password" name="password" class="form-control password2" placeholder="Password" autocomplete="off">
                                        <span class="icon "><i class="fas fa-key "></i></span>
                                        <i class="fas fa-eye showpassword" ></i> 
                                        <?php if(form_error('password')): ?>  
                                            <label id="password-error" class="error" for="password"><?=form_error('password');?></label>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-12">

                                        </div>
                                        <div class="col-lg-7 col-md-7 col-12">
                                            <div class="form-group " >
                                                <a href="<?=base_url('forgot-password')?>" class="forgot-link">Forgot Password?</a>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <input type="submit" class="login_button" value="Login">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group account_content">
                                                <h5><a href="<?=base_url('sign-up')?>">Signup</a></h5>
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
    <?php include('common/footer_script.php') ?>
    <!-- Country code popup Ui start
    <?php include('common/mobile/countrycode-list.php') ?>
    <!-- Country code popup Ui end -->

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
</script>
<script type="text/javascript">
    $("#login1").validate({
        rules: {
            userid: { 
                required: true ,
                email : true
            },
            password: { 
                required: true 
            },
        },
        messages:{
            userid: { 
                    required: 'Please enter your email',
                    email: 'Please enter a valid email',
                 },
            password: { required: 'Please enter password',
        }

        },
        submitHandler: function(form) {
            form.submit();
        }
    });
        $('#login_button2').on("click", function() {

             var userData =  $("#userid2").val();
             var passwordData =  $("#password2").val();
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

            }else if(passwordData == ""){
                $('#error_userid2').empty();
                $('#error_password2').empty().append('Please enter password.');
                return false;
           }else{
             $('#error_password2').empty();
           }

        });
</script>
<script>
    function reveal() {
      var reveals = document.querySelectorAll(".reveal");

      for (var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var elementTop = reveals[i].getBoundingClientRect().top;
        var elementVisible = 20;

        if (elementTop < windowHeight - elementVisible) {
          reveals[i].classList.add("active");
      } else {
          reveals[i].classList.remove("active");
      }
  }
}

window.addEventListener("scroll", reveal);
</script>

<link href="<?=base_url('/assets/css/fSelect.css');?>" rel="stylesheet">
<script src="<?=base_url('/assets/js/fSelect.js');?>"></script> 
<script type="text/javascript">
  $(document).ready(function(){  
    $('.select-search').fSelect();
  });
</script>



</body>
</html>

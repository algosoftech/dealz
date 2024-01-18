<!Doctype html>
<html lang="eng">
<head>
  <!-- Basic page needs --> 
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <?php include('common/head.php') ?>
  <style>
    .my-profile input[type="date"] {
      width: 96%;
      border: 2px solid #f1f1f1;
      border-radius: 4px;
      margin: 8px 0px;
      outline: none;
      padding: 5px 28px 5px;
      box-sizing: border-box;
      transition: 0.3s;
    }
    .deopdown {
      width: 67%;
      border: 2px solid #f1f1f1;
      border-radius: 4px;
      margin: -6px 0;
      outline: none;
      padding: 5px 3px 5px;
      box-sizing: border-box;
      transition: 0.3s;
      position: absolute;
      top: 0px;
    }
    .my-profile .users_form {
      background: #f5fcff00;
      border-radius: 8px;
      position: relative;
      margin-top: 32px;
      border: 1px solid #ebebeb;
      text-align: left;
      margin-bottom: 10px;
    }
    .my-profile .change_password {

      display: flex;
      justify-content: space-between;
      padding: 20px 0px;
    }
    .error{
      font-family: 'Open Sans', sans-serif;
      font-size: 14px;
      line-height: 22px;
      font-weight: 400;
      text-align: left;
      color: #e22c2d;
      margin-bottom: 0;
    }
    .my-profile .btn:hover{
      background: #e72d2e;
      color: #ffffff;
    }
    .my-profile .btn {
      background: #ffffff;
      border: 1px solid #e72d2e;
      padding: 4px 13px !important;
      border-radius: 8px;
      color: #e72d2e;
      font-family: 'Open Sans', sans-serif;
      font-size: 15px;
      font-weight: 400;
      margin-top: 0px;
    }      
    .my-profile .confirm_password .change_passworded {
      text-align: end!important;
      padding: 28px 28px 17px 36px;
    }
    .recharge .users_form {
      width: 100%;
    }
    .my-profile .form-inline {
      padding: 0px; 
    }
    .my-profile input[type="number"] {
      width: 100%;
      border: 2px solid #f1f1f1;
      border-radius: 4px;
      margin: 8px 0;
      outline: none;
      padding: 5px 35px 5px;
      box-sizing: border-box;
      transition: 0.3s;
    }
    tr{
      border: 1px solid #ddd;
    }
    table td {
      border: 1px solid #eee;
      border-top: 0;
      font-weight: 400;
      text-align:center;
      padding: 0.75rem;
      vertical-align: top;
      color: #6c757d;
      font-size: 14px;
      line-height: 22px;

      text-align: center;
    }
    .my-profile .inputWithIcon input::placeholder {
      color: rgb(209 209 209) !important;
      font-size: 15px;
    }
    table th {
      padding: 0.75rem;
      vertical-align: top;
      border: 1px solid #dee2e6;
      font-size: 14px;
      line-height: 22px;
      font-weight: 600;
      text-align: center;
      color: #6c757d;
    }
    .form_user {
      padding: 0px 35px;
    }
    table {
      border-collapse: collapse;
    }
    .user_list{
      padding-top: 19px; 
    }
    .show_record{
      position: absolute;
      left: -35px;
      top: -6px;
      padding: 6px 20px!important;
    }
    .advance-cash-btn{
      background: #ffffff !important;
        border: 1px solid #e72d2e !important;
        padding: 8px 13px;
        border-radius: 8px;
        color: #e72d2e;
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        font-weight: 400;
        margin-top: 0px;
    }
    .adress-edit-dropdown .dropdown-menu {
      top: 30px !important;
    }

    .percentace_active{
      background  : #e22c2d !important;
      color : #fff !important;
    }
    .hidden{
      display : none;
    }

    .advanced-cash-section {
        margin-bottom: 75px;
        margin-right: 15px;
    }



    .modal.show .modal-dialog {
        position: relative;
        top: 11%;
    }

    @media (min-width: 360px) and (max-width: 600px) {
      .show_record {
        position: absolute;
        left: 266px;
        top: -31px;
        padding: 6px 20px!important;
      }
      .user_list {
        padding-top: 19px;
        padding-bottom: 40px;
      }
      .label{
        text-align:left !important;
      }
      .my-profile .form-inline {
        padding: 0px;
        margin-bottom: 20px;
      }
    }
  </style>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!-- Boostrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
  $( function() {
    $("#fromDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
    $("#toDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
  } );
  </script> -->

</head>
<body>
  <?php include('common/header.php') ?>
  <!-- profile -->
  <div class="my-profile recharge">
    <div class="container">
      <div class="row">
        <?php include ('common/profile/menu.php') ?>
        <div class="col-md-9">
          <?php include ('common/profile/head.php') ?>
            <?php if ($this->session->flashdata('error')) { ?>
              <label class="alert alert-danger" style="width:100%;"><?=$this->session->flashdata('error')?></label>
            <?php } ?>
            <?php if ($this->session->flashdata('success')) { ?>
              <label class="alert alert-success" style="width:100%;"><?=$this->session->flashdata('success')?></label>
            <?php  } ?>
            <div class="users_form">
              <div class="profiles">
                <h4 class="information" >Due Management</h4>
              </div>

              <div class=" advanced-cash-section text-right">
                <!-- Button trigger modal -->
                <div class="adress-edit-dropdown show m-4">
                <?php if(!empty($salespersonList)): ?>
                  <form method="post">
                                <div class="row">
                      <div class="col-sm-12 col-md-5">
                        <div>
                          <label class="label">Total Recharge : </label>
                          <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                          <span class="ared" style="font-weight: bold;"> : <?=number_format($TotalRecharge , 2);?></span><br>
                        </div>
                        <div>
                          <label class="label">Today's Total Recharge : </label>
                          <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                          <span class="ared" style="font-weight: bold;"> : <?=number_format($todayTotalRecharge , 2);?></span><br>
                        </div>

                         <div>
                          <label class="label">Today's Total Sales : </label>
                          <img src="<?=base_url('/assets/AP-GREEN.png');?>" width="25px" alt="appgreen">
                          <span class="ared" style="font-weight: bold;"> : <?=number_format($todaystotalSales , 2);?></span><br>
                        </div>

                        
                      </div>
                      
                      <div class="col-sm-12 col-md-7">
                        <div class="row">
                                          <div class="col-sm-12 col-md-12">
                                           <select name="salesperson" class="salesperson form-select w-100" aria-label="Default select example">
                                            <option value=""> Select Sales Person</option>
                                           <?php foreach ($salespersonList as $key => $items):
                                             $Saleperson_fullName = $items['users_name'].' '.$items['last_name'];
                                             $Saleperson_email = $items['users_email'];
                                             $Saleperson_mobile = $items['users_mobile'];

                                              if($Saleperson_email):
                                                $optionTitle = $Saleperson_fullName .' '.' ('.$Saleperson_email.')';
                                              elseif($Saleperson_mobile):
                                                $optionTitle = $Saleperson_fullName .' '.' ('.$Saleperson_mobile.')';
                                              else:
                                                 $optionTitle = $Saleperson_fullName;
                                              endif;
                                             ?>
                                            <option value="<?=$Saleperson_mobile;?>"  <?php if($Saleperson_mobile == $salesperson): echo "selected"; endif; ?> > <?=$optionTitle;?> </option>
                                          <?php endforeach;?>
                                         </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                             <input type="text" name="fromDate" id="fromDate" autocomplete="off" value="<?=$fromDate;?>" class="form-control form-control-sm" placeholder="From Date">
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                             <input type="text" name="toDate" id="toDate" autocomplete="off" value="<?=$toDate;?>" class="form-control form-control-sm" placeholder="To Date">
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                          <button class="btn btn-primary text-left"> Search</button>
                                        </div>
                                    </div>
                      </div>
                    </div>
                                <?php if($salesperson): $DueManagement = $Salesperson_Due; endif; ?>
                  </form>
                            <?php endif; ?>

                  <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(488px, 26px, 0px);">
                    
                    <div class="container mb-2">
                      
                      <form id="currentPageForm" name="currentPageForm" action="<?=base_url('profile/advancecash')?>" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                        
                        <div class="row">
                          <div class="col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes"/>


                            <div class="col-md-12 px-0 mb-3">
                              <div class="tab-box">
                                <ul class="nav nav-tabs justify-content-center">
                                  <li><a data-toggle="tab" class="active" href="#mobiletab">Mobile</a></li>
                                  <li><a data-toggle="tab" href="#emailtab">Email</a></li>
                                </ul>
                              </div>
                            </div>

                            <div class="col-md-6 px-0">

                              <div class="tab-content">
                                <div id="mobiletab" class="tab-pane fade show active">

                                  <div class="inputWithIcon">
                                    <input type="text" autocomplete="off" name="mobile" id="mobile" value="<?php if(set_value('mobile')): echo set_value('mobile'); endif; ?>" placeholder="Mobile">
                                    <i class="fa fa-address-card" aria-hidden="true"></i>
                                    <?php if(form_error('email')): ?>
                                      <label id="email-error" class="error" for="mobile"><?php echo form_error('mobile'); ?></label>
                                    <?php elseif($emailError): ?>
                                      <label id="email-error" class="error" for="recharge_amt"><?php echo $emailError; ?></label>
                                    <?php endif; ?>
                                  </div>
                                </div>
                                <div id="emailtab" class="tab-pane fade">

                                  <div class="inputWithIcon">
                                    <input type="text" autocomplete="off" name="email" id="email" value="<?php if(set_value('email')): echo set_value('email'); endif; ?>" placeholder="Email">
                                    <i class="fa fa-address-card" aria-hidden="true"></i>
                                    <?php if(form_error('email')): ?>
                                      <label id="email-error" class="error" for="email"><?php echo form_error('email'); ?></label>
                                    <?php elseif($emailError): ?>
                                      <label id="email-error" class="error" for="recharge_amt"><?php echo $emailError; ?></label>
                                    <?php endif; ?>
                                  </div>
                                </div>
                              </div>
                            </div>  


                            <input type="number" name="advanced_amount" id="advanced_amount" class="from-control" placeholder="Advance Cash" min="1" max="<?=$item['advanced_amount']?>">
                            <input type="submit" class="advanced_amount btn btn-success" id="advanced_amount" value="submit">
                          </div>
                        </div>
                      </form>



                    </div>

                  </div>
                </div>
              </div>

              <div class="row form_user">

                <table>
                  <tr>
                    <th style="text-align:center;">S.No</th>
                    <th style="text-align:center;">User's Details</th>
                    <?php if( !empty($salesperson) && $this->session->userdata('DZL_USERSTYPE') == "Super Retailer" ||  !empty($salesperson) && $this->session->userdata('DZL_USERSTYPE') == "Super Salesperson"): ?>
                      <th style="text-align:center;">Total Sales</th>
                      <th style="text-align:center;">Available ArabianPoints</th>
                    <?php else: ?>
                      <th style="text-align:center;">Today's Sale</th>
                      <th style="text-align:center;">Total Recharges</th>
                      <th style="text-align:center;">Recharge Amount</th>
                      <th style="text-align:center;">Cash Collected Amount</th>
                      <th style="text-align:center;">Due Amount</th>
                      <th style="text-align:center;">Advance Amount</th>
                      <!-- <th style="text-align:center;">Created At</th> -->
                      <th style="text-align:center;">Action</th>
                    <?php endif; ?>
                  </tr>

                  <?php if($DueManagement): ?>
                    <?php foreach ($DueManagement as $key => $item): ?>
                      <tr>
                      <td><?php echo $key+1;  ?> </td>
                      <td>
                        <?= 'Name : ' .$item['users_name'].' ' .$item['last_name'] . "<br>";  ?> 
                        <?= 'mobile : ' .$item['country_code'].' ' .$item['users_mobile'] . "<br>";  ?> 
                        <?= 'Email : ' .$item['users_email'] . "<br>" ;  ?> 
                      </td>
                      <?php if( !empty($salesperson) && $this->session->userdata('DZL_USERSTYPE') == "Super Retailer" ||  !empty($salesperson) && $this->session->userdata('DZL_USERSTYPE') == "Super Salesperson"): ?>
                          <td><?= $item['todaySales'];  ?> </td>
                          <td><?= $item['availableArabianPoints'];  ?> </td>
                    <?php else: ?>
                      
                      <td><?= $item['todaySales'];  ?> </td>
                      <td><?= $item['count'];  ?> </td>
                      <td><?= $item['recharge_amt'];  ?> </td>
                      <td><?= $item['cash_collected'];  ?> </td>
                      <!-- <td><?= $item['due_amount'];  ?> </td> -->
                      <td><?=  ($item['advanced_amount'] >0) ?  '0': $item['due_amount'];  ?> </td>

                      <td>

                        <?php 

                          if($item['advanced_amount'] <=0 ):
                          // echo $item['advanced_amount'] ;
                            echo '0';
                          else:
                           echo (float)$item['advanced_amount']-(float)$item['due_amount']; 
                        endif;
                        ?> 
                      </td>
                       
                      <td>
                        <div class="adress-edit-dropdown">
                          <button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(488px, 26px, 0px);">
                              <a href="<?=base_url('view-due-management/'.manojEncript($item['user_id_to']))?>" class="dropdown-item active"><i class="far fa-eye" aria-hidden="true"></i> View</a>
                          </div>
                        </div>
                      </td>


                    <?php endif; ?>

                      </tr>
                    <?php endforeach ?>

                  <?php else: ?>
                    <tr><td colspan="9" > Data not found. </td></tr>
                  <?php endif; ?>
                      
                  </table>
                  <?= $this->pagination->create_links(); ?>
              </div>
            </div>
          </div>
        </div>
</div>
</div>
</div>
</div>

<?php include('common/footer.php') ?>
<?php include('common/footer_script.php') ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
  $( function() {
    $("#fromDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
    $("#toDate").datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"1970:<?php echo date('Y')?>"});
  } );
  </script>
<!-- Animation Js -->
<script>
  AOS.init({
    duration: 1200,
  })
</script>

<script>
/* TOP Menu Stick
--------------------- */
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
<!-- Main Slider Js -->





<!-- Header Dropdown -->

</body>
</html>

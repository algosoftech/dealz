  <!--More Menu List start-->
  <div class="menu_list">
    <h3>More</h3>
    <ul>
        
        <?php if($this->session->userdata('DZL_USERID')): ?>
            <li>
                <a href="<?=base_url('my-profile');?>"><i class="icofont-ui-user"></i> Profile</a>
            </li>
        <?php endif;?>

        <?php if($this->session->userdata('DZL_USERID') == "" ): ?>

            <li>
                <a href="<?=base_url('login');?>"><i class="icofont-ui-user"></i> Login</a>
            </li>
         <?php endif;?>   
            <li>
                <a href="<?=base_url('winners-list');?>"><i class="icofont-win-trophy"></i> Winner List</a>
            </li>

        <?php if($this->session->userdata('DZL_USERID')): ?>

            <li>
                <a href="<?=base_url('redeem-coupon');?>"><i class="icofont-sale-discount"></i>Redeem Arabian Points</a>
            </li>
            <li>
                <a href="<?=base_url('earning');?>"><i class="icofont-coins"></i> My Arabian Points</a>
            </li>
            <li>
                <a href="<?=base_url('top-up-recharge');?>"><i class="icofont-wallet"></i> Transfer Arabian Points</a>
            </li>
            <li>
                <a href="<?=base_url('wallet-statement');?>"><i class="icofont-wallet"></i> Wallet Statement</a>
            </li>
            <li>
                <a href="<?=base_url('order-list')?>"><i class="icofont-ui-cart"></i> My Orders</a>
            </li>

            <li>
                <a href="<?=base_url('my-coupon')?>"><i class="icofont-sale-discount"></i> Active Coupons</a>
            </li>
            <!-- <li>
                <a href="javascript:void(0);"><i class="icofont-sale-discount"></i> Quick Buy</a>
            </li> -->
            <li>
                <a href="<?=base_url('pick-up-point')?>"><i class="icofont-location-pin"></i> Pick Up Points</a>
            </li>
            <?php endif;?>
            
            <?php if($this->session->userdata('DZL_USERSTYPE') == 'Sales Person' || $this->session->userdata('DZL_USERSTYPE') == 'Freelancer' || $this->session->userdata('DZL_USERSTYPE') == 'Retailer'): ?>
            <li>
                <a href="<?=base_url('add-user')?>"><i class="icofont-users-alt-6"></i> Add User</a>
            </li>
            <?php endif ?>

            <?php if($this->session->userdata('DZL_USERID') == 100000000001983 || $this->session->userdata('DZL_USERID') == 100000000013118 ): ?>
                <li>
                    <a href="<?=base_url('quick-buy')?>"><i class="icofont-law-document"></i>Quick Orders</a>
                </li>
            <?php endif;?>
                <li>
                    <a href="<?=base_url('due-management')?>"><i class="icofont-law-document"></i> Due Management </a>
                </li>

            <li>
                <a href="<?=base_url('user-agreement');?>"><i class="icofont-law-document"></i> User Agreement</a>
            </li>
            <li>
                <a href="<?=base_url('terms-condition')?>"><i class="icofont-law-document"></i> Terms and Conditions</a>
            </li>
            <li>
                <a href="<?=base_url('faqs')?>"><i class="icofont-law-document"></i>FAQs</a>
            </li>
            <li>
                <a href="<?=base_url('privacy-policy');?>"><i class="icofont-law-document"></i> Privacy Policy</a>
            </li>
            <li>
                <a href="<?=base_url('contestrule');?>"><i class="icofont-law-document"></i> Contest Rule</a>
            </li>
            <li>
                <a href="<?=base_url('help');?>"><i class="icofont-question-circle"></i> Help Center</a>
            </li>
        <?php if($this->session->userdata('DZL_USERID')): ?>
            <li>
                <a href="<?=base_url('logout');?>"><i class="icofont-logout"></i> Logout</a>
            </li>
        <?php endif; ?>
    </ul>
    
</div>
<!--More menu List End-->
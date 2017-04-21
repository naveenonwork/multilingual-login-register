<div id="mainwrapper" class="tc_register">

	<header>
    <h2><?php echo __('frm_myaccount_title','multilingual-login-register') ; ?></h2>
    <p></p>
    <!--<p>This is my form. Fill it out. It's Awesome.</p>-->
    </header>
     <hr/>

     
    <?php $redirect = site_url();
	$logout_url=wp_logout_url( $redirect ); ?>
     <a href='<?php echo $logout_url; ?>'><?php echo __('logout_link_label','multilingual-login-register') ; ?></a>
    <div class="resetpasswordlink"></div>
     <div class="profilelink"></div>
</div>
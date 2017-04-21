<div id="mainwrapper" class="tc_register">

	<header>
    <h2><?php echo __('frm_resetpassword_title','multilingual-login-register') ; ?></h2>
    <p></p>
    <!--<p>This is my form. Fill it out. It's Awesome.</p>-->
    </header>
     <hr/>


<div id="password-reset-form" class="widecolumn">
 
    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
        
        <div class="error"></div>
     <div class="msg"></div>
       
        <input type="hidden" id="user_login"  name="rp_login" value="<?php echo esc_attr( $_REQUEST['login'] ); ?>" autocomplete="off" />
        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $_REQUEST['key'] ); ?>" />
         
        
 
        <p>
            <label for="pass1"><?php echo __('frm_resetpassword_newpassword_label','multilingual-login-register') ; ?></label>
            <input type="password" name="pass1" id="pass1"  class='valid-password' size="20" required value="" autocomplete="off" />
        </p>
        <p>
            <label for="pass2"><?php echo __('frm_resetpassword_repeatpassword_label','multilingual-login-register') ; ?></label> <span class="validate-confirm-password-error"></span>
            <input type="password" name="pass2" id="pass2"  class='validate-confirm-password' size="20" value="" required autocomplete="off" />
        </p>
         
        <p class="description"><?php echo wp_get_password_hint(); ?></p>
         
        <p class="resetpass-submit">
            <input type="submit" name="submit" id="resetpass-button"
                   class="button" value="<?php echo __('frm_resetpassword_submit_label','multilingual-login-register') ; ?>" />
        </p>
    </form>
</div>
</div>
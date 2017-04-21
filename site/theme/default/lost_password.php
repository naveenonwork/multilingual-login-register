<div id="mainwrapper" class="tc_register">

	<header>
    <h2><?php echo __('frm_lostpassword_title','multilingual-login-register') ; ?></h2>
    <p></p>
    <!--<p>This is my form. Fill it out. It's Awesome.</p>-->
    </header>
     <hr/>

    <form  action="<?php echo wp_lostpassword_url(); ?>" method="post" >
    
    	<div class="error"></div>
     <div class="msg"></div>
     <h3><?php echo __('frm_lostpassword_description','multilingual-login-register') ; ?></h3>
        <label><?php echo __('frm_lostpassword_username_email_label','multilingual-login-register') ; ?></label><span class="validate-username-error"></span>
        <input type="text" required  placeholder="<?php echo __('frm_lostpassword_username_email_label','multilingual-login-register') ; ?>"   name="user_login"> 
       
        <input type="submit" name="login_register_lost_password_submit"  value="<?php echo __('frm_lostpassword_submit_label','multilingual-login-register') ; ?>"> 
        <div class="loginlink"></div>	
    </form>
</div>
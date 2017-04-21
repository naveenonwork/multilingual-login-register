
<div id="mainwrapper" class="tc_login">
    
    
    <header>
    <h2><?php echo __('frm_login_title','multilingual-login-register') ; ?></h2>
    <p></p>
    <!--<p>This is my form. Fill it out. It's Awesome.</p>-->
    </header>
     <hr/>
     <form   method="post">
	 <div class="error"></div>
     <div class="msg"></div>
    <label><?php echo __('frm_login_username_label','multilingual-login-register') ; ?></label>
    <input   name="email" type="text" required email   type="text">
    <label><?php echo __('frm_login_password_label','multilingual-login-register') ; ?></label>
     <input type="password" required   name="password" >
      
      <input type="checkbox"  name="remember" value="1" > <label><?php echo __('frm_login_rememberme_label','multilingual-login-register') ; ?></label>
     
     <div class="redirect"></div>
     
    <input id="submit" type="submit" name="login_register_login_submit" value="<?php echo __('frm_login_submit_label','multilingual-login-register') ; ?>">
    <div class="lostpasswordlink"></div>
     <div class="registerlink"></div>	
   
    </form>
</div>

 
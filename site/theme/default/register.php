<div id="mainwrapper" class="tc_register">

	<header>
    <h2><?php echo __('frm_register_title','multilingual-login-register') ; ?></h2>
    <p></p>
    <!--<p>This is my form. Fill it out. It's Awesome.</p>-->
    </header>
     <hr/>

    <form method="post" >
    
    	<div class="error"></div>
     <div class="msg"></div>
        <label><?php echo __('frm_register_username_label','multilingual-login-register') ; ?></label><span class="validate-username-error"></span>
        <input type="text" required  placeholder="Username" class='validate-username' name="user_name"> 
       
       <label><?php echo __('frm_register_password_label','multilingual-login-register') ; ?></label> <i> <?php echo __('frm_register_password_length_requirement_label','multilingual-login-register') ; ?></i><span class="validate-password-length-error "></span>
        <input type="password" required   class='valid-password' auto name="password" placeholder="********"> 
      <label><?php echo __('frm_register_confirmpassword_label','multilingual-login-register') ; ?></label><span class="validate-confirm-password-error"></span>
        <input type="password" required name="confirm_password"   class='validate-confirm-password' placeholder="********">
        <label><?php echo __('frm_register_email_label','multilingual-login-register') ; ?></label><span class="validate-email-error"></span>
        <input type="text" required email  placeholder="<?php echo __('frm_register_email_label','multilingual-login-register') ; ?>" class='validate-email' name="email">  
         <label><?php echo __('frm_register_firstname_label','multilingual-login-register') ; ?></label>
        <input type="text" required placeholder="<?php echo __('frm_register_firstname_label','multilingual-login-register') ; ?>" name="first_name"> 
       <label><?php echo __('frm_register_lastname_label','multilingual-login-register') ; ?></label>
        <input type="text" required placeholder="<?php echo __('frm_register_lastname_label','multilingual-login-register') ; ?>"  name="last_name"> 
        <div class="captcha"></div>
        <div class="redirect"></div>
        <input type="submit" name="login_register_register_submit"  value="<?php echo __('frm_register_submit_label','multilingual-login-register') ; ?>"> 
        <div class="loginlink"></div>	
    </form>
</div>

 

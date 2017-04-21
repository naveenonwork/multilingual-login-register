<?php  
global $tc_login_register_options;
$theme='default';
		if(!empty($tc_login_register_options['theme'])){
			$theme=$tc_login_register_options['theme'];
		}
		
$msgs=array(
"frm_login_title"=>"Provide your login Information",
"frm_login_username_label"=>"Username or Email address",
"frm_login_password_label"=>"Password",
"frm_login_rememberme_label"=>"Remember me",
"frm_login_submit_label"=>"Login",
"frm_login_new_user_register_link_title"=>"New user register here",
"frm_login_lost_password_link_title"=>"Lost Password ??",

"frm_register_title"=>"Register your account",
"frm_register_username_label"=>"Username",
"frm_register_password_label"=>"Password",
"frm_register_password_length_requirement_label"=>"(* Minimum 6 character is required )",
"frm_register_confirmpassword_label"=>"Confirm Password",
"frm_register_email_label"=>"Email",
"frm_register_firstname_label"=>"First Name",
"frm_register_lastname_label"=>"Last Name",
"frm_register_submit_label"=>"Register",
"frm_register_existing_user_login_link_title"=>"Existing user login here",

"frm_lostpassword_title"=>"Email",
"frm_lostpassword_username_email_label"=>"Username or Email",
"frm_lostpassword_description"=>"Enter your email address and we'll send you a link you can use to pick a new password.",
"frm_lostpassword_submit_label"=>"Submit",


"frm_resetpassword_title"=>"Pick a New Password",
"frm_resetpassword_newpassword_label"=>"New password",
"frm_resetpassword_repeatpassword_label"=>"Repeat new password",
"frm_resetpassword_submit_label"=>"Reset Password",

"frm_myaccount_title"=>"My Account",
"frm_myaccount_resetpassword_link_label"=>"Reset Password",
"frm_myaccount_profile_link_label"=>"Repeat new password",
 

"logout_link_label"=>"Logout",

"empty_username_error"=>"Username/Email field is empty",
"empty_password_error"=>"Password field is empty",

"invalid_username_error"=>"We don't have any users with this username/email address. Maybe you used a different one when signing up?",

"incorrect_password_error"=>"The password you entered wasn't quite right. <a href='[lostpasswordurl]'>Did you forget your password</a>?",

"length_of_password_error"=>"You should enter minimum 6 character",

"password_confirm_password_error"=>"Password and confirm password does not match",

"captcha_verification_failed_error"=>"Captcha is wrong.Please try again",

"username_exists_error"=>"This username is already exists!",

"email_exists_error"=>"This email already exists!",

"theme_already_exist_error"=>"A theme with same name already exists!",

"zip_file_error"=>"Please enter the zip file.",

"uploading_error"=>"There was problem in uploding file, please try again!!",

"zip_allowed_error"=>"Only the zip file is allowed!",

"theme_uploading_fail_error"=>"Theme upload failed, please try again!!",

"email_invalid_error"=>"Email is invalid,Pleace provide the correct one",

"reset_password_success"=>"Check your email for a link to reset your password",

"reset_link_expired_error"=>"This link has expired.please get new password link below",

"reset_link_invalid_error"=>"This link is invalid.please get new password link below",

"password_change_success"=>"Password has been changed succesfully .You can login now",

"username_not_available_error"=>"This username is already taken",

"username_availble_success"=>"This username is available",

"registration_success"=>"Your account has been created succesfully.You can login now",


"newuser_register_email_subject"=>"Your account has been created",

"newuser_register_email_body"=>"hello [name]<br/>Your account has been created .You can login to site now .Below are your account credentials.<br/>Username:[username]<br/>Password:[password]<br/>Email: [email]<br/><br/>Administrator",

"newuser_register_admin_email_subject"=>"New account has been created",

"newuser_register_admin_email_body"=>"Dear Administrator <br/><br/>Below are details of new user <br/>name    : [name] <br/>Username:[username]<br/>Password:[password]<br/>Email: [email]<br/><br/><br/>",
"pass_reset_email_subject"=>"Your password has been reset",

"pass_reset_email_body"=>"Hello<br/>
You asked us to reset your password for your account using the email address.
If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.
To reset your password, visit the following address:[resetlink]<br/>
<br/>
Thanks!",

);

 	
		
$translation_fields=array();
$translation_fields_path= ABSPATH ."wp-content/plugins/login-register/site/theme/$theme/__translation.php";
if(file_exists($translation_fields_path)){
	require_once($translation_fields_path);
  if ($translation_fields){
 		 if(!empty($translation_fields)){
		 
 			$msgs= $translation_fields;
		 }
   }

}

$key=$_GET['key'];
$locale=$key;
$full_key= "tc_login_register_translation_setting_$key" ;
if(isset($_POST['tc_login_register_translation_setting_save'])){

$v=stripslashes_deep($_POST[$full_key]);
 
update_option( $full_key, $v );
 
$pofile= plugin_dir_path(__FILE__)."login-register-$locale.po";
$pofile= ABSPATH . "wp-content/plugins/login-register/site/theme/$theme/language/login-register-$locale.po";


$file = fopen($pofile,"w");
$content="msgid \"\"
msgstr \"\"
\"Project-Id-Version: \\n\"
\"POT-Creation-Date: \\n\"
\"PO-Revision-Date: \\n\"
\"Last-Translator: \\n\"
\"Language-Team: \\n\"
\"MIME-Version: 1.0\\n\"
\"Content-Type: text/plain; charset=iso-8859-1\\n\"
\"Content-Transfer-Encoding: 8bit\\n\"
\"Language: en\\n\"
\"X-Generator: Poedit 1.8.11\\n\"";

//$data="";
$loop=1;
foreach($v as $key=>$value){


if(empty($value)  ){
$value=$msgs[$key];
}
$content.="

#: ../login-register.php:$loop 
msgid \"$key\"
msgstr \"$value\"";
$loop++;

}

  fwrite($file,$content);
fclose($file);

 	$mopath=   ABSPATH . "wp-content/plugins/login-register/site/theme/$theme/language";
  	$mofile= "$mopath/login-register-$locale.mo";
	if(!file_exists($mopath)){
		mkdir($mopath);
	}
 phpmo_convert( $pofile, $mofile);
?>
<div class="updated fade"><p><strong>Translation Saved</strong></p>

</div>
<?php 
}



?>

<h2>Login Register Translation Settings</h2>
<form method="post" enctype="multipart/form-data"   >
<?php 
 //settings_fields("tc_login_register_upload_section");
 

 $tc_login_register_translation_setting=get_option($full_key); 
 
 
?>




 

<?php  $sitepages=get_pages(array('post_status'=>'publish')); ?>


<div id='poststuff'>	
       <div id='post-body' class="metabox-holder columns-1"> 
    	  <div class="postbox-container">
          
           
        <div id="messagediv" class="postbox ">
                     
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span>Messages</span></h3> 
                        
                         <div class="inside" >
                       
						
						 <?php
						// wp_dropdown_languages( $args = array('name'=>'language','selected'=>'') );
						 
						//$locale= get_locale();
						// var_dump($msgs);
						// echo "<hr/>";
  //var_dump($tc_login_register_translation_setting);

foreach($msgs as $key=>$val){

if($key=="newuser_register_admin_email_body" || $key=="newuser_register_email_body" || $key=="pass_reset_email_body"  ){?>
<p><strong style="color:#0033CC"><?php echo  ucwords(str_replace("_"," ",$key)); ?></strong>
<?php if($key=="newuser_register_admin_email_body"  || $key=="newuser_register_email_body" ){echo "<i>tags : [name] [username] [password] [email]</i>";}
if($key=="pass_reset_email_body"  ){echo "<i>tags : [username] [resetlink]</i>";}?>

</p>
<?php 
$editor_id =  $key ;
$settings = array('media_buttons' => true, 'textarea_name' => "tc_login_register_translation_setting_".$locale."[".$key."]");

$content="";

	if(empty($tc_login_register_translation_setting[$full_key]) && $locale =='en_US'){
		$content= $val;
	}else{
		$content=html_entity_decode($tc_login_register_translation_setting[$key]);
	}  
	wp_editor($content, $editor_id, $settings);

}else{


?>
<p><strong style="color:#0033CC"><?php echo  ucwords(str_replace("_"," ",$key)); ?></strong></p>
<textarea placeholder="<?php  echo      $val   ;   ?>"  cols="80" name="tc_login_register_translation_setting_<?php echo $locale; ?>[<?php echo $key ;?>]"  >
<?php 

$full_key="$key" ;

if(empty($tc_login_register_translation_setting[$full_key]) && $locale =='en_US'){
echo $val;
}else{echo $tc_login_register_translation_setting[$full_key];}?></textarea>
<?php

}//else
} //for                    
   ?>    </div>
        </div>                  
      </div> 
	</div>
</div>	

<input class="button-primary" style="position:fixed;right:10px" type="submit" name="tc_login_register_translation_setting_save" value="Save"    />

</form>














<?php


$tc_login_register_options=get_option('tc_login_register_plugin_setting'); 
//register_activation_hook( __FILE__,  'tc_login_register_activated'   );
add_action('plugins_loaded', 'tc_login_register_textdomain');
function tc_login_register_textdomain() {

   global $tc_login_register_options;
		
		
	 	$theme='default';
		if(!empty($tc_login_register_options['theme'])){
			$theme=$tc_login_register_options['theme'];
		}
	load_plugin_textdomain( 'multilingual-login-register', false, dirname( plugin_basename(__FILE__) ) . "/site/theme/$theme/language/");
}

add_filter ('pre_set_site_transient_update_plugins', 'display_transient_update_plugins');
function display_transient_update_plugins ($transient)
{
    $obj = new stdClass();
    $obj->slug = 'access.php';
    $obj->new_version = '1.0';
    $obj->url = 'http://anyurl.com';
    $obj->package = 'http://anyurl.com';
    //$transient['plugin_directory/plugin_file.php'] => $obj;
    return $transient;
}


function tc_login_register_activated() {

    /* $page_definitions = array(
			'login' => array(
				'title' =>  'Sign In' ,
				'content' => '[login-register]'
			),
			'my-account' => array(
				'title' =>  'My Account' ,
				'content' => "[login-register page='user_account']"
			),
			'register' => array(
				'title' =>  'Register' ,
				'content' => "[login-register page='register']"
			),
			'lost-password' => array(
				'title' =>   'Forgot Your Password' ,
				'content' => "[login-register page='lost_password']"
			),
			'reset-password' => array(
				'title' => 'Reset Password' ,
				'content' => "[login-register page='reset_password']"
			)
		);
		foreach ( $page_definitions as $slug => $page ) {
			// Check that the page doesn't exist already
			$query = new WP_Query( 'pagename=' . $slug );
			if ( ! $query->have_posts() ) {
				// Add the page using the data from the array above
				wp_insert_post(
					array(
						'post_content'   => $page['content'],
						'post_name'      => $slug,
						'post_title'     => $page['title'],
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
					)
				);
			}
		}*/
}


function tc_form_post(){
		
    //$tc_login_register_options=get_option('tc_login_register_plugin_setting'); 
	
 		tc_login();
		tc_register();
								
	
}	//function
add_action( 'wp_loaded', 'tc_form_post' );

function tc_login() {
	global $tc_login_register_options;
	if(isset($_POST['login_register_login_submit']))
	{
	
	unset($_SESSION['error']);
	unset($_SESSION['msg']);
  
    $creds = array();
	$creds['user_login'] = $_POST['email'];
	$creds['user_password'] = $_POST['password'];
	if(isset($_POST['remember'])){
		$creds['remember'] = true;
	}else{
		$creds['remember'] = false;
	}
	//$redirect= $_POST['redirect'];
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
		if ( !is_wp_error($user) ){
			
			
		 $current_user = wp_get_current_user();
		 
		
		 $data['user_id']=$current_user->ID ;
		 $roles=$current_user->caps;
		 $role=key($roles);
		   
		 $user_role= $role;
		  
			
		
			if(!empty($_POST['redirect_to']))
			{
				$link=$_POST['redirect_to'];
			}	
			elseif(!empty($tc_login_register_options["redirect_page_".$user_role]))
			{
				$role_redirect_url=get_permalink($tc_login_register_options["redirect_page_".$user_role]);
				$link=$role_redirect_url;
			}else
			{
				$link=site_url();
			}
				
			
		}
		else{
			 $msg= $user->get_error_message();
			 $error_code =   join(',',$user->get_error_codes());
			
			switch ( $error_code ) {
				case 'empty_username':
					$msg= __( 'empty_username_error', 'multilingual-login-register' );
		 			break;
				case 'empty_password':
					$msg= __( 'empty_password_error', 'multilingual-login-register' );
		 			break;
				case 'invalid_username':
					$msg= __("invalid_username_error",'multilingual-login-register');
		 			break;
				case 'incorrect_password':
					$msg=   __("incorrect_password_error",'multilingual-login-register');
					$msg= str_replace( "[lostpasswordurl]",wp_lostpassword_url(),$msg  );
					 break;
				default:
					break;
			}
			 $current_url=  $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
				    
			if(strpos($current_url, '?') !== false){
							//it contains
			  $link=$current_url ."&error=$error_code" ;
			}else{
			  $link=$current_url ."?error=$error_code" ;
			}
			
			
			  $_SESSION['error']=$msg;
		}
		
		
	 header("Location:$link");	
	  exit;
		
	
	}
}

function tc_register() {
	global $tc_login_register_options;
	if(isset($_POST['login_register_register_submit']))
	{
		unset($_SESSION['error']);
		unset($_SESSION['msg']); 
					$is_error=false;
					$first_name=$_POST["first_name"];
					$last_name=$_POST["last_name"];
					$user_email=$_POST["email"];
					$user_name=$_POST["user_name"];
					$password=$_POST["password"];
					$confirm=$_POST["confirm_password"];
				    $current_url=  $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
				  
					
						if (strlen($password)<6) {
						 
							$msg= __('length_of_password_error','multilingual-login-register');
							$_SESSION['error']=$msg ;
							$is_error=true;
						}
						elseif($password!=$confirm){
						 
						$is_error=true;
						$msg = __('password_confirm_password_error','multilingual-login-register');
						$_SESSION['error']=$msg ;
					
						}
					 elseif ( isset($_POST['g-recaptcha-response']) 
					 && $tc_login_register_options['enable_captcha'] 
					 && !empty($tc_login_register_options['captcha_sitekey'])){
		
						//$_SESSION['error']="Captcha verification failed";
						$response=$_POST['g-recaptcha-response'];
						//$remoteip="127:0:0:1";
						$secret=$tc_login_register_options['captcha_secretkey'];
						
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				    $data="secret=$secret&response=$response";
				
				
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				
				   $response = curl_exec ($ch);
				   curl_close ($ch);
			
					 $response_obj=json_decode($response);
					  
						if($response_obj->success==false ){
							 $msg =  __('captcha_verification_failed_error','multilingual-login-register') ;
							$_SESSION['error']=$msg ; 
							 $is_error=true;
						}  
						 
					} 
					 elseif( username_exists( $user_name )){
						
						$msg=__('username_exists_error','multilingual-login-register');
						$_SESSION['error']=$msg ;
						$is_error=true;
					 }
					 
					 elseif( email_exists($user_email)  ) {
						$msg = __('email_exists_error','multilingual-login-register');
						$_SESSION['error']=$msg ;
						 $is_error=true;
						 
					} 
					
					
					
					if($is_error){
						
							if(strpos($current_url, '?') !== false){
							//it contains
								$link=$current_url ."&error=1" ;
							}else{
								 $link=$current_url ."?error=1" ;
							}
					
					}else{
						
						 $is_error=false;
						
						$user_id = wp_create_user( $user_name, $password, $user_email );
					 		update_user_meta($user_id,'first_name',$first_name);
							update_user_meta($user_id,'last_name',$last_name);
							update_user_meta($user_id,'ipaddress',$_SERVER["REMOTE_ADDR"]);
							
						//$address	get_user_meta($user_id,'address',true);
						//$email_template=$
						$subject="Your Account has been created.";
						$email_template="hello [name]<br/>Your account has been created .Below are your account credentials.<br/>Username:[username]<br/>Password:[password]<br/>Email: [email]<br/><br/>Administrator";
						$newuser_register_email_body=__('newuser_register_email_body','multilingual-login-register');
						if(!empty($newuser_register_email_body)){
							$email_template=nl2br($newuser_register_email_body);
							
						}
						
						$email_template=str_replace("[name]",$first_name .' '.$last_name,$email_template);
							$email_template=str_replace("[username]",$user_name,$email_template);
							$email_template=str_replace("[password]",$password,$email_template);
							$email_template=str_replace("[email]",$user_email,$email_template);
						
						
						$newuser_register_email_subject=__('newuser_register_email_subject','multilingual-login-register');
						if(!empty($newuser_register_email_subject)){
							$subject=$newuser_register_email_subject;
						}
						
						$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
						if(!empty($tc_login_register_options['regfrom_email'])){
							$regfrom_email=$tc_login_register_options['regfrom_email'];
							$headers .= 'From: '. $regfrom_email . "\r\n";
						}
						
								// Additional headers
							
						
						mail($user_email,$subject,$email_template,$headers);
						
						$admin_email=$tc_login_register_options['admin_email'];
						if(!empty($tc_login_register_options['admin_email'])){
						$email_template="Dear Administrator <br/><br/>Below are details of new user <br/>name    : [name] <br/>Username:[username]<br/>Password:[password]<br/>Email: [email]<br/><br/><br/>";
						$newuser_register_admin_email_body=__('newuser_register_admin_email_body','multilingual-login-register');
						
						if(!empty($newuser_register_admin_email_body)){
							$email_template=nl2br($newuser_register_admin_email_body);
							
						}	
							$email_template=str_replace("[name]",$first_name .' '.$last_name,$email_template);
							$email_template=str_replace("[username]",$user_name,$email_template);
							$email_template=str_replace("[password]",$password,$email_template);
							$email_template=str_replace("[email]",$user_email,$email_template);
						    $reg_admin_subject="New User Registered";
							$newuser_register_admin_email_subject=__('newuser_register_admin_email_subject','multilingual-login-register');
						if(!empty($newuser_register_admin_email_subject)){
							$reg_admin_subject=$newuser_register_admin_email_subject;
						}
						
						
						 mail($admin_email,$reg_admin_subject,$email_template,$headers);
						}
						
						
						if(!empty($_POST['redirect_to']))
						{
							$link=$_POST['redirect_to'];
						}
						elseif(!empty($tc_login_register_options['login_page']))
						{
							$link=get_permalink($tc_login_register_options['login_page']) ;
						}
						else
						{
							$link=site_url() ;
						}
						
						if(strpos($link, '?') !== false){
						//it contains
							$link=$link ."&success=1" ;
						}else{
							$link=$link ."?success=1" ;
						}
						
						 
						$_SESSION['msg']=__('registration_success','multilingual-login-register');  
						
					
					}
										
					
					
					
					
					
					//Add http part if missing
					
					if(strpos($link, 'http') !== false){
						//it  contains do nothing
							 
					}else{
						   $link="http://".$link ;
					}
						 
					  
					   header("location:$link");
					exit;
					
					
					
			
	}	//submit
}

function tc_login_register_register_styles() {
$theme='default';

 
 $jscss_file=plugin_dir_path(__FILE__)."site/theme/$theme/__js-css.php";
  
  if(file_exists($jscss_file))
  	include_once($jscss_file);
  
  foreach($css_files as $name=>$value)
  {
  
 	global $tc_login_register_options ;
  wp_register_style("tc-login-register-$name-style", plugins_url( "login-register/site/theme/$theme/$value" ), false, '1.0.0'); 
	wp_enqueue_style("tc-login-register-$name-style");
  
  }
  
  
  
  
	foreach($js_files as $name=>$value)
	  {
	  
	  wp_register_script("tc-login-register-$name-script", plugins_url( "login-register/site/theme/$theme/$value" ), false, '1.0.0'); 
		wp_enqueue_script("tc-login-register-$name-script");
	  
	  }
	 $tc_login_register_options=get_option('tc_login_register_plugin_setting'); 
 	 
	 
	  $enable_captcha=false;
	  if(isset($tc_login_register_options['enable_captcha'])){
	   $enable_captcha=$tc_login_register_options['enable_captcha'];
	   
	   }
	   
	   
	  if($enable_captcha){
	   wp_register_script("tc-login-register-captcha-script", "https://www.google.com/recaptcha/api.js?hl=en", false, '1.0.0'); 
		wp_enqueue_script("tc-login-register-captcha-script");
	  }
 ;

  wp_localize_script( 'ajax-script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}

add_action('wp_enqueue_scripts', 'tc_login_register_register_styles');
					
 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function tc_login_register_admin_page_init(){
	$admin_file=plugin_dir_path(__FILE__)."/admin/settings.php";
	include_once($admin_file);
		
 } 
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function tc_login_register_translations_init(){
	$file=plugin_dir_path(__FILE__)."/admin/translations_setting.php";
	include_once($file);
		
 } 
 	function  tc_login_register_admin_page(){
		add_menu_page( 'Multilingual Login Register', 'Multilingual Login Register', 'manage_options', 'multilingual-login-register', 'tc_login_register_admin_page_init','dashicons-admin-users' );
		add_submenu_page( 'multilingual-login-register', 'Translations', 'Translations',
    'manage_options', 'tc-login-register-translations','tc_login_register_translations_init');
	}
	 
	add_action('admin_menu', 'tc_login_register_admin_page');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
function tc_login_register_admin_init(){
	register_setting('tc_login_register_setting','tc_login_register_plugin_setting');
	add_settings_section("tc_login_register_upload_section", "", null, "tc-login-register");
    add_settings_field("tc_login_register_plugin_setting_theme_upload", "Upload New Theme", "tc_login_register_theme_upload_display", "tc-login-register", "tc_login_register_upload_section");  
    register_setting("tc_login_register_setting", "tc_login_register_plugin_setting_theme_upload", "tc_login_register_callback");
	

	
	/*add_settings_section("tc_theme_uploadsection", "Theme Uploader ", "tc-login-register");
	
	 add_settings_field("tc_login_register_plugin_setting_theme_upload", "Demo File", "tc_login_register_theme_upload_display", "general", "tc_theme_uploadsection"); */ 
	
	/*register_setting("tc_theme_uploadsection", "tc_login_register_plugin_setting_theme_upload",'tc_login_register_callback' );*/ 
 
}


function tc_login_register_theme_upload_display()
{
   ?>
        <input type="file" name="tc_login_register_plugin_setting_theme_upload" />
        <?php echo get_option('tc_login_register_plugin_setting_theme_upload'); ?>
   <?php
   
  global $tc_login_register_options;
		
		
		$theme='default';
		if(!empty($tc_login_register_options['theme'])){
			$theme=$tc_login_register_options['theme'];
		} 
		$msgfile=plugin_dir_path(__FILE__). "/site/theme/$theme/language/login-register-en_US.po";
		
		if(file_exists($msgfile)){
		
		 
		}



 
 
}


function tc_login_register_callback( $option ) {
	set_time_limit(0);
	
  if(!empty($_FILES["tc_login_register_plugin_setting_theme_upload"]["tmp_name"]))
  {
   
    $tmp_path=  dirname(__FILE__) ."/tmp/" ;
	if(!file_exists($tmp_path)){
		mkdir($tmp_path);
	}
	$theme_path=  dirname(__FILE__) . '/site/theme/';
	$file_name=basename( $_FILES['tc_login_register_plugin_setting_theme_upload']['name']);	
	$extensions = explode(".", $file_name);   
	$extension = end($extensions);						
	$target_file = $tmp_path . $file_name; 
	
	 $folder=str_replace(".zip","",$file_name);
	 if($extension=='zip'){
	   if(file_exists($theme_path."/".$folder)){
	   		$message=  __('theme_already_exist_error','multilingual-login-register');
		 	add_settings_error( 'tc_login_register-notices', 'tc_login_register-upload-fail', $message, 'error' );
	    }elseif( move_uploaded_file(
		$_FILES['tc_login_register_plugin_setting_theme_upload']['tmp_name'],
		 $target_file)) {
		WP_Filesystem();
			if(unzip_file( $target_file, $theme_path )){
			
			 $localepath="$theme_path/$folder/language";
			   $message=  "$localepath Success";
						 add_settings_error( 'tc_login_register-notices', 'tc_login_register-upload-fail', $message, 'error' );
			  if(file_exists($localepath)){
			
			  	foreach (glob("$localepath/*.po") as $filename) {
						
						$pofile= $filename;
						$mofile=str_replace(".po",".mo",$pofile);
						 
						  phpmo_convert( $pofile, $mofile);
					}
			  
			  }
			 
		
			 
			}else{
				$message= __('theme_uploading_fail_error','multilingual-login-register');  
		 	  add_settings_error( 'tc_login_register-notices', 'tc_login_register-upload-fail', $message, 'error' );	
			}
			 
		}else{
			$message=  __('uploading_error','multilingual-login-register');
		 	add_settings_error( 'tc_login_register-notices', 'tc_login_register-upload-fail', $message, 'error' );
		
		}
	
	}
	
	else{
	$message=__('zip_allowed_error','multilingual-login-register');
	add_settings_error( 'tc_login_register-notices', 'tc_login_register-upload-fail', $message, 'error' );
    
	//settings_errors( 'tc_login_register-notices' );

	}

  }//if empty
 
  	return $option;

}
add_action( 'admin_init', 'tc_login_register_admin_init' );
add_action( 'login_form_login',  'redirect_to_tc_login_register'   );
/**
 * Redirect the user to the custom login page instead of wp-login.php.
 */
function redirect_to_tc_login_register() {
    global $tc_login_register_options;
	if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
     
      /*  if ( is_user_logged_in() ) {
            $this->redirect_logged_in_user( $redirect_to );
            exit;
        }*/
 
        // The rest are redirected to the login page
		if(!empty($tc_login_register_options['login_page'])){
         $login_url = get_permalink($tc_login_register_options['login_page']);
		 
        if ( ! empty( $redirect_to ) ) {
            $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
        }
 
       wp_redirect( $login_url );
        exit;
		}
    }
}
add_action( 'login_form_lostpassword',    'redirect_to_tc_login_register_lostpassword'  );
 function redirect_to_tc_login_register_lostpassword() {
     
     /*   if ( is_user_logged_in() ) {
            $this->redirect_logged_in_user();
            exit;
        }*/
		
	  global $tc_login_register_options;
  	 $redirect_url = get_permalink($tc_login_register_options['lost_password_page']);
	 
	 if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
        /*if ( is_user_logged_in() ) {
             redirect_logged_in_user();
            exit;
        }*/
 
        wp_redirect( $redirect_url );
        exit;
    }
 
	 
		
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
        $errors = retrieve_password();
		//var_dump( );
        if ( is_wp_error( $errors ) ) {
            // Errors found
           	
			$_SESSION['error']=  $errors->get_error_message()   ;
            $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), $redirect_url );
        } else {
            // Email sent
            $redirect_url =  get_permalink($tc_login_register_options['lost_password_page']);
			$_SESSION['msg']=__('reset_password_success','multilingual-login-register'); 
            $redirect_url = add_query_arg( 'success', 'confirm', $redirect_url );
        }
 
         wp_redirect( $redirect_url );
        exit;
    }	
		
		
		

       
     
}

add_action( 'login_form_rp',  'redirect_to_tc_login_register_password_reset'   );
add_action( 'login_form_resetpass',  'redirect_to_tc_login_register_password_reset'   );

function redirect_to_tc_login_register_password_reset() {
 global $tc_login_register_options;
  $lost_password_page=get_permalink($tc_login_register_options['lost_password_page']);
   $resetpage=get_permalink($tc_login_register_options['reset_password_page']);
   $loginpage=get_permalink($tc_login_register_options['login_page']);
 
	if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
        // Verify key / login combo
        $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
        if ( ! $user || is_wp_error( $user ) ) {
		
		
            if ( $user && $user->get_error_code() === 'expired_key' ) {
			$_SESSION['error']=__('reset_link_expired_error','multilingual-login-register'); 
                wp_redirect(   "$lost_password_page?error=expiredkey" )  ;
            } else {
			$_SESSION['error']=__('reset_link_invalid_error','multilingual-login-register');  
          
                wp_redirect(   "$lost_password_page?error=invalidkey" ) ;
            exit;
        }
		
		
   
		   if(empty($tc_login_register_options['reset_password_page'])){
		   
		   $resetpage="wp-login.php";
		   }
 
        $redirect_url =  $resetpage;
        $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
        $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
 
        wp_redirect( $redirect_url );
        exit;
    }
	
	}
	
 
 
if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
        $rp_key = $_REQUEST['rp_key'];
        $rp_login = $_REQUEST['rp_login'];
 
        $user = check_password_reset_key( $rp_key, $rp_login );
 
        if ( ! $user || is_wp_error( $user ) ) {
            if ( $user && $user->get_error_code() === 'expired_key' ) {
				$_SESSION['error']=__('reset_link_expired','multilingual-login-register');
                wp_redirect(  "$lost_password_page?error=expiredkey"   );
            } else {
			$_SESSION['error']=__('reset_link_invalid','multilingual-login-register');
          
                wp_redirect( "$lost_password_page?error=invalidkey"   );
            }
            exit;
        }
 
        if ( isset( $_POST['pass1'] ) ) {
            if ( $_POST['pass1'] != $_POST['pass2'] ) {
                // Passwords don't match
                $redirect_url =$resetpage;
 
                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$_SESSION['error']=__('password_confirm_password_error','multilingual-login-register'); 
                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
 
                wp_redirect( $redirect_url );
                exit;
            }
 
            if ( empty( $_POST['pass1'] ) ) {
                // Password is empty
                $redirect_url = $resetpage;
 
                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
				
                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
 					$_SESSION['error']=__('empty_password','multilingual-login-register');  
                wp_redirect( $redirect_url );
                exit;
            }
 
            // Parameter checks OK, reset password
            reset_password( $user, $_POST['pass1'] );
			$_SESSION['msg']=__('password_change_success','multilingual-login-register'); 
			
            wp_redirect("$loginpage?success=changed"   );
        } else {
            echo "Invalid request.";
        }
 
        exit;
     
	 }
}

 

add_filter( 'retrieve_password_message',   'tc_login_register_replace_retrieve_password_message' , 10, 4 );

/**
 * Returns the message body for the password reset mail.
 * Called through the retrieve_password_message filter.
 *
 * @param string  $message    Default mail message.
 * @param string  $key        The activation key.
 * @param string  $user_login The username for the user.
 * @param WP_User $user_data  WP_User object.
 *
 * @return string   The mail message to send.
 */
  function tc_login_register_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
     global $tc_login_register_options;
	
	$msg=__('pass_reset_email_body','multilingual-login-register');
	// Create new message
	
	if(empty($msg)){
	$msg="Hello <br/>
You asked us to reset your password for your account [username].<br/>
If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.
To reset your password, visit the following address:[resetlink]<br/><br/><br/>

Thanks!";
	
	}else{
		$msg=nl2br($msg);
	}
    $msg  =  str_replace("[username]", $user_login,$msg);
   
    $resetpage=get_permalink($tc_login_register_options['reset_password_page']);
   if(empty($tc_login_register_options['reset_password_page'])){
  	 $resetpage="wp-login.php";
   }
	$msg  =  str_replace("[resetlink]", "$resetpage?action=rp&key=$key&login=" . rawurlencode( $user_login ),$msg);
    
 
    return $msg;
}

add_action( 'wp_footer', 'tc_login_register_ajax_action_javascript' ); // Write our JS below here

function tc_login_register_ajax_action_javascript() { 
$theme="default";
	if(!empty($tc_login_register_options['theme'])){
		$theme=$tc_login_register_options['theme'];
	}
?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
	 var ajaxurl = "<?php echo  admin_url('admin-ajax.php') ?>";
		jQuery('.validate-username').on("change",function($) {
	
	jQuery('.validate-username-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/preloader.gif" ); ?>'/>");
			var data = {
				'action': 'tc_login_register_ajax_action',
				'task':'check_user',
				'username': jQuery(this).val()
			};

		  
			jQuery.post(ajaxurl, data, function(response) {
			
				var json = jQuery.parseJSON(response );
				if(json.result=="false"){
				
					jQuery('.validate-username-error').removeClass("msg").addClass("error")
					jQuery('.validate-username-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/>" +json.msg)
				
				}else{
					jQuery('.validate-username-error').removeClass("error").addClass("msg")
					jQuery('.validate-username-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/ok.png" ); ?>'/>"+json.msg)
				
				}
			});
		});	
		
		
		
		jQuery('.validate-email').on("blur",function($) {
	
	
		if(  isValidEmailAddress(jQuery(this).val())  )
		{
		
		 
	
	jQuery('.validate-email-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/preloader.gif" ); ?>'/>");
			var data = {
				'action': 'tc_login_register_ajax_action',
				'task':'check_email',
				'email': jQuery(this).val()
			};

		/*jQuery.ajax({
		url: ajaxurl,
		data: data,
		type: 'POST',
		success: function(response) {
			var json = jQuery.parseJSON(response );
			if(json.result=="false"){
					
						jQuery('.validate-email-error').removeClass("msg").addClass("error")
						jQuery('.validate-email-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/>" +json.msg)
					
					}else{
						jQuery('.validate-email-error').removeClass("error").addClass("msg")
						jQuery('.validate-email-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/ok.png" ); ?>'/>"+json.msg)
					
					}
			}
		});*/
			jQuery.post(ajaxurl, data, function(response) {
			
				var json = jQuery.parseJSON(response );
				if(json.result=="false"){
				
					jQuery('.validate-email-error').removeClass("msg").addClass("error")
					jQuery('.validate-email-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/>" +json.msg)
				
				}else{
					jQuery('.validate-email-error').removeClass("error").addClass("msg")
					jQuery('.validate-email-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/ok.png" ); ?>'/>"+json.msg)
				
				}
			});
		
		}else{
		
		jQuery('.validate-email-error').removeClass("msg").addClass("error")
			jQuery('.validate-email-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/> <?php echo  __('email_invalid_error','multilingual-login-register'); ?>")
			
		
		
		}
		
		
		
		});
		
		jQuery('.valid-password').on("blur",function($) {
		
		
			if(jQuery(this).val().length<6){
			
			jQuery('.validate-password-length-error').removeClass("msg").addClass("error")
		jQuery('.validate-password-length-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/> <?php echo  __('length_of_password_error','multilingual-login-register');    ?>")
			}else{
			
			jQuery('.validate-password-length-error').removeClass("error").addClass("msg")
					jQuery('.validate-password-length-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/ok.png" ); ?>'/> ")
			
			}
			
			if(jQuery(".validate-confirm-password").val()!='')
			{
			
			if(jQuery(".valid-password").val()==jQuery(".validate-confirm-password").val()){
			  jQuery('.validate-confirm-password-error').removeClass("error").addClass("msg")
			  jQuery('.validate-confirm-password-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/ok.png" ); ?>'/> ")
			
			}else{
			   jQuery('.validate-confirm-password-error').removeClass("msg").addClass("error")
				jQuery('.validate-confirm-password-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/> <?php  echo __('password_confirm_password_missmatch','multilingual-login-register');  ?>")
			
			
			}
			}
		
		
		});
		
		jQuery('.validate-confirm-password').on("blur",function($) {
			
			if(jQuery(".valid-password").val()==""){
			jQuery('.validate-confirm-password-error').removeClass("msg").addClass("error")
			 jQuery('.validate-confirm-password-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/> <?php echo  __('length_of_password_error','multilingual-login-register');    ?>")
			
			}else if(jQuery(".valid-password").val()==jQuery(".validate-confirm-password").val()){
			  jQuery('.validate-confirm-password-error').removeClass("error").addClass("msg")
			  jQuery('.validate-confirm-password-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/ok.png" ); ?>'/> ")
			
			}else{
			   jQuery('.validate-confirm-password-error').removeClass("msg").addClass("error")
				jQuery('.validate-confirm-password-error').html(" <img src='<?php echo plugins_url( "login-register/site/theme/$theme/images/cancel.png" ); ?>'/> <?php echo __('password_confirm_password_missmatch','multilingual-login-register');  ?>")
			
			
			}
		
		
		});
		
		isValidEmailAddress =function(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};		
	});
	</script> <?php
}
 
add_action( 'wp_ajax_nopriv_tc_login_register_ajax_action', 'tc_login_register_ajax_action_callback' );

function tc_login_register_ajax_action_callback() {
	global $wpdb; // this is how you get access to the database
	
	  $task= $_POST['task'];
	
	if($task=='check_user'){
		  $username=$_POST['username'];
		if(username_exists( $username )){
			$response=array("result"=>"false","msg"=>__('username_not_available_error','multilingual-login-register'));  
			echo json_encode($response);
		}else{
			$response=array("result"=>"true","msg"=>__('username_availble_success','multilingual-login-register') );
			echo json_encode($response);
		}
	
	}
	if($task=='check_email'){
		$user_email=$_POST['email'];
		if(email_exists($user_email)){
			$response=array("result"=>"false","msg"=>__('email_exists_error','multilingual-login-register') );
			echo json_encode($response);
		}else{
			$response=array("result"=>"true","msg"=>"");
			echo json_encode($response);
		}
	
	}
 

	wp_die(); // this is required to terminate immediately and return a proper response
}

function tc_login_register($atts){

		global $tc_login_register_options;
		$plugin_page='';
		
		$theme='default';
		
		if(!empty($tc_login_register_options['theme'])){
			$theme=$tc_login_register_options['theme'];
		}
		 $user_data=get_current_user();
		
		if(isset($atts['page'])){
			$plugin_page=$atts['page'] ;
		};
		ob_start();
		
		 $front_file=plugin_dir_path(__FILE__)."site/theme/$theme/login.php";
		
		if(!empty($plugin_page)){
			
			  $front_file=plugin_dir_path(__FILE__)."site/theme/$theme/$plugin_page.php";
		 
		}
		 
		 include_once($front_file);
		
		 $out = ob_get_contents();
		 
		  if(isset($_REQUEST['error'])){ $error="<div class='error' >" .$_SESSION['error']."</div>"; 
		  	$out =str_replace('<div class="error"></div>', $error,$out);
		  } 
		if(isset($_REQUEST['success'])){ $msg="<div class='msg' >" .$_SESSION['msg']."</div>"; 
		  	$out =str_replace('<div class="error"></div>', $msg,$out);
		  }
		 $redirect='<input type="hidden"   name="redirect_to"  >'; 
		
		 if(isset($_REQUEST['redirect_to'])){ 
			$redirect='<input type="hidden"   name="redirect_to"  value="'.$_REQUEST['redirect_to'].'">'; 
		 
		}  
 		 $out =str_replace('<div class="redirect"></div>', $redirect,$out);
		  if($tc_login_register_options['enable_captcha'] && !empty($tc_login_register_options['captcha_sitekey'])){
		  	$captcha="<div class=\"captcha\"><div class=\"g-recaptcha\" data-sitekey=\"".$tc_login_register_options['captcha_sitekey']."\"  ></div></div>";
		  $out =str_replace('<div class="captcha"></div>', $captcha,$out);
		  }
		   if( !empty($tc_login_register_options['login_page'])){
		   	 $login_page=get_permalink($tc_login_register_options['login_page']) ;
			 $login_page='<div class="loginlink"><a href="'.$login_page.'"  title="'.__('frm_register_existing_user_login_link_title','multilingual-login-register') .'">'.__('frm_register_existing_user_login_link_title','multilingual-login-register') .'</a></div>'; 
		
		   		$out =str_replace('<div class="loginlink"></div>', $login_page,$out); 
		 	}
		if( !empty($tc_login_register_options['register_page'])){
		   	 $register_page=get_permalink($tc_login_register_options['register_page']) ;
			 $register_page='<div class="registerlink"><a href="'.$register_page.'" title="'.__('frm_login_new_user_register_link_title','multilingual-login-register') .'"></a></div>'; 
		
		   		$out =str_replace('<div class="registerlink"></div>', $register_page,$out); 
		 	}
			 if( !empty($tc_login_register_options['profile_page'])){
		   	  
			 $resetpassword_page=get_permalink($tc_login_register_options['resetpassword_page']) ;
			 $resetpassword_page='<div class="resetpasswordlink"><a href="'.$resetpassword_page.'" title="'.__('frm_myaccount_resetpassword_link_label','multilingual-login-register') .'"></a></div>'; 
		
		   		$out =str_replace('<div class="resetpasswordlink"></div>', $resetpassword_page,$out); 
                
		 	}
		 if( !empty($tc_login_register_options['profile_page'])){
		   	  
			 $profile_page=get_permalink($tc_login_register_options['profile_page']) ;
			 $register_page='<div class="profilelink"><a href="'.$profile_page.'" title="'.__('frm_myaccount_profile_link_label','multilingual-login-register') .'"></a></div>'; 
		
		   		$out =str_replace('<div class="registerlink"></div>', $profile_page,$out); 
                
		 	}
			$lostpasswordlink='<a href="'. wp_lostpassword_url(  ). '" title="'.__('frm_login_lost_password_link_title','multilingual-login-register') .'">'.__('frm_login_lost_password_link_title','multilingual-login-register') .'</a>';
			$out =str_replace('<div class="lostpasswordlink"></div>', $lostpasswordlink,$out); 
		 
				
			
		ob_clean();
		return $out;



}


 add_filter('wp_mail_from', 'tc_login_register_mail_from');
 add_filter('wp_mail_from_name', 'tc_login_register_mail_from_name');
 
function tc_login_register_mail_from($old) {
	global $tc_login_register_options;
	$return= $tc_login_register_options['regfrom_email'];
	if(empty($tc_login_register_options['regfrom_email'])){
		$url =  site_url() ;
		$return='admin@'.  parse_url($url, PHP_URL_HOST);
	}
 return $return;

  
}
function tc_login_register_mail_from_name($old) {
global $tc_login_register_options;
$return= $tc_login_register_options['regfrom_name'];
/*if(empty($tc_login_register_options['regfrom_name'])){
	$return='admin';
}*/
 return $return;
}


add_filter('retrieve_password_title', 'tc_login_register_reset_subject');
function tc_login_register_reset_subject($subject)
{
   
$passresetsubject= __('pass_reset_email_subject','multilingual-login-register');
if(!empty($passresetsubject)){
	$subject=$passresetsubject;
}
 return $subject; 
}

add_shortcode( 'multilingual-login-register', 'tc_login_register' );




   $license_result=object ;
   $x=array();
//if ( is_admin() ) {

 	 $license_manager = new Wp_License_Manager_Client(
        'multilingual-login-register',
        'Multilingual Login Register',
        'multilingual-login-register',
        'http://localhost/demo/api/license-manager/v1'
    );
	
	global $tc_login_register_options;
	
	 $license_result=$license_manager->get_license_info($tc_login_register_options);
	$x['info']=$license_result;
	
  //add_filter('pre_set_site_transient_update_plugins', 'check_update' );
 //}  
  var_dump($x);
      function check_update($transient){
	  
       
	  
	   
	    if (empty($transient->checked)) {
            return $transient;
        }
	

        $pluginData = get_plugin_data(__FILE__);
	  	$plugin_current_version = $pluginData['Version'];
		
		
	 	$plugin_remote_path = 'http://onebuckresume.com/plugin/';  
 		$plugin_slug = plugin_basename(__FILE__);  
  		$response = wp_remote_get( $plugin_remote_path);
    	if( is_array($response) ) {
        $content = $response['body']; // Remote get the file content. Now get the version number in $content.
	 
   		$data=json_decode($content);
		
		
		 $remote_version=$data->version;
	 
		 if (version_compare($plugin_current_version, $remote_version, '<')) {
				$obj = new stdClass();
				$obj->slug = $plugin_slug;
				$obj->new_version = $remote_version;
				$obj->url = $plugin_remote_path ;
				$obj->package = $plugin_remote_path;
				$transient->response[$plugin_slug] = $obj;
			}
     
		}
        return $transient;
    }
   
   


?>
















<?php  

$notices= get_settings_errors( "tc_login_register-notices"  );



if($_REQUEST['settings-updated']){?>
<div class="updated fade"><p><strong> <?php _e('options saved','tc_login_register') ?> </strong></p>

</div>
<?php } ?>



<?php


foreach($notices as $notice){

if(!empty($notice['message'])){
?>
<div class="error fade"><p><strong> <?php echo $notice['message'];  ?> </strong></p>

</div>
<?php
}

}

?>

<h2>Login Register Settings</h2>
<form method="post" enctype="multipart/form-data"  action='options.php' >
<?php 
 //settings_fields("tc_login_register_upload_section");
settings_fields('tc_login_register_setting'); 
 $tc_login_register_options=get_option('tc_login_register_plugin_setting'); 
 
 
?>




 

<?php  $sitepages=get_pages(array('post_status'=>'publish')); ?>


<div id='poststuff'>	
       <div id='post-body' class="metabox-holder columns-1"> 
    	  <div class="postbox-container">
          
                    <h2><a href='#' class="move" data-target='themediv'>Theme</a> |
          <a href='#' class="move" data-target='pagesdiv'>Pages</a> |
          <a href='#' class="move" data-target='captchadiv'>Captcha</a> |
          <a href='#' class="move" data-target='emaildiv'>Email</a> 
         
     <!--     	<a href='#' class="move" data-target='apidiv'>API</a> | 
            <a href='#' class="move" data-target='taxdiv'>Tax</a> </h2>	-->
		<div class="meta-box-sortables">
                    <div id="themediv" class="postbox ">
                     
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span>Theme</span></h3> 
                        
                         <div class="inside" >
                           <p><strong>Select Existing Theme</strong></p><?php 
								$path= ABSPATH . 'wp-content/plugins/login-register/site/theme';
								
								
								$dirs = array_filter(glob($path . '/*'), 'is_dir');
								
								 ?>
                             <select  name="tc_login_register_plugin_setting[theme]" >
                             	<option  value="default">default</option>
                                <?php 
								 foreach ( $dirs  as $dir ) { 
								 if(basename($dir)!='default')
								 {
								 ?>
								 <option <?php if(basename($dir)==$tc_login_register_options['plugin_theme']) {  echo " selected='selected' " ; }?> value="<?php echo basename($dir);  ?>"><?php echo basename($dir);  ?></option>
								 <?php
								 
								 }
								 }
								
								?>
                             </select> 
                        <?php   
						
						
						do_settings_sections("tc-login-register");   ?>
                         
                         </div>
        </div>
        			<div id="pagesdiv" class="postbox ">
                     
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span>Pages</span></h3> 
                        
                         <div class="inside" >
                           
                              <!-- <input type="file" name="tc_login_register_plugin_setting_theme_upload" />               -->
                            <p><strong>Login Page </strong></p>
                                                      
                    <select  
                    name="tc_login_register_plugin_setting[login_page]" >
                                                        <option value="">Later</option>
                                                   
                                                         <?php 	
                                                        foreach ( $sitepages as $pagg ) {
                                                        $option = '<option value="' .  $pagg->ID .'" ' ;
                                                        if( $pagg->ID==$tc_login_register_options['login_page']){
                                                            $option .=  " selected='selected'";
                                                        }
                                                        $option .=  '>';
                                                        $option .= $pagg->post_title;
                                                        $option .= '</option>';
                                                        echo $option;
                                                      }
                                                  
                                                  ?>
                                                  </select>         
                             
       <p><strong>Register Page </strong></p>
                                                      
                    <select  
                    name="tc_login_register_plugin_setting[register_page]" >
                                                        <option value="">Later</option>
                                                   
                                                         <?php 	
                                                        foreach ( $sitepages as $pagg ) {
                                                        $option = '<option value="' .  $pagg->ID .'" ' ;
                                                        if( $pagg->ID==$tc_login_register_options['register_page']){
                                                            $option .=  " selected='selected'";
                                                        }
                                                        $option .=  '>';
                                                        $option .= $pagg->post_title;
                                                        $option .= '</option>';
                                                        echo $option;
                                                      }
                                                  
                                                  ?>
                                                  </select> 
                    <p><strong>Lost Password Page </strong></p>
                                                      
                    <select  
                    name="tc_login_register_plugin_setting[lost_password_page]" >
                                                        <option value="">Later</option>
                                                   
                                                         <?php 	
                                                        foreach ( $sitepages as $pagg ) {
                                                        $option = '<option value="' .  $pagg->ID .'" ' ;
                                                        if( $pagg->ID==
														$tc_login_register_options['lost_password_page']){
                                                            $option .=  " selected='selected'";
                                                        }
                                                        $option .=  '>';
                                                        $option .= $pagg->post_title;
                                                        $option .= '</option>';
                                                        echo $option;
                                                      }
                                                  
                                                  ?>
                                                  </select> 
                     <p><strong>Reset Password Page </strong></p>
                                                      
                    <select  
                    name="tc_login_register_plugin_setting[reset_password_page]" >
                                                        <option value="">Later</option>
                                                   
                                                         <?php 	
                                                        foreach ( $sitepages as $pagg ) {
                                                        $option = '<option value="' .  $pagg->ID .'" ' ;
                                                        if( $pagg->ID==
														$tc_login_register_options['reset_password_page']){
                                                            $option .=  " selected='selected'";
                                                        }
                                                        $option .=  '>';
                                                        $option .= $pagg->post_title;
                                                        $option .= '</option>';
                                                        echo $option;
                                                      }
                                                  
                                                  ?>
                                                  </select>                                                             
                  
                  
                  <p><strong>Redirect Page After Login For Each Role </strong></p> 
                  <table>
                   <?php foreach (get_editable_roles() as $role_name => $role_info): ?>
    				<tr><td><?php echo $role_name ?></td><td><select   
                    name="tc_login_register_plugin_setting[redirect_page_<?php echo $role_name ?>]" >
                                                        <option value="">Later</option>
                                                   
                                                         <?php 	
                                                        foreach ( $sitepages as $pagg ) {
                                                        $option = '<option value="' .  $pagg->ID .'" ' ;
                                                        if( $pagg->ID==$tc_login_register_options["redirect_page_".$role_name]){
                                                            $option .=  " selected='selected'";
                                                        }
                                                        $option .=  '>';
                                                        $option .= $pagg->post_title;
                                                        $option .= '</option>';
                                                        echo $option;
                                                      }
                                                  
                                                  ?>
                                                  </select>  </td></tr>
 					 <?php endforeach; ?>
                   </table>                     
                     
                             
                                                                       
                                                            
                           <br class="clear">
                    
                    </div>
                  </div>  	
        
        </div>
        
        
        
         <div id="captchadiv" class="postbox ">
                     
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span>Google Captcha</span></h3> 
                        
                         <div class="inside" >
                         
                          <p><input type="checkbox" name="tc_login_register_plugin_setting[enable_captcha]"
                   <?php if($tc_login_register_options['enable_captcha']){?> checked="checked"  <?php } ?>
                   
                    value="1" /><strong>Enable Google captcha </strong></p>                                 
                  
                  
                   <p><strong>Google Captcha Site key</strong></p>                                        
                  <input type="text"  name="tc_login_register_plugin_setting[captcha_sitekey]" 
                                                    value="<?php echo $tc_login_register_options['captcha_sitekey'] ; ?>" class="pintop-input" size="30"  >  
                   <p><strong>Google Captcha Secret key</strong></p>                                        
                  <input type="text"  name="tc_login_register_plugin_setting[captcha_secretkey]" 
                                                    value="<?php echo $tc_login_register_options['captcha_secretkey'] ; ?>" class="pintop-input" size="30"  > 
                         </div>
        </div>
        
        <div id="emaildiv" class="postbox ">
                     
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span>Emails</span></h3> 
                        
                         <div class="inside" >
                         
                         <p><strong>From Name</strong></p>
                                                      
                                                    <input type="text" autocomplete="off" spellcheck="true"   
                                                    name="tc_login_register_plugin_setting[regfrom_name]" 
                                                    value="<?php echo $tc_login_register_options['regfrom_name'] ; ?>" class="pintop-input" size="30"  >
                                <p><strong>From Email</strong></p>
                                                      
                                                    <input type="text" autocomplete="off" spellcheck="true"   
                                                    name="tc_login_register_plugin_setting[regfrom_email]" 
                                                    value="<?php echo $tc_login_register_options['regfrom_email'] ; ?>" class="pintop-input" size="30"  > 
                                                
      <p><strong>Register Admin Email</strong></p>
                                                      
                                                    <input type="text" autocomplete="off" spellcheck="true"   
                                                    name="tc_login_register_plugin_setting[admin_email]" 
                                                    value="<?php echo $tc_login_register_options['admin_email'] ; ?>" class="pintop-input" size="30"  > 
      <p><strong>Register Email Subject</strong></p>
                                                    <input type="text" autocomplete="off" spellcheck="true"  
                                                    name="tc_login_register_plugin_setting[regsubject]" 
                                                    value="<?php echo $tc_login_register_options['regsubject'] ; ?>" class="pintop-input" size="30"  > 
                                                    
                                                                       
       
      
       <p><strong>Register Email Body</strong> <i>tags : [name] [username] [password] [email]</i></p>
                                                   <?php 
                                                      $content=html_entity_decode($tc_login_register_options['regbody']);
                                                  /*  the_editor($content, $id = 'tc_login_register_plugin_setting[regbody]', $prev_id = 'regbody_prev', $media_buttons = true, $tab_index = 8);*/ 
			$editor_id = 'regbody';
$settings = array('media_buttons' => true, 'textarea_name' => 'tc_login_register_plugin_setting[regbody]');
wp_editor($content, $editor_id, $settings);										
													
													?>   
                                                    
         <p><strong>Password Reset Email Subject</strong></p>
                                                    <input type="text" autocomplete="off" spellcheck="true"  
                                                    name="tc_login_register_plugin_setting[passresetsubject]" 
                                                    value="<?php echo $tc_login_register_options['passresetsubject'] ; ?>" class="pintop-input" size="30"  >                                             
        <p><strong>Password Reset Email Body</strong> <i>tags : [username] [resetlink]</i></p>
                                                     <?php 
                                                      $content=html_entity_decode($tc_login_register_options['passresetbody']);
                                                   
			$editor_id = 'passresetbody';
$settings = array('media_buttons' => true, 'textarea_name' => 'tc_login_register_plugin_setting[passresetbody]');
wp_editor($content, $editor_id, $settings);										
													
													?>               
                          
        </div> 
        </div>
          
           
                          
      </div> 
	</div>
</div>	

<input class="button-primary" style="position:fixed;right:10px" type="submit" name="save" value="<?php _e('Save Settings','tc_login_register') ?>"    />
<script type="text/javascript">
            jQuery(function($)
            {
               $(".inside").hide().eq(0).show();
			  
			   jQuery(".handlediv").on("click",
			  
			   			function(){
						
						$(this).parent().find(".inside").toggle("fast");
						
						}
			   );
			   
			   jQuery(".move").on("click",
			  		
					
			   			function(){
						openelem=$(this).attr("data-target")
						$(".inside").hide();
						 $("#"+openelem).find(".inside").toggle("fast",function(){
						 
									 $('html, body').animate({
											scrollTop:  $("#"+openelem).find(".inside").offset().top
										}, 2000);
						 
						 });
						 
						 
						
						}
			   );
			   
			    $(".meta-box-sortables").sortable();
			 }) 
</script>
</form>














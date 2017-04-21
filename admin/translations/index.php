<?php  

$notices= get_settings_errors( "tc_login_register-notices"  );



if(isset($_REQUEST['settings-updated'])){?>
<div class="updated fade"><p><strong><?php _e('options saved','tc_login_register') ?> </strong></p>

</div>
<?php } ?>



<?php


require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
$language_list = wp_get_available_translations();
//en_US
//var_dump($language_list);
?><table border="1">

<tr>
	<td><a href='admin.php?page=tc-login-register-translations&task=translate&key=en_US'>Default ( English U.S )</a></td>
</tr>

<?php
foreach($language_list as $key=>$val){
?>
<tr>
	<td><a href='admin.php?page=tc-login-register-translations&task=translate&key=<?php echo $key ?>'><?php echo $val['english_name'] ?> ( <?php echo $val['native_name'] ?>)</a></td>
</tr>
<?php



}
 ?>
 </table>
 
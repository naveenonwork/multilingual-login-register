<?php  

$task="";
$translationspath=plugin_dir_path(__FILE__)."translations/";
if(isset($_GET['task'])){
$task=$_GET['task'];

}else{
$task="list";
}

if($task=="list"){
	require_once($translationspath."index.php");
}

if(	$task=="translate"){
require_once($translationspath."translate.php");
}


?>
<?php
include("../../mainfile.php");
include_once ($xoopsConfig['root_path']."class/xoopsuser.php"); 
if(file_exists("language/".$xoopsConfig['language']."/main.php")){
	include("language/".$xoopsConfig['language']."/main.php");
}else{
	include("language/english/main.php");
}
?>
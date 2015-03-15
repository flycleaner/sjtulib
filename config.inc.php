<?php

/**
 * error reporting email
 * */
$contact_email = '379749641@qq.com';
ini_set('date.timezone','Asia/Shanghai');
set_time_limit(180);
/**
 * define base URI
 * */
define('BASE_URI', dirname(__FILE__));

session_start();		//start session
/**
 * Determin whether we're working on a local server
 * */
$host = substr($_SERVER['HTTP_HOST'],0,5);
if(in_array($host, array('local','127.0','192.1'))){
	$local = TRUE;
}else {
	$local = FALSE;
}
if($local){
	$debug = TRUE;
	//define('BASE_URL', 'http://localhost/SJTULibrary');
	
}else {
	$debug = FALSE;
	define('BASE_URL', 'http://xgh.lib.sjtu.edu.cn/home');
	
}



/**
 * Get BASE_URL
 * */

define('BASE_URL', GetHtmlRootPath());
function GetHtmlRootPath(){
	$rootPath = BASE_URI;
	$selfRoot = $_SERVER['PHP_SELF'];
	$rootPathArray = explode('/', $rootPath);
	$selfRootArray = explode('/', $selfRoot);
	$nRPA = count($rootPathArray);
	$nSRA = count($selfRootArray);
	
	$dir = '';
	for($i=1;$i<$nSRA;$i++){
		$k=1;
		for($j=$k;$j<$nRPA;$j++){
			if($selfRootArray[$i] == $rootPathArray[$j]){
				$dir .= '/'.$selfRootArray[$i];
				$k = $j+1;
			}
		}
	}
	$htmlRootPath = 'http://'.$_SERVER['HTTP_HOST'].$dir;
	return $htmlRootPath;
}
$html_root_path = BASE_URL;
$htmlRootPath = BASE_URL;

//Asume debug is off
if(!isset($debug)){
	$debug = FALSE;
}

#**************************#
#****ERROR MANAGEMENT *****#
function my_error_handler($e_number,$e_message,$e_file,$e_line,$e_vars){
	global $debug,$contact_email;
	
	//build the error message:
	$message = "An error occured in script '$e_file' on line '$e_line':'$e_message'";
	//append $e_vars to the message:
	$message .= print_r($e_vars, 1);
	
	if($debug){ //show the error.
		echo '<div class = "error">'. $message.'</div>';
		debug_print_backtrace();
	}else{
		//Log the error:
		error_log($message, 1, $contact_email);
		
		if(($e_number != E_NOTICE)&&($e_number <2048)){
			echo '<div class="error">A system error occured. We apologize for the inconvenience.</div>';
		}
	}
}

//use my error handler:
set_error_handler('my_error_handler');

#****ERROR MANAGEMENT *****#
#**************************#

#***********************************#
#****INCLUDE NECESSARY FILES********#
header("Content-type: text/html; charset=utf-8"); 
/**
 * Autoload classes from "classes"
 * */
function class_loader($class){
	require BASE_URI.'/classes/'.$class.'.php';
}
spl_autoload_register('class_loader');

require_once 'function.php';
require_once 'plugins/htmlpurifier/HTMLPurifier.auto.php';

require_once 'inc/safeFilter.php';
#****INCLUDE NECESSARY FILES********#
#***********************************#

/**
 * mysql connection
 * */
if($local){
	$conn = new UniversalConnect();
}else{
	$conn = new UniversalConnect('xgh.lib.sjtu.edu.cn','SJTULibrary','root','adm123321');
}


//define userId


if(isset($_COOKIE['userId']) && isset($_COOKIE['status'])){
	//使用了加密模块
	//$md5Key = decrypt($_COOKIE['status']);			
	//$userId = decrypt($_COOKIE['userId'],$md5Key);	
	//未使用加密模块
	$userId = $_COOKIE['userId'];
}else if(isset($_SESSION['userId'])) {
	//$userId = decrypt($_SESSION['userId']);
	$userId = $_SESSION['userId'];
}else {
	$userId = null;
}

if(!User::isUserExist($userId)){
	$userId = null;
}

$pageTitle = "上海交通大学图书馆学生管理委员会";
//htmlpurifier @defend xss attack. filter html text
//usage: $clean_html = $purifier->purify($dirty_html);
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
?>
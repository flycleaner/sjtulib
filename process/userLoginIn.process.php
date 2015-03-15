<?php
//服务器上无法使用 mcrypt ，无法使用 encrypt 和 decrypt ,取消 cookie功能
require_once '../config.inc.php';
if(isset($_POST)){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$remember = $_POST['remember'];
	
	//echo $remember;
	$password = myMd5($password);
	$sql = sprintf("select * from SJTULib_user where (username = '%s' or sno = '%s' or email = '%s' or tel = '%s' ) and password = '%s'",
			mysql_string($username),mysql_string($username),mysql_string($username),mysql_string($username),
			mysql_string($password));
	$res = UniversalConnect::doSql($sql);
	if($res){
	//	$_SESSION['userId'] = encrypt($res[0]['id']);	//解密使用 decrypt($_SESSION['userId']）
		/*if($remember == '1'){
			$key = rand(100000,999999);		//随机生成一个key
			$md5key = myMd5($key);	//使用md5加密
			$enKey = encrypt($md5key);	//可逆加密
			$userId = encrypt($res[0]['id'],$md5key);	//使用$md5Key 加密。解密时先解密 $endKey, 然后 用$endKey解密&userId
			setcookie('userId',$userId,time()+3600*24*7,'/');	//keep 7 days
			setcookie('status',$enKey,time()+3600*24*7,'/');	//keep 7 days
		}*/
		$_SESSION['userId'] = $res[0]['id'];
		echo 'success';
	}else{
		echo 'failed';
	}
}
?>
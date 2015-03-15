<?php
require_once '../config.inc.php';
if($userId == null){
	header('location:../index.php');
	exit();
}
if(!isset($_POST['changePassword_oldPwd']) || !isset($_POST['qgzxManagement_newPwd']) || !isset($_POST['qgzxManagement_confirmNewPwd'])){
	echo '<script>alert("参数错误")</script>';
	exit();
}
$old = mysql_string($_POST['changePassword_oldPwd']);
$old_md5 = myMd5($old);
$new = mysql_string($_POST['qgzxManagement_newPwd']);
$newConfirm = mysql_string($_POST['qgzxManagement_confirmNewPwd']);
$sql = "select * from SJTULib_user where id='$userId' and password='$old_md5'";
if(!UniversalConnect::doSql($sql)){
	echo '<script>alert("修改失败;原密码错误");history.go(-1)</script>';
	exit();
}
if($new != $newConfirm){
	echo '<script>alert("修改失败;新密码不匹配");history.go(-1)</script>';
	exit();
}
if(strlen($new) <= 6){
	echo '<script>alert("修改失败;新密码至少为8个字符");history.go(-1)</script>';
	exit();
}

$new_md5 = myMd5($new);
$sql = "update SJTULib_user set password='$new_md5' where id='$userId'";
if(UniversalConnect::doSql($sql)){
	echo '<script>alert("修改成功;请重新登录!");window.location.href="userLogOut.process.php"</script>';
}else{
	echo '<script>alert("修改失败;未知错误，请重试");history.go(-1)</script>';
	exit();
}
?>
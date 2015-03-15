<?php
require_once '../config.inc.php';
if(isset($_POST['activityId'])){
	$activityId = $_POST['activityId'];
}

if(isset($_POST['userId'])){
	$userId = $_POST['userId'];
}else if(isset($_POST['name']) && isset($_POST['sno']) && isset($_POST['tel'])){
	$name = $_POST['name'];
	$sno = $_POST['sno'];
	$tel = $_POST['tel'];
	if($name == ''||$sno==''||$tel==''){
		echo '报名失败！姓名/学号/手机号 不能为空！';
		exit();
	}
	
	$sql = sprintf("select * from SJTULib_user where name='%s' and sno='%s' and tel='%s'",
			mysql_string($name),mysql_string($sno),mysql_string($tel));
	$checkArray = UniversalConnect::doSql($sql);
	if($checkArray){
		$userId = $checkArray[0]['id'];
	} else{
		$sql = sprintf("insert into SJTULib_user(sno,name,tel,username,password) values('%s','%s','%s','%s','%s')",
				mysql_string($sno),mysql_string($name),mysql_string($tel),
				mysql_string($sno),myMd5(mysql_string($sno)));
		$res = UniversalConnect::doSql($sql);
		$sql = sprintf("select * from SJTULib_user where name='%s' and sno='%s' and tel='%s'",
				mysql_string($name),mysql_string($sno),mysql_string($tel));
		$userArray = UniversalConnect::doSql($sql);
		$userId = $userArray[0]['id'];
	}
} else{
	exit();
}


$sql = "select * from SJTULib_registration where userId='$userId' and activityId='$activityId'";
$IsRegist = UniversalConnect::doSql($sql);
if(!$IsRegist){
	$sql = sprintf("insert into SJTULib_registration(activityId,userId) values('%s','%s')",
			mysql_string($activityId),mysql_string($userId));
	$res = UniversalConnect::doSql($sql);
	if($res){
		echo '报名成功！';
	}else{
		echo 'sorry,请重试！';
	}
} else {
	echo '您已报名';
}

?>
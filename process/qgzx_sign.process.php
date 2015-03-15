<?php
require_once '../config.inc.php';
if(!isset($_POST['signCode']) || !isset($_POST['sign'])|| $userId == null){
	exit();
}
$code = mysql_string($_POST['signCode']);
$time = time();
$date = date('Y-m-d');
$user = qgzx_User::setQgzx_user($userId);
//flag1	: code 正确
$flag1 = 0; 	
$sql = "select * from SJTULib_qgzx_dailyCodes where code='$code' and isUsed != 1";
$res = UniversalConnect::doSql($sql);
if($res){
	$flag1 = 1;		//如果正确设为1
	$sql = "update SJTULib_qgzx_dailyCodes set isUsed=1 where code='$code'";
	$res = UniversalConnect::doSql($sql);
}
//flag2 : ip 允许	$realip == '202.120.57.120'|| $realip == '202.121.183.37'
$flag2 = 0;			//如果正确设为1
$realIp = real_ip();
if($realIp=='202.120.57.120'|| $realIp == '202.121.183.37'){
	$flag2 = 1;
}

if($flag1 || $flag2){
	$job = $user->isOnduty($time);	//这个时候是他值班
	if(!$job){
		echo '签到失败！值班时间未到，仅能在值班时间开始30分钟前后签到！';
	}else{
		$result = $user->isAlreadyOnWork($job);	//是否已经签到
		if(!$result){	
			$sql = sprintf("insert into SJTULib_qgzx_signed(jobId,userId,date) values(%d,%d,'%s')",$job->getId(),$userId,$date);
			$res = UniversalConnect::doSql($sql);
			if($res){		
				echo '签到成功';
			}else{
				echo '签到失败，请重试或者联系管理员';
			}
		}else{
			echo '您已签到！';
		}
		
	}
}else{
	echo '验证码错误或者到指定地点签到!';
	exit();
}
?>
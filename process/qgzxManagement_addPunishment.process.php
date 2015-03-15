<?php
require_once '../config.inc.php';
/**
 * @should be included by every private page !important
 * */
$user = User::setUser($userId);
if(!$user->isAdmin(4)){
	echo '<div>对不起，您不是勤助管理员</div>';
	echo '<script>history.go(-1)</script>';
	exit();
}

if(!isset($_POST['qgzxManagement_scoreSno'])||!isset($_POST['qgzxManagement_scores']) || !isset($_POST['qgzxManagement_scoreReason'])||!isset($_POST['qgzxManagement_scoreTime'])){
	echo '<script>alert("参数错误，添加失败<-1>");history.go(-1)</script>';
	exit();
}
$sno = mysql_string($_POST['qgzxManagement_scoreSno']);
$scores = mysql_string($_POST['qgzxManagement_scores']);
$reasons = mysql_string($_POST['qgzxManagement_scoreReason']);
$datetime = mysql_string($_POST['qgzxManagement_scoreTime']);
//检查学号/姓名/手机号
$sql = "select * from SJTULib_user where sno='$sno' or name='$sno' or tel='$sno'";
$res =UniversalConnect::doSql($sql);
if(!$res){
	echo '<script>alert("添加失败;用户不是馆员<0>");history.go(-1)</script>';
	exit();
}
if(count($res) > 1){
	echo '<script>alert("添加失败;发现姓名重复用户，请输入学号<1>");history.go(-1)</script>';
	exit();
}
$TheUserId = $res[0]['id'];
if(!in_array(4, User::userDepartmentId($TheUserId))){
	echo '<script>alert("添加失败;用户不是馆员<2>");history.go(-1)</script>';
	exit();
}
//检查扣除分
if(!is_numeric($scores)){
	echo '<script>alert("添加失败;扣除分不为数字<3>");history.go(-1)</script>';
	exit();
}
$scores = intval($scores);
//检查日期时间
$datePreg = '/^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9]):(0?[0-9]|[1-5][0-9]))$/';
$match = preg_match($datePreg, $datetime);
if(!$match){
	echo '<script>alert("添加失败;时间格式错误<4>");history.go(-1)</script>';
	exit();
		}
//插入数据表
$sql = "insert into SJTULib_qgzx_scores(userId,scores,datetime,reason) values('$TheUserId','$scores','$datetime','$reasons') ";
$res = UniversalConnect::doSql($sql);
if($res){
	echo '<script>alert("添加成功!")</script>';
	header('location:../index.php?p=userCenter&oper=qgzxManagement');
	exit();
}else{
	echo '<script>alert("添加失败;请重试<5>");history.go(-1)</script>';
	exit();
}
?>
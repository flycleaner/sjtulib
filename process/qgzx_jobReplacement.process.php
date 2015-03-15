<?php
require_once '../config.inc.php';
if($userId == null){
	exit();
}
$user = qgzx_User::setQgzx_user($userId);
if(!isset($_POST['oper'])){
	exit();
}

switch ($_POST['oper']){
	case 'confirm':
		if(!isset($_POST['id'])){
			exit();
		}
		$id = intval($_POST['id']);
		$date = date('Y-m-d H:i:s');
		$sql = sprintf("update SJTULib_qgzx_jobReplacement set confirm=1,dateConfirmed='%s' where id=%d and userId2=%d and isDelete != 1",$date,$id,$userId);
		$res = UniversalConnect::doSql($sql);
		if($res){
			echo '确认成功！别忘记值班哦';
		}else{
			echo '确认失败!请重试';
		}
		break;
		
	case 'delete':
		if(!isset($_POST['id'])){
			exit();
		}
		$id = intval($_POST['id']);
		$sql = sprintf("update SJTULib_qgzx_jobReplacement set isDelete=1 where id=%d and userId1=%d and isDelete != 1",$id,$userId);
		$res = UniversalConnect::doSql($sql);
		if($res){
			echo '删除成功';
		}else{
			echo '删除失败!请重试';
		}
		break;
		
	case 'add':
if(!isset($_POST['datetime']) || !isset($_POST['sno'])){
	exit();
}

$datetime = mysql_string($_POST['datetime']);
$sno = mysql_string($_POST['sno']);
$time = strtotime($datetime);
$now = time();
//检查时间输入是否有误
$patten = "/^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9]):(0?[0-9]|[1-5][0-9]))$/";	//匹配时间日期
$match = preg_match($patten, $datetime);
if(!$match){
	echo '添加失败！时间格式错误！慎重使用360产品';	//时间格式错误，匹配失败
	exit();
}	else if($time < $now){
			echo '时间小于当前时间！';		//时间小于当前时间
			exit();
}

//检查班次
$job = $user->isOnduty($time);	//时间务必在开始时间30分钟前后
if($job){
	if(!$user->isAlreadyOnWork($job)){
		$jobId = $job->getId();		
		$sql = "select * from SJTULib_user where sno='$sno'";
		$res = UniversalConnect::doSql($sql);
		if(!$res){								//检查替班是否馆员
			echo '添加失败！替班不是馆员';		
		}else{
			$user2 = new qgzx_User($res[0]);	//替班
			if(!in_array(4, User::userDepartmentId($user2->getId()))){	//不是勤工助学部成员
				echo '添加失败！替班不是馆员';
			}else{
				$sql = sprintf("select * from SJTULib_qgzx_jobReplacement where jobId=%d and to_days(date)=to_days('%s') and userId1=%d",
						$jobId,$datetime,$userId);
				$res = UniversalConnect::doSql($sql);
				if($res){
					echo '添加失败！您已添加替班';
					exit();
				}
				$sql = sprintf("select * from SJTULib_qgzx_JobReplacement where jobId=%d and to_days(date)=to_days('%s') and userId2=%d and isDelete != 1",
						$jobId,$datetime,$userId);		//检查该班次是否是替班
				$res = UniversalConnect::doSql($sql);
				if($res){
					$sql = sprintf("update SJTULib_qgzx_JobReplacement set isDelete=1 where jobId=%d and to_days(date)=to_days('%s') and userId2=%d and isDelete != 1",
						$jobId,$datetime,$userId);	
					$res = UniversalConnect::doSql($sql);
					$sql = sprintf("insert into SJTULib_qgzx_scores(userId,scores,datetime,reason) values(%d,%d,'%s','%s')",
						$userId,10,$datetime,'将替班班次转让');
					$res = UniversalConnect::doSql($sql);
				}
				
				$sql = sprintf("insert into SJTULib_qgzx_jobReplacement(jobId,date,userId1,userId2) values(%d,'%s',%d,%d)",
						$jobId,$datetime,$userId,$user2->getId());
				$res = UniversalConnect::doSql($sql);
				if($res){
					echo '添加成功！请通知替班确认！';
				}
			}
		}
	} else{
		echo '您已签到！无法再找替班!';
	}
}else{
	echo '登记失败！该时间段内您无班次';
}
break;
}
?>
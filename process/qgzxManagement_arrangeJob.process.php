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

if(!isset($_POST['sig'])){
	exit();
}

switch ($_POST['sig']){
	case 'user':
		$name = mysql_string($_POST['name']);
		$sql="select * from SJTULib_user where name='$name'";
		$arr = UniversalConnect::doSql($sql);
		echo $arr[0]['name'].'&'.$arr[0]['sno'].'&'.$arr[0]['tel'];
		break;
	case 'job':
		$placeId = intval($_POST['placeId']);
		$timeArea = mysql_string($_POST['timeArea']);
		$timeArray = explode('&', $timeArea);
		$begintime=$timeArray[0];
		$hours = $timeArray[1];
		$day = intval($_POST['day']);
		
		$jobIdRange = intval($_POST['jobIdRange']);
		$inputText = mysql_string($_POST['inputText']);
		
		$sql_job_id = "select * from SJTULib_qgzx_job where placeId = '$placeId' and begintime='$begintime' and hours='$hours'";
		$arr_job_id = UniversalConnect::doSql($sql_job_id);
		$job_id = $arr_job_id[0]['id'];
		
		if($inputText == ''){//如果输入为空，删除此次排班；
			$sql ="delete from SJTULib_qgzx_jobArrangement where jobId='$job_id' and day='$day' and JobIdRange='$jobIdRange'";
			$res = UniversalConnect::doSql($sql);
			exit();
		}
		$sql_if_sno = "select * from SJTULib_user where sno = '$inputText'";
		$sql_if_name = "select * from SJTULib_user where name = '$inputText'";
		$sql_if_tel = "select * from SJTULib_user where tel = '$inputText'";
		$arr_sno =  UniversalConnect::doSql($sql_if_sno);
		$arr_name =  UniversalConnect::doSql($sql_if_name);
		$arr_tel =  UniversalConnect::doSql($sql_if_tel);
		
		if(!$arr_name){
			if(!$arr_sno){
				if(!$arr_tel){
					echo "1&";
					echo "请检查 姓名/工号/手机号 是否错误";
					$flag = 0;
				} else {
					$flag = 1;
					$userId = $arr_tel[0]['id'];
					echo $arr_tel[0]['name'];
				}
			} else {
				$flag = 1;
				$userId = $arr_sno[0]['id'];
				echo $arr_sno[0]['name'];
			}
		} else {
			if(count($arr_name) >= 2) {
				$flag = 0;
				echo "1&";
				echo "有重名工作人员，请输入学号号";
			} else {
				$flag = 1;
				$userId = $arr_name[0]['id'];
				echo $arr_name[0]['name'];
			}
		}
		
		if($flag == 1) {
			$sql_check = "select * from SJTULib_qgzx_jobArrangement where jobId = '$job_id' and day = '$day' and jobIdRange='$jobIdRange'";
			$arr_check =  UniversalConnect::doSql($sql_check);
			if(!$arr_check) {
				$sql_update = "insert into SJTULib_qgzx_jobArrangement(jobId, day, userId, jobIdRange) values('$job_id','$day','$userId','$jobIdRange')";
			} else {
				$sql_update = "update SJTULib_qgzx_jobArrangement set userId = '$userId' where jobId = '$job_id' and day = '$day' and jobIdRange='$jobIdRange'";
			}
		
			$do = UniversalConnect::doSql($sql_update);
			if(!$do) {
				echo "1&";
				echo '失败！';
			}
		}
		break;
	default:;
}

?>
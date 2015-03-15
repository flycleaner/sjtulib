<?php
class qgzx_User  extends User{
	
	static function setQgzx_user($id){
		if(is_numeric($id)){
			$sql = "select * from SJTULib_user where id = '$id'";
			$userArray = UniversalConnect::doSql($sql);
			if($userArray){
				$newUser = new qgzx_User($userArray[0]);
				return $newUser;
			}else{
				return false;
			}
		} else {
			return false;
		}
	}
	
/**
 * @return 该用户的工作安排
 * */
public function getJobArrangement(){
		$userId =$this->getId();
		$sql = "select * from SJTULib_qgzx_jobArrangement where userId='$userId'";
		$res = UniversalConnect::doSql($sql);
		$result = array();
		if($res){
			foreach($res as $single){
				$result[] = qgzx_Job::setJob($single['jobId'],$single['day'],null,$userId,$userId);
			}
			return $result;
		}
		return false;
}
/**
 * 
 * 返回日期（$date）的工作
 * @param
 *  $date: Y-m-d. $date 日期
 * */
public function getJobOfDate($date){
	$userId = $this->getId();
	$jobs = array();
	$day = getDayOfDate($date);
	//检查替班.
	$sql = sprintf("select * from SJTULib_qgzx_jobReplacement where to_days(date)=to_days('%s') and userId2=%d and isDelete!=1",mysql_string($date),$this->getId());
	$res_replacement = UniversalConnect::doSql($sql);
	if($res_replacement){	
		foreach ($res_replacement as $job_replacement){
			$jobs[] = qgzx_Job::setJob($job_replacement['jobId'],null,$job_replacement['date'],$job_replacement['userId1'],$job_replacement['userId2']);
		}
	}
	//检查值班安排.有安排但是未被替班
	$job_arrangement = $this->getJobArrangement();
	if($job_arrangement){
		foreach ($job_arrangement as $job){
			if($job->getDay() == $day){	//今天有安排值班
				//判断该班次是否被替班
				$sql = sprintf("select * from SJTULib_qgzx_jobReplacement where jobId=%d and userId1=%d and to_days(date)=to_days('%s')",$job->getId(),$this->getId(),$date);
				$check = UniversalConnect::doSql($sql);
				if(!$check)	{	//未被替班，添加至 jobs
					$job->setDate($date);
					$jobs[] = $job;
				}	
			}
		}
	}
	
	return $jobs;
}

/**
 *如果应该值班 返回 该班次（类 qgzx_Job 的实力）
 *否则，返回 false
 * @param
 * $time unix timestamp 
 * */
public function isOnduty($time,$treathhold=30){
	$date = date('Y-m-d',$time);
	$jobs = $this->getJobOfDate($date);
	if($jobs){
		foreach($jobs as $job){		//遍历今天的工作
			if($job->isTimeToWork($time,$treathhold)){
				return $job;
			}
		}
	}
	return false;
}

/**
 * 返回是否签到
 * @param
 *  qgzx_job类
 * */
public function isAlreadyOnWork(qgzx_Job $job){
	$sql = sprintf("select * from SJTULib_qgzx_signed where jobId=%d and userId=%d and to_days(date)=to_days('%s')",$job->getId(),$this->getId(),$job->getDate());
	$res = UniversalConnect::doSql($sql);
	if($res){
		return true;
	}
	return false;
}

}

?>
<?php
class qgzx_Job {
	protected $id;
	protected $placeId;
	protected $begintime;
	protected $hours;
	protected $maxnum;
	protected $day;		
	protected $date;
	protected $originalOwnerId;
	protected $belongerId;
	
	public function __construct(array $place,$day=null,$date=null,$originalOwnerId=null,$belongerId=null){
			foreach($place as $key=>$value){
				$this->$key = $place[$key];
			}
			$this->day = $day;
			$this->date = $date;
			$this->originalOwnerId = $originalOwnerId;
			$this->belongerId = $belongerId;
	}
	
	static function setJob($jobId,$day=null,$date=null,$originalOwnerId=null,$belongerId=null){
		$sql=sprintf("select * from SJTULib_qgzx_job where id=%d",intval($jobId));
		$res = UniversalConnect::doSql($sql);
		if($res){
			$newJob = new qgzx_Job($res[0],$day,$date,$originalOwnerId,$belongerId);
			return $newJob;
		}else{
			return false;
		}
	}
	
	/**
	 * @return 
	 * 某一天的全部工作
	 * 类型： array(qgzx_job) 
	 * @param 
	 * date string
	 * 日期，格式： Y-m-d
	 * */
	static function getJobOfDate($date){
		$datePreg = '/^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])$/';
		$match = preg_match($datePreg, $date);
		if(!$match){
			return false;
		}
		$jobs = array();
		$day = getDayOfDate($date);							//获得今天值周几的班

		//检查值班安排
		$sql = "select * from SJTULib_qgzx_jobArrangement where day='$day'";		//获得今天的值班安排
		$res = UniversalConnect::doSql($sql);
		if($res){
			foreach ($res as $jobArray){			
				$sql = sprintf("select * from SJTULib_qgzx_jobReplacement where jobId=%d and userId1=%d and to_days(date)=to_days('%s')",
								$jobArray['jobId'],$jobArray['userId'],$date);			//检查该班次是否被替班
				$check = UniversalConnect::doSql($sql);
				if(!$check){							//没有被替班
					$jobs[] = qgzx_Job::setJob($jobArray['jobId'],$day,$date,$jobArray['userId'],$jobArray['userId']);
				}
			}
		}
		//检查替班表
		$sql = "select * from SJTULib_qgzx_jobReplacement where to_days(date)=to_days('$date') and isDelete != 1";
		$res = UniversalConnect::doSql($sql);
		if($res){
			foreach ($res as $singleArray){
				$jobs[] = qgzx_Job::setJob($singleArray['jobId'],null,$date,$singleArray['userId1'],$singleArray['userId2']);
			}
		}
		return $jobs;
	}
	
	/**
	 * @return
	 * 某一天的没有签到的工作
	 * 类型： array(qgzx_job)
	 * @param
	 * date string
	 * 日期，格式： Y-m-d
	 * */
	static function getUnsignedWorkOfDate($date){
		$datePreg = '/^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])$/';
		$match = preg_match($datePreg, $date);
		if(!$match){
			return false;
		}
		$unsignedJobs = array();
		$jobs = qgzx_Job::getJobOfDate($date);
		foreach ($jobs as $job){
			$user = qgzx_User::setQgzx_user($job->getBelonger());
			if(!$user->isAlreadyOnWork($job)){
				$unsignedJobs[] = $job;
			}
		}
		return $unsignedJobs;
	}
	
	/**
	 * @return
	 * 某一天的已经签到的工作
	 * 类型： array(qgzx_job)
	 * @param
	 * date string
	 * 日期，格式： Y-m-d
	 * */
	static function getSignedWorkOfDate($date){
		$datePreg = '/^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])$/';
		$match = preg_match($datePreg, $date);
		if(!$match){
			return false;
		}
		$signedJobs = array();
		$jobs = qgzx_Job::getJobOfDate($date);
		foreach ($jobs as $job){
			$user = qgzx_User::setQgzx_user($job->getBelonger());
			if($user->isAlreadyOnWork($job)){
				$signedJobs[] = $job;
			}
		}
		return $signedJobs;
	}
	
	/**
	 * 返回bool类型，$time时间是否应该值班
	 * @param
	 * $time unix timestamp
	 * */
	public function isTimeToWork($time,$treathhold=30){
		$hour = date('G',$time);
		$minute = date('i',$time);
		$in_toMinute = $hour*60+$minute;
		$begintime_toMinute = $this->begintime * 60;
		$endtime_toMinute = ($this->begintime + $this->hours)*60;
		if(abs($in_toMinute - $begintime_toMinute) <= $treathhold){	//班次开始前30分钟或开始和30分钟内才能签到
			return true;
		}
		return FALSE;
	}
	/**
	 * @return the place
	 * */
	public function getPlace(){
		$sql = sprintf("select * from SJTULib_qgzx_place where id=%d",$this->getPlaceId());
		$res = UniversalConnect::doSql($sql);
		if($res) {
			$place = $res[0]['content'];
			return $place;
		}
		return false;
		
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $placeId
	 */
	public function getPlaceId() {
		return $this->placeId;
	}

	/**
	 * @return the $begintime
	 */
	public function getBegintime() {
		return $this->begintime;
	}

	/**
	 * @return the $hours
	 */
	public function getHours() {
		return $this->hours;
	}

	/**
	 * @return the $maxnum
	 */
	public function getMaxnum() {
		return $this->maxnum;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param field_type $placeId
	 */
	public function setPlaceId($placeId) {
		$this->placeId = $placeId;
	}

	/**
	 * @param field_type $begintime
	 */
	public function setBegintime($begintime) {
		$this->begintime = $begintime;
	}

	/**
	 * @param field_type $hours
	 */
	public function setHours($hours) {
		$this->hours = $hours;
	}

	/**
	 * @param field_type $maxnum
	 */
	public function setMaxnum($maxnum) {
		$this->maxnum = $maxnum;
	}
	/**
	 * @return the $day
	 */
	public function getDay() {
		return $this->day;
	}

	/**
	 * @return the $date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param string $day
	 */
	public function setDay($day) {
		$this->day = $day;
	}

	/**
	 * @param string $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}
	/**
	 * @return the $originalOwnerId
	 */
	public function getOriginalOwnerId() {
		return $this->originalOwnerId;
	}

	/**
	 * @return the $belonger
	 */
	public function getBelonger() {
		return $this->belongerId;
	}

	/**
	 * @param string $originalOwnerId
	 */
	public function setOriginalOwnerId($originalOwnerId) {
		$this->originalOwnerId = $originalOwnerId;
	}

	/**
	 * @param field_type $belonger
	 */
	public function setBelonger($belongerId) {
		$this->belonger = $belongerId;
	}



	
	
}

?>
<?php
class Activity {
	protected $id;
	protected $catId;
	protected $userId;
	protected $departmentId;
	protected $oneWord;
	protected $title;
	protected $post;
	protected $content;
	protected $dateAdded;
	protected $lastEdit;
	protected $isDelete;
	protected $maxNum;
	protected $dateEnded;
	/**
	 * only received the data of below format
	 * @realize an activity
	 * */
	public function __construct($acti){
		if(is_array($acti)){
			foreach($this as $key=>$value){
				$this->$key = $acti[$key];
			}
		}
	}
	
	static function setActivity($activityId){
		if(is_numeric($activityId)){
			$sql = "select * from SJTULib_activity where id = '$activityId'";
			$acti = UniversalConnect::doSql($sql);
			$activity = new Activity($acti[0]);
			return $activity;
		} else {
			return false;
		}
	}
	
	static function isActivityExist($activityId){
		if(is_numeric($activityId)){
			$sql = "select * from SJTULib_activity where id = '$activityId' and isDelete != 1";
			$acti = UniversalConnect::doSql($sql);
			return $acti;
		}else{
			return false;
		}
	}
	
	static function isActivityRegistrationable($activityId){
		if(is_numeric($activityId)){
			$sql = "select * from SJTULib_activity where id = '$activityId' and maxNum > 0";
			$acti = UniversalConnect::doSql($sql);
			return $acti;
		}else{
			return false;
		}
	}
	
	public function isEnded(){
		$now = time();
		return ($now > strtotime($this->dateEnded));
	}
	
	public function isFull(){
		$sql = sprintf("select count(*) as registNum from SJTULib_registration where activityId=%d",$this->id);
		$countArray = UniversalConnect::doSql($sql);
		return ($countArray[0]['registNum'] >= $this->maxNum);
	}
	
	public function getRegistNum(){
		$sql = sprintf("select count(*) as registNum from SJTULib_registration where activityId=%d",$this->id);
		$countArray = UniversalConnect::doSql($sql);
		return $countArray[0]['registNum'];
	}
	
	public function getActivityUser(){
		$sql = sprintf("select * from SJTULib_registration where activityId=%d order by dateAdded ASC",$this->id);
		$usersArray = UniversalConnect::doSql($sql);
		$i = 0;
		if($usersArray){
			foreach ($usersArray as $usr){
				$users[$i] = User::setUser($usr['userId']);
				$i++;
			}
			return $users;
		}else 
			return false;
		
	}
	
	/**
	 * @return the first 200 letters of the content
	 * */
	public function getIntro($count = 50){
		//return htmlentities(substr(strip_tags($this->content), 0, $count) . '...',ENT_QUOTES,'UTF-8');
		return substr(strip_tags($this->content), 0, $count) . '...';
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $catId
	 */
	public function getCatId() {
		return $this->catId;
	}

	/**
	 * @return the $userId
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @return the $departmentId
	 */
	public function getDepartmentId() {
		return $this->departmentId;
	}

	/**
	 * @return the $oneWord
	 */
	public function getOneWord() {
		return htmlentities($this->oneWord,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return htmlentities($this->title,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $post
	 */
	public function getPost() {
		return htmlentities($this->post,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $content
	 */
	public function getContent() {
		//return htmlentities($this->content,ENT_QUOTES,'UTF-8');
		return $this->content;
	}

	/**
	 * @return the $dateAdded
	 */
	public function getDateAdded() {
		return htmlentities($this->dateAdded,ENT_QUOTES,'UTF-8');
	}
	/**
	 * @return the $lastEdit
	 */
	public function getLastEdit() {
		return htmlentities($this->lastEdit,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $isDelete
	 */
	public function getIsDelete() {
		return $this->isDelete;
	}
	/**
	 * @return the $maxNum
	 */
	public function getMaxNum() {
		return $this->maxNum;
	}

	/**
	 * @return the $dateEnded
	 */
	public function getDateEnded() {
		//return $this->dateEnded;
		return htmlentities($this->dateEnded,ENT_QUOTES,'UTF-8');
	}



	
	
}

?>
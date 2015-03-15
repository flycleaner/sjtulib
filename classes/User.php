<?php
class User {
	protected $id;
	protected $sno;
	protected $name;
	protected $tel;
	protected $email;
	protected $username;
	protected $password;
	protected $headPhoto;
	protected $userType;
	protected $userLever;
	protected $dateAdded;
	protected $isActive;
	
	public function __construct(array $user){
	if(is_array($user)){
			foreach($this as $key=>$value){
				$this->$key = $user[$key];
			}
		}
	}
	
	
	static function setUser($id){
		if(is_numeric($id)){
			$sql = "select * from SJTULib_user where id = '$id'";
			$userArray = UniversalConnect::doSql($sql);
			if($userArray){
				$newUser = new User($userArray[0]);
				return $newUser;
			}else{
				return false;
			}
		} else {
			return false;
		}
	}
	/**
	 * 是否存在 id 为$id的用户 
	 * */
	static function isUserExist($id){
		if(!is_numeric($id)){
			return false;
		}
		$id = intval($id);
		$sql = "select * from SJTULib_user where id=$id";
		$res = UniversalConnect::doSql($sql);
		if($res){
			return true;
		}
		return false;
	}
		
	public function isAdmin($departmentId = 0){
		if($this->userType == 'admin') {
			return true;
		}
		$sql = sprintf("select * from SJTULib_departmentMembers where userId=%d and departmentId=%d",intval($this->id),intval($departmentId));
		$res = UniversalConnect::doSql($sql);
		if($res){
			foreach($res as $one){
				switch ($res[0]['status']){
					case 0: case 1: return true;
					case 2: ;
					default: ;
			}
			}
			return false;
		}
	}
	//if is admin ,return the department Id
	public function IdOfDepartmentIfAdmin(){
		$result = array();
			$sql = sprintf("select * from SJTULib_departmentMembers where userId=%d and (status=0 or status=1)",intval($this->id));
			$res = UniversalConnect::doSql($sql);
			if($res){
				foreach ($res as $single){
					$result[] = $single['departmentId'];
				}
			}
			return $result;
	}
	
	static function userDepartmentId($id){
		$result = array();
		if(is_numeric($id)){
			$sql = "select * from SJTULib_departmentMembers where userId='$id' and confirm=1";
			$res = UniversalConnect::doSql($sql);
			$result = array();
			if($res){
				foreach ($res as $single){
					$result[] = $single['departmentId'];
				}
			}
		}
		return $result;
	}
	
	/**
	 * @return the time when the user registrate the activity($id)
	 * */
	public function getActivityRegistrateTime($activityId){
		$sql = sprintf("select * from SJTULib_registration where activityId='%s' and userId=%d",intval($activityId),intval($this->id));
		$res = UniversalConnect::doSql($sql);
		if($res){
			return $res[0]['dateAdded'];
		}else{
			return false;
		}
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return intval($this->id);
	}

	/**
	 * @return the $sno
	 */
	public function getSno() {
		return htmlentities($this->sno,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $username
	 */
	public function getUsername() {
		return htmlentities($this->username,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @return the $headPhoto
	 */
	public function getHeadPhoto() {
		if(!isset($this->headPhoto)){
			return './img/userHeadPhoto/default.png';
		}else{
			return htmlentities($this->headPhoto,ENT_QUOTES,'UTF-8');
		}
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return htmlentities($this->email,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $userType
	 */
	public function getUserType() {
		return htmlentities($this->userType,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $userLever
	 */
	public function getUserLever() {
		return htmlentities($this->userLever,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $dateAdded
	 */
	public function getDateAdded() {
		return htmlentities($this->dateAdded,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $isActive
	 */
	public function getIsActive() {
		return htmlentities($this->isActive,ENT_QUOTES,'UTF-8');
	}
	/**
	 * @return the $name
	 */
	public function getName() {
		return htmlentities($this->name,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $tel
	 */
	public function getTel() {
		return htmlentities($this->tel,ENT_QUOTES,'UTF-8');
	}


	
	
}

?>
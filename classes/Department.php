<?php
class Department {
	protected $id;
	protected $name;
	protected $departmentDescribe;
	protected $departmentPhoto;
	
	public function __construct(array $depart){
		if(is_array($depart)){
			foreach($this as $key=>$value){
				$this->$key = $depart[$key];
			}
		}
	}
	
	static function setDepartment($departmentId){
		if(is_numeric($departmentId)){
			$sql = "select * from SJTULib_department where id = '$departmentId'";
			$departments = UniversalConnect::doSql($sql);
			$depart = new Department($departments[0]);
			return $depart;
		}else {
			return false;
		}
	}
	
	static function isExitDepartment($id){
		if(is_numeric($id)){
			$sql = 'select * from SJTULib_department where id = '.$id;
			$res = UniversalConnect::doSql($sql);
			return $res;
		}
		return FALSE;
	}
	
	public function getDepartmentMembers($i){
		switch ($i){
			case 0:case 1:case 2: break;
			default: $i=2;break;
		}
		$sql = 'select * from SJTULib_departmentMembers where departmentId = '.$this->id.' and status = '.$i.' and confirm = 1';
		$res = UniversalConnect::doSql($sql);
		$members = array();
		if($res){
			foreach ($res as $single){
				$members[] = User::setUser($single['userId']);
			}
			return $members;
		}else {
			return false;
		}
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return htmlentities($this->name,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $describe
	 */
	public function getDescribe() {
		return htmlentities($this->departmentDescribe,ENT_QUOTES,'UTF-8');
	}

	public function getIntro($count = 200){
		return htmlentities(substr(strip_tags($this->departmentDescribe), 0, $count) . '...',ENT_QUOTES,'UTF-8');
	}
	/**
	 * @return the $departmentPhoto
	 */
	public function getDepartmentPhoto() {
		return htmlentities($this->departmentPhoto,ENT_QUOTES,'UTF-8');
	}

	
	
}

?>
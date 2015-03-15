<?php
class Photo {
	protected $id;
	protected $departmentId;
	protected $catId;
	protected $photoPath;
	protected $title;
	protected $photoDescribe;
	
	public function __construct(array $photo){
		foreach ($this as $key=>$value){
			$this->$key = $photo[$key];
		}
	}
	
	static function setPhoto($photoId){
		if(is_numeric($photoId)){
			$sql = "select * from SJTULib_photo where id = '$photoId'";
			$photoArray = UniversalConnect::doSql($sql);
			$photo = new Photo($photoArray);
			return $photo;
		}
	}
	
	static function getPhotoNum(){
		$sql = "select count(*) as num from SJTULib_photo";
		$photoArray = UniversalConnect::doSql($sql);
		return $photoArray[0]['num'];
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $departmentId
	 */
	public function getDepartmentId() {
		return $this->departmentId;
	}

	/**
	 * @return the $catId
	 */
	public function getCatId() {
		return $this->catId;
	}

	/**
	 * @return the $photoPath
	 */
	public function getPhotoPath() {
		return htmlentities($this->photoPath,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return htmlentities($this->title,ENT_QUOTES,'UTF-8');
	}

	/**
	 * @return the $describe
	 */
	public function getDescribe() {
		return htmlentities($this->photoDescribe,ENT_QUOTES,'UTF-8');
	}

	
	
}

?>
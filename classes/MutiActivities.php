<?php
//require_once 'UniversalConnect.php';
//require_once 'Activity.php';

class MutiActivities {
	protected $activities = array();
	protected $length;
	
	public function __construct($sql){
		$array = UniversalConnect::doSql($sql);
		$this->length = count($array);
		$i = 0;
		foreach ($array as $acti){
			$this->activities[$i] = new Activity($acti);
			$i++;
		}
	}
	/**
	 * @return the $activities
	 */
	public function getActivities() {
		return $this->activities;
	}

	/**
	 * @return the $length
	 */
	public function getLength() {
		return $this->length;
	}

	
	
}

?>
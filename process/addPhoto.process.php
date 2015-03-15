<?php
require_once '../config.inc.php';
/**
 * @should be included by every private page !important
 * */
$user = User::setUser($userId);
if(!$user->isAdmin() && in_array(0, $user->IdOfDepartmentIfAdmin())){
	echo '<div>对不起，您不是管理员</div>';
	echo '<script>history.go(-1)</script>';
	exit();
}
//var_dump($_POST);
if(isset($_POST)){
	$departmentId = $_POST['departmentId'];
	$photos = $_POST['photos'];
	//echo $departmentId;
	foreach($photos as $photo){
		$sql = sprintf("update SJTULib_photo set departmentId = %d, title = '%s', photoDescribe = '%s' where id = %d",
				intval($departmentId),mysql_string($photo['name']),mysql_string($photo['describe']),intval($photo['id']));
		echo $sql;
		$res = UniversalConnect::doSql($sql);
	}
	echo '保存成功';
}
?>
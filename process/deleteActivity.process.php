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
if(isset($_POST['id'])){
$id = $_POST['id'];
};
if( isset($id) && is_numeric($id)){
	$sql = sprintf("update SJTULib_activity set isDelete = 1 where id='%s'",mysql_string($id));
	$res = UniversalConnect::doSql($sql);
	if($res){
		echo '删除成功';
	}else{
		echo '删除失败！';
	}
}
?>
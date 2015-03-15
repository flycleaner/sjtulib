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

if(!isset($_POST['sig']) || !isset($_POST['id']) || !is_numeric($_POST['id'])){
	echo '操作失败';
	exit();
}
$id = intval($_POST['id']);
switch($_POST['sig']){
	case 'delete':
		$sql = "update SJTULib_qgzx_scores set isDelete=1 where id='$id'";
		$sucess = '删除成功';
		$fail = '删除失败';
		break;
	case 'redone':
		$sql = "update SJTULib_qgzx_scores set isDelete=0 where id='$id'";
		$sucess = '恢复成功';
		$fail = '恢复失败';
		break;
	default:;
}


$res = UniversalConnect::doSql($sql);
if($res){
	echo $sucess;
} else {
	echo $fail;
}

?>
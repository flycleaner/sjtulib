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

if(!isset($_POST['sig'])){
	exit();
}

$sig = $_POST['sig'];

$year = $_POST['year'];
$month = $_POST['month'];
//挑出已删除项目
$sql = sprintf("select * from SJTULib_qgzx_scores where extract(year from datetime)=%d and extract(month from datetime)=%d and isDelete=1 order by id DESC",
		intval($year),intval($month));
$res = UniversalConnect::doSql($sql);
if($res){
	$str = '';
	foreach ($res as $punish){
		$user = qgzx_User::setQgzx_user($punish['userId']);
		echo '<tr id="'.$punish['id'].'">
				<td>'.$user->getName().'</td>
				<td>'.$punish['datetime'].'</td>
				<td>'.$punish['scores'].'</td>
				<td>'.$punish['reason'].'</td>
				<td><a id="punishment_redone">撤销</a></td>
			</tr>';
	}
}
//挑出未删除项目
$sql = sprintf("select * from SJTULib_qgzx_scores where extract(year from datetime)=%d and extract(month from datetime)=%d and isDelete!=1 order by id DESC",
					intval($year),intval($month));	
$res = UniversalConnect::doSql($sql);
if($res){
	$str = '';
	foreach ($res as $punish){
		$user = qgzx_User::setQgzx_user($punish['userId']);
		echo '<tr id="'.$punish['id'].'">
				<td>'.$user->getName().'</td>
				<td>'.$punish['datetime'].'</td>
				<td>'.$punish['scores'].'</td>
				<td>'.$punish['reason'].'</td>
				<td><a id="punishment_delete">删除</a></td>
			</tr>';
	}
}else{
	echo '<tr><td>没有数据哦</td></tr>';
}
?>
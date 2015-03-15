<?php
require_once '../config.inc.php';
if(isset($_POST['email']) && isset($_POST['tel']) ){
$email = mysql_string($_POST['email']);
$tel = mysql_string($_POST['tel']);
$sql = sprintf("select * from SJTULib_user where tel='%s' and id != '%s'",$tel,$userId);
$res = UniversalConnect::doSql($sql);
if($res){
	echo '该手机号已被注册！';
} else{
	$sql = sprintf("update SJTULib_user set email = '%s',tel = '%s' where id=%s",$email,$tel,$userId);
	//echo $sql;
	$res = UniversalConnect::doSql($sql);
	if($res){
		echo $res[0]['name'];
		echo '修改成功';
	}else{
		echo '修改失败!';
	}
}
}

?>
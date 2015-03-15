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
if(isset($_POST['editDepartment_id']) && isset($_POST['editDepartment_name']) &&isset($_POST['editDepartment_describe'])){
	if(is_numeric($_POST['editDepartment_id'])){
		$id = intval($_POST['editDepartment_id']);
	} else {
		echo '<script>history.go(-1)</scirpt>';
		exit();
	}
	$name = mysql_string($_POST['editDepartment_name']);
	$describe = mysql_string($_POST['editDepartment_describe']);
} else{
	echo '<script>history.go(-1)</scirpt>';
	exit();
}

$date = date("Y-m-d H:i:s");
$flag=1;

$filter = array("image/gif","image/jpeg","image/pjpeg","image/png");
if(!empty($_FILES['editDepartment_photo']['tmp_name'])){	//if upload pictures
	if(is_uploaded_file($_FILES['editDepartment_photo']['tmp_name'])&&in_array($_FILES['editDepartment_photo']['type'], $filter)){
		$file = $_FILES['editDepartment_photo'];
		$error = $file['error'];
		if($error == 0){
			$newFileName = date("Y-m-d").'_'.$id.'_'.$name.'_'.rand(10000, 99999).'_'.$file['name'];
			$path = '../img/departmentPhoto/';
			$post = 'img/departmentPhoto/'.$newFileName;
			move_uploaded_file($file['tmp_name'], $path.$newFileName);
		} elseif($error == 1||$error == 2){
			$flag = 0;
			echo "<script>alert('添加失败！海报图片超过限定大小!');history.go(-1);</script>";
			exit();
		} elseif($error == 3){
			$flag = 0;
			echo "<script>alert('添加失败！海报只有部分上传');history.go(-1);</script>";
			exit();
		} elseif($error == 4){
			$flag = 0;
			echo "<script>alert('添加失败！海报没有上传');history.go(-1);</script>";
			exit();
		} else{
			$flag = 0;
			echo "<script>alert('海报文件大小为0');history.go(-1);</script>";
			exit();
		}
	}else{
		$flag=0;
		echo "<script>alert('添加失败！图片格式为jpeg,png,jpg,gif');history.go(-1);</script>";
		exit();
	}
}else{	//dosen't set department photo
	$sql = "select * from SJTULib_department where id=".$id;
	$res = UniversalConnect::doSql($sql);
	if($res){
		$post = $res[0]['departmentPhoto'];
	}else{
		echo "<script>alert('保存失败');history.go(-1);</script>";
		$flag=0;
		exit();
	}
}


if($flag == 1){
	$sql = sprintf("update SJTULib_department set name='%s', departmentDescribe='%s',departmentPhoto='%s' where id=%d",$name,$describe,$post,$id);
	$res = UniversalConnect::doSql($sql);
	if($res){
		echo '<script>alert("保存成功");history.go(-1)</script>';
	}
	else{
		echo '<script>alert("保存失败");history.go(-1)</script>';
	}
}
?>
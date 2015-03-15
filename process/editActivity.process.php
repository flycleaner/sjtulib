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
/**
 * @should be included by every private page !important
* */
$user = User::setUser($userId);
if(!$user->isAdmin() || !$user->IdOfDepartmentIfAdmin()){
	echo '<div>对不起，您不是管理员</div>';
	echo '<script>history.go(-1)</script>';
	exit();
}

if(isset($_POST)){
	$activityId = mysql_string($_POST['editArticle_id']);
	$title = mysql_string($_POST['editArticle_title']);
	$oneWord = mysql_string($_POST['editArticle_oneWord']);
	$departmentId = mysql_string($_POST['editArticle_department']);
	$maxNum = mysql_string($_POST['editArticle_maxNum']);
	$dateEnded = mysql_string($_POST['editArticle_endDate']);
	
	$content = mysql_string($_POST['editorValue']);
	$content = $purifier->purify($content);	//filter dangerous html
	
	$date = date("Y-m-d H:i:s");
	$flag=1;
	//check maxNum and departmentId
	if((!is_numeric($maxNum) && $maxNum != null)||(!is_numeric($departmentId))){
		$flag=0;
		echo "<script>alert('添加失败！人数应为数字');history.go(-1);</script>"; 
		exit(); 
	}
	//check and save post
	$filter = array("image/gif","image/jpeg","image/pjpeg","image/png");
	if(!empty($_FILES['editArticle_post']['tmp_name'])){	//if upload pictures
	if(is_uploaded_file($_FILES['editArticle_post']['tmp_name'])&&in_array($_FILES['editArticle_post']['type'], $filter)){
		$file = $_FILES['editArticle_post'];
		$error = $file['error'];
		if($error == 0){
			$newFileName = date("Y-m-d").'_'.$title.'_'.rand(10000, 99999).'_'.$file['name'];
			$path = '../img/activityPost/';
			$post = 'img/activityPost/'.$newFileName;
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
	}else{
		$sql = "select * from SJTULib_activity where id=".$activityId;
		$res = UniversalConnect::doSql($sql);
		if($res){
			$post = $res[0]['post'];
		}else{
			echo '修改失败';
			$flag=0;
			exit();
		}
	}
	
	if($flag){
		$sql = sprintf("update SJTULib_activity set userId='%s',departmentId='%s',oneWord='%s',title='%s',post='%s',content='%s',dateAdded='%s',maxNum='%s',dateEnded='%s' where id='%s'",
						$userId,$departmentId,$oneWord,$title,$post,$content,$date,$maxNum,$dateEnded,$activityId);
		//echo $sql;
		$res = UniversalConnect::doSql($sql);
		if($res){
			echo "<script>alert('修改成功！');history.go(-1);</script>";
		}else{
			echo "<script>alert('修改失败！请重试！');history.go(-1);</script>";
		}
	}
	
	}

?>
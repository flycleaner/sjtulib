<?php
require_once '../config.inc.php';
switch ($_POST['signal']){
	case 'username':
		$sql = sprintf("select * from SJTULib_user where username = '%s'",mysql_string($_POST['username']));
		$res = UniversalConnect::doSql($sql);
		if($res){
			 $array['callback'] = '用户名已被注册';
		} else{
			 $array['callback'] = 'ok';
		}
		echo json_encode($array);
		break;
	case 'sno':
		$sql = sprintf("select * from SJTULib_user where sno = '%s'",mysql_string($_POST['sno']));
		$res = UniversalConnect::doSql($sql);
		if($res){
			$array['callback'] = '学号已被注册，请直接用学号登陆!';
		}else {
		    $array['callback'] = 'ok';
		}
		echo json_encode($array);
		break;
	case 'email':
		$sql = sprintf("select * from SJTULib_user where email = '%s'",mysql_string($_POST['email']));
		$res = UniversalConnect::doSql($sql);
		if($res){
			$array['callback'] = '邮箱已被注册，请直接用邮箱登陆！';
		}else{
			$array['callback'] = 'ok';
		}
		echo json_encode($array);
		break;
	case 'tel':
		$sql = sprintf("select * from SJTULib_user where tel = '%s'",mysql_string($_POST['tel']));
		$res = UniversalConnect::doSql($sql);
		if($res){
			$array['callback'] = '手机号已被注册，请直接用手机号登陆!';
		}else {
			$array['callback'] = 'ok';
		}
		echo json_encode($array);
		break;
	case 'regist':
		$sql = sprintf("select * from SJTULib_user where username = '%s' or sno = '%s' or email = '%s' or tel = '%s'",
				mysql_string($_POST['username']),mysql_string($_POST['sno']),mysql_string($_POST['email']),mysql_string($_POST['tel']));
		$res = UniversalConnect::doSql($sql);
		if($res){
			echo '注册失败,请检查输入!';
		}else{
			if($_POST['password'] == $_POST['confirmPassword']){
				$password = myMd5($_POST['password']);
				$sql = sprintf("insert into SJTULib_user(username,password,name,sno,email,tel) values('%s','%s','%s','%s','%s','%s')",
						mysql_string($_POST['username']),mysql_string($password),mysql_string($_POST['name']),
						mysql_string($_POST['sno']),mysql_string($_POST['email']),mysql_string($_POST['tel']));
				$res = UniversalConnect::doSql($sql);
				if($res){
					echo '恭喜您，注册成功!请返回登陆';
				}else{
					echo '很抱歉发生意外错误，请重试！';
				}
			}else{
				echo '注册失败，请检查输入!';
			}
		}
		break;
	default:;
}

?>


<?php
require_once("../config.inc.php");

if(isset($_GET['sentense'])){
	$date = date('Y-m-d');
	$sentense = urldecode($_GET['sentense']);
	$sql = sprintf("select * from SJTULib_sentenseEveryDay where to_days(dateAdded)= to_days('%s')",$date);
	//echo $sql.'</br>'.$sentense;
	$res = UniversalConnect::doSql($sql);
	$flag = 1;
	if($res && $res[0]['content'] != ''){
		$flag =0;
		$sentense = $res[0]['content'];
	}elseif($res && $res[0]['content'] == ''){
		$sql = "delete from SJTULib_sentenseEveryDay where to_days(dateAdded)=to_days('$date')";
		$res = UniversalConnect::doSql($sql);
	}
	if($flag == 1){
		$sql = sprintf("insert into SJTULib_sentenseEveryDay(content) values('%s')",mysql_string($sentense));
		$res = UniversalConnect::doSql($sql);
		//echo $sql;
	}
}
?>
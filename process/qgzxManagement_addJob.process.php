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

if(isset($_GET['q'])&&$_GET['q']==2) {
	$page = intval($_GET['page']); // get the requested page
	$limit = intval($_GET['rows']); // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	// connect to the database
	$sql="select count(*) as count from SJTULib_qgzx_job";
	$row = UniversalConnect::doSql($sql);
	$count = $row[0]['count'];

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) {
		$page=$total_pages;
	}
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	$sql = "select * from SJTULib_qgzx_job limit $start,$limit";
	$result = UniversalConnect::doSql($sql);

	$responce['page'] = $page;
	$responce['total'] = $total_pages;
	$responce['records'] = $count;
	$i=0;

	if($result){
		foreach ($result as $row){
			$sql = "select * from SJTULib_qgzx_place where id={$row['placeId']}";
			$res = UniversalConnect::doSql($sql);
			$place = $res[0]['content'];
			$responce['rows'][$i]['id']=$row['id'];
			$responce['rows'][$i]['cell']=array($row['id'],$place,$row['begintime'],$row['hours'],$row['maxnum']);
			$i++;
		}
	}
	echo json_encode($responce);
}

if(isset($_POST['id']) && is_numeric($_POST['id'])){
	$id = intval($_POST['id']);
	$placeId = intval($_POST['placeId']);
	$begintime = floatval($_POST['begintime']);
	$hours = floatval($_POST['hours']);
	$maxnum = intval($_POST['maxnum']);
}

if(isset($_POST['oper'])){
	if($_POST['oper'] == 'edit'){
		$sql = "update SJTULib_qgzx_job set placeId='$placeId',begintime='$begintime',hours='$hours',maxnum='$maxnum' where id = '$id'";
		$res = UniversalConnect::doSql($sql);
	}
	if($_POST['oper'] == 'add'){
		$sql ="insert into SJTULib_qgzx_job(placeId,begintime,hours,maxnum) values('$placeId','$begintime','$hours','$maxnum')";
		$res = UniversalConnect::doSql($sql);
		echo '成功';
	}
	if($_POST['oper']=='del'){
		$sql ="delete from SJTULib_qgzx_job where id='$id'";
		//echo $sql;
		$res = UniversalConnect::doSql($sql);
	}
}

?>
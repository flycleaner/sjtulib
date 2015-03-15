<?php 
/*avoid this page was visited directly*/
/**
 * @should be included by every view file  !important
 * */
if(!defined('BASE_URL')){
	$url = '../index.php';
	header("location:$url");
	exit();
}
?>
<?php
/**
 * @should be included by every private page !important
 * */
$user = User::setUser($userId);
if(!$user->isAdmin() && !$user->IdOfDepartmentIfAdmin()){
	echo '<div>对不起，您不是管理员</div>';
	echo '<script>history.go(-1)</script>';
	exit();
}
?>
<main role="main" >
	<div id="main">
		
		<section class="row section" style="margin: 30px 0">
			<div class="row-content buffer clear-after" style="border:2px solid blanchedalmond">
				<?php displayActivityRegistrationInfo($id);?>
			</div>
		</section>
		</div>	
		
</main>
<?php 
function displayActivityRegistrationInfo($id){
	if(!Activity::isActivityExist($id) || !Activity::isActivityRegistrationable($id)){
		return false;
	}
	$activity = Activity::setActivity($id);
	$title = $activity->getTitle();
	$dateAdded = $activity->getDateAdded();
	$dateEnded = $activity->getDateEnded();
	$maxNum = $activity->getMaxNum();
	$users = $activity->getActivityUser();
	$userNum = $activity->getRegistNum();
	
	echo "<div class=\"section-title\"><h3>$title</h3></div>";
	echo '<div style="background:antiquewhite;padding:0 3%">';
	echo "<p>活动添加时间： $dateAdded</p>";
	echo "<p>活动截止时间： $dateEnded</p>";
	echo "<p>最大人数：	$maxNum</p>";
	echo "<p>已报名人数：	$userNum</p>";
	echo '</div>';
	echo "<div class=\"section-title\"><h5>详细信息</h5></div>";
	
	echo '<table class="mytable" style="border: 1px solid lightgrey;padding:0 3%;width:100%;background:white">';
	echo '<thead>
			<th>编号</th>
			<th>姓名</th>
			<th>学号</th>
			<th>手机号</th>
			<th>邮箱</th>
			<th>报名时间</th>
			</thead>';
	echo '<tbody id="searchRegistrationUsers_showTable_tbody">';
	if($users){
		$i=1;
		foreach ($users as $usr){
			$name = $usr->getName();
			$sno = $usr->getSno();
			$tel = $usr->getTel();
			$email = $usr->getEmail();
			$registrationTime = $usr->getActivityRegistrateTime($id);
			
			echo "<tr style=\"border: 1px solid lightgrey\">
					<td>$i</td>
					<td>$name</td>
				  <td>$sno</td>
				  <td>$tel</td>
				  <td>$email</td>
				  <td>$registrationTime</td>
				  </tr>
			";
			$i++;
		}
	}
	
	echo '</tbody>';
	echo '</table>';
	echo '<div class="searchRegistrationUsers_showTable_holder" style="text-align:center"></div>';
}
?>

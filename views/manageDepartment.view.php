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
if(!$user->isAdmin() && in_array(0, $user->IdOfDepartmentIfAdmin())){
	echo '<div>对不起，您不是管理员</div>';
	echo '<script>history.go(-1)</script>';
	exit();
}
?>
 
<main role="main" >
	<div id="main">
		
		<section class="row section text-light full-height more-button" style="background-color:#cec8bc">
			<div class="row-content buffer even clear-after">
				<?php manageDepartment($user);?>
		 	</div>
		</section>
		</div>	
		
</main> 
<?php 
function manageDepartment(User $user){
	$departmentIds = $user->IdOfDepartmentIfAdmin();
	if($user->isAdmin()){	//if is admin
		$departmentIds = array();
		$sql = "select * from SJTULib_department";
		$res = UniversalConnect::doSql($sql);
		foreach ($res as $single){
			$departmentIds[] = $single['id'];
		}
	}
	if(!$departmentIds){
		return;
	}
	foreach ($departmentIds as $departmentId){
		$department = Department::setDepartment($departmentId);
		$departName = $department->getName();
		$departPhoto = $department->getDepartmentPhoto();
		$departDescribe = $department->getDescribe();
		echo '
				
 					<form id="editDepartment-form" class="contact-section" enctype="multipart/form-data" method="post" 
		action="process/userCenter_editDepartment.process.php" style="text-align:center;margin:3%;padding:2%;border:2px solid ">
						<div class="column five">
								<img src="'.$departPhoto.'">
								<figure>
									<p style="text-align:left">
							<input id="editDepartment_photo" name="editArticle_photo" type="file" style="display:inline" ></br>
							<span>建议选取长宽一致的大图</span></p>
								</figure>
							</div>
					
						<div class="column seven last">
							<h3><input type="text"  name="editDepartment_name" style="" value="'.$departName.'"></h3>
							<h4><textarea style="float:left;width:80%;height:200px;overflow:auto" name="editDepartment_describe" >'.$departDescribe.'</textarea></h4>
						</div>
						<div class="column twelve last" style="text-align:center">
					<input id="editDepartment_id" name="editDepartment_id" style="display:none" value="'.$departmentId.'">
					<input id="editDepartment_submit" class="plain button green" type="submit" value="保存">
 							</div>
					
					</form>
 			';
	}
	
}
?>
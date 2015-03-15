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
<!-- umeditor-->	
 			
<link href="plugins/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
 <script type="text/javascript" charset="utf-8" src="./plugins/umeditor/umeditor.config.js"></script>
 <script type="text/javascript" charset="utf-8" src="./plugins/umeditor/umeditor.min.js"></script>
 <script type="text/javascript" src="./plugins/umeditor/lang/zh-cn/zh-cn.js"></script>
 		
<main role="main" >
	<div id="main">
		
		<section class="row section" style="margin: 30px 0">
			<div class="row-content buffer clear-after">
				<?php editAticle($userId,$id);?>
			</div>
		</section>
		</div>	
		
</main>
<script>
/*edit activity content @umeditor*/
var url = window.location.href;
var re = new RegExp(".*oper=edit.*");
if(re.test(url)){
	//alert('yes');
	var um1 = UM.getEditor('editArticle_umeditor'
	);
	content = $("div#editArticle_hiddenContent").html();
	um1.setContent(content);
}
</script>
<?php
function editAticle($userId,$activityId){
	$activity = Activity::setActivity($activityId);
	$departmentId = $activity->getDepartmentId();
	echo '	<div class="section-title"><h3>编辑活动</h3></div>';

	echo '<form id="article-form" class="contact-section" enctype="multipart/form-data" method="post" action="process/editActivity.process.php" style="text-align:center;margin:3%">';
	echo '<input id="editArticle_id" name="editArticle_id" style="display:none" value="'.$activityId.'">';
	echo 	'<p style="text-align:left">活动标题
							<input id="editArticle_title" name="editArticle_title" type="text" style="display:inline" value="'.$activity->getTitle().'" required>
							</p>';
	echo  '<p style="text-align:left">关键字
							<input id="editArticle_oneWord" name="editArticle_oneWord" type="text" style="display:inline"value="'.$activity->getOneWord().'" required>
							</p>';
	echo	'<p style="text-align:left">活动部门
							<select  id="editArticle_department" name="editArticle_department" style="display:inline">
							'.showDepartmentSelect($departmentId).
							'</select>
							</p>';
	echo '<p style="text-align:left">最大人数
							<input id="editArticle_maxNum" name="editArticle_maxNum" type="text" style="display:inline" value='.$activity->getMaxNum().'>
							<span>报名截止时间</span><input id="datetime" name="editArticle_endDate" value="'.$activity->getDateEnded().'" type="text" style="display:inline">
							</p>';
	echo '<p style="text-align:left">海报
							<input id="editArticle_post" name="editArticle_post" type="file" style="display:inline" >
							<span>不修改则不选文件</span></p>';
	echo '<div id="editArticle_hiddenContent" style="display:none">'.$activity->getContent().'</div>';
	echo '<script type="text/plain" id="editArticle_umeditor" style="width:100%;height:240px;text-align:left">
			</script>';
	echo	'<p style="text-align:center;font-color:red" id= "userCenter_callbackMsg"></p>';
	echo	'<input id="editArticle_submit" class="plain button green" type="submit" value="提交">';
	echo '</form>';
}
?>
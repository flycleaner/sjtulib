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
<!-- umeditor-->	
 			
<link href="plugins/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
 <script type="text/javascript" charset="utf-8" src="./plugins/umeditor/umeditor.config.js"></script>
 <script type="text/javascript" charset="utf-8" src="./plugins/umeditor/umeditor.min.js"></script>
 <script type="text/javascript" src="./plugins/umeditor/lang/zh-cn/zh-cn.js"></script>
 		
<main role="main" >
	<div id="main">
		<?php oneSentenseOneDay();?>
		
		<section class="row section" style="margin: 30px 0">
			<div class="row-content buffer clear-after">
			
				<!-- 个人信息 -->
				<div class="column six" style="border: 2px solid blanchedalmond;background-color:white" >
				<?php changeUserInfo($userId);?>
				</div>
				
				<!-- 参与的活动 -->
				<div class="column six last" style="border: 2px solid blanchedalmond;background-color:white ">
				<?php userActivity($userId);?>
				</div>
				<!-- 修改密码 -->
				<div class="column six" style="border: 2px solid blanchedalmond;background-color:white;margin-top:20px ">
				<?php changePassword($userId);?>
				</div>
			</div>
		</section>
				<!--勤工助学签到等 -->
				<?php qgzxManagement($userId);?>
		
		<section class="row section" style="margin: 30px 0">
			<div class="row-content buffer clear-after">
				<?php moreApp($userId);?>
			</div>
		</section>
		</div>	
</main>
<script>
$(function(){
	$('#qgzx_jobReplacement_datetime').datetimepicker({
		yearOffset:0,
		lang:'ch',
		timepicker:true,
		format:'Y-m-d H:i:s',
		formatDate:'Y-m-d H:i:s',
		//minDate:'-1970/01/02', // yesterday is minimum date
		//maxDate:'+1970/01/02' // and tommorow is maximum date calendar
	});
	$('#addArticle_endDate').datetimepicker({
		yearOffset:0,
		lang:'ch',
		timepicker:true,
		format:'Y-m-d H:i:s',
		formatDate:'Y-m-d H:i:s',
		//minDate:'-1970/01/02', // yesterday is minimum date
		//maxDate:'+1970/01/02' // and tommorow is maximum date calendar
	});
	//修改密码 新密码确认
	$("#qgzxManagement_confirmNewPwd").blur(function(){
		confirm = $(this).val();
		newPwd = $("#qgzxManagement_newPwd").val();
		if(confirm != newPwd){
			$("#changePasswordTip").html('新密码不匹配').show();
			}else{
				$("#changePasswordTip").html('').hidden();
			}
		});
	//签到按钮
	$("#qgzx_submitButton").click(function(){
		signCode = $("#qgzx_signCode").val();
		$.ajax({
			type:'POST',
			url:'process/qgzx_sign.process.php',
			data:{signCode:signCode,sign:'sign'} ,
			success: function(msg){
				alert(msg);
			}
			});
		});
	//登记替班
	$("#qgzx_jobReplacement_submit").click(function(){
		datetime = $("#qgzx_jobReplacement_datetime").val();
		sno = $("#qgzx_jobReplacement_sno").val();
		$.ajax({
			type:'POST',
			url:'process/qgzx_jobReplacement.process.php',
			data:{oper:'add',datetime:datetime,sno:sno} ,
			success: function(msg){
				alert(msg);
			}
			});
		});
	//替班确认
	$("table tbody tr td a#qgzx_jobReplacementConfirm").click(function(){
		id = $(this).parent().parent().attr('id');
		$.ajax({
			type:'POST',
			url:'process/qgzx_jobReplacement.process.php',
			data:{oper:'confirm',id:id},
			success: function(msg){
				alert(msg);
				window.location.reload();
				}
			});
		});
	$("table tbody tr td a#qgzx_jobReplacement_delete").click(function(){
		id = $(this).parent().parent().attr('id');
		$.ajax({
			type:'POST',
			url:'process/qgzx_jobReplacement.process.php',
			data:{oper:'delete',id:id},
			success: function(msg){
				alert(msg);
				window.location.reload();
				}
			});
		});
	
});
</script>

<?php 
function changeUserInfo($userId){
	$user = User::setUser($userId);
	$email = $user->getEmail();
	$tel = $user->getTel();
	echo <<<START
	<div class="section-title"><h3>个人信息</h3></div>
					<form id="contact-form" class="contact-section" style="text-align:center;margin-left:3%">
							<p style="text-align:left">修改邮箱
							<input id="userCenter_email" type="text" style="display:inline" value="$email">
							</p>
							
							<p style="text-align:left">修改手机号
							<input  id="userCenter_tel" type="text" style="display:inline" value="$tel">
							</p>
							<p style="text-align:center;font-color:red" id= "userCenter_callbackMsg"></p>
							<input id="userCenter_saveChanges" class="plain button green" type="button" value="保存">
					</form>
START;
}

function userActivity($userId){
	echo '	<div class="section-title"><h3>参加的活动</h3></div>';
	echo '<table class="mytable" style="border: 1px solid lightgrey;margin:0 3%;width:94%">
			<thead style="border: 1px solid lightgrey">
				<th>Id</th>
				<th>活动名称</th>
				<th>参加时间</th>
			</thead>
			<tbody id="userCenter_Activities" style="border: 1px solid lightgrey">';
	$i = 1;
	$sql = sprintf("select * from SJTULib_registration where userId=%s",$userId);
	$res = UniversalConnect::doSql($sql);
	if($res){
		foreach ($res as $single){
			$acti =  Activity::setActivity($single['activityId']);
			$id=$acti->getId();
			$title = $acti->getTitle();
			$time = $single['dateAdded'];
			echo "<tr style=\"border: 1px solid lightgrey\">
						<td>$i</td>
						<td><a style='text-decoration:none' href=\"index.php?p=activity&id=$id\">$title</a></td>
						<td>$time</td>
					</tr>";
			$i++;
		}
	}
	echo '	</tbody>
		</table>';
	echo '<div class="holder" style="text-align:center"></div>';
}
function changePassword($userId){
	echo '<div class="section-title"><h3>修改密码</h3></div>';
	echo '<form class="contact-section" method="post" action="process/userCenter_changePassword.process.php" style="text-align: center">
					<div style="width:100%">
									<span class="pre-input"><i class="icon icon-lock"></i></span>
									<input id="changePassword_oldPwd" class="email plain buffer" type="password" name="changePassword_oldPwd" placeholder="原密码" style="width: 300px;display:inline" required="required">
								</div>
					<div style="width:100%">
									<span class="pre-input"><i class="icon icon-lock"></i></span>
									<input id="qgzxManagement_newPwd" class="email plain buffer" type="password" name="qgzxManagement_newPwd" placeholder="新密码" style="width: 300px;display:inline" required="required">
								</div>
					<div style="width:100%">
									<span class="pre-input"><i class="icon icon-lock"></i></span>
									<input id="qgzxManagement_confirmNewPwd" class="email plain buffer" type="password" name="qgzxManagement_confirmNewPwd" placeholder="确认新密码" style="width: 300px;display:inline" required="required">
								</div>
					<div id="changePasswordTip"style="color:red;font-size:70% display:none"></div>
				<input class="button green" type="submit" style="float:center" value="修改">
			</form>
			';
					
}

function qgzxManagement($userId){
	$user = User::setUser($userId);
	if(!$user->isAdmin(4) && !in_array(4, User::userDepartmentId($userId))){
		return;
	}
	$user = qgzx_User::setQgzx_user($userId);
	echo '<section class="row section" style="margin: 30px 0;">
			<div class="row-content buffer clear-after" style="border:2px solid blanchedalmond;">';
	echo '<div class="section-title"><h3>勤工助学管理</h3></div>';
	//今日班次 box
	echo '<div class="column twelve last" style="border: 2px solid blanchedalmond;background-color:white;" >';
	echo '<div class="section-title"><h3>今日班次</h3></div>';
	echo '<table class="mytable" style="border: 1px solid lightgrey;margin:3%;width:94%">
			<thead style="border: 1px solid lightgrey">
				<th>地点</th>
				<th>工作时间</th>
				<th>日期</th>
			</thead>
			<tbody style="border: 1px solid lightgrey">';
	//列出今日班次
	$today = date('Y-m-d');
	$jobs = $user->getJobOfDate($today);
	if($jobs){
		foreach ($jobs as $job){
			echo '<tr>
					<td>'.$job->getPlace().'</td>
					<td>'.generateJobTimes($job->getBegintime(), $job->getHours()).'</td>
					<td>'.$job->getDate().'</td>
				</tr>';
		}
	}
	echo '</tbody></table>';
	echo '</div>';
	
	//签到 box
	echo '<div class="column six" style="border: 2px solid blanchedalmond;background-color:white;margin-top:20px" >';
	echo '<div class="section-title"><h3>签到</h3></div>';
	echo '<div id="contact-form" class="contact-section" style="text-align:center">
			<div style="width:100%">				
				<span class="pre-input"><i class="icon icon-lock"></i></span>
				<input id="qgzx_signCode" class="email plain buffer" type="text" name="qgzx_signCode" placeholder="输入验证码或者到指定地点直接签到" style="width: 70%;display:inline">
			</div>
				<input id="qgzx_submitButton" class="plain button green" type="button" value="签到" style="">
		 </div>';
	echo '</div>';
	
	//替班登记 box
	echo '<div class="column six last" style="border: 2px solid blanchedalmond;background-color:white;margin-top:20px" >';
	echo '<div class="section-title"><h3>登记替班</h3></div>';
	echo '<div id="contact-form" class="contact-section" style="text-align:center">
				<div style="width:100%">
									<span class="pre-input"><i class="icon icon-calendar"></i></span>
									<input id="qgzx_jobReplacement_datetime" class="email plain buffer" type="text" name="qgzx_jobReplacement_datetime" placeholder="时间选择在班次开始30分钟前后" style="width: 300px;display:inline" required="required">
								</div>
			<div style="width:100%">
									<span class="pre-input"><i class="icon icon-user"></i></span>
									<input id="qgzx_jobReplacement_sno" class="email plain buffer" type="text" name="sno" placeholder="替班学号" style="width: 300px;display:inline" required="required">
								</div>
			<div style="width:100%">
								</div>
				<input id="qgzx_jobReplacement_submit" class="plain button green" type="button" value="确认" style="">
		 </div>';
	echo '</div>';
	//替班确认box
	echo '<div class="column twelve" style="border: 2px solid blanchedalmond;margin-top:20px;background-color:white" >';
	echo '<div class="section-title"><h3>替班确认</h3></div>';
	echo '<table class="mytable" style="border: 1px solid lightgrey;margin:3%;width:94%">
			<thead style="border: 1px solid lightgrey">
				<th>地点</th>
				<th>工作时间</th>
				<th>日期</th>
				<th>状态</th>
				<th>操作	</th>
			</thead>
			<tbody id="jpage" style="border: 1px solid lightgrey">';
	//列出待确认项目
	$sql = "select * from SJTULib_qgzx_jobReplacement where userId2='$userId' and (isDelete!=1 or confirm=1) order by id DESC";
	$res = UniversalConnect::doSql($sql);
	if($res){
		foreach ($res as $single){
			$id = $single['id'];
			$datetime = $single['date'];
			$date = substr($datetime, 0,10);
			$job = qgzx_Job::setJob($single['jobId']);
			$time = generateJobTimes($job->getBegintime(), $job->getHours());
			$place = $job->getPlace();
			echo '<tr id="'.$id.'">
					<td>'.$place.'</td>
					<td>'.$time.'</td>
					<td>'.$date.'</td>';
			if($single['confirm'] == 1){
				echo '<td>已确认</td><td></td>';
			}else{
					echo '<td style="color:red">待确认</td>
							<td><a id="qgzx_jobReplacementConfirm">确认</a></td>';
			}
		}
					
			echo	 '</tr>';
	}
	//列出可删除项目
	$sql = "select * from SJTULib_qgzx_jobReplacement where userId1='$userId' and (isDelete!=1 or confirm=1) order by id DESC";
	$res = UniversalConnect::doSql($sql);
	if($res){
		foreach ($res as $single){
			$id = $single['id'];
			$datetime = $single['date'];
			$date = substr($datetime, 0,10);
			$job = qgzx_Job::setJob($single['jobId']);
			$time = generateJobTimes($job->getBegintime(), $job->getHours());
			$sql = sprintf("select * from SJTULib_qgzx_place where id=%d",$job->getPlaceId());
			$res = UniversalConnect::doSql($sql);
			$place = $res[0]['content'];
			echo '<tr id="'.$id.'">
					<td>'.$place.'</td>
					<td>'.$time.'</td>
					<td>'.$date.'</td>';
			if($single['confirm'] == 1){
				echo '<td>已被确认</td><td></td>';
			}else{
				echo '<td style="color:red">可删除</td>
							<td><a id="qgzx_jobReplacement_delete">删除</a></td>';
			}
		}
			
		echo	 '</tr>';
	}
	echo '	</tbody>
		</table>';
	echo '<div class="holder" style="text-align:center"></div>';
	echo '</div>';
	//列出值班安排
	echo '<div class="column twelve last" style="border: 2px solid blanchedalmond;background-color:white;margin-top:20px" >';
	echo '<div class="section-title"><h3>班次安排</h3></div>';
	echo '<table class="mytable" style="border: 1px solid lightgrey;margin:3%;width:94%">
			<thead style="border: 1px solid lightgrey">
				<th>地点</th>
				<th>工作时间</th>
				<th>星期</th>
			</thead>
			<tbody style="border: 1px solid lightgrey">';
	$jobs = $user->getJobArrangement();
	if($jobs){
		foreach ($jobs as $job){
			echo '<tr>
					<td>'.$job->getPlace().'</td>
					<td>'.generateJobTimes($job->getBegintime(), $job->getHours()).'</td>
					<td>'.$job->getDay().'</td>
				</tr>';
		}
	}
	echo '</tbody></table>';
	echo '</div>';
	
	echo '</div>
		</section>';
}

function moreApp($userId){
	
	function appInterface($href,$img,$text){
			
		echo '<article class="item column four">
								<a href="'.$href.'">
									<figure><img src="'.$img.'" alt=""><span class="blog-overlay"><i class="icon icon-doc">'.$text.'</i></span></figure>
									<div class="blog-excerpt">
										<div class="blog-excerpt-inner">
											<h5 class="meta-post" style="text-align:center">'.$text.'</h5>
										</div><!-- blog-excerpt -->
									</div><!-- blog-excerpt-inner -->
								</a>
							</article>';
	}
	
	$user = User::setUser($userId);
	if($user->isAdmin() || $user->IdOfDepartmentIfAdmin()){
		
		
		echo '	<div class="section-title"><h2>管理</h2></div>';
		
		echo '		<div class="grid-items blog-section masonry-style preload">';
		/*here add appInterfaces*/
		appInterface('index.php?p=userCenter&oper=manageDepartment', 'img/appPictures/manageDepartment.jpg', '部门管理');
		appInterface('index.php?p=userCenter&oper=qgzxManagement','img/appPictures/qgzxManagement.jpg','勤工助学管理');
		echo '<div class="shuffle-sizer three"></div>
						</div>';
		
		echo '<div class="column twelve last" style="border: 2px solid blanchedalmond;background-color:white ">';
		manageActivity($user);
		echo '</div>';
		
		echo '<div class="column twelve last" style="border: 2px solid blanchedalmond;background-color:white ">';
		addPhoto($user);
		echo '</div>';
		echo '<div class="column twelve last" style="border: 2px solid blanchedalmond;background-color:white ">';
		addArticle($user);
		echo '</div>';
	}
}
function manageActivity(User $user){
	$userId = $user->getId();
	echo '	<div class="section-title"><h3>活动管理</h3></div>';
	echo '<table class="mytable" style="border: 1px solid lightgrey;margin:0 3%;width:94%">
			<thead style="border: 1px solid lightgrey">
				<th>Id</th>
				<th>活动名称</th>
				<th>参加时间</th>
				<th>报名信息</th>
				<th>修改</th>
				<th>删除</th>
			</thead>
			<tbody id="userCenter_manageActivity" style="border: 1px solid lightgrey">';
	
	$sql = "select * from SJTULib_activity where isDelete != 1 order by id DESC";
	$res = UniversalConnect::doSql($sql);
	if($res){
		foreach ($res as $single){
			$acti =  new Activity($single);
			$id=$acti->getId();
			$title = $acti->getTitle();
			$time = $single['dateAdded'];
			echo "<tr style=\"border: 1px solid lightgrey\">
			<td>$id</td>
			<td><a  href=\"index.php?p=activity&id=$id\">$title</a></td>
			<td>$time</td>";
			if(Activity::isActivityRegistrationable($id)){
				echo "<td><a href='index.php?p=userCenter&oper=searchRegistrationUsers&id=$id'>查看</a></td>";
			}else{
				echo '<td></td>';
			}
			echo"<td><a href='index.php?p=userCenter&oper=edit&id=$id'>修改</a></td>
			<td><a id='delete_activity'>删除</a></td>
			</tr>";
		}
	}
	echo '	</tbody>
	</table>';
	echo '<div class="manageActivity_holder" style="text-align:center"></div>';
}

function addPhoto($user){
	echo '<div class="section-title"><h3>添加图片<h3></div>';
	echo '<div id="upload_photo" style="text-align:center"></div>';
	echo '<div id="success_upload_photo" style="display:none">
			<p style="text-align:left">选择部门
							<select  id="addPhoto_department" name="addPhoto_department" style="display:inline">
							'.showDepartmentSelect().	
							'</select>
					<input id="addPhoto_save" class="plain button green" style="margin-left:30px" type="button" value="保存">
							</p>
			
		 </div>';
}

function addArticle($user){
	echo '	<div class="section-title"><h3>添加活动</h3></div>';
		
	echo '<form id="article-form" class="contact-section" enctype="multipart/form-data" method="post" action="process/addActivity.process.php" style="text-align:center;margin:3%">';
							
	echo 	'<p style="text-align:left">活动标题
							<input id="addArticle_title" name="addArticle_title" type="text" style="display:inline" required>
							</p>';
	echo  '<p style="text-align:left">关键字
							<input id="addArticle_oneWord" name="addArticle_oneWord" type="text" style="display:inline" required>
							</p>';
	echo	'<p style="text-align:left">活动部门
							<select  id="addArticle_department" name="addArticle_department" style="display:inline">
							'.showDepartmentSelect().	
							'</select>
							</p>';
	echo '<p style="text-align:left">最大人数
							<input id="addArticle_maxNum" name="addArticle_maxNum" type="text" style="display:inline" placeholder="不接受报名则不填">
							<span>报名截止时间</span><input id="addArticle_endDate" name="addArticle_endDate" type="text" style="display:inline">
							</p>';
	echo '<p style="text-align:left">海报
							<input id="addArticle_post" name="addArticle_post" type="file" style="display:inline" required>
							</p>';
	echo '<script type="text/plain" id="myEditor" style="width:100%;height:240px;text-align:left">
			</script>';
	echo	'<p style="text-align:center;font-color:red" id= "userCenter_callbackMsg"></p>';
	echo	'<input id="addArticle_submit" class="plain button green" type="submit" value="提交">';
	echo '</form>';
	
	//umeditor
	echo '
			<script>
	var um = UM.getEditor("myEditor"
		);
			</script>';
	

	
}


?>

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

echo '<main role="main">';

/**
 * This part show the introImages
 * */
/*
echo '<div id="intro-wrap">
				<div id="intro" class="preload darken" data-autoplay="5000" data-navigation="true" data-pagination="true" data-transition="fadeUp">';
$limit = 5;		//how many pictures show in the intro
$sql = "select * from SJTULib_activity order by dateAdded DESC limit $limit";
$selectActi = new MutiActivities($sql);
$activities = $selectActi->getActivities();
foreach ($activities as $activity){
	introShow($activity);
}
echo	"</div><!-- intro -->
			</div><!-- intro-wrap -->";
*/
/////////////////End Slider Picture

echo '<div id="main">';

?>
<!-- regist form -->
<section class="row section">
					<div class="row-content buffer even clear-after">
						<div class="section-title"><h3>用户注册</h3></div>	
						<p style="text-align: center">注册以后可以报名参加图书馆学生管理委员会举办的活动、参与图书馆勤工助学</p>
						<div >
							<div id="contact-form" class="contact-section" style="text-align:center">
								<div style="width:100%">
									<span class="pre-input"><i class="icon icon-user"></i></span>
									<input id="username" class="name plain buffer" type="text" name="username" placeholder="用户名" style="width: 300px;display:inline" required="required">
									<div id="usernameTip" style="color:red;display:inline"></div>
								</div>
								
								<div style="width:100%">
									<span class="pre-input"><i class="icon icon-lock"></i></span>
									<input id="password" class="email plain buffer" type="password" name="password" placeholder="密码" style="width: 300px;display:inline" required="required">
									<div id="passwordTip" style="color:red;display:inline"></div>
								</div>
								
								<div style="width:100%">
									<span class="pre-input"><i class="icon icon-lock"></i></span>
									<input id="confirmPassword" class="email plain buffer" type="password" name="confirmPassword" placeholder="确认密码" style="width: 300px;display:inline"  required="required">
									<div id="confirmPasswordTip" style="color:red;display:inline"></div>
								</div>
								
								<div style="width:100%">
									<span class="pre-input"><i class="icon icon-user"></i></span>
									<input id="name" class="email plain buffer" type="text" name="name" placeholder="真实姓名" style="width: 300px;display:inline" required="required">
									<div id="nameTip" style="color:red;display:inline"></div>
								</div>
								
								<div style="width:100%">
									<span class="pre-input"><i class="icon icon-pencil"></i></span>
									<input id="sno" class="email plain buffer" type="text" name="sno" placeholder="学号" style="width: 300px;display:inline" required="required">
									<div id="snoTip" style="color:red;display:inline"></div>
								</div>
								
								<div style="width:100%">
									<span class="pre-input"><i class="icon icon-email"></i></span>
									<input id="email" class="email plain buffer" type="email" name="email" placeholder="邮箱" style="width: 300px;display:inline" required="required">
									<div id="emailTip" style="color:red;display:inline"></div>
								</div>
								
								<div style="width:100%">
									<span class="pre-input"><i class="icon icon-smartphone"></i></span>
									<input id="tel" class="email plain buffer" type="text" name="tel" placeholder="手机号" style="width: 300px;display:inline" required="required">
									<div id="telTip" style="color:red;display:inline"></div>
								</div>
								
								<input id="registForm" class="plain button red" type="button" value="注册" style="">
							</div>	
							<div id="success"></div>
						</div>
					</div>
				</section>

<?php 

echo '</div>';

echo '</main>';
?>


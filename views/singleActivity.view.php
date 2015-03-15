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

#************************************#
#****setting necessary variables ****#
$sql = "select * from SJTULib_activity where id=$id";
$activityArray = UniversalConnect::doSql($sql);
$acti = new Activity($activityArray[0]);

$src = $acti->getPost();			//activity post src
$time = $acti->getDateAdded();		//the date when the activity added
$title = $acti->getTitle();			//title
$tag = $acti->getOneWord();			//tag
//$content = $acti->getContent();		//content
$content = $purifier->purify($acti->getContent());	//content
$Recieve = $acti->getMaxNum();		//receive registration or not
$articleClass = $Recieve?'column nine':'';	//show sidebar if receiving registration
$end = $acti->isEnded();					//whether the activity is end
$isfull = $acti->isFull();					
$dateEnded = $acti->getDateEnded();			//date when the activity endded
$maxNum = $acti->getMaxNum();				// max number 
$nowNum = $acti->getRegistNum();			// present number
$allUsers = $acti->getActivityUser();		// the users participate in the activity

if(is_numeric($id)){
	$preId = preActivityId($id);
	$nextId = nextActivityId($id);
	if(Activity::isActivityExist($preId)){
		$preHref = 'index.php?p=activity&id='.$preId;
	} else {
		$preHref = '#';
	}
	if(Activity::isActivityExist($nextId)){
		$nextHref = 'index.php?p=activity&id='.$nextId;
	} else {
		$nextHref = '#';
	}
	if(Activity::isActivityRegistrationable($id)){
		$homeHref = 'index.php?p=activity&type=volunteer';
	}	else{
		$homeHref = 'index.php?p=activity';
	}
}

#***setting necessary variables ****#
#***********************************#
echo '<main role="main">';
	/*show the intro*/
	echo <<<Intro
	<div id="intro-wrap" class="full-height">
				<div id="intro" class="preload darken more-button">
					<div class="intro-item" style="background-image: url($src);">
					</div>
				</div>
	</div>
Intro;
	/*end the intro*/
	
	/*begin main*/
	echo '<div id="main" class="row">
		<div class="row-content buffer-left buffer-right buffer-bottom clear-after">';
		
		/**
		 * display top bar
		 * */
	echo <<<START
	<div id="post-nav">
		<ul class="clear-after reset plain">
			<li id="prev-items" class="post-nav"><a href="$preHref"><i class="fa fa-chevron-left"></i><span class="label hidden-mobile">Prev</span></a></li>
			<li id="all-items" class="post-nav"><a href="$homeHref"><i class="icon icon-images"></i></a></li>
			<li id="next-items" class="post-nav"><a href="$nextHref"><span class="label hidden-mobile">Next</span><i class="fa fa-chevron-right"></i></a></li>
		</ul>
	</div>
START;
		/**
		 * display the content of the activity
		 * */
	echo '<div class="post-area clear-after">';
	
	echo <<<START
	<article role="main" class="$articleClass">
	<h5 class="meta-post">$tag---<time datetime="$time">$time</time></h5>
	<h1>$title</h1>
	<figure><img src="$src" alt=""></figure>
	<p>$content</p>
START;
	displaySocialMeta($acti);
	echo '</article>';
	
	if($Recieve){
		//display side bar
		
		echo '<aside role="complementary" class="sidebar column three last">';
		
		/**widget 1:	show registration button*/
		echo '<div class="widget">';
		echo '<h4>我要报名</h4>';
		if(!$end){
			echo '<input id="activity" type="hidden" value="'.$acti->getId().'">';
			if(isset($userId)){
				echo '<input id="user" type="hidden" value="'.$userId.'">';
				echo '<input id="LogInRegistration" class="plain button red" type="button" value="我要报名">';
			} else {
				echo '<input class="name plain buffer" style="width:80%;margin-left:20px;margin-top:30px" type="text" name="name" placeholder="姓名" maxlength="30">';
				echo '<input class="name plain buffer" style="width:80%;margin-left:20px;margin-top:30px" type="text" name="sno" placeholder="学号" maxlength="10">';
				echo '<input class="name plain buffer" style="width:80%;margin-left:20px;margin-top:30px" type="text" name="tel" placeholder="手机号" maxlength="11">';
				echo '<input id="UnLogInRegistration" class="plain button red" style="margin-left:20px" type="button" value="我要报名">';
			}
		}else{
			echo '<input id="RegistrationStopped" class="plain button gray" style="margin-left:20px" type="button" value="报名已截止">';
		}
		echo '</div>';
		/**end widget 1*/
		
		
		/**widget 2:	show registration ones**/
		echo '<div class="widget">';
		echo '<h4>报名人数: <font color="red">'.$nowNum.'/'.$maxNum.'</font></h4>';
		echo '<h4>截止时间: <font color="red">'.$dateEnded.'</font></h4>';
		if($nowNum != 0){
			foreach ($allUsers as $usr){
				displayUserIcon($usr);
			}
		}else {
			echo '<p>还没人报名哦,赶快报名吧！</p>';
		}
		echo '<div style="clear:both"></div>';
		echo '</div>';
		/**end widget 2*/
		echo '</aside>';
		//end side bar
	}
	echo '</div>';
		
		
	echo '</div></div>';

echo '</main>';
?>
<?php 



function displaySocialMeta(Activity $acti){
	$title = $acti->getTitle();
	$description = $acti->getIntro(200);
	echo<<<SOCIALSHARE
	
	<div class="">
						<ul class="inline center">
							<li style="margin:0 2%"><a name="xn_share" onclick="shareClick()" type="button-medium"></a></li>
						</ul>
					</div>
	<!-- 人人分享-->
		<script type="text/javascript" src="http://widget.renren.com/js/rrshare.js"></script>
<script type="text/javascript">
	function shareClick() {
		var rrShareParam = {
			resourceUrl : '',	//分享的资源Url
			srcUrl : '',	//分享的资源来源Url,默认为header中的Referer,如果分享失败可以调整此值为resourceUrl试试
			pic : '',		//分享的主题图片Url
			title : '$title',		//分享的标题
			description : '$description'	//分享的详细描述
		};
		rrShareOnclick(rrShareParam);
	}
</script>
	<!--qq空间分享-->
	
SOCIALSHARE;
}

?>
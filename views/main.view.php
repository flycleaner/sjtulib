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

//////////////
/**
 * This part show the introImages
 * */
echo '<div id="intro-wrap">
				<div id="intro" class="preload darken" data-autoplay="5000" data-navigation="true" data-pagination="true" data-transition="fadeUp">';
$limit = 5;		//how many pictures show in the intro
$sql = "select * from SJTULib_activity where isDelete != 1 order by dateAdded DESC limit $limit";
$selectActi = new MutiActivities($sql);
$activities = $selectActi->getActivities();
foreach ($activities as $activity){
	introShow($activity);
}
echo	"</div><!-- intro -->
			</div><!-- intro-wrap -->";

/////////////////End Slider Picture

echo '<div id="main">';
///////////////////////////////////
/**
 * one scentense one day
 * */
oneSentenseOneDay();

/**
 *four column grid style
 *@show the latest activities. 
 **/

echo '<section class="row section">';
echo '<div class="row-content buffer even clear-after">
		<div class="section-title"><h3>最近活动</h3></div>
		<div class="grid-items blog-section masonry-style preload">';
/*****this part is display the activity*****/
$limit2 = $limit+6;
$sql = "select * from SJTULib_activity where isDelete != 1 order by dateAdded DESC limit $limit,$limit2";
$selectActi2 = new MutiActivities($sql);
$secondLatestActi = $selectActi2->getActivities();
displayActivityColumn($secondLatestActi[0],0);
displayActivityColumn($secondLatestActi[1],1);
displayActivityColumn($secondLatestActi[2],1);
displayActivityColumn($secondLatestActi[3],1);
displayActivityColumn($secondLatestActi[4],1);
displayActivityColumn($secondLatestActi[5],0);
/*********/

echo '<div class="shuffle-sizer three"></div>
						</div><!-- grid-items -->
						<div class="more-btn"><a class="button transparent aqua" href="index.php?p=activity">更多</a></div>						
					</div>';
echo '</section>';
/*****End activity display******/
/*photo galaxy*/
displayPhotoGalaxy("照片墙");

/*end photo galaxy*/
/***Begin apartment display breif*****/
echo '<section class="row section">
					<div class="row-content buffer even clear-after">';
/**
 * show all the departments
 * */
$sql = "select * from SJTULib_department";
$departemnts = UniversalConnect::doSql($sql);
$i=1;
foreach ($departemnts as $depart){
	$department = new Department($depart);
	displayDepartmentBrief($department,$i);
	$i++;
}
echo '</div></section>';
/****End apartment display******/
echo '</div>';
echo '</main>';
?>



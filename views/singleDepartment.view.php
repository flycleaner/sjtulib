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

/**
 * display department photo
 * display latest news
 * display brief introduction
 * display department members
 *  
 * */
#***************************#
#***set necessary variables*#
$department = Department::setDepartment($id);
$departmentName = $department->getName();
$departmentDescribe = $department->getDescribe();
$describeIntro = $department->getIntro();
$departmentPhoto = $department->getDepartmentPhoto();

//members
$minister = $department->getDepartmentMembers(0);
$vice_minister = $department->getDepartmentMembers(1);
$staff = $department->getDepartmentMembers(2);

//latest activities
$sql = "select * from SJTULib_activity where idDelete != 1 and departmentId = ".$id.' order by dateAdded DESC limit 3';
$activityArray = UniversalConnect::doSql($sql);
if($activityArray){
	foreach ($activityArray as $single){
		$acti[] = new Activity($single);
}
}

//latest photos
$sql = "select * from SJTULib_photo where departmentId = ".$id.' order by dateAdded DESC limit 6';
$photoArray = UniversalConnect::doSql($sql);
$photoDisplay = '';
if($photoArray){
foreach ($photoArray as $single){
	$photo = new Photo($single);
	$photoDisplay .= '<div><img src="'.$photo->getPhotoPath().'" alt=""></div>';
}
}
#***set necessary variables#
#***************************#

#***************************#
#***display part************#
echo '<main role="main">';

/**
 * 
 * display department photo
 * 
 * */
echo <<<Intro
		<div id="intro-wrap" class="full-height">
					<div id="intro" class="preload darken more-button">
						<div class="intro-item" style="background-image: url($departmentPhoto);">
							<div class="caption">
								<h2>$departmentName</h2>
							</div><!-- caption -->
						</div>
					</div>
		</div>
Intro;

	echo '<div id="main" class="row">';

		/**
		 * display latest news
		 * 
		 * */
		echo '<section class="row section">
					<div class="row-content buffer even clear-after">
						<div class="section-title"><h3>最近活动</h3></div>
						<div class="grid-items blog-section masonry-style preload">';
						if(isset($acti)){
							foreach ($acti as $activity){
								displayActivityColumn($activity,4);
							}}
		echo '				<div class="shuffle-sizer four"></div>	
						</div>	<!--end grid items-->	
					</div>
			  </section>';



		/**
 		* display department describe
 		*			
 		**/	
		echo <<<DSCRIBE
		<section class="row section text-light" style="background-color:#cec8bc">
					<div class="row-content buffer even clear-after">
						<div class="column six push-six last-special">
							<h2>$departmentName</h2>
							<p>$departmentDescribe</p>
						</div>						
						<div class="side-mockup left-mockup animation">
							<div class="slider ipad-slider white" data-autoplay="3000">
								<figure>
									$photoDisplay
								</figure>
							</div>
						</div>						
					</div>	
				</section>	
DSCRIBE;

		/**
		 * display department members
		 * */
		echo '
		<section class="row section" style="background-color:floralwhite">
					<div class="row-content buffer even clear-after">';
					echo	'<div class="section-title"><h3 style="font-size:200%">部门成员</h3></div>';
					
					echo '<div style="margin-bottom:30px;border:2px solid rgb(220,220,220)">
							<div style="width:60px;margin:41px;float:left;clear:none">部长</div>';
					if($minister){
						foreach ($minister as $single){
							displayUserIcon($single);
						}
					}
					echo '<div style="clear:both"></div>
							</div>';
					
					echo '<div style="margin-bottom:30px;border: 2px solid rgb(220,220,220)">
							<div style="width:60px;margin:41px;float:left;clear:none">副部长</div>';
					if($vice_minister){
						foreach ($vice_minister as $single){
							displayUserIcon($single);
						}
					}
					echo '<div style="clear:both"></div></div>';
					
					echo '<div style="margin-bottom:30px;border: 2px solid rgb(220,220,220)">
							<div style="width:60px;margin:41px;float:left;clear:none">部员</div>';
					if($staff){
						foreach ($staff as $single){
							displayUserIcon($single);
						}
					}
					echo '<div style="clear:both"></div>
							</div>';
						
					echo '</div>';
		echo '		</div>	
		</section>';

		
		
	echo '</div>';   //end main div


echo '</main>';

?>
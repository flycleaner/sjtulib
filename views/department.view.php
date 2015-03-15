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
 * display each single department breif describe
 *
 * */
#***************************#
#***set necessary variables*#
$allDepartmentPhoto = 'img/allDepartmentPhoto.jpg';

//all the department
$sql = "select * from SJTULib_department";
$resArray = UniversalConnect::doSql($sql);
$department = array();
foreach ($resArray as $single){
	$department[] = new Department($single);
}
#***set necessary variables#
#***************************#

/**
 * display Intro
 * */
echo <<<Intro
		<div id="intro-wrap" class="full-height">
					<div id="intro" class="preload darken more-button">
						<div class="intro-item" style="background-image: url($allDepartmentPhoto);">
							<div class="caption">
								<h2>部门风采</h2>
							</div><!-- caption -->
						</div>
					</div>
		</div>
Intro;

echo '<div id="main" class="row">';


/**
 * display department describe
 *
 **/
foreach ($department as $singleDepart){
	$departmentName = $singleDepart->getName();
	$departmentDescribe = $singleDepart->getDescribe();
	$id = $singleDepart->getId();
	$departmentHref = './index.php?p=department&id='.$id;
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
	$sectionId = "department_".$id;
	$nextId = "#department_".($id+1);
echo <<<DSCRIBE
		<section class="row section text-light full-height more-button" style="background-color:#cec8bc" id="$sectionId">
					<div class="row-content buffer even clear-after">
						<div class="column six push-six last-special">
							<h2>$departmentName</h2>
							<p>$departmentDescribe</p>
							<a class="button transparent white" href="$departmentHref">点击进入...</a>
						</div>
						<div class="side-mockup left-mockup animation">
							<div class="slider ipad-slider white" data-autoplay="3000">
								<figure>
									$photoDisplay
								</figure>
							</div>
						</div>
					</div>
					<div class="more" style="text-align:center;font-size:200%"><a href="$nextId"><i class="icon icon-arrow-down"></i></a></div>
				</section>
DSCRIBE;
}

echo '</div>';   //end main div


echo '</main>';
?>
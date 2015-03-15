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

$html_root_path = BASE_URL;
$introImage = "img/photoGalaxyIntro.jpg";
$introTitle = "照片墙";
$perPage = 13;
/*catalogue*/
$sql = "select * from SJTULib_catalogue";
$catArray = UniversalConnect::doSql($sql);
/*photos to display*/
$totalPhoto = Photo::getPhotoNum();
$totalPage = intval($totalPhoto/$perPage);
if($id != null){
	if($id > $totalPage){
		$id = $totalPage;
	}
	$startlimit = $id * $perPage;
} else {
	$id = 0;
	$startlimit = 0;
}
$sql = "select * from SJTULib_photo order by dateAdded DESC limit $startlimit, $perPage";
$photoArray = UniversalConnect::doSql($sql);	

echo '<main role="main">';
/*show the intro*/
echo <<<Intro
	<div id="intro-wrap" data-height="16.667">
				<div id="intro" class="preload darken">
					<div class="intro-item" style="background-image: url($introImage);">
							<div class="caption">
								<h2>$introTitle</h2>
							</div><!-- caption -->	
					</div>
				</div>
	</div>
Intro;
/*end the intro*/

/*begin show the photos*/
echo '<div id="main" class="row">
		<div class="row-content buffer clear-after">';
		/*filter*/
		/*echo '<ul class="inline cats filter-options">';
		foreach ($catArray as $cat){
			echo '<li data-group="'.$cat['id'].'">'.$cat['describe'].'</li>';
		}*
		echo '</ul>';
		/*end filter*/
		echo '<div class="grid-items portfolio-section preload lightbox">';
			  displaySinglePagePhoto($photoArray);
		echo '<div class="shuffle-sizer three"></div>';
		echo '</div>';
		
		/*pagination*/
		echo '<div id="pagination">';	
		echo '<ul class="clear-after reset plain">';
		 if($id <= 0){
		 	
		 	echo '<li id="older" class="pagination-nav"><div class="button transparent gray"><i class="fa fa-chevron-left"></i><span class="label">上一页</span></div></li>'; 
		 } else {
		 	$olderId = $id-1;
		 	echo '<li id="older" class="pagination-nav"><a href="index.php?p=photoGalaxy&id='.$olderId.'" class="button transparent aqua"><i class="fa fa-chevron-left"></i><span class="label">上一页</span></a></li>';
		 }
		 
		 if($id < $totalPage){
		 	$newId = $id + 1;
		 	echo '<li id="newer" class="pagination-nav"><a href="index.php?p=photoGalaxy&id='.$newId.'" class="button transparent aqua"><span class="label">下一页</span><i class="fa fa-chevron-right"></i></a></li>';  
		 
		 }else {
		 	echo '<li id="newer" class="pagination-nav"><div class="button transparent gray"><span class="label">下一页</span><i class="fa fa-chevron-right"></i></div></li>';  
		 			 }			 
		echo '</ul></div>';   //pagination
		
echo '  </div><!-- row-content -->
	  </div><!-- row -->';
/*end show photo*/
echo '</main>';
?>

<?php 
function displaySinglePagePhoto(array $photoArray){
	$htmlRootPath = BASE_URL;
	$i = 0;
	foreach($photoArray as $singlePhotoArray){
		$photo = new Photo($singlePhotoArray);
		$catId = $photo->getCatId();
		$imgUrl = htmlentities($photo->getPhotoPath(),ENT_QUOTES,'UTF-8');
		$imgTitle = htmlentities($photo->getTitle(),ENT_QUOTES,'UTF-8');
		$imgDescribe = htmlentities($photo->getDescribe(),ENT_QUOTES,'UTF-8');
		$departmentId = htmlentities($photo->getDepartmentId(),ENT_QUOTES,'UTF-8');
		$articleClass = photoClass($i);
		$i++;
		echo <<<ARTICLE
			<article class="$articleClass" >
							<figure><img class="photo" src="$imgUrl" alt=""></figure>
							<a class="overlay" href="$imgUrl">
								<div class="overlay-content">
									<div class="post-type"><i class="icon icon-search"></i></div>
									<h2>$imgTitle</h2>
									<p>$imgDescribe</p>
								</div><!-- overlay-content -->
							</a><!-- overlay -->
						</article>
ARTICLE;
	}
}

function photoClass($i){
	if($i != 1){
		return 'item column three';
	} else {
		return 'item column six';
	}
}
?>
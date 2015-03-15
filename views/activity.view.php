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

#***************************#
#**set necessary variables**#
if($type == 'volunteer'){
	$sql = "select * from SJTULib_activity where maxNum > 0 and isDelete != 1 order by id DESC ";
}else{
	$sql = "select * from SJTULib_activity where isDelete != 1 order by id DESC";
}
$allActivityArray = UniversalConnect::doSql($sql);
$allActivity = array();
if($allActivityArray){
	foreach ($allActivityArray as $single){
		$allActivity[] = new Activity($single);
	}
}

#**set necessary variables**#
#***************************#

echo '<main role="main">';

//////////////
/**
 * This part show the introImages
 * */
echo '<div id="intro-wrap">
				<div id="intro" class="preload darken" data-autoplay="5000" data-navigation="true" data-pagination="true" data-transition="fadeUp">';
$limit = 5;		//how many pictures show in the intro
$i = 0;
foreach ($allActivity as $acti){
	if($i<$limit){
		introShow($acti);
	}else{
		break;
	}
	$i++;
}

echo	"</div><!-- intro -->
			</div><!-- intro-wrap -->";

/////////////////End Slider Picture

echo '<div id="main">
		<div class="row-content buffer-left buffer-right buffer-bottom clear-after">
		';
foreach ($allActivity as $acti){
	$actiPost = $acti->getPost();
	$actiHref = 'index.php?p=activity&id='.$acti->getId();
	$actiTitle = $acti->getTitle();
	$tag = $acti->getOneWord();
	$addedTime = $acti->getDateAdded();
	$intro = $acti->getIntro(200);
	echo <<<ARTICLE
	<article class="clear-after">
							<div class="column three">
								<figure><img src="$actiPost" alt=""></figure>
							</div><!-- column three -->
							<div class="column nine last">
								<h2><a href="$actiHref">$actiTitle</a></h2>
								<h5 class="meta-post"> $tag --- <time datetime="$addedTime">$addedTime</time></h5>
								<p>$intro</p>
							</div><!-- column nine -->
						</article>
ARTICLE;
}
echo '	</div>
	  </div>';
?>

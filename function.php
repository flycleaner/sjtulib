<?php
///////////////////////////////////////////
/**
 * this function is used to encryption the password
 * */
function myMd5($pwd){
	$SALT = '';	//you can't know it
	$first = md5(md5($pwd).$SALT);
	return $first;
}

/*加密*/
//加密解密在学校服务器无法使用！
//salt was hidden...
function encrypt($plain_text,$key='') {
	$salt = '';
	$plain_text = trim($plain_text);
	$plain_text .=$salt;
	$iv = substr(myMd5($key), 0,mcrypt_get_iv_size (MCRYPT_CAST_256,MCRYPT_MODE_CFB));
	$c_t = mcrypt_cfb (MCRYPT_CAST_256, $key, $plain_text, MCRYPT_ENCRYPT, $iv);
	return trim(chop(base64_encode($c_t)));
}
/*解密*/
function decrypt($c_t,$key='') {
	$salt = '';
	$c_t = trim(chop(base64_decode($c_t)));
	$iv = substr(myMd5($key), 0,mcrypt_get_iv_size (MCRYPT_CAST_256,MCRYPT_MODE_CFB));
	$p_t = mcrypt_cfb (MCRYPT_CAST_256, $key, $c_t, MCRYPT_DECRYPT, $iv);
	$textWithSalt = trim(chop($p_t));
	$array = explode('&', $textWithSalt);
	return $array[0];
}

/**
 * @return 某一天值周几的班
 * @param
 *  string $date
 * 	format: Y-m-d
 * */
function getDayOfDate($date){
	$datePreg = '/^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])$/';		//正则表达式检查输入，不符合要求则返回false
	$match = preg_match($datePreg, $date);
	if(!$match){
		return false;
	}
	$sql = "select * from SJTULib_qgzx_setDayOfDate where date='$date'";
	$res = UniversalConnect::doSql($sql);
	if($res){
		return $res[0]['day'];
	}
	$day = date('w',strtotime($date));
	return $day;
}

/**
 * @return 某天工资倍数
 * @param 
 * date string
 * 日期 格式 Y-m-d
 * */
function getSalaryRate($date){
	$datePreg = '/^\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])$/';
	$match = preg_match($datePreg, $date);
	if($match){
		$sql = "select * from SJTULib_qgzx_setSalaryRate where date='$date'";
		$res = UniversalConnect::doSql($sql);
		if($res){
			return $res[0]['rate'];
		}else{
			return 1;
		}
	}
	return 1;
}
/**
 * generate daily code
 *@codes are used to  sign in qgzx jobs
 * */
function qgzx_generateDailyCodes(){
	$time = date('Y-m-d');
	$sql = sprintf("select * from SJTULib_qgzx_dailyCodes where to_days(dateAdded)=to_days('%s')",$time);	//已经写入表中
	$res = UniversalConnect::doSql($sql);
	if($res){
		return true;
	}
	
	$sql = 'delete from SJTULib_qgzx_dailyCodes';	
	$res = UniversalConnect::doSql($sql);	//清空表
	$salt = '';	//salt was hidden
	$str = date('Y-m-d H:i:s').$salt;
	for($i=0;$i<200;$i++){	//generate 200 codes every day
		$code = substr(myMd5($str.$i),0,6);	//截取前6位
		$sql =sprintf("insert into SJTULib_qgzx_dailyCodes(code) values('%s')",$code);
		$res = UniversalConnect::doSql($sql);
	}
	return true;
}

/**
 * 返回时间字符串 ，如 generateJobTimes(8.5,3)将返回 $str = 8:30-11:30
 * */
function generateJobTimes($begintime,$hours){
	$begintime = floatval($begintime);
	$hours = floatval($hours);
	$endtime = $begintime + $hours;
	$result = '';
	$begintime_hour = intval($begintime);
	$begintime_minute = round(($begintime - $begintime_hour)*60);
	$endtime_hour = intval($endtime);
	$endtime_minute = round(($endtime-$endtime_hour)*60);
	$begintime_minute = ($begintime_minute<10)?('0'.$begintime_minute):$begintime_minute;
	$endtime_minute = ($endtime_minute<10)?('0'.$endtime_minute):$endtime_minute;
	$result .= $begintime_hour.':'.$begintime_minute.'-'.$endtime_hour.':'.$endtime_minute;
	return $result;
}

/**
 * 处理即将插入sql语句的变量，如果 mgic_quotes = on,将不做处理，否则将转义特殊字符
 *@所有即将插入sql语句的变量必须经过此函数处理  !important
 * */
function mysql_string($string){
	if(!get_magic_quotes_gpc()){
		return mysql_real_escape_string($string);
	}else{
		return $string;
	}
}

/**
 * 返回访问者Ip地址
 * */
function real_ip()
{
	static $realip = NULL;

	if ($realip !== NULL)
	{
		return $realip;
	}

	if (isset($_SERVER))
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
			foreach ($arr AS $ip)
			{
				$ip = trim($ip);

				if ($ip != 'unknown')
				{
					$realip = $ip;

					break;
				}
			}
		}
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else
		{
			if (isset($_SERVER['REMOTE_ADDR']))
			{
				$realip = $_SERVER['REMOTE_ADDR'];
			}
			else
			{
				$realip = '0.0.0.0';
			}
		}
	}
	else
	{
		if (getenv('HTTP_X_FORWARDED_FOR'))
		{
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_CLIENT_IP'))
		{
			$realip = getenv('HTTP_CLIENT_IP');
		}
		else
		{
			$realip = getenv('REMOTE_ADDR');
		}
	}
	$onlineip = null;
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

	return $realip;
}
/**
 * display the option of department, the department Id which is $id will be selected
 * use as below:
 * 
 * php code:
 * 	echo '<option id="xxx" class = "xxx">';
 * 	showDepartmentSelect($id);
 * 	echo '</option>';
 * */
function showDepartmentSelect($id = 0){
	$sql = "select * from SJTULib_department";
	$res = UniversalConnect::doSql($sql);
	$str="";
	foreach ($res as $single){
		$departmentId = $single['id'];
		$departmentName = $single['name'];
		if($departmentId != $id){
			$str .= "<option value='$departmentId'>$departmentName </option>";
		}else{
			$str .= "<option value='$departmentId' selected>$departmentName </option>";
		}
	}
	return $str;
}

/**
 * 
 * */
function showQgzxPlaceSelect($id = 0){
	$sql = "select * from SJTULib_qgzx_place";
	$res = UniversalConnect::doSql($sql);
	$str = '';
	foreach ($res as $place){
		$placeId = $place['id'];
		$placeName = $place['content'];
		if($placeId != $id){
			$str .= "<option value='$placeId'>$placeName </option>";
		}else{
			$str .= "<option value='$placeId' selected>$placeName </option>";
		}
	}
	return $str;
}

/* return the id of the previous activity*/
function preActivityId($id){
	if(!is_numeric($id) ||$id <= 1){
		return null;
	}
	$preId = $id - 1;
	$sql = "select * from SJTULib_activity where id='$preId'";
	$res = UniversalConnect::doSql($sql);
	if($res){
		return $preId;
	}
	
	$sql = "select id from SJTULib_activity";
	$Ids = UniversalConnect::doSql($sql);
	$length = count($Ids);
	for($i=$length-1;$i>0;$i--){
		if($Ids[$i]['id'] == $id){
			return $Ids[$i-1]['id'];
		}
	}
	return null;
}

/*return the id of the next activity*/
function nextActivityId($id){
	if(!is_numeric($id) ||$id < 1){
		return null;
	}
	$nextId = $id + 1;
	$sql = "select * from SJTULib_activity where id='$nextId'";
	$res = UniversalConnect::doSql($sql);
	if($res){
		return $nextId;
	}
	
	$sql = "select id from SJTULib_activity";
	$Ids = UniversalConnect::doSql($sql);
	$length = count($Ids);
	if($Ids[$length-1]['id'] == $id){
		return null;
	}
	for($i=$length-2;$i>=0;$i--){
		if($Ids[$i]['id'] == $id){
			return $Ids[$i+1]['id'];
		}
	}
	return null;
}
/**
 * This part is for web show
 * */
function introShow(Activity $activity){
	$imgUrl = $activity->getPost();
	$captionHead = $activity->getTitle();
	$caption = $activity->getIntro(30);
	$href = 'index.php?p=activity&id='.$activity->getId();
	echo <<<START
	<div class="intro-item" style="background-image: url($imgUrl);">
		<div class="caption">
			<h2>$captionHead</h2>
			<p>$caption</p>
			<a class="button white transparent" href="$href">查看详情</a>
		</div><!-- caption -->
			
		<!--<div class="photocaption">
		<h4>摄影师 <a href="#">谁谁谁</a></h4>
		</div><!-- photocaption -->
	</div>
	
START;
}

/*this is used on the server*/
function oneSentenseOneDay(){
	$day = date('d');
	$monthYear = date('M Y');
	$date = date('Y-m-d');
	
	$initial = strtotime('2015-2-20');	//vol.866
	$today = strtotime(date('Y-m-d'));
	$days = round(($today-$initial)/3600/24);
	$vol = 866 + $days;
	$url = "http://wufazhuce.com/one/vol.".$vol;
	
	$sql = sprintf("select * from SJTULib_sentenseEveryDay where to_days(dateAdded)= to_days('%s')",$date);
	$res = UniversalConnect::doSql($sql);
	if($res){
		$sentense = $res[0]['content'];
	}else{
		$cloudUrl = "http://1.sjtulibrary.sinaapp.com/generateOnDuty.php";
		$contents = file_get_contents($cloudUrl);
		$sentense = $contents;
	}
	echo <<<START
	<section class="row section call-to-action">
	<div class="row-content buffer even">
	<div class="column nine">
	<p style="text-align:center;vertical-align:center">$sentense</p>
	</div>
	<div class="column three last" style="color:#01aef0;width:120px">
	<a href="$url" target="new" style="color:#01aef0">
	<p style="font-size:56px;font-weight:600;height:56px;line-height:56px;margin:0;text-align:center">$day</p>
	<p style="margin:0;font-size:14px;text-align:center">$monthYear</p>
	</a>
	</div>
	<!--<a class="button transparent aqua" target="new" href="$url">ONE 一个</a>-->
	</div>
	</section>
START;
}
/*this is used localhost
 *the server don't support curl lib. so this function won't work
 */
/*
function oneSentenseOneDay(){
	$day = date('d');
	$monthYear = date('M Y');
	$date = date('Y-m-d');
	
	$initial = strtotime('2015-2-20');	//vol.866
	$today = strtotime(date('Y-m-d'));
	$days = round(($today-$initial)/3600/24);
	$vol = 866 + $days;
	$url = "http://wufazhuce.com/one/vol.".$vol;
	
	$sql = sprintf("select * from SJTULib_sentenseEveryDay where to_days(dateAdded)= to_days('%s')",$date);
	$res = UniversalConnect::doSql($sql);
	$flag = 0;
	if($res && $res[0]['content'] != ''){
		$flag =1;
		$sentense = $res[0]['content'];
	}elseif($res[0]['content'] == ''){
		$sql = "delete from SJTULib_sentenseEveryDay where to_days(dateAdded)=to_days('$date')";
		$res = UniversalConnect::doSql($sql);
	}
	
	if(!$flag){
	$initial = strtotime('2015-2-20');	//vol.866
	$today = strtotime(date('Y-m-d'));
	$days = round(($today-$initial)/3600/24);
	$vol = 866 + $days;
	$url = "http://wufazhuce.com/one/vol.".$vol;
	//echo $url;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 设为TRUE让结果不要直接输出
	$content = curl_exec($curl);
	//echo $result;
	$matches = array();
	$result = preg_match('/<div class="one-cita">(.*?)</is', $content,$matches);
	$sentense = $matches[1];
	$sql = sprintf("insert into SJTULib_sentenseEveryDay(content) values('%s')",mysql_string($sentense));
	$res = UniversalConnect::doSql($sql);
	}
	echo <<<START
	<section class="row section call-to-action">
	<div class="row-content buffer even">
	<div class="column nine">
	<p style="text-align:center;vertical-align:center">$sentense</p>
	</div>
	<div class="column three last" style="color:#01aef0;width:120px">
	<a href="$url" target="new" style="color:#01aef0">
	<p style="font-size:56px;font-weight:600;height:56px;line-height:56px;margin:0;text-align:center">$day</p>
	<p style="margin:0;font-size:14px;text-align:center">$monthYear</p>
	</a>
	</div>
	<!--<a class="button transparent aqua" target="new" href="$url">ONE 一个</a>-->
	</div>
	</section>
START;
}
*/
function displayActivityColumn(Activity $activity,$type=0){
	$href = 'index.php?p=activity&id='.$activity->getId();
	$imgSrc = $activity->getPost();
	$intro = $activity->getIntro(60);
	$oneWord = $activity->getOneWord();
	$title = $activity->getTitle();
	switch ($type){
		case 0: 
			$articleClass = 'item column six';
			break;
		case 1:
			$articleClass = 'item column three';
			break;
		case 4:
			$articleClass = 'item column four';
			break;
		case 5:
			$articleClass = 'item column five';
			break;
		default:
			$articleClass = 'item column six';
			break;
	}
	echo <<<START
	<article class="$articleClass">
								<a href="$href">
									<figure><img src="$imgSrc" alt=""><span class="blog-overlay"><i class="icon icon-doc"></i></span></figure>
									<div class="blog-excerpt">
										<div class="blog-excerpt-inner">
											<h5 class="meta-post">$oneWord</h5>
											<h2>$title</h2>
											<p>$intro</p>
										</div><!-- blog-excerpt -->
									</div><!-- blog-excerpt-inner -->	
								</a>
							</article>
START;
}

function displayPhotoGalaxy($title){
	
	echo "<section class='row section text-light' style='background-color:#FF8CB9'>
	<div class='row-content buffer even clear-after'>
		<div class='section-title'><h3>$title</h3></div>
		<div class='grid-items portfolio-section preload lightbox'>";
			
	
	$sql = "select * from SJTULib_photo order by dateAdded DESC limit 4";
	$photoArray = UniversalConnect::doSql($sql);
	foreach ($photoArray as $photo){
		$photoclass = new Photo($photo);
		displaySinglePhoto($photoclass);
	}
	
	echo '	<div class="shuffle-sizer three"></div>
	</div><!-- grid-items -->
			<div class="more-btn"><a class="button transparent white" href="index.php?p=photoGalaxy">More Photos</a></div>
		</div>
			
		</section>';
}

function displaySinglePhoto(Photo $photo){
	$src = $photo->getPhotoPath();
	$title = $photo->getTitle();
	echo <<<START
	<article class="item column three">
	<figure><img src="$src" alt=""></figure>
	<a class="overlay" href="$src">
	<div class="overlay-content">
	<div class="post-type"><i class="icon icon-search"></i></div>
	<h2>$title</h2>
	<p></p>
	</div><!-- overlay-content -->
	</a><!-- overlay -->
	</article>
START;
}

function displayDepartmentBrief(Department $department,$i){
	$href = 'index.php?p=department&id='.$department->getId();
	$src = $department->getDepartmentPhoto();
	$name = $department->getName();
	$describe = $department->getIntro();
	if($i%3 != 0){
		$class = 'column four';
	} else{
		$class = 'column four last';
	}
	echo <<<START
	<a href = "$href">
	<div class="$class">
		<div class="small-icon red"><img src="$src" alt=""></div>
		<div class="small-icon-text clear-after">
			<h4>$name</h4>
			<p class="text-xs">$describe</p>
		</div>
	</div>
	</a>
START;
}


function displayUserIcon(User $usr){
	$headPhoto = $usr->getHeadPhoto();
	$name = $usr->getName();
	echo '<div style = "float:left;clear:none;margin:15px">';
	echo '<figure><div class="small-icon red">';
	echo "<img src =\"$headPhoto\"></div>";
	echo "<div style='font-size:50%;text-align:center;margin:0;width:43px'>$name</div>";
	echo '</figure>';
					echo '</div>';
}

/**
 * activity.view.php
 * */


/**
 * photoGalaxy.view.php
 * */




////////////////////////////////////////


?>
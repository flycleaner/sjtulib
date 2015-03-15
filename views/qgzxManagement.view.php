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
if(!$user->isAdmin(4)){
	echo '<div>对不起，您不是勤助管理员</div>';
	echo '<script>history.go(-1)</script>';
	exit();
}
?>

<!-- jqGrid-->
 <link type="text/css" rel="stylesheet" href="plugins/jqGrid/css/jquery-ui.theme.min.css">
<link type="text/css" rel="stylesheet" href="plugins/jqGrid/css/ui.jqgrid.css">
 <script type="text/javascript" src="plugins/jqGrid/js/i18n/grid.locale-cn.js"/></script>
 <script type="text/javascript" src="plugins/jqGrid/js/jquery.jqGrid.min.js"/></script>

 
<main role="main" >
	<div id="main">
	
		<!-- search sign information 签到信息 -->
			 	<section class="row section" style="background-color:;border: 2px solid lightgrey;padding:1%;">
				<div class="row-content buffer even clear-after">
			 		<div class="section-title"><h3>签到信息</h3></div>
			 		
			 		<!-- 未来签到 -->
			 		<div class="column twelve last " style="border: 2px solid lightgrey;padding:1%">
					<div class="section-title"><h5>未来签到</h5></div>
					<table class="mytable" style="font-size: 70%">
					<thead><th>姓名</th> <th>学号</th><th>手机号</th> <th>地点</th> <th>时间</th></thead>
					<tbody id="qgzxManagement_unsign_jpage">
					<?php generateDifUserOfDate(0);?>
					</tbody>
					</table>
					<div class="qgzxManagement_unsign_holder" style="text-align:center"></div>
 					</div>
 					
 					<!-- 已签到 -->
 					<div class="column twelve last " style="border: 2px solid lightgrey;padding:1%">
					<div class="section-title"><h5>已签到</h5></div>
					<table class="mytable" style="font-size: 70%">
					<thead><th>姓名</th><th>学号</th> <th>手机号</th> <th>地点</th> <th>时间</th></thead>
					<tbody id="qgzxManagement_sign_jpage">
					<?php generateDifUserOfDate(1);?>
					</tbody>
					</table>
					<div class="qgzxManagement_sign_holder" style="text-align:center"></div>
 					</div>
			 	</div>
			 	</section>
			 	<!-- 发放验证码 -->
			 	<section class="row section" style="background-color:;border: 2px solid lightgrey;padding:1%;">
				<div class="row-content buffer even clear-after">
				<div class="column twelve last " style="border: 2px solid lightgrey;padding:1%">
					<div class="section-title"><h5>验证码</h5></div>
					<table class="mytable">
					<?php generateDailyCodeTable();?>
					</table>
				</div>
				</div>
				</section>
		<!-- 奖惩 -->
			 	<section class="row section" style="background-color:;border: 2px solid lightgrey;padding:1%;">
				<div class="row-content buffer even clear-after">
			 		<div class="section-title"><h3>奖惩</h3></div>
			 		
			 		<!-- 添加奖惩 -->
			 		<div class="column four" style="border: 2px solid lightgrey;padding:1%">
					<div class="section-title"><h5>添加奖惩</h5></div>
					<form class="contact-section" method="post" action="process/qgzxManagement_addPunishment.process.php" style="text-align: center">
					<div style="font-size: 60%;color:red">
					Tips: 奖励为负，处罚为正。旷班扣分40，写 -40
					</div>
					<div style="width:100%">
									<span class="pre-input"><i class="icon icon-pencil"></i></span>
									<input id="qgzxManagement_scoreSno" class="email plain buffer" type="text" name="qgzxManagement_scoreSno" placeholder="姓名/学号/手机号" style="width: 300px;display:inline" required="required">
								</div>
					<div style="width:100%">
									<span class="pre-input"><i class="icon icon-star"></i></span>
									<input id="qgzxManagement_scores" class="email plain buffer" type="text" name="qgzxManagement_scores" placeholder="扣除分 奖励为负，处罚为正" style="width: 300px;display:inline" required="required">
								</div>
					<div style="width:100%">
									<span class="pre-input"><i class="icon icon-pencil"></i></span>
									<input id="qgzxManagement_scoreReason" class="email plain buffer" type="text" name="qgzxManagement_scoreReason" placeholder="原因" style="width: 300px;display:inline" required="required">
								</div>
					<div style="width:100%">
									<span class="pre-input"><i class="icon icon-calendar"></i></span>
									<input id="datetime" class="email plain buffer" type="text" name="qgzxManagement_scoreTime" placeholder="时间" style="width: 300px;display:inline" required="required">
								</div>
								<input id="qgzxManagement_scoreSubmit" class="button green" type="submit" style="float: center" value="提交">
					</form>
 					</div>
 					
 					<!-- 查询 -->
 					<div class="column eight last " style="border: 2px solid lightgrey;padding:1%">
					<div class="section-title"><h5>奖惩查询</h5></div>
				<div style="text-align: center">
					<select id="qgzxManagement_punishmentSearch_year" name="qgzxManagement_punishmentSearch_year">
					<?php for($i=2014;$i<2021;$i++){
						$year = date('Y');
						if($i == $year){
							echo '<option  selected="selected" value="'.$i.'">'.$i.'年</option>';
						} else echo '<option value="'.$i.'">'.$i.'年</option>';
					}?>
					</select>
					<select id="qgzxManagement_punishmentSearch_month" name="qgzxManagement_punishmentSearch_month">
					<?php for($i=1;$i<13;$i++){
						$month = date('n');
						if($i == $month){
							echo '<option  selected="selected" value="'.$i.'">'.$i.'月</option>';
						} else echo '<option value="'.$i.'">'.$i.'月</option>';
					}?>
					</select>
					<input id="qgzxManagement_punishmentSearch_Submit" class="button green" style="height: 20px" type="button" value="查询">
			 	
			 		<div id="qgzxManagement_punishmentSearchContainer" style="display:none">
			 		<table class='mytable' style="font-size: 70%">
			 		<thead><th>姓名</th><th>日期</th><th>扣除分</th><th>原因</th><th>删除</th></thead>
			 		<tbody id="qgzxManagement_punishmentSearch_jpage"></tbody>
			 		</table>
			 		<div class="qgzxManagement_punishmentSearch_holder"></div>
			 		</div>
			 		
			 	</div>
			 		</div>
			 	</section>
	
	
	
	 	<section class="row section">
			<div class="row-content buffer even clear-after">
				<!-- set place -->
				<div class="column four " style="border: 2px solid lightgrey;padding:1%">
					<table id="qgzx_addplace"></table>
					<div id="qgzx_navi_addplace"></div>
 				</div>
 				
 				<!-- set job -->
 				<div class="column eight last" style="border: 2px solid lightgrey;padding:1%">
 					<div style="color: black;font-size:60%">tips: 开始时间和时长写数字。如开始时间为8：00，填8；8：30，填8.5。<p>地点请填写具体地点（具体到<span style="color: red">阅览室</span>）</p></div>
 					<table id="qgzx_addjob"></table>
 					<div id="qgzx_navi_addjob"></div>
 				</div>
 			</section>
 				<!-- arrange jobs -->
			<section class="row section" style="background-color:;border: 2px solid lightgrey;padding:1%;">
				<div class="row-content buffer even clear-after">
			 		<div class="section-title"><h3>值班安排</h3></div>
			 		<div id="arrangeJobs_info" class="section-title"><h3></h3></div>
			 		<table id="arrangeJobs_table" style="font-size:70%">
			 		<thead><th>地点</th> <th>时间</th> <th>周一</th> <th>周二</th> <th>周三</th> <th>周四</th> <th>周五</th> <th>周六</th> <th>周日</th></thead>
			 		<?php generateJobTbody();?>
			 		</table>
			 	</div>
			 	</section>
		 </div>
		</div>	
</main>



<script>
//add place scripts
var lastsel;
jQuery("#qgzx_addplace").jqGrid({
   	url:'process/qgzxManagement_addPlace.process.php?q=2',
	datatype: "json",
   	colNames:['ID','地点'],
   	colModel:[
   		{name:'id',index:'id',width:"75px"},
   		{name:'content',index:'content',width:'220px', editable:true},
   	],
   	rowNum:8,
   	rowList:[10,20,30],
   	pager: '#qgzx_navi_addplace',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	/*onSelectRow: function(id){
		if(id && id!==lastsel){
			jQuery('#qgzx_addplace').jqGrid('restoreRow',lastsel);
			jQuery('#qgzx_addplace').jqGrid('editRow',id,true);
			lastsel=id;
		}
	},*/
	editurl: "process/qgzxManagement_addPlace.process.php",
	caption: "添加地点",
	height: "100%"
});
jQuery("#qgzx_addplace").jqGrid('navGrid',"#qgzx_navi_addplace",{edit:true,add:true,del:true,search:false});
//jQuery("#qgzx_addplace").jqGrid('inlineNav',"#qgzx_navi_addplace");

//add job scripts
var lastsel;
jQuery("#qgzx_addjob").jqGrid({
   	url:'process/qgzxManagement_addJob.process.php?q=2',
	datatype: "json",
   	colNames:['ID','地点','开始时间','时长','最大人数'],
   	colModel:[
   		{name:'id',index:'id', width:'100'},
   		{name:'placeId',index:'placeId', width:'200', editable:true,edittype:'select',editoptions:{value:
		<?php 
			$sql = 'select * from SJTULib_qgzx_place';
			$places = UniversalConnect::doSql($sql);
			$length = count($places);
			$str = '';
			for($i=0;$i < $length-1; $i++){
				$str .= $places[$i]['id'].':'.$places[$i]['content'].';';
			}
			$str =  $str.$places[$length-1]['id'].':'.$places[$length-1]['content'];
			echo "'$str'";
		?>}},
   		{name:'begintime',index:'begintime', width:150, editable:true},
   		{name:'hours',index:'hours', width:50, editable:true},
   		{name:'maxnum',index:'maxnum', width:60, editable:true},
   	],
   	rowNum:8,
   	rowList:[6,12,20],
   	pager: '#qgzx_navi_addjob',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	/*onSelectRow: function(id){
		if(id && id!==lastsel){
			jQuery('#place').jqGrid('restoreRow',lastsel);
			jQuery('#place').jqGrid('editRow',id,true);
			lastsel=id;
		}
	},*/
	editurl: "process/qgzxManagement_addJob.process.php",
	caption: "添加工作",
	height: "100%"
});
jQuery("#qgzx_addjob").jqGrid('navGrid',"#qgzx_navi_addjob",{edit:false,add:false,del:true,search:false});
jQuery("#qgzx_addjob").jqGrid('inlineNav',"#qgzx_navi_addjob");

//jpages
$("div.qgzxManagement_unsign_holder").jPages({  //分页
    containerID : "qgzxManagement_unsign_jpage",  
    previous : "上一页",  
    next : "下一页",  
    perPage : 5,  
    delay : 100  
  });
$("div.qgzxManagement_sign_holder").jPages({  //分页
    containerID : "qgzxManagement_sign_jpage",  
    previous : "上一页",  
    next : "下一页",  
    perPage : 5,  
    delay : 100  
  });
  
//qgzxManagement_punishmentSearch_Submit
function displayPunishSearchResult(){
	month = $("#qgzxManagement_punishmentSearch_month").val();
	year = $("#qgzxManagement_punishmentSearch_year").val();
	$.ajax({
		type: 'POST',
		url: 'process/qgzxManagement_punishmentSearch.process.php',
		data: {month:month,year:year,sig:'search'},
		success: function(msg){
			$("div#qgzxManagement_punishmentSearchContainer>table>tbody>tr").remove();
				$("div#qgzxManagement_punishmentSearchContainer>table>tbody").append(msg);
				$("div#qgzxManagement_punishmentSearchContainer").show();
				$("div#qgzxManagement_punishmentSearchContainer>table>tbody>tr>td>a").click(deletePunishmentClick); //绑定单击事件
				$("div.qgzxManagement_punishmentSearch_holder").jPages({  //分页
				      containerID : "qgzxManagement_punishmentSearch_jpage",  
				      previous : "上一页",  
				      next : "下一页",  
				      perPage : 5,  
				      delay : 100  
				    });
			}
		});
}

function deletePunishmentClick(){
	id = $(this).parent().parent().attr('id');
	if($(this).attr('id') == 'punishment_delete'){
			sig = 'delete';
		}else{
			sig = 'redone';
			}
	$.ajax({
		type: 'POST',
		url: 'process/qgzxManagement_punishmentDelete.process.php',
		data: {id:id,sig:sig},
		success: function(msg){
				alert(msg);
				displayPunishSearchResult();
			}
		});
}
$(function(){ 
	$("#qgzxManagement_punishmentSearch_Submit").click(displayPunishSearchResult);
});
//arrangeJobs_table 合并内容一致的单元格
$(function(){
	$tbody = $("table#arrangeJobs_table tbody");
	$trs = $tbody.children();
	
	for(var i=0;i<2;i++){
		$trs.each(function(){
			$td = $(this).children(":eq("+i+")");
			$preRowTd = $(this).prev().children(":eq("+i+")");
			$nextRowTd = $(this).next().children(":eq("+i+")");
			//console.info($td);
			if($td.attr('id') == $preRowTd.attr('id')){
				$td.css("border-top","none");
				console.info($td.css("border-top"));
				$td.empty();	
			}
			if($td.attr('id') == $nextRowTd.attr('id')){
				$td.css("border-bottom","none");		
			}
		});
	}
	});

//arangeJob table click function
$(function(){
var tds=$("#arrangeJobs_table tr td:not(:nth-child(1)):not(:nth-child(2))");
tds.dblclick(tddblclick);  
tds.click(function(){
	 var td = $(this);
 var tdText = td.text();
	$.ajax({
		type:"POST",
		url:"process/qgzxManagement_arrangeJob.process.php",
		data:{sig:'user',name:tdText},
		success:function(msg){	
		var arr = msg.split('&');
		var	str = "姓名：<span style='color:chocolate'>"+arr[0]+" </span> 工号：<span style='color:chocolate'>"+arr[1]+" </span> 手机号：<span style='color:chocolate'>"+arr[2]+'</span>';
		$('#arrangeJobs_info').html(str).show();
			}
	})
	});
});
//arrangeJob table td double click function
function tddblclick(){  
            //得到该单元格的对象  
            var td=$(this);  
            //取出改单元格的内容  
            var tdText=td.text();  
            //alert(tdText);      
            //清空改单元格内容  
            td.empty();//或者td.html("");也可以  
            //创建一个input文本框  
            var input=$("<input>");  
            //将原来的值赋值给input文本框  
            input.attr("value",tdText);  
            //给文本框注册一个keyup事件  
            input.keyup(function(event){  
                 var myevent=event||window.event;  
                //判断是否是回车键安县  
                if(myevent.keyCode==13){  
                    //将当前输入的信息保存下来  
                    var inputnode=$(this);  
                    //文本框的值  
                    var inputText=inputnode.val();  
                    var mytd=inputnode.parent();  
					var place_td=mytd.siblings(":first");
					var timeArea_td = mytd.siblings(":eq(1)");
					
					var day = mytd.attr('class');
					var placeId = place_td.attr('id');
					var jobIdRange = mytd.attr('id');
					var timeArea = timeArea_td.attr('id');
					$.ajax({
						type:"POST",
						url:"process/qgzxManagement_arrangeJob.process.php",
						data:{sig:'job',placeId:placeId,timeArea:timeArea,day:day,jobIdRange:jobIdRange,inputText:inputText},
						success:function(msg){		
						var arr = msg.split('&');
						if (arr.length == 2) {
							alert(arr[1]);
							} else {
								 //清空改td中的内容  
                   				 mytd.empty();  
                    			//将文本框值赋给td  
                   				 mytd.html(msg); 
								
								}
						}
					})
					    
                    //让td重新拥有单击事件  
                    mytd.dblclick(tddblclick);      
                }  
            });  
           input.blur(function(){  
                var mytd=$(this).parent();  
                var inputText=$(this).val();  
                mytd.empty();  
                mytd.html(tdText);  
                mytd.dblclick(tddblclick);  
            });  
            //将文本框追加给单元格  
            td.append(input);  
            //文本框高亮选中  
            var inputdom=input.get(0);  
            inputdom.select();  
              
            //td.html(input.val());  
            //一处该单元格的单击事件  
            td.unbind("dblclick"); 
};
</script>
<?php 
function generateDifUserOfDate($type,$Indate=null){
	if($Indate == null){
		$date = date('Y-m-d');	
	}else{
		$date = $Indate;
	}
	if($type == 0){
		$jobs = qgzx_Job::getUnsignedWorkOfDate($date);
	}elseif($type == 1){
		$jobs = qgzx_Job::getSignedWorkOfDate($date);
	}else {
		return ;
	}
	
	if($jobs){
		foreach ($jobs as $job){
			$user = qgzx_User::setQgzx_user($job->getBelonger());
			echo '<tr>
				<td>'.$user->getName().'</td>
				<td>'.$user->getSno().'</td>
				<td>'.$user->getTel().'</td>
				<td>'.$job->getPlace().'</td>
				<td>'.generateJobTimes($job->getBegintime(), $job->getHours()).'</td>
				</tr>';
		}
	}
}

function  generateDailyCodeTable(){
	if(qgzx_generateDailyCodes()){
		$sql = "select * from SJTULib_qgzx_dailyCodes where isUsed != 1";
		$res = UniversalConnect::doSql($sql);
		if(count($res) > 10){
			for($i=0;$i<2;$i++){
				echo '<tr>';
				for($j=$i*5;$j<$i*5+5;$j++){
					echo '<td>'.$res[$j]['code'].'</td>';
				}
				echo '</tr>';
			}
		}
	}
}

function generateJobTbody(){
	$sql_place = "select * from SJTULib_qgzx_place";
	$arr_place = UniversalConnect::doSql($sql_place);
	$count_place = count($arr_place);			//地点个数
	echo '<tbody>';
for($i=0;$i<$count_place;$i++){			//对于地点i
	$sql_job_begintime = "select * from SJTULib_qgzx_job where placeId = {$arr_place[$i]['id']}";
	$arr_dif_job = UniversalConnect::doSql($sql_job_begintime);		//地点i不同工作岗位（开始时间不同）
	$count_dif_job = count($arr_dif_job);							//地点i不同岗位个数
	$total = 0;														//总共需求人数
	for($j = 0; $j < $count_dif_job;$j++){
		$maxnum[$j] = $arr_dif_job[$j]['maxnum'];					//岗位j的最大人数
		$total += $maxnum[$j];
	}
	for($j = 0; $j < $count_dif_job;$j++){
		$sql_job_id = "select * from SJTULib_qgzx_job where placeId = {$arr_place[$i]['id']} and begintime={$arr_dif_job[$j]['begintime']} and hours={$arr_dif_job[$j]['hours']}";
		$arr_job_id = UniversalConnect::doSql($sql_job_id);
		$job_id = $arr_job_id[0]['id'];
		for($k=0;$k < $maxnum[$j];$k++){
			for($p = 1; $p <= 7;$p++){
				if($p == 7) {$day = 0;}
				else {$day = $p;}
				$sql_select_tno = "select * from SJTULib_qgzx_jobArrangement where jobId = '$job_id' and day = '$day' and jobIdRange = '$k'";
				$arr_select_tno = UniversalConnect::doSql($sql_select_tno);
				if($arr_select_tno) {
					$sql_select_name = "select * from SJTULib_user where id = {$arr_select_tno[0]['userId']}";
					$arr_select_name = UniversalConnect::doSql($sql_select_name);
					$name[$p] = $arr_select_name[0]['name'];
				}
				else $name[$p] = null;
			}
			echo "<tr>
			
				<td id={$arr_place[$i]['id']}  > {$arr_place[$i]['content']}</td>
			<td id='{$arr_dif_job[$j]['begintime']}&{$arr_dif_job[$j]['hours']}'  >".generateJobTimes($arr_dif_job[$j]['begintime'], $arr_dif_job[$j]['hours'])."</td>
			
			<td class = '1' id='$k'>{$name[1]}</td>
			<td class = '2' id='$k'>{$name[2]}</td>
			<td class = '3' id='$k'>{$name[3]}</td>
			<td class = '4' id='$k'>{$name[4]}</td>
			<td class = '5' id='$k'>{$name[5]}</td>
			<td class = '6' id='$k'>{$name[6]}</td>
			<td class = '0' id='$k'>{$name[7]}</td>
			</tr>";
		
		}
		}
		}
	echo '</tbody>';
}
?>

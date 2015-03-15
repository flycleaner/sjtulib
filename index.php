<?php
require_once 'config.inc.php';

//index.php?p=value1?id=value2
if(isset($_GET['p'])){
	$p = $_GET['p'];
} else if(isset($_POST['p'])){
	$p = $_POST['p'];
} else {
	$p = null;
}


if(isset($_GET['id'])){
	$id = $_GET['id'];
} else if(isset($_POST['id'])){
	$id = $_POST['id'];
} else {
	$id = null;
}
if(!is_numeric($id)){
	$id = null;
}


/**
 * Determine which page to display
 * */
$page = 'main.view.php';
$bodyClass = 'page';

switch ($p){
	case 'regist' :
		$page = 'regist.view.php';
		$pageTitle = '用户注册';
		$bodyClass = 'single single-post';
		break;
	case 'department' :
		$page = 'department.view.php';
		$pageTitle = '部门风采';
		if(Department::isExitDepartment($id)){
			$department = Department::setDepartment($id);
			$page='singleDepartment.view.php';
			$pageTitle = $department->getName();
		}
		break;
	case 'activity' :
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		} else if(isset($_POST['type'])){
			$type = $_POST['type'];
		} else {
			$type = null;
		}
		$page = 'activity.view.php';
		$pageTitle = '活动列表';
		$bodyClass = 'blog list-style';
		if(Activity::isActivityExist($id)){
			$acti = Activity::setActivity($id);
			$page='singleActivity.view.php';
			$pageTitle = $acti->getTitle();
			$bodyClass = 'single single-post';
		}
		
		break;
	case 'photoGalaxy' :
		$page = 'photoGalaxy.view.php';
		$pageTitle = '活动照片';
		$bodyClass = 'portfolio';
		break;
	case 'userCenter' :
		if($userId != null){
			$page = 'userCenter.view.php';
			$pageTitle = '用户中心';
			
			//get oper
			if(isset($_GET['oper'])){
				$oper = $_GET['oper'];
			} else if(isset($_POST['oper'])){
				$oper = $_POST['oper'];
			} else {
				$oper = null;
			}
			
			//choose $page to dislay
			switch ($oper){
				case 'edit':
					if(Activity::isActivityExist($id)){
						$page = 'userCenterEditActivity.view.php';
						$pageTitle = '编辑活动';
					}
					break;
				case 'searchRegistrationUsers':
					if(Activity::isActivityExist($id)){
						$page = 'userCenterSearchRegistrationUsers.view.php';
						$pageTitle = '活动报名信息';
					}
					break;
				case 'manageDepartment':
					$page = 'manageDepartment.view.php';
					$pageTitle ='部门管理';
					break;
				case 'qgzxManagement':
					$page = 'qgzxManagement.view.php';
					$pageTitle = '勤工助学管理';
					break;
				default:;
			}
			
		}else {
			$page = 'main.view.php';
			$bodyClass = 'page';
			}
		break;
	default:
		$page = 'main.view.php';
		$bodyClass = 'page';
		break;
}
if(!file_exists(BASE_URI.'/views/'.$page)){
	$page = 'main.view.php';
}
require_once 'inc/include.inc.php';		//include nessesary files   @!important
echo '<body class="'.$bodyClass.'">';
include 'inc/header.inc.php';
include 'views/'.$page;
include 'inc/footer.inc.php';
?>

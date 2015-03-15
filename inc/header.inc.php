		<header role="banner" class="transparent light">
			<div class="row">
				<div class="nav-inner row-content buffer-left buffer-right even clear-after">
					<div id="brand">
						<h1 class="reset"><!--<img src="img/logo.png" alt="logo">--><a href="index.php">SJTU Library</a></h1>
					</div><!-- brand -->
					<a id="menu-toggle" href="#"><i class="fa fa-bars fa-lg"></i></a>
					<nav>
						<ul class="reset" role="navigation">
							<li class="menu-item"><a href="index.php">主页</a></li>
							<li class="menu-item">
								<a href="index.php?p=department">部门风采</a>
								<ul class="sub-menu">
									<?php 
									$sql = "select * from SJTULib_department";
									$departmentArray = UniversalConnect::doSql($sql);
									if($departmentArray){
									foreach ($departmentArray as $department){
										echo '<li><a href="index.php?p=department&id='.$department['id'].'">'.$department['name'].'</a></li>';
									}
									}
									?>
								</ul>
							</li>
							<li class="menu-item">
								<a href="index.php?p=activity">活动展示</a>
							</li>
							<li class="menu-item"><a href="index.php?p=activity&type=volunteer">志愿活动报名</a></li>
							<li class="menu-item">
								<a href="index.php?p=photoGalaxy">图片欣赏</a>					
							</li>
							<!-- loginIn part -->
							<?php
							$flag=0;
							if($userId != null){
								$user = User::setUser($userId);
								
								if($user){
									$flag = 1;
								$name = $user->getName();
							echo <<<LOGININ
							<li class="menu-item"><a href="index.php?p=userCenter"><i class="icon-user"></i>$name</a>
								<ul id = "alreadyLogin" class = "sub-menu" style="background-color: rgb(246,246,246)">
									<li style="width:200px"></li>
									<li><a href="index.php?p=userCenter">个人中心</a></li>
									<li id="logOut"style="width:200px"><a>注销</a></li>
								</ul>
							</li>
LOGININ;
								}
							}
							if($flag==0){
								
							echo <<<LOGIN
							<li class="menu-item"><a href="#">登陆</a>
								<ul id = "loginList" class = "sub-menu" style="background-color: rgb(246,246,246)">
									<li style="width:200px"><input class="name plain buffer" style="width:80%;margin-left:20px;margin-top:30px" type="text" name="username" placeholder="用户名/学号/邮箱/手机号" maxlength="30"></li>
									<li style="width:200px"><input class="name plain buffer" style="width:80%;margin-left:20px;margin-top:30px" type="password" name="password" placeholder="密码" maxlength="30"></li>
									<li id ="loginAppendMsg" style="color:red;text-align:center"></li>
									<!--<li style="text-align:center">
										<input name="remember" class="plain button green" style="margin-right:2%" type="checkbox" value="7">记住我(7天)
									</li>-->
									<li style="width:200px;text-align:center">
										<input id="loginIn" class="plain button green" style="margin-right:2%;width:15%" type="button" value="登陆">
										<input id="regist" onclick="location.href='index.php?p=regist'" class="plain button red" style="margin-right:2%;width:15%" type="button" value="注册">
									</li>
									
								</ul>
							</li>
LOGIN;
							}
							
							?>
						</ul>
					</nav>
				</div><!-- row-content -->	
			</div><!-- row -->	
			
		</header>
		

       
<?php

require "login.php";
require "update.php";
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none'", false);
$bodolka = true;
$boolka = true ;

$userin = $_SESSION['logged_user'];

if(isset($_POST['login'])){
    
    $logg=$_POST;
    unset($_POST);
    $bodolka = logined($logg['uname'],$logg['haslo']);
    
}
if(isset($_POST['reg'])){
    $errors = array();
    if($_POST['fname'] == " " or $_POST['fname']=='' or strlen($_POST['fname']) < 3){
        $errors[] = "Invalid name";
    }
     if($_POST['lname'] == " " or $_POST['lname']=='' or strlen($_POST['lname']) < 3){
         $errors[] = "Invalid surname";
    }
     if($_POST['email'] == " " or $_POST['email']=='' or strlen($_POST['email']) < 5){
         $errors[] = "Invalid email";
    }
   if (empty($errors)){
        $useri=$_POST;
        unset($_POST);
        $boolka = register($useri['email'],$useri['parol'],$useri['fname'],$useri['lname']);
    }
   
}
  
    
if($userin['id']==$_GET['id']){
    $uinfo=$_SESSION['logged_user'];
    $double_penetration = true;
       
    }
else {
        $uinfo =  R::findOne('users','id = ?', [ $_GET['id'] ]);
        $x=$uinfo['views']+1;
        R::exec('UPDATE `users` SET `views` = ? WHERE `users`.`id` = ?',[ $x, $uinfo['id'] ]);  
        $uinfo=$uinfo->export();
        $double_penetration = false;
    }


$mailc=true; #Лучше не удаляти 

if(isset($_POST['confirm'])){
    
    $ninfo=$_POST;
    unset($_POST);
    $error = update($ninfo);
    $uinfo=R::findOne('users','id= ?',[ $uinfo['id'] ]);
    $uinfo=$uinfo->export();
    $_SESSION['logged_user']=$uinfo;
    
}
    
if(isset($_POST['upload'])){

    $errors=array();
    if($_FILES['file']['name']==''){
        $errors[]='Выберете файл';
    }
    else {
        $name=$_FILES['file']['name'];
        $tmp=$_FILES['file']['tmp_name'];
        $size=$_FILES['file']['size'];
        $type=$_FILES['file']['type'];
        
        $distantion='images/'.$uinfo['id'].$name;
       
        if($size > 1500000) { 
        $errors[] = "Розмір файлу не повинен бути більшим аніж 1.5 МБ ";
        }
        
        if ($type == 'image/png' or $type == 'image/jpg' or $type == "image/jpeg" or $type == "image/gif" ){
            if (empty($errors)){
                
                
                if($uinfo['profile_photo'] != 'icons/img/profile.png'){
                       unlink($uinfo['profile_photo']);
                }
                move_uploaded_file($tmp,$distantion);
                R::exec('UPDATE `users` SET `profile_photo` = ? WHERE `users`.`id` = ?',[ $distantion, $uinfo['id'] ]);
                $uinfo=R::findOne('users','id= ?',[ $uinfo['id'] ]);
                $uinfo=$uinfo->export();
                $_SESSION['logged_user']=$uinfo; 
                header("Location: profile.php?id=".$uinfo['id']);
            }
        }
        else {
            $errors[] = 'Нам потрібно фото! png, jpg, jpeg, gif';
        }
    }
}


  
?>



<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Axilium">
    <meta name="keywords" content="crowdfonding, fundraising, money, profile">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="indreex.css">
	<link rel="stylesheet" href='frames/smoke-pure.css'>
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/main_.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="icon" href="icons/logo1-восстановлено.png">
	<link rel="apple-touch-icon" href="/PWA logo/180x180.png">
	<title><?php echo htmlspecialchars($uinfo['name']." ".$uinfo['surname']); ?> - профиль пользователя</title>
    
    <style>
    	.btn-primary {
       		background-color: #004bff;
        	display: inline-block;
    	}
    	.btn-primary:hover {
        	background-color: grey;
    	}
    </style>

</head>
<body id="bg">
	
	<div id="progressbar"></div>

	<div id="preloader" class="center loader">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
	</div>

	<div id="fixed">
		<img src="icons/img/stay_home_.png" class="widget img-fluid" alt="Stay Home">
	</div>

	<!-- HEADER -->
	<header class="sticky-top">
		<nav class="navbar navbar-expand-lg navbar-light">
			
			<!-- LOGO -->
			<a href="index.php" onclick="return up()" class="logo-header mostion"><img class="axilium-logo" src="icons/logo1-восстановлено.png" alt="logo" width="70" height="45"></a>
			
			<!-- COLLAPSE BTN -->
			<div class="navbar_container">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Togglenavigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div>

			<!-- NAVIGATION -->
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a href="index.php" class="nav-link">Головна</a>
					</li>										
				</ul>
				<div class="extra-nav"><?php
		            if(empty($_SESSION['logged_user'])){    
		            ?>
		                <button class="menu-item menu-green" onclick='smoke.alert("<h1>Axilium</h1><br/> Ой, ви не авторизовані! Ввійдіть у свій аккаунт або авторизуйтеся.");'style="width:auto;">
							<i class="icon-plus"></i>
							Зберіть на Axilium
						</button>
		              <?php }
		            else { 
		            ?>  <a href='form/'>
		             <button class="menu-item menu-green"style="width:auto;">
							<i class="icon-plus"></i>
							Зберіть на Axilium
						</button></a>
		        	<?php } ?>
							
					<?php if(!empty($_SESSION['logged_user'])){ ?>
					
					<!-- AVTORIZATION PROFILE -->
					<div class="dropdown open avtorization" tabindex="0">						
						<button class="btn btn-secondary dropdown-toggle avtorization" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<a class="menu-item menu-green user-name" href="#">
								<img class="avatar" src="<?php echo $userin['profile_photo']; ?>">
								<span><?php  echo htmlspecialchars($userin['name']." ".$userin['surname']);?></span>
							</a>
						</button>

						<div class="dropdown-menu avtorization" aria-labelledby="dropdownMenuButton" tabindex="-1">
							<a class="item" href="<?php echo "profile.php?id=".$userin['id']; ?>">
								<i class="icon-cogs"></i>
								<span>Мій профіль</span>
							</a>
							<a class="item" href="logout.php">
								<i class="icon-exit"></i>
								<span>Вийти</span>
							</a>
						</div>					
					</div> <!-- /AVTORIZATION PROFILE --> <?php }
		            else {
		            ?>

					<button class="site-button pull-right m-t15 login" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button>

					<?php } ?>
				</div> <!-- /extra-nav -->
			</div> <!-- /collapse -->
			
			<!-- MODAL -->
			<div id="id01" class="modal">
				<div class="col-md-offset-3 col-md-6">
					<div class="tab" role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">							
							<li role="presentation" id="sign_in" class="active"><a href="#Section1" aria-controls="home" role="tab" data-toggle="tab">Увійти</a></li>
							<li role="presentation" id="sign"><a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab">Зареєструватися</a></li>						
						</ul>
						<div class="tab-content tabs">							
							<div role="tabpanel" class="tab-pane fade in active show" id="Section1">								
								<form class="form-horizontal" action='' method='POST'>
									<?php
                                        if ($bodolka == false) {
                                            echo "Неправильний логін чи пароль.";
                                            echo "<script>document.getElementById('id01').style.display='block';</script>";
                                        }
                                    ?>
									<div class="form-group">									
										<label for="exampleInputEmail1">Email</label>
										<input type="email" class="form-control" id="exampleInputEmail1" name='uname' required>								
									</div>
									<div class="form-group">
										<label for="exampleInputPassword1">Пароль</label>
										<input type="password" class="form-control" id="exampleInputPassword1" name='haslo' required>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-default" name='login'>Sign in</button>
									</div>								
								</form> <!-- /form -->
							    <div class="form-group forgot-pass">
                                    <button onclick="smoke.prompt('Введіть свій email. Ви отримаєте лист з підтвердженням на свою пошту', function(result){window.location.href = 'reset.php?token='+result;})" class="btn btn-default" style='color:grey'>Забули пароль ?</button>
								</div>  
							</div> <!-- /Section1 -->
							
							<div role="tabpanel" class="tab-pane fade" id="Section2">								
								<form class="form-horizontal" action='' method="POST">
									<?php
                                        if(!empty($errors)){
                                            echo "<font style='color:red'>".$errors[0]."</font>";
                                            echo "<script>document.getElementById('id01').style.display='block';</script>";
                                        }
                                        if ($boolka == false) {
                                            echo "<font style='color:red'>Ваш email уже зареєстрований або пароль повинен містити не менше 8-ми символів</font>";
                                            echo "<script>document.getElementById('id01').style.display='block';</script>";
                                        }
                                    ?>
									<div class="form-group">
										<label for="exampleInputEmail1">Ім'я</label>
										<input type="text" class="form-control" id="exampleInputEmail1" name='fname' required>
									</div>									
									<div class="form-group">
										<label for="exampleInputEmail1">Прізвище</label>
										<input type="text" class="form-control" id="exampleInputEmail1" name='lname' required>
									</div>									
									<div class="form-group">
										<label for="exampleInputEmail1">Ваш Email</label>
										<input type="email" class="form-control" id="exampleInputEmail1" name='email' required>
									</div>									
									<div class="form-group">
										<label for="exampleInputPassword1">Пароль</label>
										<input type="password" class="form-control" id="exampleInputPassword1" name='parol' required>
									</div>									
									<div class="form-group">
										<button type="submit" class="btn btn-default" name='reg'>Зареєструватися</button>
									</div>								
								</form> <!-- /form -->							
							</div> <!-- /Section2 -->						
						</div> <!-- /tab-content -->					
					</div> <!-- /tab -->
				</div><!-- /.col-md-offset-3 col-md-6 -->
			</div> <!-- /modal -->
		</nav> <!-- /nav -->		
	</header> <!-- /HEADER -->
	
	<!-- PROFILE -->
	<section class="profile">
		<div class="container profile">
			<div class="row m-y-2">
				<div class="col-lg-4 pull-lg-8 text-xs-center">
					<img src="<?php echo $uinfo['profile_photo']; ?>" class="m-x-auto img-fluid img-circle wow slideInLeft" alt="avatar">
					<hr>
                    
				    <?php
                        if($double_penetration){
                        if(!empty($errors)){
                            echo "<font style='color:red'>".$errors[0]."</font>";
                        }
                    ?>
					<form action="" method='post' enctype="multipart/form-data">
				
						<label class="custom-file">
							<span class="custom-file-control">Оберіть файл для нового фото</span>
							<input type="file" id="file" class="custom-file-input" name="file" accept="image/*">                   			
	                    </label>
	                    <button type = 'submit' name='upload' class='fa fa-upload' style='background-color:#004bff;height:35px;width:35px;border-radius:50%;'> </button>  Завантажити фото
	                    
                    </form><?php } ?>
                    <br/>
				</div> <!-- /photo -->

				<div class="col-lg-8 push-lg-4">
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a href="" data-target="#profile" data-toggle="tab" class="nav-link active profile">Профіль</a>
						</li>

                        <?php if($double_penetration){ ?>
						<li class="nav-item">
							<a href="" data-target="#edit" data-toggle="tab" class="nav-link profile">Редагувати</a>
						</li><?php } ?>
					</ul> <!-- /tabs -->

					<div class="tab-content p-b-3">
						<div class="tab-pane active" id="profile">
							<div class="row">
								<div class="col-md-6">
									<h4>Про мене</h4>
									<hr>
									<h6><?php echo htmlspecialchars($uinfo['name']." ".$uinfo['surname']); ?></h6>						
									<hr>
									<h4>Хоббі</h4>
									<hr>
									<p><?php echo htmlspecialchars($uinfo['hobby']); ?></p>
									<hr>
								</div>
								
								<div class="col-md-6">

									<h4>Активність</h4>
									<hr/>
								    <?php 
                                    $sum = (int)R::getCell('SELECT SUM(amount) FROM `transactions` WHERE `user` = ? ;',[ $uinfo['id'] ]);
                                    ?> 
									<span class="tag tag-primary"><i class="fa fa-money"></i> Сума пожертвувань: <?php echo $sum ?> UAH</span><br/><br/>
									<span class="tag tag-danger"><i class="fa fa-eye"></i> Переглядів: <?php echo $uinfo['views']; ?></span><br/><br/>
									<span class="tag tag-danger"><i class="fa fa-briefcase"></i> Кількість зборів: <?php echo R::count('patients','WHERE `user_id` = ? ',[ $uinfo['id'] ])?></span><br/><br/>
								</div>

								<div class="col-md-12">
									<h4 class="m-t-2"><span class="fa fa-clock-o ion-clock pull-xs-right"></span>Остання активність</h4>
									<hr>
									<table class="table table-hover table-striped">
										<tbody>                                    
                                         <?php
    									$x = 0;
                                        $tranc = R::findCollection('transactions','user = ? AND `verify` = ? ORDER BY `id` DESC',[ $uinfo['id'],1 ]);
                                        
                                        while ( $row = $tranc->next()) {
                                            $x++;
                                            if($x==11){
                                                break;
                                            }
                                            ?>                                       
                                            <tr>
												<td>
													<strong><?php echo $uinfo['name']; ?></strong> donate <strong><?php echo $row->amount; ?> UAH</strong> into <strong><?php 
                                                    $stringer = R::findOne('patients','id = ?',[ $row->target ]);
                                                    echo "<a href='patient.php?idkey=".$row->target."'>".$stringer['full_name']."</a>";
                                                    ?></strong>
												</td>
											</tr>
                                        <?php } 
                                        $liczba = R::count('transactions',' `user` = ? AND `verify` = ?',[ $uinfo['id'],1 ]);
                                        if($liczba == 0){
                                            echo "<tr>
												<td>
													<strong>У цього користувача немає пожертвувань.</strong>
												</td>
											     </tr>
                                            ";
                                        }    
                                        ?>
										</tbody>										
									</table> <!-- /table -->									
								</div>
							</div> <!-- /row panel -->
						</div> <!-- /active panel -->

					<?php if($double_penetration){ ?>
						<div class="tab-pane" id="edit">

							<h4 class="m-y-2">Редагувати профіль</h4>
							<hr>
							<?php
                                echo $error;
                            ?>
							<form role="form" action="" method="POST">
								<div class="form-group row">
									<label class="col-lg-3 col-form-label form-control-label">Ім'я</label>
									<div class="col-lg-9">
										<input class="form-control" type="text" placeholder="Adam" name='fname'>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-3 col-form-label form-control-label">Прізвище</label>
									<div class="col-lg-9">
										<input class="form-control" type="text" placeholder="Marcus" name='lname'>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-3 col-form-label form-control-label">Email</label>
									<div class="col-lg-9">
										<input class="form-control" type="email" placeholder="email@gmail.com" name='email'>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-3 col-form-label form-control-label">Хоббі</label>
									<div class="col-lg-9">
										<input class="form-control" type="text" value="" name='hobby'>
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-lg-3 col-form-label form-control-label">Пароль</label>
									<div class="col-lg-9">
										<input class="form-control" type="password" value="" name='p1'>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-3 col-form-label form-control-label">Підтвердіть пароль</label>
									<div class="col-lg-9">
										<input class="form-control" type="password" value="" name='p2'>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-3 col-form-label form-control-label"></label>
									<div class="col-lg-9">
									
										<input type="submit" class="btn btn-primary" value="Save Changes" name='confirm'>
									</div>
								</div>
							</form> <!-- /form -->
						</div> <!-- /edit --><?php } ?>
					</div> <!-- /content -->
				</div> <!-- /info -->
			</div> <!-- /row -->

			<div class="pre wow zoomIn" data-wow-delay="0.2s">
				<span class="text-accent profile">Мої збори</span>
			</div>
			<?php
            $catgirl = R::findCollection('patients','WHERE `user_id` = ? ORDER BY `id` DESC ',[ $uinfo['id'] ]);
            while($row = $catgirl->next()) {
            ?>
			<div class="row justify-content-sm-center">
					
				<article class="col-md-10 col-xl-8 wow fadeIn">
					<a href='patient.php?idkey=<?php echo $row->id ?>'>
					<div class="post-event-single section-md">
						<div class="post-event-single-body text-md-left">
							<img src="<?php echo $row->profile_image; ?> " alt="Donater" style='height:100%; width:100%'>
						</div>
					</div>
					<div class="group">
						
						<?php echo $row->full_name; ?>

					</div>
                    </a>
                </article>

			</div> <!-- /row --><?php } 
            if(R::count('patients','WHERE `user_id` = ? ',[ $uinfo['id'] ]) == 0){
                
            
            ?>
			<div class="row justify-content-sm-center">				
				<article class="col-md-10 col-xl-8 wow fadeIn">
					<center>                    <font style='color:black; font-weight:bold;'>У цього користувача немає зборів.</font>
                    </center>
				</article>
			</div> <!-- /row --><?php } ?>
		</div> <!-- /container -->
	</section> <!-- /PROFILE -->
	
	<div class="pre_footer">
		<img src="icons/img/fot.png" class="mx-auto d-block footer" alt="">
	</div>
	
	<!-- FOOTER -->
	<footer class="page-footer font-small stylish-color-dark pt-4">
		<div class="container text-center text-md-left">
			<div class="row">
				<div class="col-md-4 mx-auto wow slideInLeft">
					<h6 class="text-uppercase font-weight-bold">Розташування та контакти :</h6>
					<hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 260px; background-color: #0e199e;">
					<p>
						<i class="fa fa-home "></i> Вул. Чехова 8, навчальникй корпус №6, ТНЕУ.
					</p>
					<p>
						<i class="fa fa-envelope"></i> andsof123455@gmail.com
					</p>
					<p>
						<i class="fa fa-phone"></i> +380 97 520 08 79 - Андрій;<br>
						<i class="fa fa-phone"></i>	+380 68 057 19 30 - Віталій.
					</p>
				</div>
				<hr class="clearfix w-100 d-md-none">
				<div class="col-md-4 mx-auto">
					<div class="footer_title">
						<h3 class="text-center wow jackInTheBox">Axilium</h3>
					</div>
				</div>
				<hr class="clearfix w-100 d-md-none">
				<div class="col-md-4 mx-auto content wow slideInRight">
					<h6 class="text-uppercase font-weight-bold content">Додаткова інформація :</h6>
					<hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto content" style="width: 220px; background-color: #0e199e;">
					<p class="text_about">
						Сайт розроблено на базі Факультету комп'ютерних інформаційних технологій<br>Тернопільського національного економічного уніврситету.
					</p>
				</div>
			</div> <!-- /row -->
		</div> <!-- /container -->
		<hr>
		<?php if(empty($_SESSION['logged_user'])){?>
		<ul class="list-unstyled list-inline text-center py-2">
			<li class="list-inline-item">
				<h5 class="mb-1">Зареєструйся безкоштовно</h5>
			</li>
			<li class="list-inline-item">
				<button class="btn btn-danger btn-rounded" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Приєднуйся!</button>
			</li>
		</ul>
        <?php }
        else {
        ?>
        <ul class="list-unstyled list-inline text-center py-2">
            <li class="list-inline-item">
                <h5 class="mb-1">Вітаємо:</h5>
			</li>
			<li class="list-inline-item">
                <font style="color:black; font-size:20px; text-decoration: underline;"><?php echo htmlspecialchars($userin['name']); ?></font>
			</li>
        </ul> <?php } ?>
		<hr>
		<ul class="list-unstyled list-inline text-center">
			<li class="list-inline-item wow rollIn">
				<a class="btn-floating btn-fb mx-1" href="#">
					<i class="fa fa-facebook-f"> </i>
				</a>
			</li>
			<li class="list-inline-item wow rollIn" data-wow-delay="0.2s">
				<a class="btn-floating btn-tw mx-1" href="#">
					<i class="fa fa-twitter"> </i>
				</a>
			</li>
			<li class="list-inline-item wow rollIn" data-wow-delay="0.4s">
				<a class="btn-floating btn-gplus mx-1" href="#">
					<i class="fa fa-google"> </i>
				</a>
			</li>
		</ul>
		<div class="footer-copyright text-center py-3">© 2020 Copyright: 
			<a href="policy.html">Axilium</a>
		</div>
	</footer> <!-- /FOOTER -->
	
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->	
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
 	<script src="frames/smoke-pure.js"></script>
	
	<!-- ANIMATION -->
	<link rel="stylesheet" href="animate.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
	
	<!-- Анімація -->
	<script>
		new WOW().init();
	</script>
	
	<!-- Логін -->
	<script>
		document.getElementById('sign').onclick = function() {
			document.getElementById('sign').classList.add('active');
			document.getElementById('sign_in').classList.remove('active');
		};

		document.getElementById('sign_in').onclick = function() {
			document.getElementById('sign').classList.remove('active');
			document.getElementById('sign_in').classList.add('active');
		};
	</script>
	
	<!-- Кнопка вгору -->
	<script>
		$(document).ready(function(){
  			$('body').append('<a href="#" id="to-top" class="to-top active"><i class="fa fa-angle-up"></i></a>');
		});	
		$(function() {
	 			$.fn.scrollToTop = function() {
	  				$(this).hide().removeAttr("href");
	  				if ($(window).scrollTop() >= "250") $(this).fadeIn("slow")
	  				var scrollDiv = $(this);
	  				$(window).scroll(function() {
	   					if ($(window).scrollTop() <= "250") $(scrollDiv).fadeOut("slow")
	   					else $(scrollDiv).fadeIn("slow")
	  				});
	  				$(this).click(function() {
	   					$("html, body").animate({scrollTop: 0}, "slow")
	  				})
	 			}
		});
	
		$(function() {
			 $("#to-top").scrollToTop();
		});
	</script>
	
	<!-- Скрол -->
	<script>
		$(window).scroll(function(){
			var scroll = $(window).scrollTop(),
			dh = $(document).height(),
			wh = $(window).height();
			scrollPercent = (scroll / (dh - wh)) * 100;
			$('#progressbar').css('height', scrollPercent + '%');
		})
	</script>
	
	<!-- Прелоадер -->
	<script>
		$(window).on('load', function () {
    		var $preloader = $('#preloader'),
        	$anm   = $preloader.find('span');
    		$anm.fadeOut();
			$preloader.delay(500).fadeOut('slow');
		});
	</script>

	<!-- Stay Home -->
	<script>
		$(document).ready(function () {
      		var offset = $('#fixed').offset();
    		var topPadding = 0;
    		$(window).scroll(function() {
        		if ($(window).scrollTop() > offset.top) {
            	$('#fixed').stop().animate({marginTop: $(window).scrollTop() - offset.top + topPadding});
        		}
        		else {
            	$('#fixed').stop().animate({marginTop: 0});
        		}
   			 });
		});
	</script>

	<script src="script.js"></script>

</body>
</html>
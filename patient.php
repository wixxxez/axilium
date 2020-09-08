<?php
require "login.php";
require "widget.php";
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none'", false);
$bodolka = true;
$boolka = true ; 
$errores = array();
if (empty($_GET)) {
    header("Location: ooops.html");
}
else {
$patient = R::findOne('patients','WHERE `id` = ?',[ $_GET['idkey'] ]);
$x=$patient['views']+1;
R::exec('UPDATE `patients` SET `views` = ? WHERE `patients`.`id` = ?',[ $x, $patient['id'] ]);  
$patient = $patient ->export();
if(empty($patient)){
    header("Location: ooops.html");
}

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

if (!empty($_SESSION['logged_user'])){
    $userin = $_SESSION['logged_user'];
    
}
    if(file_exists("patients/comments/".$patient['id']."idcom.json")){
       
        }
    else {
        $com = fopen("patients/comments/".$patient['id']."idcom.json",'a+');
        fwrite($com,'[]');
        fclose($com);
        
    }
if(isset($_POST['sellc'])){
    if(strlen($_POST['text']) > 5){
        $data = file_get_contents("patients/comments/".$patient['id']."idcom.json");
        $arrd = json_decode($data,true);
        
        $extra = array (
            
            'time'=>date("F d, Y  H:i"),
            'text'=>$_POST['text'],
            'id'=>$userin['id']
    
        );
        $arrd[] = $extra;
    $newdata = json_encode($arrd);
    if(file_put_contents("patients/comments/".$patient['id']."idcom.json",$newdata)){
        header('Location: patient.php?idkey='.$patient['id'].'#comm');
    }
    }
    else {
        $errores[] = "Коментар повинен містити не менше 5-ти символів.";
    }
    
     
    
}

}

if(isset($_POST['go_tranc'])) { 
    $kwargs = $_POST;
    $kwargs['target'] = $patient['id'];
    $kwargs['fulln']=$patient['full_name'];
    $kwargs['order'] = sha1(microtime(true));
    $widget = create_widget($kwargs);
    R::exec('INSERT INTO `transactions` (`order_reference`, `target`, `user`, `status`) VALUES ( ?, ?, ?,NULL);',[ $kwargs['order'],$kwargs['target'],$userin['id'] ]);
    
    echo '<font style="display:none">'.$widget."</font>";
    unset($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Axilium">
    <meta name="keywords" content="crowdfonding, fundraising, money, donat">	
    
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="indreex.css">
	<link rel="stylesheet" href="form/index.css">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/main_.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="icon" href="icons/logo1-восстановлено.png">
	<link rel="stylesheet" href='frames/smoke-pure.css'>
	<title><?php echo $patient['full_name']; ?> - збір коштів </title>
	
	<style>
        .author:hover {
            text-decoration: underline;
        }           
        .col-md-offset-3 col-md-6{
            margin-left:25%;
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
		<nav class="navbar navbar-expand-lg navbar-light" id="menu">
			
			<!-- LOGO -->
			<a href="index.php"  class="logo-header mostion"><img class="axilium-logo" src="icons/logo1-восстановлено.png" alt="logo" width="70" height="45"></a>
			
			<!-- COLLAPSE BTN -->
			<div class="navbar_container">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Togglenavigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div> <!-- /COLLAPSE BTN -->

			<!-- collapse -->
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a href="index.php"  class="nav-link">Головна</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Фінансування
							</a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<li>
									<a href="index.php" class="dropdown-item">
										<i class="icon-star-empty"></i>
										<span>Рекомендовані</span>
									</a>
								</li>								
								<li>
									<a href="index.php" class="dropdown-item">
										<i class="fa fa-users"></i>
										<span>Популярні</span>
									</a>
								</li>
							</ul>							
						</li>												
					</ul>
				
				<!-- extra-nav -->			
				<div class="extra-nav" >
		            <?php
		            if(empty($_SESSION['logged_user'])){    
		            ?>
		                <button class="menu-item menu-green" onclick='smoke.alert ("<h1>Axilium</h1><br/> Ой, ви не авторизовані! Ввійдіть у свій аккаунт або авторизуйтеся.");'style="width:auto;">
							<i class="icon-plus"></i>
							Зберіть на Axilium
						</button>
		              <?php }
		            else { 
		            ?>  <a href='form/'>
		            		<button class="menu-item menu-green"style="width:auto;">
								<i class="icon-plus"></i>
							Зберіть на Axilium
							</button>
						</a>
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
					
					<!-- LOGIN FORM -->
					<button class="site-button pull-right m-t15 login" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button>

					<?php } ?>
				</div> <!-- /extra-nav -->
			</div> <!-- /colapse -->
			
			<!-- MODAL -->
			<div id="id01" class="modal">
				<div class="col-md-offset-3 col-md-6">
					<div class="tab" role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" id="sign_in" class="active">
								<a href="#Section1" aria-controls="home" role="tab" data-toggle="tab">Увійти</a>
							</li>
							<li role="presentation" id="sign">
								<a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab">Зареєструватися</a>
							</li>
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
										<input type="submit" class="btn btn-default" name='login' value='sign in'>
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
										<input type="text" class="form-control" id="exampleInputEmail1" name='fname' required >
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
		</nav>		
	</header> <!-- /HEADER -->
	
	<!-- PATIENT -->
	<section class="probootstrap-section">
		<div class="container patient">			
			<div class="tablet-stackable" id="column-container">				
				<div class="ten wide column" id="left-column">					
					<div class="box with-padding with-margin" id="cause-content">
						<?php
                        if($patient['immediantly'] == 1){
                        ?>
						<div class="ribbon label red">
							<span>Recommended</span>
						</div><?php } ?>
						<div class="main-photo">
							<img alt="Фото пацієнта" src="<?php echo $patient['profile_image']?>">
						</div>
						<div class="bordered-box with-padding gray-corner" id="cause-info">							
							<div class="goal">
								<h5 class="label">Goal collection</h5>
								<p class="value"><?php echo htmlspecialchars($patient['naglowek']); ?></p>
							</div>
							<div class="two columns stackable" id="icoMoon">								
								<div class="column">
									<i class="icon-user"></i>
									<p><?php echo htmlspecialchars($patient['full_name']); ?></p>
								</div>								
								<div class="column">
									<i class="icon-location"></i>
									<p><?php echo htmlspecialchars($patient['adress']); ?></p>
								</div>				
							</div> <!-- /columns -->
						<?php echo htmlspecialchars($patient['content']); ?>
						</div> <!-- /cause-info -->
					</div> <!-- /cause-content -->
				</div> <!-- /left-column -->
				<div class="six wide column" id="right-column">					
					<div class="sticky">						
						<div class="box with-padding human-cause">							
							<div class="cause-top">	
								<h6>Axilium</h6>
							</div>
							                         
                            <center>
                            
                            
								<a class="sp-button big red with-icon full-width" target="_blank" href='https://www.ipay.ua/ru/p2p/default/constructor/aac074a03c15cc7b69d4fe9d5d379326'>								
									<span class="icon">
										<i class="icon-heart"></i>
									</span>
									<span>Donate</span>
								</a>

								<!-- MODAL DONAT-->
											
                            </center>
							<div class="share" id="share">								
								<div class="share-title">									
									<span>Давай змінювати світ разом!</span>
								</div>
								<div class="equal width">									
									<div class="column">										
										<a data-fb-share="#" href="#">											
											<div class="logo">
												<i class="icon-facebook2"></i>
											</div>											
											<div class="label">
												<span>Share</span>
											</div>
										</a>
									</div>
									<div class="column">										
										<a data-twitter-tweet="#" href="#">											
											<div class="logo">
												<i class="icon-twitter"></i>
											</div>
											<div class="label">
												<span>Tweet</span>
											</div>
										</a>
									</div>
								</div> <!-- /columns -->
								<div class="share-amount"></div>
							</div> <!-- /share -->
						</div>
					</div> <!-- /sticky -->
				</div> <!-- /right-column -->
			</div> <!-- /column-container -->
			<div class="col-md-12" >
				<div class="dez-tabs product-description bg-tabs">
					<ul class="nav nav-tabs">					
						<?php
                        $string = file_get_contents("patients/comments/".$patient['id']."idcom.json");
                            $json_a = json_decode($string, true);
                        ?>
						<li class="active" id="tab_1">
							<a data-toggle="tab"  aria-expanded="false" id ='comm' >
								<span class="text-primary"><?php echo sizeof($json_a);?></span>Коментарів
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="developement-1" class="tab-pane active">								
							<div id="comments">								
								<ol class="commentlist" >									

										
	                            <?php
	                               $score = 0;
	                            if(sizeof($json_a)!= 0 ) {
	                            for ($i = sizeof($json_a)-1; $i >= 0;$i--){
	                                $comi = R::findOne('users','id = ?',[ $json_a[$i]['id'] ]);
	                                $score++;
	                                if ($score == 61) {
	                                    break;
	                                }
	                            ?>
	                                <li class="comment">
										<div class="comment_container clearfix"> 
											<img class="avatar avatar-60 photo" src="<?php echo htmlspecialchars($comi['profile_photo']);?>" alt="" >
											<div class="comment-text">
												<p class="meta"> 
													<strong class="author"> <a href="profile.php?id=<?php echo $comi['id']?>"><?php echo htmlspecialchars($comi['name']." ".$comi['surname']);?></a></strong> 
													<span>
														<i class="fa fa-clock-o"></i> <?php echo $json_a[$i]['time'];?>
													</span> 
												</p>
												<div class="description">
													<p><?php echo htmlspecialchars($json_a[$i]['text']);?></p>
												</div>
											</div>
										</div>
	                                </li>
	                                <?php }}
	                                else {

	                                ?>	<li class="comment">
												<div class="comment-text">
													<div class="description">
														<p>Немає коментарів </p>
													</div>
												</div>
										</li><?php }?>
								</ol> <!-- /commentlist -->							
							</div> <!-- /#comments -->	
                            <?php if(!empty($errores)) {
    
  							echo "<font style='color:red'>".$errores[0]."</font>";
							} ?>
							<div class="row">
								<div class="col-md-7">
									<div class="widget-area no-padding blank">
										<div class="status-upload">
										<?php
                                        if(!empty($_SESSION['logged_user'])){    
                                        ?>
											<form action="" method='post'>												
												<textarea placeholder="Напишіть свій коментар."  name="text"></textarea>
												<button type="submit" class="btn btn-success comment" name="sellc">
													<i class="fa fa-share"></i> Відправити
												</button>
											</form><?php }
                                            
                                            else {
                                                
                                            ?>
                                            <center>
												<style>
                                                    .status-upload {
                                                        background-color: white;
                                                    }
                                                </style>
                                                <p style='background-color:white;'>Ввійдіть, щоб залишити коментар.</p>
                                            </center>
											<?php } ?>											
										</div><!-- Status Upload  -->
									</div><!-- Widget Area -->
								</div>
							</div> <!-- /row -->											
						</div> <!-- /developement-1 -->
					</div> <!-- /tab-content -->
				</div> <!-- /bg-tabs -->
			</div> <!-- /col-md-12 -->
		</div> <!-- /container -->
	</section> <!-- /PATIENT -->

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
	</footer> <!-- FOOTER -->	

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->	
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>    
    <script src='frames/smoke-pure.js'></script>
	
	<!-- ANIMATION -->
	<link rel="stylesheet" href="animate.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
	<script type="text/javascript" id="widget-wfp-script" src="https://secure.wayforpay.com/server/pay-widget.js?ref=button"></script>
    <script type="text/javascript">function runWfpWdgt(url){var wayforpay=new Wayforpay();wayforpay.invoice(url);}</script>
    
    
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

	<script>
		document.getElementById('tab').onclick = function() {
			document.getElementById('tab').classList.add('active');
			document.getElementById('tab_2').classList.remove('active');
		};

		document.getElementById('tab_2').onclick = function() {
			document.getElementById('tab').classList.remove('active');
			document.getElementById('tab_2').classList.add('active');
		};
	</script>

	<!--<script>
	$(document).ready(function(){		
		$("[data-toggle=tooltip]").tooltip();
	});
	</script> -->
	
	<script>window.onload=wfpPay();</script>
	
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
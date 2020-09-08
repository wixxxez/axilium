<?php
require "login.php";
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none'", false);
$bodolka = true;
$boolka = true ; 
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




?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Axilium">
    <meta name="keywords" content="crowdfonding, fundraising, money">
    
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="indreex.css">
	<link rel="stylesheet" href="form/index.css">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/final.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="icon" href="icons/logo1-восстановлено.png">
	<link rel="stylesheet" href='frames/smoke-pure.css'>
	<title>Axilium - веб портал для збору коштів</title>
    
    <style>
	    .block {
			background: linear-gradient( rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.9) ), 
			            url(icons/img/aerial-photography-of-water-beside-forest-during-golden-hour-1144176.jpg);
			background-position: center center;
			background-attachment: fixed;
			background-size: cover;
			width: 100%;
			height: 100vh;
			background-repeat: no-repeat; 
			z-index: 20;
			background-blend-mode: multiply;   
		}
	 	.col-md-offset-3 col-md-6 {
	    	margin-left:25%; 
	    }
    </style>

</head>
<body id="bg" class="load">
	
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
			<a href="index.php" onclick="return up()" class="logo-header mostion"><img class="axilium-logo" src="icons/logo1-восстановлено.png" alt="logo" width="70" height="45"></a>
			
			<!-- COLLAPSE BTN -->
			<div class="navbar_container">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Togglenavigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div>

			<!-- collapse -->
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a href="#" onclick="return up()" class="nav-link">Головна</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Фінансування</a>

						<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<li>
								<a href="#recomended" class="dropdown-item">
									<i class="icon-star-empty"></i>
									<span>Рекомендовані</span>
								</a>
							</li>	
							<li>
								<a href="#popular" class="dropdown-item">
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
	                <button class="menu-item menu-green" onclick='smoke.alert ("<h1>Axilium</h1><br/> Ой, вы не авторизованы! Войдите в свой аккаунт или зарегистрируйтесь.");'style="width:auto;">
						<i class="icon-plus"></i>
						Зберіть на Axilium
					</button>
	              <?php }
	            else { 
	            ?>  <a href='form/'>
	             		<button class="menu-item menu-green" style="width:auto;">
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
								<span>Вийти </span>
							</a>
						</div>					
					</div> <!-- /AVTORIZATION PROFILE --> <?php }
		            else {
		            ?>
					<!-- LOGIN FORM -->
					<button class="site-button pull-right m-t15 login" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button>
					<?php } ?>
				</div> <!-- /extra-nav -->
			</div> <!-- /collapse -->
			
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
                                            echo "Неправильний логін чи пароль..";
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
							</div>
							
							<div role="tabpanel" class="tab-pane fade" id="Section2">									
								<form class="form-horizontal" action='' method="POST">
										<?php
	                                        if(!empty($errors)){
	                                            echo "<font style='color:red'>".$errors[0]."</font>";
	                                            echo "<script>document.getElementById('id01').style.display='block';</script>";
	                                        }
	                                        if ($boolka == false) {
	                                            echo "<font style='color:red'>Твой email уже зарегестрирован или пароль должен быть не менее 8-ми символов</font>";
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
							</div>							
						</div> <!-- /tab-content -->
					</div> <!-- /tab -->
				</div><!-- /.col-md-offset-3 col-md-6 -->
			</div> <!-- /modal -->
		</nav>		
	</header> <!-- /HEADER -->
	
	<!-- MAIN IMG -->
	<div id="home" class="block">
		<div class="container home">			
			<h1 class="wow fadeInDown" data-wow-delay="0.1s">Ласкаво просимо на Axilium</h1>
			<h3 id="example" style="font-weight: bold;">веб портал для збору коштів</h3>
		</div> <!-- /container -->

		<div class="row pop-up" id="policy">
			<div class="box mx-auto">
				<div class="col-md-11">
					<p class="policy">Використовуючи наш сайт, ви підтверджуєте, що прочитали і зрозуміли 
						<a href="policy.html" class="user_policy">угоду користувача.</a> 					
					</p>
				</div>
				<div class="col-md-1">
					<a href="#" class="close-button policy">&#10006;</a>
				</div>
			</div>
		</div>		
	</div> <!-- /MAIN IMG -->

	<!-- SERVICE -->
	<section id="service" class="gray_bg section_padding pb_70">
		<div class="container">			
			<div class="row text-center">				
				<div class="col-lg-4 col-sm-6 col-xs-12">					
					<div class="single-service wow slideInLeft">
						<div class="service_icon">
							<i class="fa fa-dollar"></i>
						</div>
						<h4>Збір Коштів</h4>
						<p>Презентуйте ідею свого проекту та заручіться підтримкою спонсорів.</p>
					</div>
				</div> <!-- End Col -->				
				<div class="col-lg-4 col-sm-6 col-xs-12">					
					<div class="single-service wow slideInUp">
						<div class="service_icon">
							<i class="fa fa-pagelines"></i>
						</div>
						<h4>Розвиток</h4>
						<p>Розвивайте свою ідею, інвестуйте отримані кошти та залучайте нових людей.</p>
					</div>
				</div> <!-- End Col -->										
				<div class="col-lg-4 col-sm-6 col-xs-12">					
					<div class="single-service wow slideInRight">
						<div class="service_icon">
							<i class="fa fa-users"></i>
						</div>
						<h4>Комунікація</h4>
						<p>Знаходьте однодумців та працюйте разом для здійснення ваших мрій.</p>
					</div>
				</div> <!-- End Col -->
			</div> <!-- /row -->
		</div> <!-- /container -->		
	</section> <!-- /SERVICE -->

	<!-- INFO -->
	<section id="info" class="section section-lg info" >
		<div class="container fluid info">		
			<div class="row info">				
				<div class="col col-xs-12 text" style="background-image: url(icons/img/dol.png)">
					<div class="text_img wow fadeIn"  data-wow-delay="0.2s">
						<p>Зацікавте людей<br>своєю ідеєю, <br>зберіть спонсорів, <br>розповідайте про те, <br>що ви робите, <br>залучайте інших <br>людей у вашу справу :)</p>
					</div>
				</div>				
				<div class="col col-xs-12 img">
					<p class="photo_center"><img class="img-fluid" src="icons/img/frame1_cut.jpg" alt="Frame1"></p>
				</div>
			</div> <!-- /row -->
		</div> <!-- /container -->
	</section> <!-- /INFO -->
	
	<!-- INFO_SECOND -->
	<section id="info_second" class="section section-lg info second" style="margin-top:0%;">
		<div class="container fluid info second">			
			<div class="row info second">				
				<div class="col col-xs-12 text second" style="background-image: url(icons/img/gora.png)">
					<div class="text_img second wow fadeIn" data-wow-delay="0.2s">
						<p>Інвестуйте також в<br>ідеї, які вам сподобались, <br>діліться ними з друзями,<br>підтримуйте зачинання <br>інших. Спілкуйтесь напряму <br>з засновниками, <br>та оцінюйте <br>їхню тяжку роботу :)</p>
					</div>
				</div>				
				<div class="col col-xs-12 img second">
					<p class="photo_center second"><img class="img-fluid" src="icons/img/frame2.jpg" alt="Frame2"></p>
				</div>
			</div> <!-- /row -->
		</div> <!-- /container -->
	</section> <!-- /INFO_SECOND -->

	<!-- RECOMENDED -->
	<section id="recomended" class="section section-lg">
		<div class="pre wow zoomIn" data-wow-delay="0.2s">
			<span class="text-accent">Рекомендовані збори</span>
		</div>
		<div class="slider">			
			<div class="slider__wrapper">				
				<div class="slider__items">				
					<?php
                    $x=0;
        $patients = R::findCollection('patients','WHERE `immediantly` = 1 ORDER BY `id` DESC LIMIT 3');
        while ($roe = $patients->next()){ 
                    $x=$x+1;
                    ?>
					<div class="slider__item slider__item_<?php echo $x; ?>" >						
						<span class="slider__item_inner">							
							<span class="slider__item_img">
								<img class="slider__item_photo wow rollIn" src="<?php echo $roe->profile_image; ?>" width="570" height="330" alt="Recomended Patient`s">
							</span>							
							<span class="slider__item_testimonial">								
								<span class="slider__item_name wow slideInDown">  <?php echo $roe ->full_name; ?></span>																
								<span class="slider__item_post wow zoomInRight">
									<?php echo $roe->naglowek; ?>
								</span>								
								<span class="slider__item_text wow zoomInRight">
									<?php echo substr($roe->content,0,225)." ..."; ?>
								</span>
								<span class="slider__item_action wow slideInUp">
									<a href="patient.php?idkey=<?php echo $roe->id; ?>" class="btn btn-outline-secondary" style="color:white">Дізнатися більше</a>
								</span>
							</span>
						</span> <!-- /slider__item_inner -->
					</div> <!-- /slider__item slider__item_1 --> <?php } ?>				
				</div> <!-- /slider__items -->				
				<a class="slider__control slider__control_prev" href="#" role="button"></a>
				<a class="slider__control slider__control_next" href="#" role="button"></a>
			</div> <!-- /slider__wrapper -->
		</div> <!-- /slider -->
	</section> <!-- /RECOMENDED -->
	
	<!-- POPULAR -->
	<section id="popular" class="section section-lg popular">
		<div class="pre wow zoomIn" data-wow-delay="0.2s">
			<span class="text-accent">Популярні збори</span>
		</div>
		<div class="container popular">			
					<?php
                $x = 0;
        $patientsp = R::findCollection('patients','ORDER BY `views` DESC LIMIT 6');
        while ($row = $patientsp->next()){ 
                
                if($x%3==0){
                   echo '<div class="row">' ;
                }
                $x++;
                
                ?>
                <div class="col-sm"></div>
				<div class="col-sm">					
					<div class="card wow fadeInDown">
						<img class="card-img-top" src="<?php echo $row->profile_image; ?>" alt="Популярні Пацієнти" style= "max-height:300px;">
						<div class="card-body">							
							<div class="card-title">
								<h5><?php echo $row->full_name; ?></h5>
							</div>							
							<p class="card-text"><?php
                                echo substr($row->content,0,220).'...';
                                ?></p>
							<a href="patient.php?idkey=<?php echo $row->id; ?>" class="btn btn-outline-secondary">Пожертвуйте зараз</a>
						</div>
					</div>
                </div>
				<div class="col-sm"></div>
                <?php 
                         if($x%3==0){
                   echo '</div>' ;
                }
        
        } ?>		
			<div class="container_divider wow zoomIn" data-wow-delay="0.2s">				
				<div class="divider"></div>
			</div> <!-- /container_divider -->
		</div> <!-- /container -->
	</section> <!-- /POPULAR -->
	
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
    <script src='frames/smoke-pure.js' ></script>

	<!-- ANIMATION -->
	<link rel="stylesheet" href="animate.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
	
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

	<!-- Угода користувача -->
	<script>
		$(function() {
			$('.pop-up').hide();
			$('.pop-up').fadeIn(1000);
			
			$('.close-button').click(function (e) {			 
				$('.pop-up').fadeOut(700);
				$('#overlay').removeClass('blur-in');
				$('#overlay').addClass('blur-out');
				e.stopPropagation();
			});
		});
	</script>

	<script>
		$('#policy').show(1000, function(){
  			setTimeout(function(){
    		$('#policy').hide(500);
  			}, 3800);
		});
	</script>

	<script src="script.js"></script>	
	
</body>
</html>
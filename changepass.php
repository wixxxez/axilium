<?php
require "login.php";
$key = $_GET['key'];
$kobka = R::count('users','token = ?',[ $key ]);
if ($kobka == 0) {
    header ("Location: ooops.html");
}
else {
    $sobaka = R::findOne('users','token = ?',[ $key ]);
    $sobaka= $sobaka->export();  
}

$error = array();
if(isset($_POST['commit'])) {
    $pas = $_POST;
    if(strlen($pas['p1']) > 1) {
        if(strlen($pas['p1']) > 7){
            if($pas['p1'] == $pas['p2']){
                $newp = password_hash($pas['p1'],PASSWORD_DEFAULT);
                R::exec('UPDATE `users` SET `password` = ? WHERE `users`.`id` = ?',[ $newp, $sobaka['id'] ]);
                R::exec('UPDATE `users` SET `token` = ? WHERE `users`.`id` = ?',[ NULL, $sobaka['id'] ]);
                logined($sobaka['email'],$newp);
                header ("Location: profiles.php?id=".$sobaka['id']);   
            }
            else {
               $error[] = "<font style='color:red'>Пароли не совпадают</font>"; 
            }
        }
        else {
            $error[] = "<font style='color:red'>Пароль должен быть не менее 8 символов</font>";
        }
    }
    if(strlen($pas['p2']) > 1) {
        if($pas['p1'] == $pas['p2']){
                $newp = password_hash($pas['p1'],PASSWORD_DEFAULT);
                R::exec('UPDATE `users` SET `password` = ? WHERE `users`.`id` = ?',[ $newp, $sobaka['id'] ]);
                R::exec('UPDATE `users` SET `token` = ? WHERE `users`.`id` = ?',[ NULL, $sobaka['id'] ]);
                logined($sobaka['email'],$newp);
                header ("Location: profiles.php?id=".$sobaka['id']);   
                
            }
            else {
               $error[] =  "<font style='color:red'>Пароли не совпадают</font>"; 
            }
    }
}
?>
<!DOCTYPE html>

<html lang="en"> 
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Reset Form</title>
  <link rel="stylesheet" href="css/style.css">
  
</head>
<body>
  <section class="container">
    <div class="login">
      <h1>Input your new password, <?php echo $sobaka['name']; ?></h1>
      <?php if(!empty($error)){
    echo $error[0];
}?>
      <form method="post" action="" method='post'>
        <p><input type="password" name="p1" value="" placeholder="Password"></p>
        <p><input type="password" name="p2" value="" placeholder="Confirm password"></p>
       
        <p class="submit"><input type="submit" name="commit" value="Change password"></p>
      </form>
    </div>
    </section>
</body>
</html>

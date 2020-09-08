<?php
function update($arui){
  
    $omg=R::count('users','email = ?', [ $arui['email'] ]);
    
    
    $x=0;
    $chk = $_SESSION['logged_user'];
    
   if(strlen($arui['fname']) > 1){
        if(strlen($arui['fname']) > 2){
            
            R::exec('UPDATE `users` SET `name` = ? WHERE `users`.`id` = ?',[ $arui['fname'], $chk['id'] ]);
            $x++;
        }
        else {

             return "<font style='color:red'>Invalid name</font>";
        }
    }
    
    if(strlen($arui['lname']) > 1){
        if(strlen($arui['lname']) > 2){
            
            R::exec('UPDATE `users` SET `surname` = ? WHERE `users`.`id` = ?',[ $arui['lname'], $chk['id'] ]);
             $x++;
        }
        else {
            return "<font style='color:red'>Invalid surname</font>";
        }
    }
    if(strlen($arui['email']) > 5){
        if ($omg == 0){
            R::exec('UPDATE `users` SET `email` = ? WHERE `users`.`id` = ?',[ $arui['email'], $chk['id'] ]);
            $x++;
        }
        else {
            return "<font style='color:red'>Этот email уже зарегестрирован.</font>";
        }
    }
    if(strlen($arui['hobby']) > 1) {
        
        if (strlen($arui['hobby']) > 9) {
            R::exec('UPDATE `users` SET `hobby` = ? WHERE `users`.`id` = ?',[ $arui['hobby'], $chk['id'] ]);
             $x++;
        }
        else {
            return "<font style='color:red'>Описание хобби должо быть больше 10 символов</font>";
        }
    }
    if(strlen($arui['p1']) > 1) {
        if(strlen($arui['p1']) > 7){
            if($arui['p1'] == $arui['p2']){
                $newp = password_hash($arui['p1'],PASSWORD_DEFAULT);
                R::exec('UPDATE `users` SET `password` = ? WHERE `users`.`id` = ?',[ $newp, $chk['id'] ]);
                $x++;
            }
            else {
               return "<font style='color:red'>Пароли не совпадают</font>"; 
            }
        }
        else {
            return "<font style='color:red'>Пароль должен быть не менее 8 символов</font>";
        }
    }
    if(strlen($arui['p2']) > 1) {
        if($arui['p1'] == $arui['p2']){
                $newp = password_hash($arui['p1'],PASSWORD_DEFAULT);
                R::exec('UPDATE `users` SET `password` = ? WHERE `users`.`id` = ?',[ $newp, $chk['id'] ]);
                $x++;
            }
            else {
               return "<font style='color:red'>Пароли не совпадают</font>"; 
            }
    }
    if ($x>1){
        return "<font style='color:green'>Изменения успешно сохранены.</font>";
    }
}
?>
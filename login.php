<?php
require "connect.php";
require 'mail.php';
function logined($login,$password){
        $user=R::findOne('users','email = ?', [ $login ]);
        
        if($user) {
            if(password_verify($password,$user->password)){
                unset($_SESSION['logged_user']);
                $user=$user->export();
                $_SESSION['logged_user']=$user;
                header("Location: ".$_SERVER['HTTP_REFERER']);
               
            }
            else {
                return false;
            } 
             
        }
        else {
            return false;
        }
 
    
}
function register($email,$password,$fname,$lname){
    $us = R::count('users', 'email=?', [ $email ]);
    if (strlen($password) < 7){
        return false;
    }
    if($us == 0) {
        $useradd = R::dispense('users');
       
        $useradd -> name = $fname;
        $useradd -> surname = $lname;
        $useradd -> email = $email;
        $useradd -> password = password_hash($password, PASSWORD_DEFAULT);
        R::store($useradd);
        welcome($email,$password);
        logined($email, $password);
        
    }
    else {
        return false;
    }
}




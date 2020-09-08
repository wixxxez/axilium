<?php
require 'mail.php';
require 'connect.php';
$mail = $_GET['token'];

if ($mail == 'false') {
    header ("Location: index.php");
}
if ($mail == '') {
    header ("Location: index.php");
}
if ($mail == NULL) {
    header ("Location: index.php");
}
$information = R::count('users', 'email = ?', [ $mail ] );

function generateRandomString($length = 60) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if ($information == 0 ){
    header("Location: index.php");
}
else {
    $token = generateRandomstring();
    R::exec('UPDATE `users` SET `token` = ? WHERE `users`.`email` = ?',[ $token, $mail ]);    
    sendmess_for_resset($mail,$token);
    
}

?>
	
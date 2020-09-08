<?php
session_start();
if(!empty($_SESSION['logged_user'])){
    unset($_SESSION['logged_user']);
    #var_dump($_SERVER['HTTP_REFERER']);exit();
    if($_SERVER['HTTP_REFERER']=='https://axilium.best/form/' or $_SERVER['HTTP_REFERER']=='https://www.axilium.best/form/' or $_SERVER['HTTP_REFERER']=='https://www.axilium.best/form/index.php' or $_SERVER['HTTP_REFERER']=='https://axilium.best/form/index.php'){
        header("Location: https://www.axilium.best/");
    }
    else {
    header("Location: ".$_SERVER['HTTP_REFERER']);
    }
    
}
else {
    header ('Location: ooops.html');
}

?>
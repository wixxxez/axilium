<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
function sendmess_for_resset($mailse,$token){
// Instantiation and passing `true` enables exceptions

$mail = new PHPMailer(true);
$iin = R::findOne('users', 'email = ?', [ $mailse ]);
$iin = $iin->export();
try {
    //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'axilium.team@gmail.com';                     // SMTP username
    $mail->Password   = '*****';                               // SMTP password
    $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('axilium.team@gmail.com', 'Axilium Team');
    $mail->addAddress( $mailse, $mailse);     // Add a recipient
   

    // Attachments
  
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Axilium team';
    $mail->Body    = 'Привет '.$iin['name'].'. Я Юля помощник команды Axilium. Я здесь чтобы помочь тебе с восстановлением пароля. Перейди по ссылке ниже что бы сменить свой пароль на новый, более крутой <br/>'."http://axilium.best/changepass.php?key=".$token;


    $mail->send();
    header('Location: index.php');
} catch (Exception $e) {
    #echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
function welcome($mailse,$pass){
// Instantiation and passing `true` enables exceptions

$mail = new PHPMailer(true);
$iin = R::findOne('users', 'email = ?', [ $mailse ]);
$iin = $iin->export();
try {
    //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'axilium.team@gmail.com';                     // SMTP username
    $mail->Password   = '********';                               // SMTP password
    $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('axilium.team@gmail.com', 'Axilium Team');
    $mail->addAddress( $mailse, $mailse);     // Add a recipient
   

    // Attachments
  
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Axilium team';
    $mail->Body    = 'Привет '.$iin['name'].'. Я Юля помощник команды Axilium. Я чертовски рада видеть тебя среди нас. Ах да чуть не забыла, вот твой логин и пароль:<br/>Login:   '.$mailse.'<br/> И пароль к этому столь прекрасному логину:<b> '.$pass.'</b>';


    $mail->send();
    
} catch (Exception $e) {
    #echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
function sendmessp($mailse,$pass,$userin){
// Instantiation and passing `true` enables exceptions

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'axilium.team@gmail.com';                     // SMTP username
    $mail->Password   = '************';                               // SMTP password
    $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('axilium.team@gmail.com', 'Axilium Team');
    $mail->addAddress( $mailse['pmail'], "Patient##");     // Add a recipient
    $x=0;

    // Attachmentss
    foreach ($pass as $p){
        $mail->addAttachment($p); 
        $x++;
    }
    
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Axilium team';
    $mail->Body    = 'Привет '.$mailse['pname'].' Мы получили вашу заявку на открытие сбора средств. Вы можете внести поправки ответив на это сообщение.<br/>Имя и фамилия: '.$mailse['pname'].'<br/>Номер телефона: '.$mailse['pphone'].'<br/>Адресс email: '.$mailse['pmail'].'<br/> Дата рождения: '.$mailse['pday'].':'.$mailse['pmounth'].':'.$mailse['pyear'].'<br/>Номер паспорта: '.$mailse['ppesel'].'<br/>Адресс: '.$mailse['pcity'].'<br/>Зачем: '.$mailse['ptext'].'<br/>Сумма: '.$mailse['pprice'].'<br/>Описание: '.$mailse['pstory'].'<br/>Страница facebook: '.$mailse['ppage'].'<br/>Мы расмотрим вашу заявку и ответим в течении 24 часов.<br/> ID аккаунта: '.$userin['id'];


    $mail->send();
    for($i = 0 ; $i < $x; $i++ ){
        unlink($pass[$i]);
    }
} catch (Exception $e) {
    #echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}


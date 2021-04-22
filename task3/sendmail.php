<?php
require 'PHPMailer-6.4.0/src/Exception.php';
require 'PHPMailer-6.4.0/src/PHPMailer.php';
require 'PHPMailer-6.4.0/src/SMTP.php';

// Переменные, которые отправляет пользователь


// Формирование самого письма
$title = "Заявка с сайта.";
$body = "
<h2>Поступила новая заявка:</h2>
<b>ФИО:</b> $_POST[name]<br>
<b>Почта:</b> $_POST[email]<br>
<b>Телефон:</b> $_POST[phone]<br><br>
<b>Сообщение:</b><br>$_POST[message]";

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    //$mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    // Настройки вашей почты
    $mail->Host = 'smtp.yandex.ru';
    $mail->Port = 465;
    $mail->Username = 'PHP-TEST-MAILER@yandex.ru';
    $mail->Password = 'jkkyfjcvknrkiubm';
    //$mail->Host       = 'smtp.yandex.ru'; // SMTP сервера вашей почты
    //$mail->Username   = 'your_login'; // Логин на почте
    //$mail->Password   = 'password'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    //$mail->Port       = 465;
    $mail->setFrom('PHP-TEST-MAILER@yandex.ru', 'PHP-TEST-MAILER'); // Адрес самой почты и имя отправителя

    // Получатель письма
    $mail->addAddress('Killrad.St@yandex.ru');


// Отправка сообщения
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;
    $response = array();
    $fullname = array();
    $fullname = explode(" ", $_POST['name'],3);
    if (sizeof($fullname) < 2) $fullname[1] = '--';
    if (sizeof($fullname) < 3) $fullname[2] = '--';
// Проверяем отравленность сообщения
    if ($mail->send()) {
        $result = "success";
        $status  = "ok";
        $response['name1']= $fullname[0];
        $response['name2']= $fullname[1];
        $response['name3']= $fullname[2];
        $response['mail']= $_POST['email'];
        $response['phone']= $_POST['phone'];
        $response['msg'] = $_POST['message'];
    }
    else {$result = "error"; $status  = "mail error:\n".$mail->ErrorInfo;}

} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}

$response["result"] = $result;
$response["status"] = $status;
// Отображение результата
echo json_encode($response);
?>

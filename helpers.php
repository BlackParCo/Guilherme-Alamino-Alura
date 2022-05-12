<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');

//php mail 
function phpMail($phone, $dayofweek, $timedayofweek, $payments, $email)
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: cred_cesta@blackpar.co';

    $mensagem = 'Confirmação dos seus agendamentos <br> ';
    $mensagem .= 'Telefone: ' . $phone . "<br> Semana: " . $dayofweek . "<br> Horário: " . $timedayofweek . "<br> Forma de pagamento: " . $payments . "<br> E-mail: " . $email;
    $to = $email;
    $assunto = "Mensagem de CredCesta";

    mail($to, $assunto, $mensagem, $headers);
}
//php mail 

//format DataTime
function formatDataTime()
{
    //formatando data antes de inserir
    date_default_timezone_set('America/Sao_Paulo');
    //formatando data antes de inserir

    // Data e hora atual
    $dateTime = date("Y-m-d H:i:s");

    // Converter $datetime1 para o formato Unix timestamp
    $timestamp = strtotime($dateTime);

    // Tempo a ser subtraido - 0:00:0 horas
    $hours = 0;      // cada hora corresponde a 3600 segundos
    $minute = 0;   // cada minuto corresponde a 60 segundos
    $seconds = 0;

    // Transforma o tempo a ser subtraido em segundos
    $time = ($hours * 3600) + ($minute * 60) + $seconds;

    // Subtrair $tempo de $datetime1
    $newDataHours = $timestamp - $time;

    // Data e hora apos a subtracao
    $dateTimeNew = date("Y-m-d H:i:s", $newDataHours);
    // Data e hora apos a subtracao

    return $dateTimeNew;
}
//format DataTime

//format Time
function formatHours($text)
{
    //formatando data antes de inserir
    date_default_timezone_set('America/Sao_Paulo');
    //formatando data antes de inserir

    // Data e hora atual
    $dateTime = $text;
    // Converter $datetime1 para o formato Unix timestamp
    $timestamp = strtotime($dateTime);

    // Tempo a ser subtraido - 0:00:0 horas
    $hours = 0;      // cada hora corresponde a 3600 segundos
    $minute = 0;   // cada minuto corresponde a 60 segundos
    $seconds = 0;

    // Transforma o tempo a ser subtraido em segundos
    $time = ($hours * 3600) + ($minute * 60) + $seconds;

    // Subtrair $tempo de $datetime1
    $newDataHours = $timestamp - $time;

    // Data e hora apos a subtracao
    $newTime = date("H:i", $newDataHours);
    // Data e hora apos a subtracao

    return $newTime;
}
//format Time

//send Curl with message
function startCurl($from, $message)
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.zenvia.com/v2/channels/whatsapp/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"from": "551142808467","to": "' . $from . '","contents": [{"type": "text","text": "' . $message . '"}]}',
        CURLOPT_HTTPHEADER => array(
            'X-API-TOKEN: dFwJZvEKRMSLehyJ-ep3Q41inKkIRM6BEAUr',
            'Content-type: application/json'
        ),
    ));

    curl_exec($curl);

    curl_close($curl);
    // echo $response;
}
//send Curl with message

//connect with o database
function connectDB()
{
    static $dataBase = null;
    if ($dataBase === null) {
        $dataBase = new PDO(URL, USER, PASS, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8 COLLATE utf8_unicode_ci"
        ));
    }
    return $dataBase;
}
//connect with o database

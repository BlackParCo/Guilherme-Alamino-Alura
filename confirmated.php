<?php

//chamando paginas dinâmicas
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/helpers.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
//chamando paginas dinâmicas

if (isset($_GET['id']) && $_GET['phone'] && $_GET['email']) {

    $id = $_GET['id'];

    $phone = $_GET['phone'];

    $email = $_GET['email'];

    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //envio de mensagems
    $message = "Seu pedido foi confirmado!" . '\n' . "Referente ao E-mail : " . $email . ""; 

    startCurl($phone, $message);
    //envio de mensagems

    //atualizando o enviado com o 2 da tabela pedido
    $updateAberto = $dbh->prepare("UPDATE neonetc_dev10_pedido SET aberto = '2  ' WHERE phone = '" . $phone . "' AND id ='" . $id . "'");
    $updateAberto->execute();
    //atualizando o enviado com o 2 da tabela pedido

    header('Location: /proposals/proposal.php');
}

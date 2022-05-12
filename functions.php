<?php

//chamando paginas dinâmicas
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/helpers.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
//chamando paginas dinâmicas

//return order actual
function retornaPedidoAtual($from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //seleciona a coluna pedido da tabela tb-status onde o telefone seja igual a ele mesmo
    $get_pedido_atual = $dbh->prepare("SELECT pedido_atual FROM neonetc_dev10_tb_status WHERE phone_status = '" . $from . "'");
    $get_pedido_atual->execute();
    //seleciona a coluna pedido da tabela tb-status onde o telefone seja igual a ele mesmo

    //pegando somente um dado associado a ele mesmo e colocando em uma variavel
    $get_only_pedido_atual = $get_pedido_atual->fetch(PDO::FETCH_ASSOC);
    //pegando somente um dado associado a ele mesmo e colocando em uma variavel

    //atribuindo o dado com o pedido atual
    $pedido_atual =  $get_only_pedido_atual['pedido_atual'];
    //atribuindo o dado com o pedido atual

    //retornando o meu pedido
    return $pedido_atual;
    //retornando o meu pedido

}
//return order actual

//verify process
function initialProcess($from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //seleciona as colunas step e telefone e id da tabela tb-status onde o telefone seja igual a ele mesmo
    $getInProcess = $dbh->prepare("SELECT id,phone_status,step FROM neonetc_dev10_tb_status WHERE phone_status = '" . $from . "' ");
    $getInProcess->execute();
    //seleciona as colunas step e telefone e id da tabela tb-status onde o telefone seja igual a ele mesmo

    //pegando somente um dado associado a ele mesmo e colocando em uma variavel
    $get_only_verifyprocess = $getInProcess->fetch(PDO::FETCH_ASSOC);
    //pegando somente um dado associado a ele mesmo e colocando em uma variavel

    //atribuindo o dado com o step
    $step = $get_only_verifyprocess['step'];
    //atribuindo o dado com o step

    //verifica se o tem dado repetido se tiver ele insere na tabela tb-status o step e telefone 
    if ($getInProcess->rowcount() <= 0) {

        //insere na tabela tb-status o step e telefone 
        $insertInStepAndPhone = $dbh->prepare("INSERT INTO neonetc_dev10_tb_status ( step,phone_status ) VALUES (:st, :ph)");
        $insertInStepAndPhone->bindValue(':st', '0');
        $insertInStepAndPhone->bindValue(':ph', $from);
        $insertInStepAndPhone->execute();
        //insere na tabela tb-status o step e telefone 

        //envia mensagem com o curl setando a mensagem
        startCurl($from, "Bem-vindo ao CredCesta, digite seu nome ?");
        //envia mensagem com o curl setando a mensagem

        //retorna a data time formatada
        $dateTimeNew = formatDataTime();
        //retorna a data time formatada

        //inserindo o meu status aberto,phone,datatime na tabela pedido
        $insertStatusAndOpenAndPhoneAndDataTime = $dbh->prepare("INSERT INTO neonetc_dev10_pedido ( phone, aberto, create_at ) VALUES (:ph, :ab, :dt)");
        $insertStatusAndOpenAndPhoneAndDataTime->bindValue(':ph', $from);
        $insertStatusAndOpenAndPhoneAndDataTime->bindValue(':ab', '1');
        $insertStatusAndOpenAndPhoneAndDataTime->bindValue(':dt', $dateTimeNew);
        $insertStatusAndOpenAndPhoneAndDataTime->execute();
        //inserindo o meu status aberto,phone,datatime na tabela pedido

        //pegando o ultimo id que foi inserido
        $id_pedido = $dbh->lastInsertId();
        //pegando o ultimo id que foi inserido

        //atualizando para step 0 e pedido atual que seja o ultimo dado inserido na tabela pedido, e na tabela tb-status onde o telefone seja igual o telefone
        $updateInStepAndPedido = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = '0' , pedido_atual = '" . $id_pedido . "' WHERE phone_status = '" . $from . "'");
        $updateInStepAndPedido->execute();
        //atualizando para step 0 e pedido atual que seja o ultimo dado inserido na tabela pedido, e na tabela tb-status onde o telefone seja igual o telefone

        die();

    } else if ($step === '-1') {
        //verifica se o step recebido está com resultado -1 se tiver ele faz um update e começa um novo registro

        //atualizando para step 0 na tabela tb-status onde o telefone seja igual o telefone
        $updateInStep = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = :st  WHERE phone_status = :ph ");
        $updateInStep->bindValue(':st', '0');
        $updateInStep->bindValue(':ph', $from);
        $updateInStep->execute();
        //atualizando para step 0 na tabela tb-status onde o telefone seja igual o telefone

        //retorna a data time formatada
        $dateTimeNew = formatDataTime();
        //retorna a data time formatada

        //inserindo o meu status aberto,phone,aberto,datatime na tabela pedido
        $insertPhoneAndOpenAndDataTime = $dbh->prepare("INSERT INTO neonetc_dev10_pedido ( phone, aberto, create_at ) VALUES (:ph, :ab, :dt)");
        $insertPhoneAndOpenAndDataTime->bindValue(':ph', $from);
        $insertPhoneAndOpenAndDataTime->bindValue(':ab', '1');
        $insertPhoneAndOpenAndDataTime->bindValue(':dt', $dateTimeNew);
        $insertPhoneAndOpenAndDataTime->execute();
        //inserindo o meu status aberto,phone,aberto,datatime na tabela pedido

        //pegando o ultimo id que foi inserido
        $id_pedido = $dbh->lastInsertId();
        //pegando o ultimo id que foi inserido

        //atualizando para step 0 e pedido atual na tabela tb-status onde o telefone seja igual o telefone
        $updateInStepAndPedido = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = '0' , pedido_atual = '" . $id_pedido . "' WHERE phone_status = '" . $from . "'");
        $updateInStepAndPedido->execute();
        //atualizando para step 0 e pedido atual na tabela tb-status onde o telefone seja igual o telefone

        //envia mensagem com o curl setando a mensagem
        startCurl($from, "Bem-vindo ao CredCesta, digite seu nome ?");
        //envia mensagem com o curl setando a mensagem
        die();

    }
}
//verify process

//consult step
function consultStep($from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //seleciona as colunas step e pedido_atual na tabela tb-status onde o telefone seja igual ao telefone,
    $consultStep = $dbh->prepare("SELECT step,pedido_atual FROM neonetc_dev10_tb_status WHERE phone_status = '" . $from . "' ORDER BY id DESC LIMIT 1");
    $consultStep->execute();
    //seleciona as colunas step e pedido_atual na tabela tb-status onde o telefone seja igual ao telefone,

    //pegando somente um dado associado a ele mesmo
    $get_only_step = $consultStep->fetch(PDO::FETCH_ASSOC);
    //pegando somente um dado associado a ele mesmo

    //retornando o meu step
    return $get_only_step;
    //retornando o meu step
}
//consult step

//send Name 
function sendNome($text, $from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //chama função e coloca na variavel o meu pedido atual
    retornaPedidoAtual($from);
    //chama função e coloca na variavel o meu pedido atual

    //atualizando para step 1 e nome na tabela tb-status onde o telefone seja igual o telefone
    $updateInNameAndStep = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET nome = :nm , step = :st  WHERE phone_status = :ph");
    $updateInNameAndStep->bindValue(':nm', $text);
    $updateInNameAndStep->bindValue(':st', '1');
    $updateInNameAndStep->bindValue(':ph', $from);
    $updateInNameAndStep->execute();
    //atualizando para step 1 e nome na tabela tb-status onde o telefone seja igual o telefone

    //envia mensagem com o curl setando a mensagem
    startCurl($from, "Hey, " . $text . " :)" . '\n' . "Escreva um *Dia* *útil* *da* *semana* *?* ");
    //envia mensagem com o curl setando a mensagem
}
//send Name 

//send DayofWeek
function sendDayofWeek($text, $from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //chama função e coloca na variavel o meu pedido atual
    $pedido_atual = retornaPedidoAtual($from);
    //chama função e coloca na variavel o meu pedido atual

    //atualiza o meu dayofweek,phone,aberto,create_at na tabela pedido
    $updateDayOfWeek = $dbh->prepare("UPDATE neonetc_dev10_pedido SET dayofweek = :dw  WHERE phone = :ph AND id = '" . $pedido_atual . "'");
    $updateDayOfWeek->bindValue(':dw', $text);
    $updateDayOfWeek->bindValue(':ph', $from);
    $updateDayOfWeek->execute();
    //atualiza o meu dayofweek,phone,aberto,create_at na tabela pedido

    //atualizando para step 2 e pedido atual na tabela tb-status onde o telefone seja igual o telefone e o pedido seja igual ao meu pedido
    $updateInStepAndPedido = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = '2' WHERE phone_status = '" . $from . "' AND pedido_atual = '" . $pedido_atual . "'");
    $updateInStepAndPedido->execute();
    //atualizando para step 2 e pedido atual na tabela tb-status onde o telefone seja igual o telefone e o pedido seja igual ao meu pedido

    //envia mensagem com o curl setando a mensagem
    startCurl($from, "Digite um horário das *10:00* ás *18:00* ?");
    //envia mensagem com o curl setando a mensagem

}
//send DayofWeek

//send Time 
function sendTime($newTime, $from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //chama função e coloca na variavel o meu pedido atual
    $pedido_atual = retornaPedidoAtual($from);
    //chama função e coloca na variavel o meu pedido atual

    //atualizando a tabela pedido na coluna timedayweek onde o telefone seja igual a ele mesmo e o id seja igual a o pedido atual
    $updateInTime = $dbh->prepare("UPDATE neonetc_dev10_pedido SET timedayweek = :nt WHERE phone = :ph AND id = :id_atual");
    $updateInTime->bindValue(':nt', $newTime);
    $updateInTime->bindValue(':ph', $from);
    $updateInTime->bindValue(':id_atual', $pedido_atual);
    $updateInTime->execute();
    //atualizando a tabela pedido na coluna timedayweek onde o telefone seja igual a ele mesmo e o id seja igual a o pedido atual

    //atualizando para step 3 na tabela tb-status onde o telefone seja igual o telefone e o pedido atual seja igual a ele mesmo
    $updateInStep = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = '3' WHERE phone_status = '" . $from . "' AND pedido_atual = '" . $pedido_atual . "'");
    $updateInStep->execute();
    //atualizando para step 3 na tabela tb-status onde o telefone seja igual o telefone e o pedido atual seja igual a ele mesmo

    //envia mensagem com o curl setando a mensagem
    startCurl($from, "Obrigado, agora escreva a Forma de pagamento ?" . '\n' . "*(Débito)*" . '\n' . "*(Crédito)*" . '\n' . "*(Pix)*");
    //envia mensagem com o curl setando a mensagem

}
//send Time 

//send Payments
function sendPayments($text, $from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //chama função e coloca na variavel o meu pedido atual
    $pedido_atual = retornaPedidoAtual($from);
    //chama função e coloca na variavel o meu pedido atual

    //atualizando a tabela pedido na coluna payaments onde o telefone seja igual a ele mesmo e o id seja igual a o pedido atual
    $updateInPaymnents = $dbh->prepare("UPDATE neonetc_dev10_pedido SET payments = :nm WHERE phone = :ph AND id = :id_atual");
    $updateInPaymnents->bindValue(':nm', $text);
    $updateInPaymnents->bindValue(':ph', $from);
    $updateInPaymnents->bindValue(':id_atual', $pedido_atual);
    $updateInPaymnents->execute();
    //atualizando a tabela pedido na coluna payaments onde o telefone seja igual a ele mesmo e o id seja igual a o pedido atual

    //atualizando para step 4 na tabela tb-status onde o telefone seja igual o telefone e o pedido atual seja igual a ele mesmo
    $updateInPaymnentsAndStep = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = '4' WHERE phone_status = '" . $from . "' AND pedido_atual = '" . $pedido_atual . "'");
    $updateInPaymnentsAndStep->execute();
    //atualizando para step 4 na tabela tb-status onde o telefone seja igual o telefone e o pedido atual seja igual a ele mesmo

    //envia mensagem com o curl setando a mensagem
    startCurl($from, "Reserva em andamento, me fala seu e-mail ?");
    //envia mensagem com o curl setando a mensagem

}
//send Payments

//Atualiza o Email onde o telefone é igual a ele mesmo, e o id é igual a minha consulta no banco de dados da tabela tb-status no campo pedido_atual
function sendEmail($text, $from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //chama função e coloca na variavel o meu pedido atual
    $pedido_atual = retornaPedidoAtual($from);
    //chama função e coloca na variavel o meu pedido atual

    //atualizando o e-mail onde o telefone seja igual a ele mesmo e id seja igual ao pedido atual
    $updateInEmail = $dbh->prepare("UPDATE neonetc_dev10_pedido SET email = '" . $text . "' WHERE phone ='" . $from . "' AND  id = '" . $pedido_atual . "'");
    $updateInEmail->execute();
    //atualizando o e-mail onde o telefone seja igual a ele mesmo e id seja igual ao pedido atual

    //envia mensagem com o curl setando a mensagem
    startCurl($from, "Foi confirmada a sua reserva, acabei de te enviar no e-mail *" . $text . "* ");
    //envia mensagem com o curl setando a mensagem

    //atualizando o step para 5 onde o telefone seja igual o telefone, e o pedido atual seja igual a ele mesmo
    $updateInStep = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = '5' WHERE phone_status = $from AND pedido_atual = '" . $pedido_atual . "'");
    $updateInStep->execute();
    //atualizando o step para 5 onde o telefone seja igual o telefone, e o pedido atual seja igual a ele mesmo

    //seleciona todos as colunas da tabela pedido onde o telefone seja igual ao telefone, e id seja igual ao meu pedido da tabela do pedido
    $getAllData = $dbh->prepare("SELECT * FROM neonetc_dev10_pedido WHERE phone = :ph AND id = '" . $pedido_atual . "'");
    //seleciona todos as colunas da tabela pedido onde o telefone seja igual ao telefone, e id seja igual ao meu pedido da tabela do pedido

    //executa e atribui a sigla :ph a seleção do $from que vem sendo verificada na tabela, e transforma em array e buscando se existe o erro da ultima execução
    if ($getAllData->execute(array(':ph' => $from))) {
        print_r($getAllData->errorInfo);
    }
    //executa e atribui a sigla :ph a seleção do $from que vem sendo verificada na tabela, e transforma em array e buscando se existe o erro da ultima execução

    //pegando somente um dado associado a ele mesmo
    $itens = $getAllData->fetch(PDO::FETCH_ASSOC);
    //pegando somente um dado associado a ele mesmo

    //variaveis recebendo cada item da tabela pedido
    $phone = $itens['phone'];
    $dayofweek = $itens['dayofweek'];
    $timedayofweek = $itens['timedayweek'];
    $payments = $itens['payments'];
    $email = $itens['email'];
    //variaveis recebendo cada item da tabela pedido

    //envia o email com as variaveis adicionadas acima
    phpMail($phone, $dayofweek, $timedayofweek, $payments, $email);
    //envia o email com as variaveis adicionadas acima

    //chama função de step -1 na tabela tb-status, e pedido 0 fechado na tabela pedido
    closeOrder($from);
    //chama função de step -1 na tabela tb-status, e pedido 0 fechado na tabela pedido

}
//Atualiza o Email onde o telefone é igual a ele mesmo, e o id é igual a minha consulta no banco de dados da tabela tb-status

//fecha ordem de pedido apos enviar o e-mail e iniciar com o *
function closeOrder($from)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //chama função e coloca na variavel o meu pedido atual
    $pedido_atual = retornaPedidoAtual($from);
    //chama função e coloca na variavel o meu pedido atual

    //atualizando o pedido para fechado com o 0 da tabela pedido
    $updateAberto = $dbh->prepare("UPDATE neonetc_dev10_pedido SET aberto = '0' WHERE phone = '" . $from . "' AND id = '" . $pedido_atual . "'");
    $updateAberto->execute();
    //atualizando o pedido para fechado com o 0 da tabela pedido

    //atualizando o step -1 e o pedido atual 0 na tabela status, ao finalizar o envio de e-mail onde o telefone seja igual a ele mesmo.
    $updateInStepAndPedido = $dbh->prepare("UPDATE neonetc_dev10_tb_status SET step = '-1', pedido_atual = '0' WHERE phone_status = '" . $from . "'");
    $updateInStepAndPedido->execute();
    //atualizando o step -1 e o pedido atual 0 na tabela status , ao finalizar o envio de e-mail onde o telefone seja igual a ele mesmo.

}
//fecha ordem de pedido apos enviar o e-mail e iniciar com o *

//função verifica na lista se repete o uid unico se existe para execução, se não insere
function verifyListWithDataRepeat($dataText, $id)
{
    //conexão com o banco
    $dbh = connectDB();
    //conexão com o banco

    //imprime a informação em forma de array
    $listOfFrom = print_r($dataText, true);
    //imprime a informação em forma de array

    //seleciona a coluna uid da tabela neonetc_dev10_mensagens onde o uid é igual a ele mesmo
    $verifyUid = $dbh->prepare("SELECT uid FROM neonetc_dev10_mensagens WHERE uid = :ids ");
    $verifyUid->bindValue(':ids', $id);
    $verifyUid->execute();
    //seleciona a coluna uid da tabela neonetc_dev10_mensagens onde o uid é igual a ele mesmo

    //pegando somente um dado associado a ele mesmo
    $uid = $verifyUid->fetch(PDO::FETCH_ASSOC);
    //pegando somente um dado associado a ele mesmo

    //verifico se existe algum dado igual a ele mesmo e se ele é maior que 0 ou seja se ele se replica.
    if ($verifyUid->rowcount() > 0) {
        error_log("response 2 = " . print_r($uid, true), 0);
        die();
    }
    //verifico se existe algum dado igual a ele mesmo e se ele é maior que 0 ou seja se ele se replica.

    //inserindo o meu uid unico e a lista de array com os dados do weebhook
    $insertInResponseAndUid = $dbh->prepare("INSERT INTO neonetc_dev10_mensagens ( response,uid ) VALUES (:rs, :id)");
    $insertInResponseAndUid->bindValue(':rs', $listOfFrom);
    $insertInResponseAndUid->bindValue(':id', $id);
    $insertInResponseAndUid->execute();
    //inserindo o meu uid unico e a lista de array com os dados do weebhook

}
//função verifica na lista se repete o uid unico se existe para execução, se não insere

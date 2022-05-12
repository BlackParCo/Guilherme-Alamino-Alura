<?php
//chamando paginas dinâmicas
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/functions.php');
//chamando paginas dinâmicas

//pegando dado que chega no arquivo
$dataBody = file_get_contents("php://input");
//pegando dado que chega no arquivo

//decodificando o dado que chega no body
$dataText = json_decode($dataBody, true);
//decodificando o dado que chega no body

//parseando o JSON em variaveis
$text = $dataText['message']['contents'][0]['text'];
$from = $dataText['message']['from'];
$id = $dataText['id'];
//parseando o JSON em variaveis

//verificando se o dado que recebe é repetido
verifyListWithDataRepeat($dataText, $id, $dbh);
//verificando se o dado que recebe é repetido

//ao enviar a primeira ou segunda mensagem cai em condição 
initialProcess($from, $dbh);
//ao enviar a primeira ou segunda mensagem cai em condição 

//minimo de caracteres digitado
$min_length = 3;
//minimo de caracteres digitado

//array com dias utéis
$dayOfWeek = array('segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira');
//array com dias utéis

//array com formas de pagamentos 
$payments = array('Débito', 'Crédito', 'Pix', 'débito', 'crédito', 'pix');
//array com formas de pagamentos 

//pega o step em que o usúario foi redirecionado apos a primeira entrada ou segunda de mensagem
$get_only_step = consultStep($from);
//pega o step em que o usúario foi redirecionado apos a primeira entrada ou segunda de mensagem

//se digitar * reinicia o fluxo e manda mensagem fluxo reiniciado
if ($text === "*") {

    //chama função de step -1 na tabela tb-status, e pedido 0 fechado na tabela pedido
    closeOrder($from);
    //chama função de step -1 na tabela tb-status, e pedido 0 fechado na tabela pedido
    
    //envia mensagem com o curl setando a mensagem
    $message = "Mande um *( Oi )* , para iniciarmos novamente!";

    startCurl($from, $message);
    //envia mensagem com o curl setando a mensagem

    //encerra a etapa
    die();
    //encerra a etapa

}

//verifica se o step é igual a 0
if ($get_only_step['step'] == 0) {

    //verifica se o meu texto é uma string e se for maior ou igual a 3 envia o nome, se não houver o requisito não faz nada 
    if (strlen($text) >= $min_length) {

        //função envia o nome
        sendNome($text, $from);
        //função envia o nome

    }
    //verifica se o meu texto é uma string e se for maior ou igual a 3 envia o nome, se não houver o requisito não faz nada 

    //encerra a etapa
    die();
    //encerra a etapa

}
//verifica se o step é igual a 0

//verifica se o step é igual a 1 
if ($get_only_step['step'] == 1) {

    //se houver similaridade do texto ao array de semanas util entra na condição 
    if (in_array($text, $dayOfWeek)) {

        //se no texto houver dentro do array que não são os dias uteis cai na condição
        if (in_array($text, array('sábado', 'domingo', 'Sábado', 'Domingo'))) {

            //envia mensagem com o curl setando a mensagem
            $message = "Desculpe, " . $text . " não é um dia útil";

            startCurl($from, $message);
            //envia mensagem com o curl setando a mensagem

        } else {

            //verifica se no texto corresponde ao array com dias utéis se corresponder cai na condição
            if (in_array($text, array('segunda', 'terça', 'quarta', 'quinta', 'sexta', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira'))) {

                //envia a semana com o dia util para a função de inserir 
                sendDayofWeek($text, $from);
                //envia a semana com o dia util para a função de inserir 

            } else {

                //envia mensagem com o curl setando a mensagem
                $message = "Desculpe, " . $text . " não é um dia útil";

                startCurl($from, $message);
                //envia mensagem com o curl setando a mensagem

            }
            //verifica se no texto corresponde ao array com dias utéis se corresponder cai na condição

        }
        //se no texto houver dentro do array que não são os dias uteis cai na condição

    } else {

        //envia mensagem com o curl setando a mensagem
        $message = "Desculpe, " . $text . " não é um dia útil";

        startCurl($from, $message);
        //envia mensagem com o curl setando a mensagem

    }
    //se houver similaridade do texto ao array de semanas util entra na condição 

    //encerra a etapa
    die();
    //encerra a etapa

}
//verifica se o step é igual a 1 

//verifica se o step é igual a 2 
if ($get_only_step['step'] == 2) {

    $newTime = formatHours($text);

    //verifica se o numero que chega na variavel newTime é igual ou maior a 10:00 e menor ou igual a 18:00 se for cai na condição
    if ($newTime >= '10:00' && $newTime <= '18:00') {

        //envia a data para a função que inserir 
        sendTime($newTime, $from);
        //envia a data para a função que inserir 

    } else {

        //envia mensagem com o curl setando a mensagem
        $message = "Desculpe esse horário *" . $text . "* não pertence ao grupo de horários";

        startCurl($from, $message);
        //envia mensagem com o curl setando a mensagem

    }
    //verifica se o numero que chega na variavel newTime é igual ou maior a 10:00 e menor ou igual a 18:00 se for cai na condição

    //encerra a etapa
    die();
    //encerra a etapa

}
//verifica se o step é igual a 2 

//verifica se o step é igual a 3 
if ($get_only_step['step'] == 3) {

    //se houver similaridade do texto ao array de semanas util entra na condição 
    if (in_array($text, $payments)) {

        //verifica se no texto corresponde ao array com dias utéis se corresponder cai na condição
        if (in_array($text, array('Débito', 'Crédito', 'Pix', 'débito', 'crédito', 'pix'))) {

            //envia mensagem com o curl setando a mensagem
            $message = "Obrigado pela escolha do *" . $text . "* vou registrar no sistema *Espere um momento*";

            startCurl($from, $message);
            //envia mensagem com o curl setando a mensagem

            sleep(5);

            //envia o pagamento para a função de inserir
            sendPayments($text, $from);
            //envia o pagamento para a função de inserir

        } else {

            //envia mensagem com o curl setando a mensagem
            $message = "Desculpe, " . $text . " não é válida";

            startCurl($from, $message);
            //envia mensagem com o curl setando a mensagem

        }
        //verifica se no texto corresponde ao array com dias utéis se corresponder cai na condição

    } else {

        //envia mensagem com o curl setando a mensagem
        $message = "Desculpe, " . $text . " não é válida";

        startCurl($from, $message);
        //envia mensagem com o curl setando a mensagem

    }
    //se houver similaridade do texto ao array de semanas util entra na condição 

    //encerra a etapa
    die();
    //encerra a etapa

}
//verifica se o step é igual a 3 

//verifica se o step é igual a 4 
if ($get_only_step['step'] == 4) {

    //se o texto for valido pela função do php de email ele passa para a condição
    if (filter_var($text, FILTER_VALIDATE_EMAIL)) {

        //enviar o email para a função de inserir
        sendEmail($text, $from);
        //enviar o email para a função de inserir

    }
    //se o texto for valido pela função do php de email ele passa para a condição

    //encerra a etapa
    die();
    //encerra a etapa

}
//verifica se o step é igual a 4 

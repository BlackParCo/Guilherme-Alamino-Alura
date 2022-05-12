<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/functions.php');

session_start();

if (!isset($_SESSION['usuario']) &&  $_SESSION['pass']) {
    header('location:login.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <!-- bootstrap -->
    <!-- style extern -->
    <link rel="stylesheet" href="./css/styles_proposal.css" />
    <!-- style extern -->
    <!-- data-table -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- data-table -->

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <!-- data table script-->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- data table script-->

    <!-- data table script-->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"></script>
    <!-- data table script-->
    <title>Auditoria Digital</title>
</head>

<body>


    <div class="container">

        <center><img src="./img/credcesta.png" width="150" class="pb-3 pt-3"></center>

    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-red">
        <div class="container">
            <a class="navbar-brand" href="#"></a>

            <div class="navbar-collapse d-flex justify-content-center" id="navbarSupportedContent">

                <span class="navbar-text w-100" id="title">
                    PROPOSTAS
                </span>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <!-- <a class="item-menu" href="home">Home</a> -->
                <!-- <a class="item-menu" href="proposals">Propostas</a> -->
                <!-- <a class="item-menu" href="settings">Settings</a> -->
                <!-- <a class="item-menu" href="https://auditadigital.com.br/proposals/msgs-chatbot">Msgs chatbot</a> -->
                <a style="border:none;" class="item-menu" href="./login.php">Sair</a>
                <!-- <a href="users" class="btn btn-danger" style="margin-left:40px;">Usuários</a> -->

            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">

            <div class="col-lg-12">

                <div class="box-card">

                    <a href="./proposal.php" class="btn btn-danger">Atualizar Propostas</a>
                    <!-- <a href="https://auditadigital.com.br/proposals/proposals" class="btn btn-primary">Propostas de hoje</a> | -->
                    <!-- <a href="https://auditadigital.com.br/proposals/proposals?status=validar-manualmente" class="btn btn-outline-primary">Validar Manualmente</a> -->
                    <!-- <div class="nav justify-content-end">
                        <a href="/proposals/export"><button type="button" class="btn btn-success btn-sm">Exportar XLS</button></a>
                    </div> -->

                    <hr>
                    <div class="py-3">
                        <table class="table table-striped table-responsive" id="myTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Telefone</th>
                                    <th>Dia da Semana</th>
                                    <th>Horário da Semana</th>
                                    <th>Forma de Pagamento</th>
                                    <th>E-mail</th>
                                    <th>Aberto</th>
                                    <th>Criado em</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //conexão com o banco
                                $dbh = connectDB();
                                //conexão com o banco
                                $selectAllData = $dbh->prepare("SELECT * FROM neonetc_dev10_pedido"); //prepare
                                $selectAllData->execute();
                                while ($row = $selectAllData->fetch(PDO::FETCH_ASSOC)) {
                                    $id = $row["id"];
                                    $phone = $row["phone"];
                                    $email = $row["email"];
                                    echo '<tr>';
                                    echo '<td>' . $row["id"] . '</td>';
                                    echo '<td>' . $row["phone"] . '</td> ';
                                    echo '<td>' . $row["dayofweek"] . '</td>';
                                    echo '<td>' . $row["timedayweek"] . '</td>';
                                    echo '<td>' . $row["payments"] . '</td>';
                                    echo '<td>' . $row["email"] . '</td>';
                                    echo '<td>' . $row["aberto"] . '</td>';
                                    echo '<td>' . $row["create_at"] . '</td>';
                                    echo '<td>' . '<a class="button-send" href="https://www.parouimpar.livewood.com.br/services/confirmated.php?id=' . $id . '&phone=' . $phone . '&email=' . $email . '">Confirmar</a>' . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- jquery extern -->
    <script src="./js/script.js"></script>
    <!-- jquery extern -->

    <!-- jquery bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    <!-- jquery bootstrap -->

    <br /><br /><br /><br />
</body>

</html>
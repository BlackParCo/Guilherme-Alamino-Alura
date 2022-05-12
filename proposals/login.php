<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/services/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/functions.php');

session_start();

if (isset($_POST['login'], $_POST['senha'])) {
    if ($_POST['login'] == 'GuilhermeAlamino' && $_POST['senha'] == '15793') {
        $_SESSION['usuario'] = $_POST['login'];
        $_SESSION['pass'] = $_POST['senha'];
        header('location: proposal.php');
    }
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
    <link rel="stylesheet" href="./css/styles_login.css" />
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

    <div class="container-fluid bg-white">
        <center><img src="./img/credcesta.png" width="150" class="pb-3 pt-3"></center>
    </div>

    <div class="container">
        <div class="row">

            <div class="col-lg-4 offset-md-4 text-center">

                <br /><br /><br />

                <div class="box-login">
                    <h4>Fazer Login</h4><br />

                    <form action="" method="POST">

                        <input type="text" name="login" id="login" class="cx" placeholder="Username" />
                        <input type="password" name="senha" login="senha" class="cx" placeholder="Senha" />

                        <br /><br />

                        <input type="submit" name="button-form" class="btn button-primary" value="Acessar" />

                        <br /><br />

                    </form>

                </div>

                <br />

                <span class="text-muted"><a href="proposals/forgot-password" class="color-white">Esqueceu sua senha? Clique aqui</a></span>

            </div>

        </div>
    </div>

    <br /><br /><br />
    <!-- jquery extern -->
    <script src="./js/script.js"></script>
    <!-- jquery extern -->

    <!-- jquery bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    <!-- jquery bootstrap -->

</body>

</html>
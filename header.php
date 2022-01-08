<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fontawesome-all.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans:300" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>Project Web 2018</title>
</head>

<body class="text-center" >
    <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top" >
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="img/logo.png" width="120"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php"><i class="fas fa-home fa-md"></i> Αρχική</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#contact" id="gotocontact"><i class="fas fa-envelope fa-md"></i> Επικοινωνία</a>
                    </li>
                    <?php if (isset($_SESSION['u_email']) || isset($_SESSION['e_uname'])) { ?>
                            <form class="form" action="includes\logout.php" method="POST">
                            <button class="btn btn-light" type="submit" name="submit" ><i class="fas fa-sign-out-alt"></i>Αποσύνδεση</button>
                            </form>
                    <?php } else { ?>
                            <li class="dropdown" id="aaa">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"><i class="fas fa-sign-in-alt"></i> Σύνδεση Προσωπικού</button>
                                <form class="dropdown-menu p-2" action="includes\employee_login.php" method="POST" name="e_login_form">
                                    <div class="form-group">
                                        <label for="exampleDropdownFormEmail2">Όνομα Χρήστη</label>
                                        <input class="form-control" id="inlineFormInputGroup" placeholder="Όνομα Χρήστη" required="" type="text" name="e_uname">
                                    </div>
                                     <div class="form-group">
                                        <label for="inlineFormInputGroup">Κωδικός Πρόσβασης</label>
                                        <input class="form-control" id="inlineFormInputGroup" placeholder="Kωδικός Πρόσβασης" required="" type="password" name="e_pwd">
                                    </div>
                                <button type="submit" class="btn btn-secondary btn-block" name="submit">Σύνδεση</button>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>
    </div></nav>
    <div id="home">
<?php

$conn = new mysqli("localhost", "root", "", "mypurse");
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué: " . $conn->connect_error);
}

$monthList = cal_info(0)['months'];

?>    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf8_general_ci" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="MyPurse" />
    <meta name="author" content="Audrey DENOUAL" />
    <title>MyPurse</title>

    <!-- Stylesheet -->
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
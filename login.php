<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/dbquery.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/shareAll.css">
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body>


    <?php
    //Include functions:
    include 'functions.php';

    //Initialize : starting session, connecting db etc...
    include 'initialize.php';

    //Include header:
    include 'header.php';



    include 'loginCode.php';

    ?>


  </body>
</html>
<?php
  session_start();
  //Unset session value
  unset($_SESSION['loggedIn']);
  unset($_SESSION['firstName']);
  unset($_SESSION['lastName']);
  unset($_SESSION['email']);
  unset($_SESSION['password']);


  header("Location: https://" . $_SERVER["HTTP_HOST"] . "/kycheung/A4/IAT352-A4/login.php");

?>

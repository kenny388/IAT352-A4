<?php
  session_start();
  //Unset session value
  unset($_SESSION['loggedIn']);
  unset($_SESSION['firstName']);
  unset($_SESSION['lastName']);
  unset($_SESSION['email']);
  unset($_SESSION['password']);


  header('Location: login.php');

?>

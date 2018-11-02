<?php
  ob_start(); // output buffering is turned on

  session_start(); // turn on sessions

  require_once('functions.php');
  require_once('private/database.php');
  // require_once('query_functions.php');
  require_once('private/validation_functions.php');

  $db = db_connect();
  $errors = [];

?>

<?php
  //This script is called if player clicked on adding to watchlist model without logging in
  //Set the callback url to addToWatchList page
  session_start();
  $_SESSION["callback_url"] = '/kycheung/A4/IAT352-A4/addtowatchlist.php';

  //After setting callback, redirect to register page with https on 
  header("Location: https://" . $_SERVER["HTTP_HOST"] . "/kycheung/A4/IAT352-A4/login.php");
 ?>

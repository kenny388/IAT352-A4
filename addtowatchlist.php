 <!DOCTYPE html>
 <html lang="en">
   <head>
     <meta charset="utf-8">
     <title>AddToWatchlist</title>
     <link rel="stylesheet" href="css/shareAll.css">
     <link rel="stylesheet" href="css/header.css">
     <link rel="stylesheet" href="css/showmodels.css">


   </head>
   <body>
     <?php

     include('private/db_credentials_watchlist.php');

     //Start Session
     session_start();

     //If there is data submitted by the form from modeldetails
     if (isset($_POST['submit'])) {
       //Receives and assign the modelID and email we need
       $modelID = $_POST['modelID'];
       $email = $_SESSION['email'];
       echo $modelID;
       echo $email;
     } else {
       //If there is not, just redirect to all-models page
       header('Location: showmodels.php');
     }

     //Establish connection
     $connection = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

     //Check if connection is succeeded
     if(mysqli_connect_errno()) {
       echo '<br>';
       die("Database connection failed: " .
            mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")"
       );
     }

     //Start inserting data
     $query = "INSERT INTO watchlist (email, model_id) VALUES ('{$email}', '{$modelID}')";

     $result = @mysqli_query($connection, $query);

     if (!$result) {
       die("Database query failed.");
     } else {
       header('Location: watchlist.php');
     }






     ?>

   </body>
 </html>

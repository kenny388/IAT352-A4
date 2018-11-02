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
     //Include the credentials for the watchlist database, which locates at a private folder
     include('private/db_credentials_watchlist.php');

     //Start Session
     session_start();

     //array for storing this account's existing watchlist imagecreatefromstring
     $modelsWatched = array();

     //If there is data submitted by the form from modeldetails
     if (isset($_POST['submit'])) {
       //Receives and assign the modelID and email we need
       $modelID = $_POST['modelID'];
       $email = $_SESSION['email'];
       //If the there is not a for submitted, and there is lastViewedModelId, this is the case
       //When a user clicked on Add to watchlist button without logging in, and has logged back in right away
       //This block of code add assign the last viewed model id to $modelID, for later inserting
     } else if (isset($_SESSION['lastViewedModelId'])) {
       $modelID = $_SESSION['lastViewedModelId'];
       $email = $_SESSION['email'];
     } else {
       //If there is not
       //That means the user just stumble upon this page with no reason
       //just redirect to all-models page
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



     //OBTAINING the existing list of models in the user's watchlist

     //Starting query to extract the watchlist
     $query = "SELECT * ";

     //Continue to add the next parts FROM
     $query .= "FROM watchlist ";

     //Starting Last Part of the Query - WHERE Clause
     $query .= "WHERE ";

     //where email = email extracted in session
     $query .= "email = '{$email}'";

     //Execute the query
     $result = @mysqli_query($connection, $query);

     //If executing query failed, print and stop the page
     if (!$result) {
       die("Database query failed.");
     } else {
       //Success!
       //Each Loop of fetching data
       while ($row = @mysqli_fetch_assoc($result)) {
         $temp = $row['model_id'];
         //append them onto an array
         array_push($modelsWatched, $temp);
       }
     }

     //If the watch list user have does not have the model
     $haveModel = false;
     foreach ($modelsWatched as $watched) {
       if ($modelID == $watched) {
         $haveModel = true;
       }
     }

     //Free $result from memory at the end
     mysqli_free_result($result);

     //Only run the insert data code if the user DOES NOT have the model
     if (!$haveModel) {
     //Start inserting data
     $query = "INSERT INTO watchlist (email, model_id) VALUES ('{$email}', '{$modelID}')";

     //Connect and get result
     $result = @mysqli_query($connection, $query);

       if (!$result) {
         //if failed
         //No need for another time for callback url as it has already been done., and the user is logged in
         unset($_SESSION['callback_url']);
         //Provide session message for watchlist.php to tell user that the database insert is successful
         $_SESSION['message'] = "The model has failed to add to your watchlist";
         //after, redirect back to watchlist
         header('Location: watchlist.php');
         die("Database query failed.");

       } else {
         //Insert success
         //No need for another time for callback url as it has already been done., and the user is logged in
         unset($_SESSION['callback_url']);
         //Provide session message for watchlist.php to tell user that the database insert is successful
         $_SESSION['message'] = "The model has been successfully added to your watchlist";
         //after, redirect back to watchlist
         header('Location: watchlist.php');
       }
     } else {
       //This is when the model is already in user's watchlist
       //No need for another time for callback url as it has already been done., and the user is logged in
       unset($_SESSION['callback_url']);
       //Provide session message for watchlist.php to tell user that the database insert is successful
       $_SESSION['message'] = "This model is already in your watchlist";
       //after, redirect back to watchlist
       header('Location: watchlist.php');
     }

     //Free $result from memory at the end
     mysqli_free_result($result);

     // Close database connection
     mysqli_close($connection);

     ?>

   </body>
 </html>

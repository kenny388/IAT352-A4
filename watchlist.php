<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Watch List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/shareAll.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/showmodels.css">


  </head>
  <body>



    <?php
    //Start the session, no need to include initialize here, because it contains another set of database credentials
    session_start();

    //Extract session email
    $email = @$_SESSION['email'];

    //Include functions:
    include 'functions.php';

    //Include header:
    include 'header.php';

    ?>


    <div class="container">

    <?php
    // Error array that might get populated if there is error
    $errors = array();

    //Receive and display session message if there is any
    if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
      $msg = $_SESSION['message'];
      unset($_SESSION['message']);
      if(isset($msg)) {
        echo '<div class="message">' . h($msg) . '</div>';
      }
    }

        //Start the query function
        $query = "SELECT * ";

        //Continue to add the next parts FROM
        $query .= "FROM products ";

        $query .= "JOIN watchlist ";

        $query .= "ON products.productCode = watchlist.model_id ";

        //Starting Last Part of the Query - WHERE Clause
        $query .= "WHERE ";

        //where email = email extracted in session
        $query .= "watchlist.email = '{$email}'";



      // <!-- Start of Connecting and executing queries to database -->

      //Get credentials
      include 'private/db_credentials_products.php';

      // Suppress if connection failed
      $connection = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);


      // Test if connection succeeded
      if(mysqli_connect_errno()) {
        echo '<br>';
        die("Database connection failed: " .
             mysqli_connect_error() .
             " (" . mysqli_connect_errno() . ")"
        );
      }
      //Else it wouldn't run any of the code below

      //Execute Query and get $result
      //
      if (count($errors) == 0) {
        //Although there wouldn't be any error theoretically, I put @ suppression here to handle exception
        $result = @mysqli_query($connection, $query);

        //If executing query failed, print and stop the page
        if (!$result) {
      		die("Database query failed.");
      	} else {
          //Recreate Result header
          echo '<div class="row">';
            echo "<h2>My Watch List </h2>";
            echo "<br>";
          echo "</div>";
          echo '<table class="table">';

          //Giving the first row as table headers
          echo '<tr class="header">';
            echo "<td>Model Name</td>";
            echo "<td>Detail Information</td>";
          echo '</tr>';

          //Get the number of rows in
          $num_rows = mysqli_num_rows($result);

          //Each Loop of fetching data
        while ($row = @mysqli_fetch_assoc($result)) {

          //Make a new table row
          echo "<tr>";

          //For each field, if checkBox checked, display the fields
          echo "<td>".$row["productName"]."</td>";

          //Give a button for further information
          echo "<td>";
          echo '<a class="detailButton" href="modeldetails.php?productName=';
          echo $row["productName"];
          echo'"><label>Detail</label></a>';
          echo "</td>";
          echo "</tr>";

          $id = $row["productCode"];
          $category = $row["productLine"];
          $scale = $row["productScale"];
          $vendor = $row["productVendor"];
          $description = $row["productDescription"];
          $price = $row["buyPrice"];

        }
        echo "</table>";
        }

        // If there is no result
        if ($num_rows <= 0) {
          echo '<br>';
          echo '<label>You do not have any model in your watch list yet</label>';
        }
      }

        //Free $result from memory at the end
        mysqli_free_result($result);
        // Close database connection
        mysqli_close($connection);

      ?>
        </div>

      <!-- Printing all the errors for debugging -->
      <?php
    	// print_r($errors);
      ?>


  </body>
</html>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Database Query</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/shareAll.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/showmodels.css">


  </head>
  <body>



    <?php
    //Start the session, no need to include initialize here, because it contains another set of database credentials
    session_start();

    //Include functions:
    include 'functions.php';

    //Include header:
    include 'header.php';

    // Error array that might get populated if there is error
    $errors = array();


    //Receives the GET model name
    $receivedName = $_GET['productName'];
    $modelName = str_replace('%20', ' ', $receivedName);
    ?>

    <!-- page content -->

    <div class="container">
      <div class="row">
        <h2>
          <?php
          // echo $_GET['modelName'];
          ?>
        </h2>
      </div>

      <!-- Start of Query Statement Construction -->
      <?php
        //Start the query function
        $query = "SELECT * ";

        //Continue to add the next parts FROM
        $query .= "FROM products ";

        //Starting Last Part of the Query - WHERE Clause
          $query .= "WHERE ";
          $query .= "products.productName LIKE '%$modelName%'";

        //Finally, echo the query out
        // echo "<h4>SQL Query</h4>";
        // echo $query;


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
            // echo "<h2>Result</h2>";
            echo "<br>";
          echo "</div>";
          echo '<table class="table">';

          //Giving the first row as table headers
          echo '<tr class="header">';
            echo "<td>Model Name</td>";
            echo "<td>Category</td>";
            echo "<td>Scale</td>";
            echo "<td>Vendor</td>";
            echo "<td>Model Description</td>";
            echo "<td>Price</td>";
          echo '</tr>';

          //Each Loop of fetching data
        while ($row = @mysqli_fetch_assoc($result)) {

          //Make a new table row
          echo "<tr>";

          //For each field, if checkBox checked, display the fields
          echo "<td>".$row["productName"]."</td>";
          echo "<td>".$row["productLine"]."</td>";
          echo "<td>".$row["productScale"]."</td>";
          echo "<td>".$row["productVendor"]."</td>";
          echo "<td>".$row["productDescription"]."</td>";
          echo "<td>".$row["buyPrice"]."</td>";
        }
        echo "</table>";
        }
      }

        //Free $result from memory at the end
        mysqli_free_result($result);
        // Close database connection
        mysqli_close($connection);

      ?>

      <!-- Printing all the errors for debugging -->
      <?php
    	// print_r($errors);
      ?>

    </div>


  </body>
</html>
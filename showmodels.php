<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Show Models</title>
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

    $modelName = "";

    //Check if the form has been submitted before
    if(isset($_POST['submit'])){

      //Check Model Name if exist,
      if (isset($_POST['modelName'])) {
        //Check if empty
        if (!empty($_POST['modelName'])) {
          //Assign the trimmed number to orderNumber
        	$modelName = trim($_POST['modelName']);
          $modelNameArray =  explode(" ", $modelName);
      	}
      }
    }

      //Check if no checkBoxes are checked
      if (isset($_POST['submit']) &&
      !isset($_POST['chkProductName']) &&
      !isset($_POST['chkProductCategory']) &&
      !isset($_POST['chkProductScale']) &&
      !isset($_POST['chkProductVendor']) &&
      !isset($_POST['chkProductDescription']) &&
      !isset($_POST['chkProductBuyPrice'])) {
        //There is nothing to select :/
        $errors['fields'] = "Please select some fields to display!";
      }

    ?>

    <!-- page content -->

    <div class="container">
      <div class="row">
        <h2>All Models</h2>
      </div>

      <!-- Start Of Form -->
      <!-- Form that direct to itself -->
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

        <div class="row">
          <!-- Left Column -->
          <div class="column">
            <!-- If field exist, it would be restored back to the input -->
            <input name="modelName" placeholder="SEARCH FOR A MODEL" value="<?php if (isset($_POST['modelName'])) echo htmlspecialchars($_POST['modelName']); ?>" type="text" size="20"/>


          </div>

          <!-- Right Column -->
          <!-- Contains all checkBoxes for displaying fields -->
          <!-- Each of these box if checked before, would remain check after submit -->
          <div class="column" id="rightColumn">
            <h3>Select info to Display</h3>
            <br>
            <div class="innerRow">
            <div class="innerColumn">
              <input type="checkbox" name="chkProductName" value="chkProductName" <?php if (isset($_POST['chkProductName'])) echo "checked"; ?>>
              <label> Model Name</label>
              <br>
              <input type="checkbox" name="chkProductCategory" value="chkProductCategory" <?php if (isset($_POST['chkProductCategory'])) echo "checked"; ?>>
              <label> Category</label>
              <br>
            </div>
            <div class="innerColumn">
              <input type="checkbox" name="chkProductScale" value="chkProductScale" <?php if (isset($_POST['chkProductScale'])) echo "checked"; ?>>
              <label> Model Scale</label>
              <br>
              <input type="checkbox" name="chkProductVendor" value="chkProductVendor" <?php if (isset($_POST['chkProductVendor'])) echo "checked"; ?>>
              <label> Vendor</label>
              <br>
            </div>
            <div class="innerColumn">
              <input type="checkbox" name="chkProductDescription" value="chkProductDescription" <?php if (isset($_POST['chkProductDescription'])) echo "checked"; ?>>
              <label> Model Description</label>
              <br>
              <input type="checkbox" name="chkProductBuyPrice" value="chkProductBuyPrice" <?php if (isset($_POST['chkProductBuyPrice'])) echo "checked"; ?>>
              <label> Price</label>
              <br>
            </div>
          </div>

            <!-- span for error message -->
            <span id='chkBox_error' class='error'>
      								<?php
                      //If there is error, display it below
                      if (isset($errors['fields'])) {
      										echo $errors['fields'];
    									}
      								?></span>
          </div>
          <br>
        </div>

        <!-- Submit Button -->
        <input class="button" type="submit" name="submit" value="Refine Search"/>

        <!-- End Of Form -->
      </form>

      <!-- Start of Query Statement Construction -->
      <?php

      //If submission exist and no error
      if(isset($_POST['submit']) && count($errors) == 0){

        //Start the curry(query, sorry, I am super hungry when coding this) with SELECT
        $temp = "SELECT ";

        //If the checkboxes are checked, add an extra part behind the query statement
        $temp .= "products.productName, ";
        if (isset($_POST['chkProductCategory'])) $temp .= "products.productLine, ";
        if (isset($_POST['chkProductScale'])) $temp .= "products.productScale, ";
        if (isset($_POST['chkProductVendor'])) $temp .= "products.productVendor, ";
        if (isset($_POST['chkProductDescription'])) $temp .= "products.productDescription, ";
        if (isset($_POST['chkProductBuyPrice'])) $temp .= "products.buyPrice, ";

        //Used substr to limit the length of the string, from 0 (start) to -2 (2 digits before the end), therefore the , " at the end is gone
        $query = substr($temp, 0, -2);
        //Add back the space at the end
        $query .= " ";

        //Continue to add the next parts FROM
        $query .= "FROM products ";

        //Starting Last Part of the Query - WHERE Clause
        //If either of the data was filled, that specify order number and date, add the WHERE statement
        if (isset($_POST['modelName']) && !empty($_POST['modelName'])) {
          $query .= "WHERE ";
          $query .= "products.productName LIKE '%$modelName%'";
        }

        //Finally, echo the query out
        // echo "<h4>SQL Query</h4>";
        // echo $query;

      }
      ?>


      <!-- Start of Connecting and executing queries to database -->
      <?php
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
      if (isset($_POST['submit']) && count($errors) == 0) {
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
            if (isset($_POST['chkProductName'])) echo "<td>Model Name</td>";
            if (isset($_POST['chkProductCategory'])) echo "<td>Category</td>";
            if (isset($_POST['chkProductScale'])) echo "<td>Scale</td>";
            if (isset($_POST['chkProductVendor'])) echo "<td>Vendor</td>";
            if (isset($_POST['chkProductDescription'])) echo "<td>Model Description</td>";
            if (isset($_POST['chkProductBuyPrice'])) echo "<td>Price</td>";
            echo "<td>Detail Information</td>";
          echo '</tr>';

          //Each Loop of fetching data
        while ($row = @mysqli_fetch_assoc($result)) {

          //Make a new table row
          echo "<tr>";

          //For each field, if checkBox checked, display the fields
          if (isset($_POST['chkProductName'])) echo "<td>".$row["productName"]."</td>";
          if (isset($_POST['chkProductCategory'])) echo "<td>".$row["productLine"]."</td>";
          if (isset($_POST['chkProductScale'])) echo "<td>".$row["productScale"]."</td>";
          if (isset($_POST['chkProductVendor'])) echo "<td>".$row["productVendor"]."</td>";
          if (isset($_POST['chkProductDescription'])) echo "<td>".$row["productDescription"]."</td>";
          if (isset($_POST['chkProductBuyPrice'])) echo "<td>".$row["buyPrice"]."</td>";
          //Give a button for further information
          echo "<td>";
          echo '<a class="detailButton" href="modeldetails.php?productName=';
          echo $row["productName"];
          echo'"><label>Detail</label></a>';
          echo "</td>";
          echo "</tr>";
        }
        echo "</table>";
        }
      }
      ?>

      <?php
      //If submission exist and no error
      if(isset($_POST['submit']) && count($errors) == 0){
        //Free $result from memory at the end
        mysqli_free_result($result);
        // Close database connection
        mysqli_close($connection);
      }
      ?>

      <!-- Printing all the errors for debugging -->
      <?php
    	// print_r($errors);
      ?>

    </div>


  </body>
</html>

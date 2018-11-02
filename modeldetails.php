<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Model Details</title>
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

    //array for storing this account's existing watchlist imagecreatefromstring
    $modelsWatched = array();

    //Extract session email
    $email = @$_SESSION['email'];


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

          //Each Loop of fetching data
        while ($row = @mysqli_fetch_assoc($result)) {
          //Extract the data and assign into variables
          $id = $row["productCode"];
          $category = $row["productLine"];
          $scale = $row["productScale"];
          $vendor = $row["productVendor"];
          $description = $row["productDescription"];
          $price = $row["buyPrice"];
          //Setting the last viewed model Id, for callback purpose
          $_SESSION["lastViewedModelId"] = $id;
        }
        }
      }

        //Free $result from memory at the end
        mysqli_free_result($result);


        //OBTAINING the existing list of models in the user's watchlist

        //Starting another query to extract the watchlist
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

        //Free $result from memory at the end
        mysqli_free_result($result);

        // Close database connection
        mysqli_close($connection);

      ?>


        <div class="recipe">
          <div class="title">
              <h2><?php echo $modelName ?></h2>
          </div>
          <hr>
          <div class="oneRow">
            <div class="leftBox">
              <h4>Description :</h4><p><?php echo $description; ?></p>
            </div>
            <div class="rightBox">
              <div class="verticalList">
                <div class="eachVerticalBox">
                  <p><strong>Category : </strong><?php echo $category; ?></p>
                </div>
                <div class="eachVerticalBox">
                  <p><strong>Scale : </strong><?php echo $scale; ?></p>
                </div>
                <div class="eachVerticalBox">
                  <p><strong>Vendor : </strong><?php echo $vendor; ?></p>
                </div>
                <div class="eachVerticalBox">
                  <p><strong>Price : </strong><?php echo $price; ?></p>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <br><br>

          <?php
          //Form to submit button add to watch list
          //Only exist if the user is logged in AND
          //Also, if logged in, there is no need for callback url to be set
          if (isset($_SESSION['loggedIn'])) {

            //If the watch list user have does not have the model
            $haveModel = false;
            foreach ($modelsWatched as $watched) {
              if ($id == $watched) {
                $haveModel = true;
              }
            }

              //Already have the model in this page
              //Provide another button telling the user they already own the model and bring them to their watchlist
              if ($haveModel) {
                echo '<form action="watchlist.php" method="post">';
                    echo '<input type="submit" name="submit" value="This model is in your watch list  Click me to browse">';
                echo '</form>';
              } else {
                //Model does not exist in watchlist
                //Provide button to add to watchlist
                echo '<form action="addtowatchlist.php" method="post">';
                    echo '<input type="hidden" name="modelID" value="' . $id . '"></input>';
                    echo '<input type="submit" name="submit" value="Add To WatchList">';
                echo '</form>';
              }
        } else {
          //if not logged in
          //will still have the button, but lead to login page
          echo '<form action="callBack_redirect.php" method="post">';
              echo '<input type="submit" name="submit" value="Add To WatchList">';
          echo '</form>';
        }
        ?>

        </div>

      <!-- Printing all the errors for debugging -->
      <?php
    	// print_r($errors);
      ?>

    </div>


  </body>
</html>

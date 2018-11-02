<?php

$errors = [];
$email = '';
$password = '';

if (is_post_request()) {
    // Set session value
    // FILL IN YOUR CODE HERE
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    //Receives and check if the forms have been filled valid
    if (!empty($email) && has_string($email, '@')) {
        $_SESSION['email'] = $email;
    } else {
        $errors['email'] = "Please format email with emailaddress@XXX.com";
    }

    if (!empty($password)) {
        $_SESSION['password'] = $password;
    } else {
        $errors['password'] = "Please fill in a password";
    }

    //if NO Error, check if exist in database
    if (count($errors) == 0) {
        //Check
        $query = "SELECT count(*), firstName, lastName, password FROM users WHERE email = '{$email}'";
        $result = $db->query($query);
        //This line could already tell theres one or more result

          //These lines below are just my attempt to fetch the result of query "count(*)"
          while($row = $result->fetch_assoc()) {
            //There is result
            if ($row['count(*)'] > 0) {
              $firstName = $row['firstName'];
              $lastName = $row['lastName'];
              $tempPassword = $row['password'];

              //verify the password with hash encryption
              if (password_verify($password, $tempPassword)) {

                //login successful!
                $_SESSION['loggedIn'] = 'T';
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;

                //Need to redirect to callback url if exist
                $callback_url = "/kycheung/A4/IAT352-A4/showmodels.php";
                if (isset($_SESSION['callback_url'])) {
                  $callback_url = $_SESSION['callback_url'];
                  //switch back to unsecure http and go to the callback url
                  header("Location: http://" . $_SERVER["HTTP_HOST"] . $callback_url);
                  exit();
                }

                //success, redirect to showmodels page with unsecure http
                header("Location: http://" . $_SERVER["HTTP_HOST"] . "/kycheung/A4/IAT352-A4/showmodels.php");
                exit();
              } else {
                $errors['noAcc'] = "The email address or password is incorrect";
              }
          } else {
            //No account exist with that email
            $errors['noAcc'] = "The email address or password is incorrect";
          }
        }

        //Close the database
        $db->close();

    }
}
?>

<?php $page_title = 'Log in'; ?>

<div id="content">

  <div class="container">
    <div class="box">
  <?php echo display_errors($errors); ?>

  <!-- Submit the form to the same page via https -->
  <form action="<?php echo 'https://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/login.php'; ?>" method="post">
    <hr>
    <label>EMAIL ADDRESS</label>
      <br />
    <input type="text" name="email" placeholder="emailaddress@mail.com" value="<?php echo h($email); ?>" />
    <br />
    <hr>
    <label>PASSWORD</label>
    <br />
    <input type="password" placeholder="password" name="password" value="" />
    <br />
    <br />
    <input type="submit" name="submit" value="Login"  />
  </form>

  <!-- Link provided to register page if user does not have an account already, with https on -->
  <a class="link" href="<?php echo 'https://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/register.php'; ?>"><label>Do not have an account? Register Here</label></a>

</div>
</div>
</div>

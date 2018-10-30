<?php
// require_once('../../private/initialize.php');
// include 'functions.php';

$errors = [];
$firstName = '';
$lastName = '';
$email = '';
$password = '';

if (is_post_request()) {
    // Set session value
    // FILL IN YOUR CODE HERE
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (!empty($firstName)) {
        $_SESSION['firstName'] = $firstName;
    } else {
        $errors['firstName'] = "Please fill in your first name";
    }
    if (!empty($lastName)) {
        $_SESSION['lastName'] = $lastName;
    } else {
        $errors['lastName'] = "Please fill in your last name";
    }

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

    // redirect_to('dbquery.php');
    //if NO Error
    if (count($errors) == 0) {
      //Check if account already exist:
      $checkQuery = "SELECT * FROM users WHERE email = '{$email}'";
      $result = $db->query($checkQuery);
      //See if there is already account exists with same credentials
      if ($result->num_rows > 0) {
        //If YES
        $errors['acc'] = "You have already registered, please try logging in";
        } else {
        //Import into Database
        $query = "INSERT INTO users (firstName, lastName, email, password) VALUES ('{$firstName}', '{$lastName}', '{$email}', '{$hashed_password}')";
        $db->query($query);
        $db->close();
        header('Location: login.php');
        exit();
      }
    }
}
?>

<?php $page_title = 'Register'; ?>

<div id="content">
  <div class="container">
    <div class="box">

  <?php echo display_errors($errors); ?>

  <h3>Register As a User</h3>

  <form action="register.php" method="post">

    <hr>
    <label>FIRST NAME</label>
    <br />
    <input type="text" name="firstName" placeholder="Your First Name" value="<?php echo h($firstName); ?>" />
    <br />
    <hr>
    <label>LAST NAME</label>
    <br />
    <input type="text" name="lastName" placeholder="Your Last Name" value="<?php echo h($lastName); ?>" />
    <br />
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
    <input type="submit" name="submit" value="Submit"  />
  </form>
</div>
</div>
</div>

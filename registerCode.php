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
        //Import into Database
        $query = "INSERT INTO users (firstName, lastName, email, password) VALUES ('{$firstName}', '{$lastName}', '{$email}', '{$password}')";
        $db->query($query);
        $db->close();
        header('Location: login.php');
        exit();
    }
}
?>

<?php $page_title = 'Register'; ?>

<div id="content">


  <?php echo display_errors($errors); ?>

  <form action="register.php" method="post">
    First Name:<br />
    <input type="text" name="firstName" value="<?php echo h($firstName); ?>" /><br />
    Last Name:<br />
    <input type="text" name="lastName" value="<?php echo h($lastName); ?>" /><br />
    Email:<br />
    <input type="text" name="email" value="<?php echo h($email); ?>" /><br />
    Password:<br />
    <input type="password" name="password" value="" /><br />
    <input type="submit" name="submit" value="Submit"  />
  </form>

</div>
